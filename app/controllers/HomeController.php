<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../repositories/DifficultyRepository.php';
require_once __DIR__ . '/../services/CocktailService.php';
require_once __DIR__ . '/../services/IngredientService.php';
require_once __DIR__ . '/../services/StepService.php';
require_once __DIR__ . '/../repositories/UnitRepository.php';

class HomeController
{
    private $cocktailService;
    private $ingredientService; // Define as a class property

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
        $unitRepository = new UnitRepository($db);

        // Instantiate the services
        $this->ingredientService = new IngredientService($ingredientRepository, $unitRepository);  // Use class property
        $stepService = new StepService($stepRepository);

        // Pass the service instances to the CocktailService constructor
        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $this->ingredientService, // Use class property
            $stepService,
            $tagRepository,
            $difficultyRepository
        );
    }

    public function index()
    {
        $loggedInUserId = $_SESSION['user']['id'] ?? null;

        // Fetch all cocktails
        $cocktails = $this->cocktailService->getAllCocktails();

        // Determine if we should show the add form
        $isAdding = isset($_GET['action']) && $_GET['action'] === 'add';

        // Fetch categories and units if we are adding a cocktail
    $categories = $this->cocktailService->getCategories(); // Get categories from service
    $units = $this->ingredientService->getAllUnits(); // Get units from service Now this will work

        // Pass the necessary data to the view
        require_once __DIR__ . '/../views/home.php';
    }
}
