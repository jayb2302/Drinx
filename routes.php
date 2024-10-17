<?php
use App\Routing\Router;

require_once __DIR__ . '/router.php'; // Make sure this is correct
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/UserController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/CocktailController.php';

$router = new Router(); // Instantiate the Router class

// Define your routes
$router->add('GET', '#^/$#', [HomeController::class, 'index']); // Home page
$router->add('POST', '#^/login$#', [AuthController::class, 'authenticate']); // Handle login
$router->add('POST', '#^/register$#', [AuthController::class, 'store']); // Handle registration
$router->add('GET', '#^/logout$#', [AuthController::class, 'logout']); // Logout route

// User routes
$router->add('GET', '#^/profile$#', [UserController::class, 'profile']); // Show profile
// User profile route with dynamic username
$router->add('GET', '#^/profile/([a-zA-Z0-9_-]+)$#', [UserController::class, 'profileByUsername']);
$router->add('POST', '#^/profile/update$#', [UserController::class, 'updateProfile']); // Handle profile update

// CRUD Routes for Cocktails
$router->add('GET', '#^/cocktails/add$#', [CocktailController::class, 'add']); // Show form to add a new cocktail
$router->add('POST', '#^/cocktails$#', [CocktailController::class, 'store']); // Handle cocktail submission
$router->add('GET', '#^/cocktails/edit/([0-9]+)$#', [CocktailController::class, 'edit']); // Show edit form for a cocktail
$router->add('POST', '#^/cocktails/update/(\d+)$#', [CocktailController::class, 'update']); // Update cocktail
$router->add('POST', '#^/cocktails/delete/([0-9]+)$#', [CocktailController::class, 'delete']); // Delete a cocktail
$router->add('GET', '#^/cocktails$#', [CocktailController::class, 'index']); // List all cocktails
$router->add('GET', '#^/cocktails/([0-9]+)-(.+)$#', [CocktailController::class, 'view']); // View specific cocktail
// get('login', function() {
//     require_once __DIR__ . '/../app/controllers/UserController.php'; 
//     $controller = new UserController();
//     $controller->showLoginForm();
// });

// // Handle login form submission
// post('login', function() {
//     require_once __DIR__ . '/../app/controllers/UserController.php'; 
//     $controller = new UserController();
//     $controller->login();
// });

// // Show the registration page
// get('register', function() {
//     require_once __DIR__ . '/../app/controllers/UserController.php'; 
//     $controller = new UserController();
//     $controller->showRegisterForm();
// });

// // Handle registration form submission
// post('register', function() {
//     require_once __DIR__ . '/../app/controllers/UserController.php'; 
//     $controller = new UserController();
//     $controller->register();
// });

// // Define a route for logging out
// get('logout', function() {
//     require_once __DIR__ . '/../app/controllers/UserController.php'; 
//     $controller = new UserController();
//     $controller->logout();
// });