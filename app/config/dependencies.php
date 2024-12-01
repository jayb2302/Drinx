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
require_once __DIR__ . '/../services/ImageService.php';
require_once __DIR__ . '/../services/BadgeService.php';

// Controllers
require_once __DIR__ . '/../controllers/TagController.php';
require_once __DIR__ . '/../controllers/IngredientController.php';
require_once __DIR__ . '/../controllers/SearchController.php';
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/CocktailController.php';
require_once __DIR__ . '/../controllers/CommentController.php';
require_once __DIR__ . '/../controllers/LikeController.php';

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
$badgeRepository = new BadgeRepository($db);

// Services
$adminService = new AdminService(
    $userRepository,
    $cocktailRepository,
    $ingredientRepository,
    $tagRepository,
    $commentRepository
);
$badgeService = new BadgeService($badgeRepository);
$imageService = new ImageService();
$ingredientService = new IngredientService($ingredientRepository, $unitRepository, $tagRepository);
$stepService = new StepService($stepRepository);
$likeService = new LikeService($likeRepository);
$userService = new UserService($userRepository);
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
    $commentRepository,
);

// Controllers
$cocktailController = new CocktailController(
    $cocktailService,
    $ingredientService,
    $stepService,
    $difficultyRepository,
    $commentService,
    $likeService,
    $tagRepository,
    $userService,
    $imageService,
    $badgeService
);

$authController = new AuthController();
$homeController = new HomeController(
    $cocktailService, 
    $ingredientService, 
    $likeService, 
    $userService, 
    $categoryRepository, 
    $difficultyRepository, 
    $tagRepository,
);
$userController = new UserController(
    $userService,
    $cocktailService,
    $imageService,
    $badgeService
);

$adminController = new AdminController($adminService, $authController, $cocktailService, $ingredientService);
$commentController = new CommentController($commentService, $cocktailService);
$likeController = new LikeController($likeService);
$tagController = new TagController($tagRepository);
$ingredientController = new IngredientController($ingredientRepository, $tagRepository);
$searchController = new SearchController($userService, $cocktailService, $ingredientService);