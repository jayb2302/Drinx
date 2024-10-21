<?php
use App\Routing\Router;

require_once __DIR__ . '/router.php';
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/UserController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/CocktailController.php';

$router = new Router(); // Instantiate the Router class

// Home Routes
$router->add('GET', '#^/$#', [HomeController::class, 'index']); // Home page

// Authentication routes
$router->add('POST', '#^/login$#', [AuthController::class, 'authenticate']); // Handle login
$router->add('POST', '#^/register$#', [AuthController::class, 'store']); // Handle registration
$router->add('GET', '#^/logout$#', [AuthController::class, 'logout']); // Logout route

// User routes
$router->add('GET', '#^/profile$#', [UserController::class, 'profile']); // Show profile
$router->add('GET', '#^/profile/([a-zA-Z0-9_-]+)$#', [UserController::class, 'profileByUsername']); // Show profile by username
$router->add('POST', '#^/profile/update$#', [UserController::class, 'updateProfile']); // Handle profile update

// CRUD Routes for Cocktails
$router->add('GET', '#^/cocktails/add$#', [CocktailController::class, 'add']); // Show form to add a cocktail
$router->add('POST', '#^/cocktails/store$#', [CocktailController::class, 'store']); // Handle cocktail submission

// Edit route (using a consistent approach with $router->add)
$router->add('GET', '#^/cocktails/(\d+)-[^\/]+/edit$#', [CocktailController::class, 'edit']);
// Update and delete routes
$router->add('POST', '#^/cocktails/update/(\d+)$#', [CocktailController::class, 'update']); // Update cocktail
$router->add('POST', '#^/cocktails/delete/(\d+)$#', [CocktailController::class, 'delete']); // Delete a cocktail
$router->add('POST', '#^/cocktails/(\d+)/delete-step$#', [CocktailController::class, 'deleteStep']);
// View cocktails
$router->add('GET', '#^/cocktails$#', [CocktailController::class, 'index']); // List all cocktails
$router->add('GET', '#^/cocktails/(\d+)-(.+)$#', [CocktailController::class, 'view']); // View specific cocktail

// Now handle the incoming request
$uri = $_SERVER['REQUEST_URI'];
$route = $router->resolve($uri);

// Execute the route if matched
if ($route) {
    [$action, $params] = $route;

    // $action is an array like [Controller::class, 'method']
    if (is_array($action)) {
        // Instantiate the controller before calling the method
        $controllerClass = $action[0];
        $method = $action[1];

        // Create an instance of the controller
        $controller = new $controllerClass();

        // Call the method dynamically with parameters
        call_user_func_array([$controller, $method], $params);
    } else {
        // If the action is a closure or callable, execute it
        call_user_func_array($action, $params);
    }
} else {
    // If no route matches, handle the 404 error
    http_response_code(404);
    echo "404 - Page Not Found";
}