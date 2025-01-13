<?php
session_set_cookie_params([
    'lifetime' => 0, // Session lasts until browser is closed
    'path' => '/',
    'domain' => '', // Adjust for your domain, leave empty for default
    'secure' => isset($_SERVER['HTTPS']), // Only send cookies over HTTPS
    'httponly' => true, // Prevent JavaScript access to cookies
    'samesite' => 'Strict' // Prevent cookies from being sent with cross-site requests
]);

session_start();
ob_start();

require_once __DIR__ . '/../app/helpers/helpers.php';
require_once __DIR__ . '/../router.php';
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../app/config/dependencies.php';

$sessionTimeout = (2 * (7 * (24 * (60 * 60)))); // (2 weeks)

if ($_SERVER['REQUEST_URI'] === '/session-check') {
    header('Content-Type: application/json');

    // Check if the session has expired
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $sessionTimeout)) {
        $errorMessage = 'Your session has expired. Please log in again.';
        session_unset();
        session_destroy();

        setcookie('session_expired', $errorMessage, time() + 5, '/'); 
        echo json_encode(['session_expired' => true]);
        exit();
    }

    // Session is still active
    echo json_encode(['session_expired' => false]);
    exit();
}


// Update session activity timestamp
$_SESSION['last_activity'] = time();

// Generate CSRF Token
generateCsrfToken();

// Resolve the current request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$action = $router->resolve($requestUri);

if ($action) {
    // Unpack the action array
    $controllerAction = $action[0]; 
    $params = $action[1]; 
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
            'AuthController' => $authController,
            'StepController' => $stepController,
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