<?php
use App\Routing\Router;
// Include files
require_once __DIR__ . '/router.php'; 
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/UserController.php';
require_once __DIR__ . '/app/controllers/CocktailController.php';

$router = new Router(); 

//Routes
$router->add('GET', '#^/$#', [HomeController::class, 'index']); // Home page

// Authentication routes
$router->add('GET', '#^/login$#', [UserController::class, 'login']); // Login page
$router->add('POST', '#^/login$#', [UserController::class, 'authenticate']); // Handle login
$router->add('GET', '#^/register$#', [UserController::class, 'register']); // Registration page
$router->add('POST', '#^/register$#', [UserController::class, 'store']); // Handle registration

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