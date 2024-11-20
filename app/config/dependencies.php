<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../repositories/DifficultyRepository.php';
require_once __DIR__ . '/../repositories/UnitRepository.php';
require_once __DIR__ . '/../repositories/LikeRepository.php';
require_once __DIR__ . '/../repositories/CommentRepository.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
// Services
require_once __DIR__ . '/../services/AdminService.php';
require_once __DIR__ . '/../services/IngredientService.php';
require_once __DIR__ . '/../services/StepService.php';
require_once __DIR__ . '/../services/LikeService.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/CommentService.php';
require_once __DIR__ . '/../services/CocktailService.php';
// Controllers
require_once __DIR__ . '/../controllers/TagController.php';
require_once __DIR__ . '/../controllers/IngredientController.php';
require_once __DIR__ . '/../controllers/SearchController.php';
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/CocktailController.php';

// Database connection
$db = Database::getConnection();

// Repositories
$cocktailRepository = new CocktailRepository($db);
$categoryRepository = new CategoryRepository($db);
$ingredientRepository = new IngredientRepository($db);
$stepRepository = new StepRepository($db);
$tagRepository = new TagRepository($db);
$difficultyRepository = new DifficultyRepository($db);
$unitRepository = new UnitRepository($db);
$likeRepository = new LikeRepository($db);
$commentRepository = new CommentRepository($db);
$userRepository = new UserRepository($db);

// Services
$adminService = new AdminService(
    $userRepository,
    $cocktailRepository,
    $ingredientRepository,
    $tagRepository,
    $commentRepository
);
$ingredientService = new IngredientService($ingredientRepository, $unitRepository);
$stepService = new StepService($stepRepository);
$likeService = new LikeService($likeRepository);
$userService = new UserService();
$commentService = new CommentService($commentRepository, $userService);
$cocktailService = new CocktailService(
    $cocktailRepository,
    $categoryRepository,
    $ingredientService,
    $stepService,
    $tagRepository,
    $difficultyRepository,
    $likeRepository,
    $userRepository,
    $commentRepository
);

// Controllers
$authController = new AuthController();
$homeController = new HomeController(
    $cocktailService, 
    $ingredientService, 
    $likeService, 
    $userService, 
    $categoryRepository, 
    $difficultyRepository, 
    $tagRepository
);


$adminController = new AdminController($adminService, $authController, $cocktailService);

$tagController = new TagController($tagRepository);
$ingredientController = new IngredientController($ingredientRepository, $tagRepository);
$searchController = new SearchController($userService, $cocktailService);