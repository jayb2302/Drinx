<?php
require_once __DIR__ . '/../config/database.php'; // Ensure DB connection
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../services/CocktailService.php';


class HomeController {
    private $cocktailService;

    public function __construct() {
        // Get the database connection
        $db = Database::getConnection();

        // Instantiate the repositories
        $cocktailRepository = new CocktailRepository($db);
        $categoryRepository = new CategoryRepository($db);
        $ingredientRepository = new IngredientRepository($db);
        $stepRepository = new StepRepository($db);

        // Pass the repository instances to the CocktailService constructor
        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $ingredientRepository,
            $stepRepository
        );
    }

    public function index() {
        // Fetching all cocktails
        $cocktails = $this->cocktailService->getAllCocktails();
        
        // Set dynamic titles
        $pageTitle = "Welcome to Drinx"; // Set your dynamic page title
        $metaTitle = "Explore Our Delicious Cocktails"; // Set your dynamic meta title

        // Pass titles and cocktails data to the view
        require_once __DIR__ . '/../views/home.php'; // Load the home view
    }
}