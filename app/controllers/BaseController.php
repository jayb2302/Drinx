<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php'; // Corrected path
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../repositories/DifficultyRepository.php';
require_once __DIR__ . '/../repositories/LikeRepository.php';
require_once __DIR__ . '/../repositories/UnitRepository.php'; // Ensure all dependencies are included
require_once __DIR__ . '/../services/IngredientService.php';
require_once __DIR__ . '/../services/StepService.php';
require_once __DIR__ . '/../services/CocktailService.php'; // Assuming you need this for instantiation
require_once __DIR__ . '/../services/UserService.php'; // Include UserService if necessary

class BaseController {
    protected $userService;
    protected $cocktailService;
    protected $stepService;

    public function __construct() {
        $dbConnection = Database::getConnection();

        // Instantiate repositories
        $cocktailRepository = new CocktailRepository($dbConnection);
        $categoryRepository = new CategoryRepository($dbConnection); // Ensure this is defined
        $ingredientRepository = new IngredientRepository($dbConnection);
        $unitRepository = new UnitRepository($dbConnection);
        $stepRepository = new StepRepository($dbConnection); // Instantiate StepRepository
        $ingredientService = new IngredientService($ingredientRepository, $unitRepository);
        $stepService = new StepService($stepRepository); // Pass only StepRepository if that is all that is needed
        $tagRepository = new TagRepository($dbConnection);
        $difficultyRepository = new DifficultyRepository($dbConnection);
        $likeRepository = new LikeRepository($dbConnection);

        // Instantiate the CocktailService with all its required dependencies
        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $ingredientService,
            $stepService,
            $tagRepository,
            $difficultyRepository,
            $likeRepository
        );

        // Instantiate UserService
        $this->userService = new UserService(/* pass dependencies if necessary */);
    }
}