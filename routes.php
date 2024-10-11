<?php
use App\Routing\Router;
// routes.php
require_once __DIR__ . '/router.php'; // Make sure this is correct
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/UserController.php';
require_once __DIR__ . '/app/controllers/CocktailController.php';

$router = new Router(); // Instantiate the Router class

// Define your routes

$router->add('GET', '#^/$#', [HomeController::class, 'index']); // Home page
$router->add('GET', '#^/login$#', [UserController::class, 'login']); // Login page
$router->add('POST', '#^/login$#', [UserController::class, 'authenticate']); // Handle login
$router->add('GET', '#^/register$#', [UserController::class, 'register']); // Registration page
$router->add('POST', '#^/user/store$#', [UserController::class, 'store']); // Handle registration


// router.php

// $router->add('GET', '#^/cocktails$#', [CocktailController::class, 'index']);
// $router->add('GET', '#^/cocktails/add$#', [CocktailController::class, 'add']);

// require_once __DIR__ . '/router.php'; // Include the router first

// // Show the login page
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