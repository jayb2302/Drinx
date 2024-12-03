<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../repositories/DifficultyRepository.php';
require_once __DIR__ . '/../repositories/LikeRepository.php';
require_once __DIR__ . '/../repositories/UnitRepository.php';
require_once __DIR__ . '/../repositories/CommentRepository.php';
require_once __DIR__ . '/../services/IngredientService.php';
require_once __DIR__ . '/../services/StepService.php';
require_once __DIR__ . '/../services/CocktailService.php';
require_once __DIR__ . '/../services/UserService.php';


class BaseController {
    protected $userService;
    protected $cocktailService;
    protected $stepService;
    protected $dbConnection;

    public function __construct() {
        // Set up DB connection once
        $dbConnection = Database::getConnection();

        // Instantiate Repositories
        $cocktailRepository = new CocktailRepository($dbConnection);
        $categoryRepository = new CategoryRepository($dbConnection);
        $ingredientRepository = new IngredientRepository($dbConnection);
        $unitRepository = new UnitRepository($dbConnection);
        $stepRepository = new StepRepository($dbConnection);
        $tagRepository = new TagRepository($dbConnection);
        $difficultyRepository = new DifficultyRepository($dbConnection);
        $likeRepository = new LikeRepository($dbConnection);
        $userRepository = new UserRepository($dbConnection);
        $commentRepository = new CommentRepository($dbConnection);

        // Instantiate Services
        $ingredientService = new IngredientService($ingredientRepository, $unitRepository, $tagRepository);
        $stepService = new StepService($stepRepository); // Pass only StepRepository if that is all that is needed
        $tagRepository = new TagRepository($dbConnection);
        $difficultyRepository = new DifficultyRepository($dbConnection);
        $likeRepository = new LikeRepository($dbConnection);
        $userRepository = new UserRepository($dbConnection);

        // Instantiate the CocktailService with all its required dependencies
        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $ingredientService,
            $this->stepService,
            $tagRepository,
            $difficultyRepository,
            $likeRepository,
            $userRepository,
            $commentRepository 
        );

        // Instantiate UserService
        $this->userService = new UserService($userRepository);  // Assuming UserService depends on UserRepository
    }
}