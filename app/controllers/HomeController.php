<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../repositories/DifficultyRepository.php'; 
require_once __DIR__ . '/../services/CocktailService.php';
require_once __DIR__ . '/../services/IngredientService.php';  // Add this
require_once __DIR__ . '/../services/StepService.php';        // Add this
require_once __DIR__ . '/../repositories/UnitRepository.php'; // Add this

class HomeController
{
    private $cocktailService;
    private $ingredientService;

    public function __construct()
    {
        $db = Database::getConnection(); 

        // Instantiate the repositories
        $cocktailRepository = new CocktailRepository($db);
        $categoryRepository = new CategoryRepository($db);
        $ingredientRepository = new IngredientRepository($db);
        $stepRepository = new StepRepository($db);
        $tagRepository = new TagRepository($db);
        $difficultyRepository = new DifficultyRepository($db);
        $unitRepository = new UnitRepository($db);  // Instantiate UnitRepository for IngredientService

        // Instantiate the services
        $ingredientService = new IngredientService($ingredientRepository, $unitRepository);  // Use service instead of repository
        $stepService = new StepService($stepRepository);  // Use service instead of repository

        // Pass the service instances to the CocktailService constructor
        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $ingredientService,  // Pass the service instead of repository
            $stepService,        // Pass the service instead of repository
            $tagRepository,
            $difficultyRepository
        );
    }

    public function index()
    {
        $loggedInUserId = $_SESSION['user']['id'] ?? null;

        // Fetch all cocktails
        $cocktails = $this->cocktailService->getAllCocktails();

        // Fetch categories and units for the form (if needed)
        $categories = $this->cocktailService->getCategories();
        $units = $this->cocktailService->getAllUnits();  // Fetch units from the service

        // Check if an edit action is requested
        $isEditing = false;
        $cocktail = null;
        $steps = [];
        $ingredients = [];

        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $cocktailId = $_GET['id'];
            $cocktail = $this->cocktailService->getCocktailById($cocktailId);

            if ($cocktail && ($cocktail->getUserId() === $loggedInUserId || AuthController::isAdmin())) {
                // Fetch steps and ingredients for the cocktail being edited
                $steps = $this->cocktailService->getCocktailSteps($cocktailId);
                $ingredients = $this->cocktailService->getCocktailIngredients($cocktailId);
                $isEditing = true;
            } else {
                // Redirect if the user doesn't have permission to edit the cocktail
                redirect('/cocktails');
            }
        }

        // Pass the necessary data to the view
        require_once __DIR__ . '/../views/home.php'; // Load the home view
    }
}