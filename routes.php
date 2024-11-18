<?php
use App\Routing\Router;

require_once __DIR__ . '/router.php';
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/UserController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/CocktailController.php';
require_once __DIR__ . '/app/controllers/CommentController.php';
require_once __DIR__ . '/app/controllers/LikeController.php';
require_once __DIR__ . '/app/controllers/SearchController.php';
require_once __DIR__ . '/app/controllers/AdminController.php';

$router = new Router(); // Instantiate the Router class

// Home Routes
$router->add('GET', '#^/$#', [HomeController::class, 'index']); // Home page
$router->add('GET', '#^/login$#', [HomeController::class, 'index']); // Show login form within home page
$router->add('GET', '#^/register$#', [HomeController::class, 'index']); // Show register form within home page
$router->add('GET', '#^/about$#', [HomeController::class, 'about']);

// Authentication routes
$router->add('POST', '#^/login$#', [AuthController::class, 'authenticate']); // Handle login
$router->add('GET', '#^/login$#', [AuthController::class, 'showLoginForm']); // Show login form
$router->add('POST', '#^/register$#', [AuthController::class, 'store']); // Handle registration
$router->add('GET', '#^/register$#', [AuthController::class, 'showRegisterForm']); // Show registration form
$router->add('GET', '#^/logout$#', [AuthController::class, 'logout']); // Logout route

// Admin User Management Routes
$router->add('POST', '#^/admin/update-status$#', [AdminController::class, 'updateUserStatus']);
// Sticky cocktail routes
$router->add('POST', '#^/admin/toggle-sticky$#', [AdminController::class, 'toggleStickyCocktail']);
$router->add('GET', '#^/admin/sticky-cocktail$#', [AdminController::class, 'getStickyCocktail']);
// Admin Tag Management Routes
$router->add('GET', '#^/admin/tags$#', [TagController::class, 'getAllTags']); // Fetch all tags
$router->add('POST', '#^/admin/tag/save$#', [TagController::class, 'saveTag']);
$router->add('POST', '#^/admin/tag/add$#', [TagController::class, 'saveTag']);
$router->add('POST', '#^/admin/tag/delete$#', [TagController::class, 'deleteTag']);
$router->add('GET', '#^/admin/tag/edit/(\d+)$#', [TagController::class, 'editTag']);
// Ingredient Manangement Routes
$router->add('GET', '#^/admin/ingredients/uncategorized$#', [IngredientController::class, 'getUncategorizedIngredients']);
$router->add('POST', '#^/admin/ingredients/assign-tag$#', [IngredientController::class, 'assignTag']);
$router->add('POST', '#^/admin/ingredients/create$#', [IngredientController::class, 'createIngredient']); // Add new ingredient
$router->add('POST', '#^/admin/ingredients/edit$#', [IngredientController::class, 'editIngredientName']); // Edit ingredient name
$router->add('POST', '#^/admin/ingredients/delete$#', [IngredientController::class, 'deleteIngredient']); // Delete ingredient
// Search route
$router->add('GET', '#^/search$#', [SearchController::class, 'search']);
$router->add('GET', '#^/searchAllUsers$#', [SearchController::class, 'adminUserSearch']);



// User routes
$router->add('GET', '#^/profile/(\d+)$#', [UserController::class, 'profile']);
$router->add('GET', '#^/profile/([a-zA-Z0-9_-]+)$#', [UserController::class, 'profileByUsername']); // Show profile by username
$router->add('POST', '#^/profile/update$#', [UserController::class, 'updateProfile']); // Handle profile update

// Account deletion routes
$router->add('POST', '#^/profile/delete$#', [UserController::class, 'deleteAccount']); // Handle account deletion directly from profile

// Follow and Unfollow Routes
$router->add('POST', '#^/user/follow/(\d+)$#', [UserController::class, 'follow']);
$router->add('POST', '#^/user/unfollow/(\d+)$#', [UserController::class, 'unfollow']); // Unfollow user

// Cocktails routes
$router->add('GET', '#^/cocktails$#', [CocktailController::class, 'index']); // Show all cocktails
$router->add('GET', '#^/cocktails/add$#', [HomeController::class, 'index']); // Show add form
$router->add('POST', '#^/cocktails/store$#', [CocktailController::class, 'store']); // Handle cocktail submission
$router->add('GET', '#^/cocktails/(\d+)-[^\/]+/edit$#', [CocktailController::class, 'edit']);
$router->add('POST', '#^/cocktails/update/(\d+)$#', [CocktailController::class, 'update']); // Update cocktail
$router->add('POST', '#^/cocktails/delete/(\d+)$#', [CocktailController::class, 'delete']); // Delete a cocktail
$router->add('POST', '#^/cocktails/(\d+)/delete-step$#', [CocktailController::class, 'deleteStep']);
$router->add('GET', '#^/cocktails/random$#', [CocktailController::class, 'getRandomCocktail']);
// View cocktails
$router->add('GET', '#^/cocktails$#', [CocktailController::class, 'index']); // List all cocktails
$router->add('GET', '#^/cocktails/(\d+)-(.+)$#', [CocktailController::class, 'view']); // View specific cocktail
// Tag Routes
$router->add('GET', '#^/tags/([a-zA-Z0-9-]+)$#', [TagController::class, 'showTagsByCategory']);  // Show tags for a category like "Flavor", "Mood", etc.
$router->add('POST', '#^/cocktails/(\d+)/assign-tags$#', [TagController::class, 'assignTagsToCocktail']);  // Assign tags based on ingredients for a cocktail
// Admin Ingredient Management
$router->add('GET', '#^/admin/ingredients/uncategorized$#', [IngredientController::class, 'getUncategorizedIngredients']); // Get uncategorized ingredients
$router->add('POST', '#^/admin/ingredients/assign-tag$#', [IngredientController::class, 'assignTag']); // Assign tag to ingredient
$router->add('POST', '#^/admin/ingredients/create$#', [IngredientController::class, 'createIngredient']); // Add new ingredient
$router->add('POST', '#^/admin/ingredients/edit$#', [IngredientController::class, 'editIngredientName']); // Edit ingredient
$router->add('POST', '#^/admin/ingredients/delete$#', [IngredientController::class, 'deleteIngredient']); // Delete ingredient 

// Admin Unit Management
$router->add('GET', '#^/admin/units$#', [IngredientController::class, 'showUnits']); // View all units
$router->add('POST', '#^/admin/unit/add$#', [IngredientController::class, 'addUnit']); // Add a new unit
$router->add('POST', '#^/admin/unit/delete$#', [IngredientController::class, 'deleteUnit']); // Delete a unit
// Comment interactions
$router->add('POST', '#^/cocktails/(\d+)-[^/]+/comments$#', [CommentController::class, 'addComment']);
$router->add('GET', '#^/comments/(\d+)/edit$#', [CommentController::class, 'edit']); // Edit comment
$router->add('POST', '#^/comments/(\d+)/update$#', [CommentController::class, 'update']); // Update comment
$router->add('POST', '#^/comments/(\d+)/delete$#', [CommentController::class, 'delete']); // Delete comment or reply
$router->add('POST', '#^/comments/(\d+)/reply$#', [CommentController::class, 'reply']);
// Toggle like route
$router->add('POST', '#^/cocktails/(\d+)/toggle-like$#', [LikeController::class, 'toggleLike']);


// Sort cocktails routes
$router->add('GET', '#^/recent$#', [HomeController::class, 'index']);
$router->add('GET', '#^/popular$#', [HomeController::class, 'index']);
$router->add('GET', '#^/hot$#', [HomeController::class, 'index']);

// Category routes
$router->add('GET', '#^/category/([a-zA-Z0-9-]+)$#', [HomeController::class, 'filterByCategory']);
