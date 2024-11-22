<?php
$metaTitle = "Drinx - Cocktail Library";
$pageTitle = "Drip, Drop, Drinx";
$page="home";

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

<?php if (isset($includeScripts) && is_array($includeScripts)): ?>
    <?php foreach ($includeScripts as $script): ?>
        <script src="<?= htmlspecialchars($script); ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
<div class="content-wrapper">
<aside class="leftSidebar">
        <?php include __DIR__ . '/cocktails/categories.php'; ?>
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
    </aside>
    <!-- Sticky Cocktail -->
    <main>

        <!-- -->
        <!-- User Management Button (only for admins) -->
        <?php
        if ($_SESSION['user']['is_admin'] ?? false): ?>
            <!-- <button class="button" onclick="toggleUserManagement()">User Management</button> -->
            <div id="userManagement" style="display: none;">
                <?php include __DIR__ . '/admin/manage_users.php'; ?>
            </div>
            <div id="tagsManagement" style="display: none;">
                <?php include __DIR__ . '/admin/manage_tags.php';?>
            </div>
            <div id="ingredientManagement" style="display: none;">
                <?php include __DIR__ . '/admin/manage_ingredients.php';?>
            </div>
        <?php endif; ?>
        <!-- Logic to include forms based on the path -->
        <?php
        if ($currentPath === '/login') {
            include __DIR__ . '/auth/login.php'; // Show login form
            // Show register form if the current path is /register
        } elseif ($currentPath === '/register') {
            include __DIR__ . '/auth/register.php'; // Show register form
            // Show add cocktail form if the current path is /cocktails/add
        } else if ($currentPath === '/about') {
            include __DIR__ . '/about/about.php'; // Show about page
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
            include __DIR__ . '/cocktails/sorting.php';
            echo '<div class="wrapper">';
            include __DIR__ . '/cocktails/index.php';
            echo '</div>';
        }
        ?>
    </main>
    <aside class="controlPanel">
        <?php
        // Only include the About section if the user is not logged in
        if (!isset($_SESSION['user'])):
            include __DIR__ . '/about/about.php';
        endif;
        ?>
        <?php
        $userProfile = $userProfile ?? null;
        include __DIR__ . '/layout/control_panel.php'; ?>
    </aside>
</div>
<?php include __DIR__ . '/layout/footer.php'; ?>