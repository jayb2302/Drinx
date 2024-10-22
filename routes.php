<?php
use App\Routing\Router;

require_once __DIR__ . '/router.php';
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/UserController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/CocktailController.php';
require_once __DIR__ . '/app/controllers/CommentController.php';

$router = new Router(); // Instantiate the Router class

// Home Routes
$router->add('GET', '#^/$#', [HomeController::class, 'index']); // Home page
$router->add('GET', '#^/login$#', [HomeController::class, 'index']); // Show login form within home page
$router->add('GET', '#^/register$#', [HomeController::class, 'index']); // Show register form within home page

// Authentication routes
$router->add('POST', '#^/login$#', [AuthController::class, 'authenticate']); // Handle login
$router->add('POST', '#^/register$#', [AuthController::class, 'store']); // Handle registration
$router->add('GET', '#^/logout$#', [AuthController::class, 'logout']); // Logout route

// User routes
$router->add('GET', '#^/profile$#', [UserController::class, 'profile']); // Show profile
$router->add('GET', '#^/profile/([a-zA-Z0-9_-]+)$#', [UserController::class, 'profileByUsername']); // Show profile by username
$router->add('POST', '#^/profile/update$#', [UserController::class, 'updateProfile']); // Handle profile update

// Cocktails routes
$router->add('GET', '#^/cocktails$#', [CocktailController::class, 'index']); // Show all cocktails
// Update the router to use HomeController for add action
$router->add('GET', '#^/cocktails/add$#', [HomeController::class, 'index']); // Show add form
$router->add('POST', '#^/cocktails/store$#', [CocktailController::class, 'store']); // Handle cocktail submission
$router->add('GET', '#^/cocktails/(\d+)-[^\/]+/edit$#', [CocktailController::class, 'edit']);
$router->add('POST', '#^/cocktails/update/(\d+)$#', [CocktailController::class, 'update']); // Update cocktail
$router->add('POST', '#^/cocktails/delete/(\d+)$#', [CocktailController::class, 'delete']); // Delete a cocktail
$router->add('POST', '#^/cocktails/(\d+)/delete-step$#', [CocktailController::class, 'deleteStep']);
// View cocktails
$router->add('GET', '#^/cocktails$#', [CocktailController::class, 'index']); // List all cocktails
$router->add('GET', '#^/cocktails/(\d+)-(.+)$#', [CocktailController::class, 'view']); // View specific cocktail

// Comment interactions
$router->add('POST', '#^/cocktails/(\d+)-[^/]+/comments$#', [CommentController::class, 'addComment']);
$router->add('GET', '#^/comments/(\d+)/edit$#', [CommentController::class, 'edit']); // Edit comment
$router->add('POST', '#^/comments/(\d+)/delete$#', [CommentController::class, 'delete']); // Delete comment