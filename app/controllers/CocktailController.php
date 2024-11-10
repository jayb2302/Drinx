<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../repositories/DifficultyRepository.php';
require_once __DIR__ . '/../repositories/CommentRepository.php';
require_once __DIR__ . '/../repositories/UnitRepository.php';
require_once __DIR__ . '/../services/CocktailService.php';
require_once __DIR__ . '/../services/CommentService.php';
require_once __DIR__ . '/../services/LikeService.php';

class CocktailController
{
    private $cocktailService;
    private $ingredientService;
    private $stepService;
    private $difficultyRepository;
    private $commentService;
    private $likeService;
    private $userService;


    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Start session if not already started
        }

        $db = Database::getConnection();  // Get the database connection

        // Initialize repositories
        $cocktailRepository = new CocktailRepository($db);
        $categoryRepository = new CategoryRepository($db);
        $ingredientRepository = new IngredientRepository($db);
        $stepRepository = new StepRepository($db);
        $tagRepository = new TagRepository($db);
        $this->difficultyRepository = new DifficultyRepository($db);
        $commentRepository = new CommentRepository($db);
        $likeRepository = new LikeRepository($db);
        $this->likeService = new LikeService(new LikeRepository($db));
        $userRepository = new UserRepository($db);
        $userService = new UserService($userRepository);

        // Initialize services    
        $unitRepository = new UnitRepository($db);
        $this->ingredientService = new IngredientService($ingredientRepository, $unitRepository);
        $this->stepService = new StepService($stepRepository);
        $this->commentService = new CommentService($commentRepository, $userService);
        $this->userService = new UserService(new UserRepository($db));
    

        // Initialize the CocktailService with services and repositories
        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $this->ingredientService,
            $this->stepService,
            $tagRepository,
            $this->difficultyRepository,
            $likeRepository,
            $userRepository
        );
    }

    private function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    // List all cocktails (public access)
    public function index()
    {
        $cocktails = $this->cocktailService->getAllCocktails();
        $categories = $this->cocktailService->getCategories();
        $loggedInUserId = $_SESSION['user']['id'] ?? null;
        // Pass `hasLiked` status to the view for each cocktail
        foreach ($cocktails as $cocktail) {
            $cocktail->hasLiked = $loggedInUserId ? $this->likeService->userHasLikedCocktail($loggedInUserId, $cocktail->getCocktailId()) : false;
        }
        require_once __DIR__ . '/../views/cocktails/index.php'; // Load the view to display cocktails
    }

    // Show the form to add a new cocktail (only for logged-in users)
    public function add()
    {
        // Fetch necessary data for the form (categories, units)
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientService->getAllUnits();

        $isEditing = false;
        // Pass the necessary data to the view
        require_once __DIR__ . '/../views/cocktails/form.php'; // Include the form view
    }

    // Store a new cocktail in the database (only for logged-in users)
    public function store()
    {
        $this->ensureLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateCocktailInput($_POST);

            // Log validation errors
            error_log("Validation errors: " . print_r($errors, true));

            // Handle the image upload
            $image = $this->handleImageUpload($_FILES['image'], $errors);

            // Log image handling errors or success
            error_log("Image handling result: " . print_r($image, true));
            error_log("Image upload errors: " . print_r($errors, true));

            if ($this->handleValidationErrors($errors, '/cocktails/add')) return;

            $cocktailData = [
                'user_id' => $_SESSION['user']['id'],
                'title' => sanitize($_POST['title']),
                'description' => sanitize($_POST['description']),
                'image' => $image,
                'is_sticky' => isset($_POST['isSticky']) ? 1 : 0,
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
                $this->handleCocktailIngredients($cocktailId, $_POST['ingredients'], $_POST['quantities'], $_POST['units']);

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
        $units = $this->ingredientService->getAllUnits(); // Fetch units from IngredientService

        $isEditing = true;

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
            'description' => sanitize($_POST['description']),
            'category_id' => intval($_POST['category_id']),
            'difficulty_id' => intval($_POST['difficulty_id']),
            'image' => $image ?: $cocktail->getImage(),
            'is_sticky' => isset($_POST['isSticky']) ? 1 : 0
        ];

        // Log the cocktail data before update
        error_log("Updating cocktail with data: " . print_r($cocktailData, true));

        try {
            $this->clearSteps($cocktailId);
            $this->cocktailService->updateCocktail($cocktailId, $cocktailData);
            $this->handleCocktailSteps($cocktailId, $_POST['steps']);

            // Check for deletions of steps
            if (!empty($_POST['delete_steps'])) {
                foreach ($_POST['delete_steps'] as $stepId) {
                    $this->stepService->deleteStep($cocktailId, $stepId); // Call to delete the step
                }
            }

            // Handle ingredients
            $this->ingredientService->updateIngredients($cocktailId, $_POST['ingredients'], $_POST['quantities'], $_POST['units']);
            $this->handleCocktailIngredients($cocktailId, $_POST['ingredients'], $_POST['quantities'], $_POST['units']);

            $this->redirect('/cocktails/' . $cocktailId . '-' . urlencode($cocktailData['title']));
        } catch (Exception $e) {
            $_SESSION['errors'] = ["Failed to update cocktail: " . $e->getMessage()];
            $this->redirect('/cocktails/' . $cocktailId . '/edit');
        }
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
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            // Sanitize and validate the original filename
            $image = sanitize($file['name']);
            $fileExtension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

            // Check the file extension
            $allowedTypes = ['jpeg', 'jpg', 'png', 'webp'];
            if (!in_array($fileExtension, $allowedTypes)) {
                $errors[] = "Invalid image format. Allowed formats are JPEG, PNG, and WEBP.";
                return null; // Exit if file type is not allowed
            }

            // Generate a unique filename to avoid conflicts
            $image = bin2hex(random_bytes(8)) . '.' . $fileExtension;
            $target_dir = __DIR__ . '/../../public/uploads/cocktails/';
            $target_file = $target_dir . $image;

            // Move the uploaded file to the target directory
            if (!move_uploaded_file($file['tmp_name'], $target_file)) {
                $errors[] = "There was an error uploading the image.";
                return null;
            }

            // Return the unique filename for storing in the database
            return $image;
        }

        return null; // Return null if no image was uploaded
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
        return false; // No errors
    }

    // View cocktail details (public access)
    public function view($cocktailId, $action = 'view')
    {
        $loggedInUserId = $_SESSION['user']['id'] ?? null;
        $cocktailId = intval($cocktailId); // Sanitize ID
        $action = sanitize($action); // Sanitize action

        $cocktail = $this->cocktailService->getCocktailById($cocktailId);
        $isEditing = ($action === 'edit');
        $cocktail->hasLiked = $loggedInUserId ? $this->likeService->userHasLikedCocktail($loggedInUserId, $cocktailId) : false;
        $ingredients = $this->cocktailService->getCocktailIngredients($cocktailId);
        $steps = $this->cocktailService->getCocktailSteps($cocktailId);
        $category = $this->cocktailService->getCategoryByCocktailId($cocktailId);
        $tags = $this->cocktailService->getCocktailTags($cocktailId);
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientService->getAllUnits();
        // Check if the user has liked this cocktail
        $hasLiked = $loggedInUserId ? $this->likeService->userHasLikedCocktail($loggedInUserId, $cocktailId) : false;

        // Fetch total likes for the cocktail (if you need to display total likes)
        $totalLikes = $this->likeService->getLikesForCocktail($cocktailId);

        $comments = $this->commentService->getCommentsWithReplies($cocktailId);

        if ($isEditing) {
            require_once __DIR__ . '/../views/cocktails/form.php'; // Load the edit form
        } else {
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

    // Handle cocktail ingredients
    private function handleCocktailIngredients($cocktailId, $ingredients, $quantities, $units)
    {
        $cocktailId = intval($cocktailId); // Sanitize ID
        // Clear existing ingredients for the cocktail
        $this->ingredientService->clearIngredientsByCocktailId($cocktailId);

        foreach ($ingredients as $index => $ingredientName) {
            $ingredientName = sanitize($ingredientName);
            $quantity = sanitize($quantities[$index] ?? '');
            $unitId = intval($units[$index] ?? null);

            // Ensure ingredient name is not empty
            if (empty($ingredientName) || empty($quantity) || empty($unitId)) {
                continue; // Skip to the next ingredient if any required field is missing
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
        $cocktailId = intval($cocktailId); // Sanitize ID
        $this->ensureLoggedIn(); // Ensure the user is logged in

        $cocktail = $this->cocktailService->getCocktailById($cocktailId);

        // Only allow the owner or an admin to delete the cocktail
        if ($cocktail->getUserId() !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            $this->redirect('/cocktails'); // Redirect if the user doesn't have permission
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
