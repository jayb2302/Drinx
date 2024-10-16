<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../services/CocktailService.php';
require_once __DIR__ . '/../controllers/AuthController.php';

class CocktailController {
    private $cocktailService;
    private $ingredientRepository;

    public function __construct() {
        $db = Database::getConnection();  // Get the database connection

        // Initialize repositories and services
        $cocktailRepository = new CocktailRepository($db);
        $categoryRepository = new CategoryRepository($db);
        $this->ingredientRepository = new IngredientRepository($db);
        $stepRepository = new StepRepository($db);
        $tagRepository = new TagRepository($db);

        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $this->ingredientRepository,
            $stepRepository,
            $tagRepository
        );
    }

    // List all cocktails (public access)
    public function index() {
        $cocktails = $this->cocktailService->getAllCocktails();
        $categories = $this->cocktailService->getCategories();
        require_once __DIR__ . '/../views/cocktails/index.php'; // Load the view to display cocktails
    }

    // Show the form to add a new cocktail (only for logged-in users)
    public function add() {
        if (!AuthController::isLoggedIn()) {
            redirect('login'); // Redirect to login if the user is not logged in
        }

        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientRepository->getAllUnits(); // Fetch units for ingredients
        require_once __DIR__ . '/../views/cocktails/form.php'; // Load the form for adding a cocktail
    }

    // Store a new cocktail in the database (only for logged-in users)
    public function store() {
        if (!AuthController::isLoggedIn()) {
            redirect('login'); // Redirect to login if the user is not logged in
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle image upload
            $image = $_FILES['image']['name'];
            $target_dir = __DIR__ . '/../public/uploads/cocktails/';
            $target_file = $target_dir . basename($image);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Save cocktail details to the database
                $cocktailData = [
                    'user_id' => $_SESSION['user']['id'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'prep_time' => $_POST['prep_time'],
                    'image' => $image,
                    'category_id' => $_POST['category_id'],
                    'difficulty_id' => $_POST['difficulty_id']
                ];

                // Save cocktail to the database
                $this->cocktailService->createCocktail($cocktailData);
                redirect('/cocktails');
            } else {
                echo "<p>There was an error uploading the image.</p>";
            }
        }
    }

    // Show the form to edit an existing cocktail (only for the owner or admin)
    public function edit($cocktailId) {
        if (!AuthController::isLoggedIn()) {
            redirect('login'); // Redirect to login if the user is not logged in
        }

        $cocktail = $this->cocktailService->getCocktailById($cocktailId);

        // Only allow the owner or an admin to edit the cocktail
        if ($cocktail->getUserId() !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            redirect('/cocktails'); // Redirect if the user doesn't have permission
        }

        $steps = $this->cocktailService->getCocktailSteps($cocktailId);
        $ingredients = $this->cocktailService->getCocktailIngredients($cocktailId);
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientRepository->getAllUnits();

        $isEditing = true; // Indicate that the form is in editing mode
        require_once __DIR__ . '/../views/cocktails/form.php'; // Load the edit form
    }

    // Update an existing cocktail in the database (only for the owner or admin)
    public function update($cocktailId) {
        if (!AuthController::isLoggedIn()) {
            redirect('login'); // Redirect to login if the user is not logged in
        }

        $cocktail = $this->cocktailService->getCocktailById($cocktailId);

        // Only allow the owner or an admin to update the cocktail
        if ($cocktail->getUserId() !== $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            redirect('/cocktails'); // Redirect if the user doesn't have permission
        }

        // Prepare cocktail data for update
        $cocktailData = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category_id' => $_POST['category_id'],
            'difficulty_id' => $_POST['difficulty_id']
        ];

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $target_dir = __DIR__ . '/../public/uploads/cocktails/';
            $target_file = $target_dir . basename($image);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $cocktailData['image'] = $image; // Update with the new image
            } else {
                echo "<p>There was an error uploading the image.</p>";
                return;
            }
        } else {
            $currentCocktail = $this->cocktailService->getCocktailById($cocktailId);
            $cocktailData['image'] = $currentCocktail->getImage(); // Retain the current image
        }

        // Update cocktail information in the database
        $this->cocktailService->updateCocktail($cocktailId, $cocktailData);

        // Handle steps
        $this->cocktailService->clearSteps($cocktailId); // Clear existing steps
        if (isset($_POST['steps']) && is_array($_POST['steps'])) {
            foreach ($_POST['steps'] as $step) {
                if (!empty($step)) {
                    $this->cocktailService->addStep($cocktailId, $step);
                }
            }
        }

        // Redirect to the updated cocktail view
        redirect('/cocktails/view/' . $cocktailId . '-' . urlencode($cocktailData['title']));
    }

    // View cocktail details (public access)
    public function view($cocktailId, $action = 'view') {
        $cocktail = $this->cocktailService->getCocktailById($cocktailId);
        $isEditing = ($action === 'edit'); // Determine if we are in editing mode

        $ingredients = $this->cocktailService->getCocktailIngredients($cocktailId);
        $steps = $this->cocktailService->getCocktailSteps($cocktailId);
        $category = $this->cocktailService->getCategoryByCocktailId($cocktailId);
        $tags = $this->cocktailService->getCocktailTags($cocktailId);
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientRepository->getAllUnits();

        require_once __DIR__ . '/../views/cocktails/view.php'; // Load the cocktail view
    }

    // Delete a cocktail (only for the owner or admin)
    public function delete($cocktailId) {
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