<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';  
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php'; // Include the TagRepository
require_once __DIR__ . '/../services/CocktailService.php';


class HomeController {
    private $cocktailService;
    
    public function __construct() {
        $db = Database::getConnection();  // Get the database connection

        // Instantiate the repositories
        $cocktailRepository = new CocktailRepository($db);
        $categoryRepository = new CategoryRepository($db);
        $ingredientRepository = new IngredientRepository($db);
        $stepRepository = new StepRepository($db);
        $tagRepository = new TagRepository($db); 

        // Pass the repository instances to the CocktailService constructor
        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $ingredientRepository,
            $stepRepository,
            $tagRepository 
        );
    }

    public function index() {
        // Fetch all cocktails
        $cocktails = $this->cocktailService->getAllCocktails(); 

        // Pass $cocktails to the view
        require_once __DIR__ . '/../views/home.php'; // Load the home view
    }
}