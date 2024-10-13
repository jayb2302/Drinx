<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';  
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../services/CocktailService.php';

class CocktailController {
    private $cocktailService;
    private $ingredientRepository; // Add this property

    public function __construct() {
        $db = Database::getConnection();  // Get the database connection

        // Instantiate the repositories
        $cocktailRepository = new CocktailRepository($db);
        $categoryRepository = new CategoryRepository($db);
        $this->ingredientRepository = new IngredientRepository($db); // Initialize the ingredient repository
        $stepRepository = new StepRepository($db);

        // Pass the repository instances to the CocktailService constructor
        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $this->ingredientRepository, // Pass the initialized ingredient repository
            $stepRepository
        );
    }

    // Index method to list all cocktails
    public function index() {
        $cocktails = $this->cocktailService->getAllCocktails(); 
        $categories = $this->cocktailService->getCategories(); // Fetch categories
 
        require_once __DIR__ . '/../views/cocktails/index.php'; // Load the cocktails view
    }

    // Show the form to add a new cocktail
    public function add() {
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientRepository->getAllUnits(); // Fetch units
        require_once __DIR__ . '/../views/cocktails/form.php'; // Load the form for adding a new cocktail
    }

    // View a specific cocktail by ID
    public function view($cocktailId) {
        error_log("Viewing cocktail with ID: " . $cocktailId); // Log the cocktail ID
        $cocktail = $this->cocktailService->getCocktailById($cocktailId);
    
        if ($cocktail) {
            error_log("Cocktail found: " . $cocktail->getTitle()); // Log cocktail title
    
            // Fetch ingredients and steps for the cocktail
            $ingredients = $this->cocktailService->getCocktailIngredients($cocktailId);
            $steps = $this->cocktailService->getCocktailSteps($cocktailId);
            $category = $this->cocktailService->getCategoryByCocktailId($cocktailId);

            // Pass cocktail, ingredients, and steps to the view
            require_once __DIR__ . '/../views/cocktails/view.php'; // Load the cocktail view
        } else {
            error_log("Cocktail not found for ID: " . $cocktailId); // Log not found
            echo "<p>Cocktail not found.</p>";
        }
    }

    // Show the form to edit an existing cocktail
    public function edit($cocktailId) {
        $cocktail = $this->cocktailService->getCocktailById($cocktailId);
        $steps = $this->cocktailService->getCocktailSteps($cocktailId);
        $ingredients = $this->cocktailService->getCocktailIngredients($cocktailId);
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientRepository->getAllUnits(); // Fetch units

        if ($cocktail) {
            require_once __DIR__ . '/../views/cocktails/form.php'; // Load the edit form
        } else {
            echo "<p>Cocktail not found.</p>";
        }
    }

    // Store a new cocktail in the database
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle file upload
            $image = $_FILES['image']['name'];
            $target_dir = __DIR__ . '/../../public/uploads/cocktails/';
            $target_file = $target_dir . basename($image);

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Save cocktail details to the database
                $cocktailData = [
                    'user_id' => $_SESSION['user_id'], // Assuming user_id is stored in session
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'prep_time' => $_POST['prep_time'],
                    'image' => $image,
                    'category_id' => $_POST['category_id'],
                    'difficulty_id' => $_POST['difficulty_id']
                ];

                // Save cocktail to the database
                $this->cocktailService->createCocktail($cocktailData);
                redirect('/cocktails'); // Redirect to the list of cocktails
            } else {
                // Handle upload error
                echo "<p>There was an error uploading the image.</p>";
            }
        }
    }
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
            $cocktailId = intval($_GET['id']);
            
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
                $target_dir = __DIR__ . '/../../public/uploads/cocktails/';
                $target_file = $target_dir . basename($image);
    
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $cocktailData['image'] = $image; // Set new image
                } else {
                    echo "<p>There was an error uploading the image.</p>";
                    return; // Exit if image upload fails
                }
            } else {
                // If no new image, keep the existing one
                $currentCocktail = $this->cocktailService->getCocktailById($cocktailId);
                if ($currentCocktail) {
                    $cocktailData['image'] = $currentCocktail->getImage(); // Retain current image
                }
            }
    
            // Update cocktail information in the database
            $this->cocktailService->updateCocktail($cocktailId, $cocktailData);
    
            // Clear existing steps for the cocktail
            $this->cocktailService->clearSteps($cocktailId);
    
            // Insert new steps if they exist
            if (isset($_POST['steps']) && is_array($_POST['steps'])) {
                foreach ($_POST['steps'] as $step) {
                    if (!empty($step)) { // Ensure the step is not empty
                        $this->cocktailService->addStep($cocktailId, $step);
                    }
                }
            }
    
            // Redirect to the updated cocktail view
            redirect('/cocktails/view/' . $cocktailId . '-' . urlencode($cocktailData['title']));
        } else {
            echo "<p>Invalid cocktail ID.</p>";
        }
    }

    // Delete a cocktail
    public function delete() {
        if (isset($_GET['id'])) {
            $cocktailId = intval($_GET['id']);
            $this->cocktailService->deleteCocktail($cocktailId);
            redirect('/cocktails'); // Redirect to the list of cocktails
        } else {
            echo "<p>Invalid cocktail ID.</p>";
        }
    }

}