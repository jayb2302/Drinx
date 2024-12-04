<?php
require_once __DIR__ . '/../config/dependencies.php';

class CocktailController
{
    private $cocktailService;
    private $ingredientService;
    private $stepService;
    private $difficultyRepository;
    private $commentService;
    private $likeService;
    private $tagRepository;
    private $userService;
    private $imageService;
    private $badgeService;

    // Maximum description length
    private const MAX_DESCRIPTION_LENGTH = 500;

    public function __construct(
        CocktailService $cocktailService,
        IngredientService $ingredientService,
        StepService $stepService,
        DifficultyRepository $difficultyRepository,
        CommentService $commentService,
        LikeService $likeService,
        TagRepository $tagRepository,
        UserService $userService,
        ImageService $imageService,
        BadgeService $badgeService
    ) {
        $this->cocktailService = $cocktailService;
        $this->ingredientService = $ingredientService;
        $this->stepService = $stepService;
        $this->difficultyRepository = $difficultyRepository;
        $this->commentService = $commentService;
        $this->likeService = $likeService;
        $this->tagRepository = $tagRepository;
        $this->userService = $userService;
        $this->imageService = $imageService;
        $this->badgeService = $badgeService;


        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    // List all cocktails (public access)
    public function index()
    {
        $isStandalone = true; // Set to true to indicate a standalone cocktails page
        $cocktails = $this->cocktailService->getAllCocktails();
        $categories = $this->cocktailService->getCategories();
        $loggedInUserId = $_SESSION['user']['id'] ?? null;

        // Pass `hasLiked` status to the view for each cocktail
        foreach ($cocktails as $cocktail) {
            // Get like status for the current user
            $cocktail->hasLiked = $loggedInUserId ? $this->likeService->userHasLikedCocktail($loggedInUserId, $cocktail->getCocktailId()) : false;

            // Fetch the top-level comments for the cocktail (limit to 3, for example)
            $comments = $this->cocktailService->getTopLevelCommentsForCocktail($cocktail->getCocktailId(), 3);
            error_log("Fetched comments for cocktail ID " . $cocktail->getCocktailId() . ": " . print_r($comments, true));

            $cocktail->comments = $comments;  // Assign the comments to the cocktail object
            $cocktail->commentCount = count($comments); // Count the number of comments
        }

        // Pass data to the view
        require_once __DIR__ . '/../views/cocktails/index.php'; // Load the view to display cocktails
    }
    // Show the form to add a new cocktail (only for logged-in users)
    public function add()
    {
        // Fetch necessary data for the form (categories, units)
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientService->getAllUnits();
        $difficulties = $this->difficultyRepository->getAllDifficulties();

        $isEditing = false;
        // Pass the necessary data to the view
        require_once __DIR__ . '/../views/cocktails/form.php';
    }

    // Store a new cocktail in the database (only for logged-in users)
    public function store()
    {
        $this->ensureLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';

            // Validate the CSRF token
            if (!$sessionToken || !hash_equals($sessionToken, $csrfToken)) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid or missing CSRF token.']);
                exit;
            }
            $errors = $this->validateCocktailInput($_POST);

            $description = sanitizeTrim($_POST['description']);
            if (strlen($description) > self::MAX_DESCRIPTION_LENGTH) {
                $errors[] = "Description cannot exceed " . self::MAX_DESCRIPTION_LENGTH . " characters.";
            }
            // Log validation errors
            error_log("Validation errors: " . print_r($errors, true));

            // Handle the image upload
            $image = $this->handleImageUpload($_FILES['image'], $errors);

            // Log image handling errors or success
            error_log("Image handling result: " . print_r($image, true));
            error_log("Image upload errors: " . print_r($errors, true));

            if ($this->handleValidationErrors($errors, '/cocktails/add'))
                return;

            $cocktailData = [
                'user_id' => $_SESSION['user']['id'],
                'title' => sanitize($_POST['title']),
                'description' => substr($description, 0, self::MAX_DESCRIPTION_LENGTH), // Enforce length
                'image' => $image,
                'category_id' => intval($_POST['category_id']),
                'difficulty_id' => intval($_POST['difficulty_id'])
            ];

            try {
                // Log cocktail data to be stored
                error_log("Storing cocktail data: " . print_r($cocktailData, true));

                // Proceed with creating the cocktail
                $cocktailId = $this->cocktailService->createCocktail($cocktailData);

                // After successful creation
                $this->handleCocktailSteps($cocktailId, $_POST['steps']);

                $parsedQuantities = $this->processQuantities($_POST['quantities']);
                $this->handleCocktailIngredients($cocktailId, $_POST['ingredients'], $parsedQuantities, $_POST['units']);

                // Check for new badges and notify user
                $userId = $_SESSION['user']['id'];
                $cocktailCount = $this->cocktailService->getCocktailCountByUserId($userId); // Fetch the updated cocktail count
                error_log("User ID: $userId | Cocktail Count: $cocktailCount");

                $this->badgeService->checkAndNotifyNewBadge($userId, $cocktailCount); // Check for new badges

                $this->redirect('/cocktails/' . $cocktailId . '-' . urlencode($cocktailData['title']));
            } catch (Exception $e) {
                $_SESSION['errors'] = ["Failed to create cocktail: " . sanitize($e->getMessage())];
                $this->redirect('/cocktails/add');
            }
        }
        $this->redirect('/cocktails');
    }

    // Show the form to edit an existing cocktail (only for the owner or admin)
    public function edit($cocktailId)
    {
        $this->ensureLoggedIn(); // Ensure user is logged in

        $cocktail = $this->cocktailService->getCocktailById($cocktailId);

        // Only allow the owner or an admin to edit the cocktail
        if ($cocktail->getUserId() !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            $this->redirect('/cocktails'); // Redirect if the user doesn't have permission
        }

        $ingredients = $this->cocktailService->getCocktailIngredients($cocktailId);
        $steps = $this->cocktailService->getCocktailSteps($cocktailId);
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientService->getAllUnits();
        $difficultyId = $cocktail->getDifficultyId();
        $difficultyName = $this->difficultyRepository->getDifficultyNameById($difficultyId);

        $difficulties = $this->difficultyRepository->getAllDifficulties();
        $isEditing = true;

        // Pass the variables to the view
        require_once __DIR__ . '/../views/cocktails/form.php'; // Load the edit form
    }

    public function update($cocktailId)
    {
        $this->ensureLoggedIn();
        $cocktail = $this->cocktailService->getCocktailById($cocktailId);

        if ($cocktail->getUserId() !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            $this->redirect('/cocktails');
        }

        $errors = $this->validateCocktailInput($_POST);
        $image = $this->handleImageUpdate($_FILES['image'], $cocktail, $errors);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('/cocktails/' . $cocktailId . '-' . urlencode($cocktail->getTitle()) . '/edit');
            return;
        }
        $isSticky = isset($_POST['isSticky']) ? 1 : 0;

        $cocktailData = [
            'title' => sanitize($_POST['title']),
            'description' => sanitizeTrim($_POST['description']),
            'category_id' => intval($_POST['category_id']),
            'difficulty_id' => intval($_POST['difficulty_id']),
            'image' => $image ?: $cocktail->getImage(),
            'is_sticky' => isset($_POST['isSticky']) ? 1 : 0
        ];
        error_log("Updating cocktail with data: " . print_r($cocktailData, true));

        try {
            $this->cocktailService->updateCocktail($cocktailId, $cocktailData);
            $this->clearSteps($cocktailId);
            $this->handleCocktailSteps($cocktailId, $_POST['steps']);

            // Check for deletions of steps
            if (!empty($_POST['delete_steps'])) {
                foreach ($_POST['delete_steps'] as $stepId) {
                    $this->stepService->deleteStep($cocktailId, $stepId); // Call to delete the step
                }
            }
            $parsedQuantities = $this->ingredientService->processQuantities($_POST['quantities']);

            // Handle ingredients
            $this->ingredientService->updateIngredients($cocktailId, $_POST['ingredients'], $parsedQuantities, $_POST['units']);
            $this->handleCocktailIngredients($cocktailId, $_POST['ingredients'], $_POST['quantities'], $_POST['units']);

            $this->redirect('/cocktails/' . $cocktailId . '-' . urlencode($cocktailData['title']));
        } catch (Exception $e) {
            $_SESSION['errors'] = ["Failed to update cocktail: " . $e->getMessage()];
            $this->redirect('/cocktails/' . $cocktailId . '/edit');
        }
    }

    public function countCocktails()
    {
        $count = $this->cocktailService->countCocktails();
        echo json_encode(['count' => $count]);
    }

    // Delete steps
    public function deleteStep($cocktailId)
    {
        $this->ensureLoggedIn(); // Ensure the user is logged in

        // Fetch the cocktail to verify ownership
        $cocktail = $this->cocktailService->getCocktailById($cocktailId);

        // Only allow the owner or an admin to delete the step
        if ($cocktail->getUserId() !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            $this->redirect('/cocktails/' . $cocktailId); // Redirect if the user doesn't have permission
        }

        // Check if there are steps to delete
        if (isset($_POST['delete_steps']) && is_array($_POST['delete_steps'])) {
            foreach ($_POST['delete_steps'] as $stepId) {
                if (is_numeric($stepId)) {
                    $this->stepService->deleteStep($cocktailId, $stepId); // Call the service to delete each step
                }
            }
        }

        // Redirect back to the cocktail edit page after deletion
        $this->redirect('/cocktails/' . $cocktailId . '-' . urlencode($cocktail->getTitle()));
    }

    // Clear existing steps
    private function clearSteps($cocktailId)
    {
        $this->stepService->clearStepsByCocktailId($cocktailId);
    }

    // Clear existing ingredients
    private function clearIngredients($cocktailId)
    {
        $this->ingredientService->clearIngredientsByCocktailId($cocktailId);
    }

    // Validate cocktail input data
    private function validateCocktailInput($data)
    {
        $errors = [];

        // Required fields
        $requiredFields = [
            'title' => 'Title',
            'description' => 'Description',
            'category_id' => 'Category',
            'difficulty_id' => 'Difficulty'
        ];

        // Check for empty required fields
        foreach ($requiredFields as $field => $label) {
            if (empty($data[$field])) {
                $errors[] = "$label is required.";
            }
        }

        // Validate title length (optional: adjust as needed)
        if (!empty($data['title']) && strlen($data['title']) > 255) {
            $errors[] = "Title cannot be more than 255 characters.";
        }

        // Validate description length (new addition)
        if (!empty($data['description']) && strlen($data['description']) > 500) { // Adjust length as needed
            $errors[] = "Description cannot be more than 500 characters.";
        }

        // Check category_id and difficulty_id for valid integers
        if (isset($data['category_id']) && !filter_var($data['category_id'], FILTER_VALIDATE_INT)) {
            $errors[] = "Category must be a valid integer.";
        }
        if (isset($data['difficulty_id']) && !filter_var($data['difficulty_id'], FILTER_VALIDATE_INT)) {
            $errors[] = "Difficulty must be a valid integer.";
        }

        // Optional: Check image if it's required and exists
        if (empty($data['image']) && !empty($data['image_required'])) {
            $errors[] = "Image is required.";
        }

        return $errors;
    }

    // Handle image upload for new cocktails
    private function handleImageUpload($file, &$errors)
    {
        try {
            // Validate if a file is uploaded
            if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception("No valid file uploaded.");
            }


            // Set the target directory for cocktail images
            $targetDir = __DIR__ . '/../../public/uploads/cocktails/';


            // Generate a unique file name for the uploaded image
            $uniqueFileName = uniqid() . '.webp';
            $targetPath = $targetDir . $uniqueFileName;

            // Set the desired dimensions for the cocktail image
            $width = 1280; // Example width
            $height = 720; // Example height

            // Process the image using the ImageService
            $this->imageService->processImage($file, $width, $height, $targetPath);

            // Return the unique file name to store in the database
            return $uniqueFileName;
        } catch (\Exception $e) {
            // Capture and log any errors
            $errors[] = "Failed to upload image: " . $e->getMessage();
            return null;
        }
    }

    // Handle image update for editing cocktails
    private function handleImageUpdate($file, $cocktail, &$errors)
    {
        if (!empty($file['name'])) {
            return $this->handleImageUpload($file, $errors);
        }
        return $cocktail->getImage(); // Retain existing image if no new one is provided
    }

    // Ensure user is logged in
    private function ensureLoggedIn()
    {
        if (!AuthController::isLoggedIn()) {
            $this->redirect('login');
        }
    }

    // Ensure user has permission to edit or delete
    private function ensureUserHasPermission($cocktailUserId)
    {
        if ($cocktailUserId !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            $this->redirect('/cocktails');
        }
    }

    // Handle validation errors
    private function handleValidationErrors($errors, $redirectUrl)
    {
        if (!empty($errors)) {
            // Log errors for debugging purposes
            error_log("Validation errors: " . print_r($errors, true));

            $_SESSION['errors'] = $errors;
            $this->redirect($redirectUrl);
            return true; // Indicate that there were errors
        }
        return false;
    }

    // View cocktail details (public access)
    public function view($cocktailId, $action = 'view')
    {
        $loggedInUserId = $_SESSION['user']['id'] ?? null;
        $currentUser = $this->userService->getUserWithProfile($loggedInUserId);
        // Sanitize inputs
        $cocktailId = intval($cocktailId); // Sanitize ID
        $action = sanitize($action); // Sanitize action
        $isEditing = ($action === 'edit');

        // Fetch primary cocktail details
        $cocktail = $this->cocktailService->getCocktailById($cocktailId);
        if (!$cocktail) {
            // Handle case where cocktail does not exist
            http_response_code(404);
            require_once __DIR__ . '/../views/errors/404.php';
            return;
        }
        $cocktailTitle = $cocktail ? htmlspecialchars($cocktail->getTitle()) : 'Unknown Cocktail';
        // Check if user has liked the cocktail
        $cocktail->hasLiked = $loggedInUserId
            ? $this->likeService->userHasLikedCocktail($loggedInUserId, $cocktailId)
            : false;

        $creator = $this->userService->getUserWithProfile($cocktail->getUserId());
        $ingredients = $this->cocktailService->getCocktailIngredients($cocktailId);
        $processedIngredients = array_map(function ($ingredient) {
            return [
                'name' => $ingredient->getName(),
                'quantity' => $this->ingredientService->convertDecimalToFraction($ingredient->getQuantity()),
                'unit' => $ingredient->getUnitName(),
            ];
        }, $ingredients);

        $steps = $this->cocktailService->getCocktailSteps($cocktailId);
        $tags = $this->cocktailService->getCocktailTags($cocktailId);
        $categories = $this->cocktailService->getCategories();

        $categoryName = 'Unknown Category';
        foreach ($categories as $category) {
            if ($category['category_id'] === $cocktail->getCategoryId()) {
                $categoryName = $category['name'];
                break;
            }
        }

        $units = $this->ingredientService->getAllUnits();
        $difficultyId = $cocktail->getDifficultyId();
        $difficulties = $this->difficultyRepository->getAllDifficulties();
        $difficultyName = $this->difficultyRepository->getDifficultyNameById($cocktail->getDifficultyId());

        // Fetch total likes for the cocktail
        $totalLikes = $this->likeService->getLikesForCocktail($cocktailId);
        $comments = $this->commentService->getCommentsWithReplies($cocktailId);
        $viewData = compact(
            'cocktail',
            'creator',
            'processedIngredients',
            'steps',
            'tags',
            'categoryName',
            'category',
            'categories',
            'units',
            'difficulties',
            'difficultyName',
            'totalLikes',
            'comments',
            'loggedInUserId',
            'isEditing'
        );

        if ($isEditing) {
            extract($viewData);
            require_once __DIR__ . '/../views/cocktails/form.php'; // Load the edit form
        } else {
            extract($viewData);
            require_once __DIR__ . '/../views/cocktails/view.php'; // Load the view page
        }
    }

    // Handle cocktail steps
    public function handleCocktailSteps($cocktailId, $steps)
    {
        $cocktailId = intval($cocktailId); // Sanitize ID
        // Clear existing steps for this cocktail first
        $this->stepService->clearStepsByCocktailId($cocktailId);

        foreach ($steps as $stepNumber => $instruction) {
            $instruction = sanitize($instruction); // Sanitize each instruction
            if (!empty($instruction)) {
                // Create a new step in the repository
                $this->stepService->addStep($cocktailId, $instruction, $stepNumber + 1);
            }
        }
    }

    // Process quantities
    private function processQuantities($quantities)
    {
        return array_map('sanitizeNumber', $quantities);
    }

    // Handle cocktail ingredients
    private function handleCocktailIngredients($cocktailId, $ingredients, $quantities, $units)
    {
        $cocktailId = intval($cocktailId); // Sanitize ID
        // Clear existing ingredients for the cocktail
        $this->ingredientService->clearIngredientsByCocktailId($cocktailId);

        foreach ($ingredients as $index => $ingredientName) {
            $ingredientName = sanitize($ingredientName);
            $quantity = sanitizeNumber($quantities[$index] ?? '');
            $unitId = intval($units[$index] ?? null);

            // Ensure ingredient name is not empty
            if (empty($ingredientName) || empty($quantity) || empty($unitId)) {
                continue;
            }

            // Check if the ingredient already exists
            $ingredientId = $this->ingredientService->getIngredientIdByName($ingredientName);

            if ($ingredientId) {
                // If the ingredient exists, use the existing ID
                $this->ingredientService->addIngredient($cocktailId, $ingredientId, $quantity, $unitId);
            } else {
                // If the ingredient does not exist, create a new one
                $ingredientId = $this->ingredientService->createIngredient($ingredientName);
                $this->ingredientService->addIngredient($cocktailId, $ingredientId, $quantity, $unitId);
            }
        }
    }

    // Delete a cocktail (only for the owner or admin)
    public function delete($cocktailId)
    {
        $csrfToken = $_POST['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';

        // Validate the CSRF token
        if (!$sessionToken || !hash_equals($sessionToken, $csrfToken)) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid or missing CSRF token.']);
            exit;
        }

        $cocktailId = intval($cocktailId); // Sanitize ID
        $this->ensureLoggedIn(); // Ensure the user is logged in

        $cocktail = $this->cocktailService->getCocktailById($cocktailId);

        // Only allow the owner or an admin to delete the cocktail
        if ($cocktail->getUserId() !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            $this->redirect('/cocktails');
        }

        // Delete the cocktail
        $this->cocktailService->deleteCocktail($cocktailId);
        $this->redirect('/'); // Redirect to the cocktail list
    }
    public function getRandomCocktail()
    {
        // Fetch a random cocktail using the service
        $cocktail = $this->cocktailService->getRandomCocktail();

        if ($cocktail) {
            header('Content-Type: application/json');
            echo json_encode([
                'id' => $cocktail->getCocktailId(),
                'title' => $cocktail->getTitle(),
                'image' => $cocktail->getImage(),
                'description' => $cocktail->getDescription()
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No cocktail found.']);
        }
        exit; // Ensure no additional HTML is sent
    }
}
