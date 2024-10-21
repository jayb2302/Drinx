<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../repositories/DifficultyRepository.php';
require_once __DIR__ . '/../repositories/UnitRepository.php';
require_once __DIR__ . '/../services/CocktailService.php';
require_once __DIR__ . '/../services/IngredientService.php';
require_once __DIR__ . '/../services/StepService.php';

class CocktailController
{
    private $cocktailService;
    private $ingredientService;
    private $stepService;
    private $difficultyRepository;

    public function __construct()
    {
        $db = Database::getConnection();  // Get the database connection

        // Initialize repositories
        $cocktailRepository = new CocktailRepository($db);
        $categoryRepository = new CategoryRepository($db);
        $ingredientRepository = new IngredientRepository($db);
        $stepRepository = new StepRepository($db);
        $tagRepository = new TagRepository($db);
        $this->difficultyRepository = new DifficultyRepository($db);
        $unitRepository = new UnitRepository($db);  // Add UnitRepository for units

        // Initialize services
        $this->ingredientService = new IngredientService($ingredientRepository, $unitRepository);
        $this->stepService = new StepService($stepRepository);

        // Initialize the CocktailService with services and repositories
        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $this->ingredientService,
            $this->stepService,
            $tagRepository,
            $this->difficultyRepository
        );
    }

    // List all cocktails (public access)
    public function index()
    {
        $cocktails = $this->cocktailService->getAllCocktails();
        $categories = $this->cocktailService->getCategories();
        $loggedInUserId = $_SESSION['user']['id'] ?? null;

        require_once __DIR__ . '/../views/cocktails/index.php'; // Load the view to display cocktails
    }
    private function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    // Show the form to add a new cocktail (only for logged-in users)
    public function add() {
        if (!AuthController::isLoggedIn()) {
            redirect('login'); // Redirect to login if the user is not logged in
        }
    
        $cocktail = null; // Initialize as null for the add scenario
    
        // Fetch necessary data for the form
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientService->getAllUnits(); // Fetch units for ingredients
    
        $isEditing = false; // Set this flag to indicate that we are adding a new cocktail
    
        require_once __DIR__ . '/../views/cocktails/form.php'; // Load the form for adding a cocktail
    }

    // Store a new cocktail in the database (only for logged-in users)
    public function store()
    {
        if (!AuthController::isLoggedIn()) {
            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateCocktailInput($_POST);

            // Handle image upload
            $image = $this->handleImageUpload($_FILES['image'], $errors);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $this->redirect('/cocktails/add'); // Redirect back to the form with errors
            }

            // Prepare the cocktail data array
            $cocktailData = [
                'user_id' => $_SESSION['user']['id'],
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'image' => $image,
                'category_id' => $_POST['category_id'],
                'difficulty_id' => $_POST['difficulty_id']
            ];

            // Save the cocktail and get the new cocktail ID
            try {
                $cocktailId = $this->cocktailService->createCocktail($cocktailData);
                $this->handleCocktailSteps($cocktailId, $_POST['steps']);
                $this->handleCocktailIngredients($cocktailId, $_POST['ingredients'], $_POST['quantities'], $_POST['units']);
                $this->redirect('/cocktails/' . $cocktailId); // Redirect to the cocktail view after successful save
            } catch (Exception $e) {
                $_SESSION['errors'] = ["Failed to create cocktail: " . $e->getMessage()];
                $this->redirect('/cocktails/add');
            }
        }
    }

    // Show the form to edit an existing cocktail (only for the owner or admin)
    public function edit($cocktailId)
    {
        if (!AuthController::isLoggedIn()) {
            redirect('login'); // Redirect to login if the user is not logged in
        }

        $cocktail = $this->cocktailService->getCocktailById($cocktailId);

        // Only allow the owner or an admin to edit the cocktail
        if ($cocktail->getUserId() !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            redirect('/cocktails'); // Redirect if the user doesn't have permission
        }

        $ingredients = $this->cocktailService->getCocktailIngredients($cocktailId);
        $steps = $this->cocktailService->getCocktailSteps($cocktailId);
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientService->getAllUnits(); // Fetch units from IngredientService

        // Set the $isEditing flag to true
        $isEditing = true;

        require_once __DIR__ . '/../views/cocktails/form.php'; // Load the edit form
    }

    public function update($cocktailId)
    {
        // Ensure user is logged in and has permission
        if (!AuthController::isLoggedIn()) {
            redirect('login');
        }

        $cocktail = $this->cocktailService->getCocktailById($cocktailId);

        // Only allow the owner or an admin to update
        if ($cocktail->getUserId() !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            redirect('/cocktails');
        }

        // Validate input
        $errors = $this->validateCocktailInput($_POST);
        $image = $this->handleImageUpdate($_FILES['image'], $cocktail, $errors);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /cocktails/' . $cocktailId . '/edit');
            exit();
        }

        $cocktailData = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category_id' => $_POST['category_id'],
            'difficulty_id' => $_POST['difficulty_id'],
            'image' => $image
        ];

        try {
            $this->clearSteps($cocktailId);
            // Update the cocktail
            $this->cocktailService->updateCocktail($cocktailId, $cocktailData);
            
            // Handle new steps
            $this->handleCocktailSteps($cocktailId, $_POST['steps']);
            // Check for deletions of steps
            if (!empty($_POST['delete_steps'])) {
                foreach ($_POST['delete_steps'] as $stepId) {
                    $this->stepService->deleteStep($cocktailId, $stepId); // Call to delete the step
                }
            }
          


            // Handle ingredients
            $this->ingredientService->updateIngredients($cocktailId, $_POST['ingredients'], $_POST['quantities'], $_POST['units']);

            // Handle new ingredients
            $this->handleCocktailIngredients($cocktailId, $_POST['ingredients'], $_POST['quantities'], $_POST['units']);

            $this->redirect('/cocktails/' . $cocktailId . '-' . urlencode($cocktailData['title']));
        } catch (Exception $e) {
            $_SESSION['errors'] = ["Failed to update cocktail: " . $e->getMessage()];
            header('Location: /cocktails/' . $cocktailId . '/edit');
            exit();
        }
    }

  // Delete steps
public function deleteStep($cocktailId)
{
    // Ensure the user is logged in
    if (!AuthController::isLoggedIn()) {
        redirect('login'); // Redirect to login if the user is not logged in
    }

    // Fetch the cocktail to verify ownership
    $cocktail = $this->cocktailService->getCocktailById($cocktailId);
    
    // Only allow the owner or an admin to delete the step
    if ($cocktail->getUserId() !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
        redirect('/cocktails/' . $cocktailId); // Redirect if the user doesn't have permission
    }

    // Check if there are steps to delete
    if (isset($_POST['delete_steps']) && is_array($_POST['delete_steps'])) {
        // Loop through each step ID provided for deletion
        foreach ($_POST['delete_steps'] as $stepId) {
            // Ensure the step ID is valid and delete it
            if (is_numeric($stepId)) {
                $this->stepService->deleteStep($cocktailId, $stepId); // Call the service to delete each step
            }
        }
    }
    
    // Redirect back to the cocktail edit page after deletion
    redirect('/cocktails/' . $cocktailId . '-' . urlencode($cocktail->getTitle())); 
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
        $requiredFields = ['title' => 'Title', 'description' => 'Description', 'category_id' => 'Category', 'difficulty_id' => 'Difficulty'];

        foreach ($requiredFields as $field => $label) {
            if (empty($data[$field])) {
                $errors[] = "$label is required.";
            }
        }

        return $errors;
    }

    // Handle image upload for new cocktails
    private function handleImageUpload($file, &$errors)
    {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $image = $file['name'];
            $target_dir = __DIR__ . '/../../public/uploads/cocktails/';
            $target_file = $target_dir . basename($image);
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileType = mime_content_type($file['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "Invalid image format. Allowed formats are JPEG, PNG, and WEBP.";
            }
            if (!move_uploaded_file($file['tmp_name'], $target_file)) {
                $errors[] = "There was an error uploading the image.";
            }
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

    // View cocktail details (public access)
    public function view($cocktailId, $action = 'view')
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $cocktail = $this->cocktailService->getCocktailById($cocktailId);
        $isEditing = ($action === 'edit');

        $ingredients = $this->cocktailService->getCocktailIngredients($cocktailId);
        $steps = $this->cocktailService->getCocktailSteps($cocktailId);
        $category = $this->cocktailService->getCategoryByCocktailId($cocktailId);
        $tags = $this->cocktailService->getCocktailTags($cocktailId);
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientService->getAllUnits();

        if ($isEditing) {
            require_once __DIR__ . '/../views/cocktails/form.php'; // Load the edit form
        } else {
            require_once __DIR__ . '/../views/cocktails/view.php'; // Load the view page
        }
    }
    public function handleCocktailSteps($cocktailId, $steps)
    {
        // Clear existing steps for this cocktail first
        $this->stepService->clearStepsByCocktailId($cocktailId);

        foreach ($steps as $stepNumber => $instruction) {
            if (!empty($instruction)) {
                // Create a new step in the repository
                $this->stepService->addStep($cocktailId, $instruction, $stepNumber + 1);
            }
        }
    }
    private function handleCocktailIngredients($cocktailId, $ingredients, $quantities, $units)
    {
        // Clear existing ingredients for the cocktail
        $this->ingredientService->clearIngredientsByCocktailId($cocktailId);

        foreach ($ingredients as $index => $ingredientName) {
            $quantity = $quantities[$index] ?? null;
            $unitId = $units[$index] ?? null;

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
        if (!AuthController::isLoggedIn()) {
            redirect('login'); // Redirect to login if the user is not logged in
        }

        $cocktail = $this->cocktailService->getCocktailById($cocktailId);

        // Only allow the owner or an admin to delete the cocktail
        if ($cocktail->getUserId() !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            redirect('/cocktails'); // Redirect if the user doesn't have permission
        }

        // Delete the cocktail
        $this->cocktailService->deleteCocktail($cocktailId);
        redirect('/cocktails'); // Redirect to the cocktail list
    }
}
