<?php
session_start(); 
ob_start();

require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../router.php';
require_once __DIR__ . '/../routes.php';

// Dependency injection (manual for now)
$db = Database::getConnection(); 

// Instantiate the repositories
$cocktailRepository = new CocktailRepository($db);
$categoryRepository = new CategoryRepository($db);
$ingredientRepository = new IngredientRepository($db);
$stepRepository = new StepRepository($db);
$tagRepository = new TagRepository($db);
$difficultyRepository = new DifficultyRepository($db);
$unitRepository = new UnitRepository($db);
$likeRepository = new LikeRepository($db);
$commentRepository = new CommentRepository($db);

// Instantiate the services
$ingredientService = new IngredientService($ingredientRepository, $unitRepository);
$stepService = new StepService($stepRepository);
$likeService = new LikeService($likeRepository);
$userService = new UserService();
$commentService = new CommentService($commentRepository);

// Instantiate the CocktailService with all its required dependencies
$cocktailService = new CocktailService(
    $cocktailRepository,
    $categoryRepository,
    $ingredientService,
    $stepService,
    $tagRepository,
    $difficultyRepository,
    $likeRepository
);

// Instantiate the HomeController with the necessary services
$homeController = new HomeController($cocktailService, $ingredientService, $likeService, $userService);

// Resolve the current request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$action = $router->resolve($requestUri);

// Dependency injection (manual for now)
$db = Database::getConnection(); 
$commentRepository = new CommentRepository($db);
$commentService = new CommentService($commentRepository);

// Dependency for Like functionality
$likeRepository = new LikeRepository($db);
$likeService = new LikeService($likeRepository);
$likeController = new LikeController($likeService);
// public/index.php
// $userId = $_GET['user_id'] ?? $_SESSION['user']['id']; // Assuming user_id comes from GET or defaults to the logged-in user
// (new UserController())->profile($userId);

if ($action) {
    // Unpack the action array
    $controllerAction = $action[0]; // This should be the controller
    $params = $action[1]; // This should be the parameters

    if (is_array($controllerAction) && count($controllerAction) === 2) {
        [$controllerClass, $method] = $controllerAction; // Unpack controller class and method

        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            // Inject the dependencies manually
            if ($controllerClass === 'CommentController') {
                $controller = new CommentController($commentService);
            } elseif ($controllerClass === 'LikeController') {
                $controller = new LikeController($likeService); // Use the instantiated LikeController
            } elseif ($controllerClass === 'SearchController') {
                $controller = new SearchController($userService, $cocktailService);
            } elseif ($controllerClass === 'HomeController') {
                $controller = $homeController; // Use the instantiated HomeController
            } else {
                $controller = new $controllerClass();
            }

            call_user_func_array([$controller, $method], $params);
        } else {
            http_response_code(404);
            include '../app/views/404.php'; // Load 404 error page
        }
    } else {
        http_response_code(404);
        include '../app/views/404.php'; 
    }
} else {
    http_response_code(404);
    include '../app/views/404.php'; 
}

ob_end_flush();