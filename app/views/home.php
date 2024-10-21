<?php 
//header 
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


<a href="/cocktails/add">Add New Cocktail</a> 
<!-- Logic to Include Forms -->
<?php 
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'login') {
        require_once __DIR__ . '/auth/login.php'; 
    } elseif ($_GET['action'] === 'register') {
        require_once __DIR__ . '/auth/register.php'; 
    } elseif ($_GET['action'] === 'add') {
        require_once __DIR__ . '/cocktails/form.php'; 
    } elseif ($_GET['action'] === 'edit' && isset($_GET['cocktail_id'])) {
        // Load the cocktail data for editing
        $cocktailId = (int) $_GET['cocktail_id'];
        $cocktail = $cocktailService->getCocktailById($cocktailId); 
        $steps = $cocktailService->getCocktailSteps($cocktailId); 
        $ingredients = $cocktailService->getCocktailIngredients($cocktailId); 
        $categories = $cocktailService->getCategories();
        $units = $ingredientRepository->getAllUnits(); 
        $isEditing = true; // Indicate editing mode

        require_once __DIR__ . '/cocktails/form.php'; 
    }
} else {
    echo "<p>No action specified</p>";
}
?>
<!-- Display Cocktails -->
<?php if (!isset($_GET['action']) || $_GET['action'] !== 'add' && $_GET['action'] !== 'edit'): ?>
    <h2>All Cocktails</h2>
    
    <div class="wrapper">
        <?php include __DIR__ . '/cocktails/index.php'; ?>
    </div>
<?php endif; ?>

