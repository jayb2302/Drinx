<?php 
// Header inclusion
include __DIR__ . '/layout/header.php';

// Check if the logout_success cookie is set and display it
if (isset($_COOKIE['logout_success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_COOKIE['logout_success']) . '</div>';
    // Clear the cookie after displaying the message
    setcookie('logout_success', '', time() - 3600, "/"); // Expire the cookie immediately
}

// Get the current URL path
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Determine if we're editing a cocktail by checking the URL format (e.g., /cocktails/123/edit)
$matches = [];
$isEditing = preg_match('#^/cocktails/(\d+)/edit$#', $currentPath, $matches);
$cocktailId = $matches[1] ?? null;
?>
<h1>Welcome to Drinx</h1>
<!-- Link to Add New Cocktail (only for logged-in users) -->
<?php if (AuthController::isLoggedIn()): ?>
    <a href="/cocktails/add" class="btn btn-primary">Add New Cocktail</a>
<?php endif; ?>

<!-- Logic to include forms based on the path -->
<?php
// Show login form if the current path is /login
if ($currentPath === '/login') {
    include __DIR__ . '/auth/login.php'; // Show login form

// Show register form if the current path is /register
} elseif ($currentPath === '/register') {
    include __DIR__ . '/auth/register.php'; // Show register form

// Show add cocktail form if the current path is /cocktails/add
} elseif ($currentPath === '/cocktails/add') {
    include __DIR__ . '/cocktails/form.php'; // Show add cocktail form

// Show edit cocktail form if we're editing a cocktail
} elseif ($isEditing && isset($cocktailId)) {
    // Fetch the cocktail data for editing
    // $cocktail = $this->cocktailService->getCocktailById($cocktailId);
    include __DIR__ . '/cocktails/form.php'; // Show edit cocktail form

} else {
    echo "<h2>All Cocktails</h2>";

    // Define available sorting options
    $sortingOptions = [
        'recent' => 'Sort by Recent',
        'popular' => 'Sort by Popular',
        'hot' => 'Sort by Hot'
    ];
    
    // Get the current sort option from the query parameter, defaulting to "recent"
    $currentSort = $_GET['sort'] ?? 'recent';
    
    // Display sorting options
    echo '<div class="sort-options">';
    foreach ($sortingOptions as $sortKey => $sortLabel) {
        $isActive = $currentSort === $sortKey ? 'active' : '';
        echo "<a href=\"/?sort={$sortKey}\" class=\"{$isActive}\">{$sortLabel}</a>";
        if (next($sortingOptions)) echo ' | '; // Add separator if there are more options
    }
    echo '</div>';
    
    // Include cocktails display
    echo '<div class="wrapper">';
    include __DIR__ . '/cocktails/index.php';
    echo '</div>';
}
?>

<?php include __DIR__ . '/layout/footer.php'; ?>