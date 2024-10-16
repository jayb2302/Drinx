<?php
session_start(); 
ob_start();

require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../router.php';
require_once __DIR__ . '/../routes.php';

// Resolve the current request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$action = $router->resolve($requestUri);

// Call the appropriate controller action based on the resolved action
if ($action) {
    // Unpack the action array
    $controllerAction = $action[0]; // This should be the controller
    $params = $action[1]; // This should be the parameters

    if (is_array($controllerAction) && count($controllerAction) === 2) {
        [$controllerClass, $method] = $controllerAction; // Unpack controller class and method

        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            $controller = new $controllerClass();
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