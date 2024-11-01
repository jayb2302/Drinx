<?php
session_start(); 
ob_start();

require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../router.php';
require_once __DIR__ . '/../routes.php';

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
                $controller = new CommentController($commentService); // Pass the CommentService here
            } elseif ($controllerClass === 'LikeController') {
                $controller = $likeController; // Use the instantiated LikeController
            } else {
                $controller = new $controllerClass(); // For other controllers that don't need dependencies
            }

            call_user_func_array([$controller, $method], $params); // Call method with params
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