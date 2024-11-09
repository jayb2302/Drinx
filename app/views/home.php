<?php
include __DIR__ . '/layout/header.php';

// Check if the logout_success cookie is set and display it
if (isset($_COOKIE['logout_success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_COOKIE['logout_success']) . '</div>';
    // Clear the cookie after displaying the message
    setcookie('logout_success', '', time() - 3600, "/"); // Expire the cookie immediately
}

// Get the current URL path
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$matches = [];
$isEditing = preg_match('#^/cocktails/(\d+)/edit$#', $currentPath, $matches);
$cocktailId = $matches[1] ?? null;
?>
<?php if (isset($stickyCocktail) && is_object($stickyCocktail)): ?>
    <div class="stickyContainer">
        <div class="stickyCard">
            <h2>📌Sticky Cocktail</h2>
            <div class="stickyMediaWrapper">
                <img src="/uploads/cocktails/<?php echo htmlspecialchars($stickyCocktail->getImage()); ?>" alt="<?php echo htmlspecialchars($stickyCocktail->getTitle()); ?>" class="cocktail-image">
            </div>
            <div class="stickyContent">
                <h3 class="cocktail-title"><?php echo htmlspecialchars($stickyCocktail->getTitle()); ?></h3>
                <p class="cocktail-description"><?php echo htmlspecialchars($stickyCocktail->getDescription()); ?></p>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>No sticky cocktail selected or invalid data.</p>
<?php endif; ?>
<?php include __DIR__ . '/about/about.php'; ?>
<?php
// Admin toggle for user management
if ($_SESSION['user']['is_admin'] ?? false): ?>
    <button class="button" onclick="toggleUserManagement()">User Management</button>
    <div id="userManagement" style="display: none;">
        <?php include __DIR__ . '/admin/manage_users.php'; ?>
    </div>
<?php endif; ?>

<!-- Link to Add New Cocktail (only for logged-in users) -->
<?php if (AuthController::isLoggedIn() && $currentUser->canAddCocktail($currentUser->getId())): ?>
    <a href="/cocktails/add" class="button-secondary"> Add New Cocktail </a>
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
    echo "<h2>All Cocktails</h2>";
    // Add sorting options here
    echo '<div class="sort-options">';
    echo '<a href="/?sort=recent" class="' . (($_GET['sort'] ?? 'recent') === 'recent' ? 'active' : '') . '">Recent</a>';
    echo ' | ';
    echo '<a href="/?sort=popular" class="' . (($_GET['sort'] ?? '') === 'popular' ? 'active' : '') . '">Popular</a>';
    echo '</div>';
    echo '<div class="wrapper">';
    include __DIR__ . '/cocktails/index.php';
    echo '</div>';
}
?>

<?php include __DIR__ . '/layout/footer.php'; ?>