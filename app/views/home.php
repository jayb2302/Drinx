<?php 
// Header inclusion
include __DIR__ . '/layout/header.php';

// Check if the logout_success cookie is set and display it
if (isset($_COOKIE['logout_success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_COOKIE['logout_success']) . '</div>';
    // Clear the cookie after displaying the message
    setcookie('logout_success', '', time() - 3600, "/"); // Expire the cookie immediately
}
?>

<!-- home.php -->
<h1>Welcome to Drinx</h1>

<!-- Link to Add New Cocktail (only for logged-in users) -->
<?php if (AuthController::isLoggedIn()): ?>
    <a href="/cocktails/add" class="btn btn-primary">Add New Cocktail</a>
<?php endif; ?>

<!-- Logic to Include Forms -->
<?php 
// Initialize variables
$isEditing = false; // Default to false
$cocktail = null;
$steps = [];
$ingredients = [];

// Logic to include forms
$requestUri = trim($_SERVER['REQUEST_URI'], '/');
$actionSegments = explode('/', $requestUri); // Split the URI into segments
$action = isset($actionSegments[1]) ? $actionSegments[1] : ''; // Safely access the second segment

// Determine the action and load the appropriate content
if ($action === 'login') {
    require_once __DIR__ . '/auth/login.php'; 
} elseif ($action === 'register') {
    require_once __DIR__ . '/auth/register.php'; 
} elseif ($action === 'add') {
    // Include the add cocktail form
    require_once __DIR__ . '/cocktails/form.php'; 
} elseif ($action === 'edit' && isset($_GET['cocktail_id'])) {
    // Load the cocktail data for editing
    $cocktailId = (int) $_GET['cocktail_id'];
    $cocktail = $cocktailService->getCocktailById($cocktailId); 
    $steps = $cocktailService->getCocktailSteps($cocktailId); 
    $ingredients = $cocktailService->getCocktailIngredients($cocktailId); 
    $isEditing = true; // Indicate editing mode

    require_once __DIR__ . '/cocktails/form.php'; 
} else {
    // Show all cocktails if no specific action is requested
    echo "<h2>All Cocktails</h2>";
    ?>
    <div class="wrapper">
        <?php include __DIR__ . '/cocktails/index.php'; ?>
    </div>
    <?php
}
?>

<?php include __DIR__ . '/layout/footer.php'; ?>