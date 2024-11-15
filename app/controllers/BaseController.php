<?php

require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../repositories/DifficultyRepository.php';
require_once __DIR__ . '/../repositories/LikeRepository.php';
require_once __DIR__ . '/../repositories/UnitRepository.php';
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
        $this->dbConnection = Database::getConnection();

        // Instantiate Repositories
        $cocktailRepository = new CocktailRepository($this->dbConnection);
        $categoryRepository = new CategoryRepository($this->dbConnection);
        $ingredientRepository = new IngredientRepository($this->dbConnection);
        $unitRepository = new UnitRepository($this->dbConnection);
        $stepRepository = new StepRepository($this->dbConnection);
        $tagRepository = new TagRepository($this->dbConnection);
        $difficultyRepository = new DifficultyRepository($this->dbConnection);
        $likeRepository = new LikeRepository($this->dbConnection);
        $userRepository = new UserRepository($this->dbConnection);

        // Instantiate Services
        $ingredientService = new IngredientService($ingredientRepository, $unitRepository);
        $this->stepService = new StepService($stepRepository);
        
        // Instantiate the CocktailService with all its required dependencies
        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $ingredientService,
            $this->stepService,
            $tagRepository,
            $difficultyRepository,
            $likeRepository,
            $userRepository
        );

        // Instantiate UserService
        $this->userService = new UserService($userRepository);  // Assuming UserService depends on UserRepository
    }
}