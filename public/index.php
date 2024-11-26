<?php
session_start(); 
ob_start();

require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../router.php';
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../app/config/dependencies.php';

// Resolve the current request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$action = $router->resolve($requestUri);

if ($action) {
    // Unpack the action array
    $controllerAction = $action[0]; // This should be the controller
    $params = $action[1]; // This should be the parameters

    if (is_array($controllerAction) && count($controllerAction) === 2) {
        [$controllerClass, $method] = $controllerAction; // Unpack controller class and method

        $controllers = [
            'CocktailController' => $cocktailController,
            'CommentController' => $commentController,
            'LikeController' => $likeController,
            'SearchController' => $searchController,
            'HomeController' => $homeController,
            'AdminController' => $adminController,
            'TagController' => $tagController,
            'IngredientController' => $ingredientController,
            'UserController' => $userController,
        ];

        if (isset($controllers[$controllerClass])) {
            $controller = $controllers[$controllerClass];
        } elseif (class_exists($controllerClass)) {
            // Fallback for controllers without dependencies
            $controller = new $controllerClass();
        } else {
            http_response_code(404);
            include '../app/views/404.php'; // Load 404 error page
            exit;
        }

        if (method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $params);
        } else {
            http_response_code(404);
            include '../app/views/404.php'; 
            exit;
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