<?php
include __DIR__ . '/layout/header.php';
include __DIR__ . '/about/about.php';

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

<?php
// Admin toggle for user management
if ($_SESSION['user']['is_admin'] ?? false): ?>
    <button onclick="toggleUserManagement()">Toggle User Management</button>
    <div id="userManagement" style="display: none;">
        <?php include __DIR__ . '/admin/manage_users.php'; ?>
    </div>
<?php endif; ?>

<!-- Link to Add New Cocktail (only for logged-in users) -->
<?php if (AuthController::isLoggedIn() && $currentUser->canAddCocktail($currentUser->getId())): ?>
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
} elseif ($currentPath === '/random') {
    include __DIR__ . '/cocktails/random.php'; // Show random cocktail
} else {
    // Show all cocktails if no specific action is requested
    echo "<h2>All Cocktails</h2>";
    // Add sorting options here
    echo '<div class="sort-options">';
    echo '<a href="/?sort=recent" class="' . (($_GET['sort'] ?? 'recent') === 'recent' ? 'active' : '') . '">Sort by Recent</a>';
    echo ' | ';
    echo '<a href="/?sort=popular" class="' . (($_GET['sort'] ?? '') === 'popular' ? 'active' : '') . '">Sort by Popular</a>';
    echo '</div>';
    // Add sorting options here
    echo '<div class="sort-options">';
    echo '<a href="/?sort=recent" class="' . (($_GET['sort'] ?? 'recent') === 'recent' ? 'active' : '') . '">Sort by Recent</a>';
    echo ' | ';
    echo '<a href="/?sort=popular" class="' . (($_GET['sort'] ?? '') === 'popular' ? 'active' : '') . '">Sort by Popular</a>';
    echo '</div>';
    echo '<div class="wrapper">';
    include __DIR__ . '/cocktails/index.php';
    echo '</div>';
}
?>

<?php include __DIR__ . '/layout/footer.php'; ?>