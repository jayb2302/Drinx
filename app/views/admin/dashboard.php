<?php 
$metaTitle = "Drinx - Cocktail Library";
$pageTitle = "Drinx - Admin Dashboard";
$page="admin";
require_once __DIR__ . '/../layout/header.php'; 
?>
<!-- Admin Dashboard Navigation -->
<?php if ($_SESSION['user']['is_admin'] ?? false): ?>
    <div class="admin-dashboard">
        <!-- Stats Section -->
        <div class="dashboard-stats">
            <div class="stats-scroll">
                <?php
                $statItems = [
                    ['icon' => 'fa-solid fa-user', 'value' => $stats['totalUsers'] ?? 'N/A', 'title' => 'Users'],
                    ['icon' => 'fa-solid fa-martini-glass-citrus', 'value' => $stats['totalCocktails'] ?? 'N/A', 'title' => 'Recipes'],
                    ['icon' => 'fa-solid fa-tags', 'value' => $stats['totalTags'] ?? 'N/A', 'title' => 'Tags'],
                    ['icon' => 'fa-solid fa-comments', 'value' => $stats['totalComments'] ?? 'N/A', 'title' => 'Comments'],
                    ['icon' => 'fa-solid fa-lemon', 'value' => $stats['mostUsedIngredient'] ?? 'N/A', 'title' => 'Top Ingredient'],
                    ['icon' => 'fa-solid fa-fire', 'value' => $stats['mostPopularCocktail'] ?? 'N/A', 'title' => 'Popular Cocktail'],
                    ['icon' => 'fa-solid fa-clipboard', 'value' => $stats['unusedTags'] ?? 'N/A', 'title' => 'Unused Tags'],
                    ['icon' => 'fa-solid fa-circle-exclamation', 'value' => $stats['cocktailsWithoutComments'] ?? 'N/A', 'title' => 'No Comments']
                ];

                // Add Top Creator logic
                if (!empty($stats['userWithMostRecipes'])) {
                    $user = $stats['userWithMostRecipes'];
                    $profilePicture = isset($user['profile_picture']) && !empty($user['profile_picture'])
                        ? asset('/../uploads/users/' . htmlspecialchars($user['profile_picture']))
                        : asset('/../uploads/users/user-default.svg');
                    $username = htmlspecialchars($user['username'] ?? 'N/A');
                    $recipesCount = htmlspecialchars($user['recipes_count'] ?? '0');

                    $statItems[] = [
                        'custom_html' => "
                    <div class='stat-card'>
                        <div class='stat-info'>
                            <h4 class='stat-title'>Top Creator</h4>
                        </div>
                        <div class='stat-icon'>
                            <a href='/profile/{$username}' class='view-profile'>
                                <img src='{$profilePicture}' class='profile-pic' alt='Profile picture of {$username}'>
                            </a>
                        </div>
                        <div class='stat-info'>
                            <span class='stat-value'>{$username}</span>
                            <span class='stat-value'>{$recipesCount} recipes</span>
                        </div>
                    </div>"
                    ];
                }

                // Render stats
                foreach ($statItems as $statItem):
                    if (isset($statItem['custom_html'])) {
                        echo $statItem['custom_html'];
                    } else {
                ?>
                        <div class="stat-card">
                            <div class="stat-info">
                                <h4 class="stat-title"><?= $statItem['title']; ?></h4>
                            <i class="stat-icon <?= htmlspecialchars($statItem['icon']); ?>"></i>
                            <span class="stat-value"><?= $statItem['value']; ?></span>
                            </div>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>
            </div>
        </div>
         <!-- Navigation -->
        <nav class="dashboard-navigation">
            <button class="button admin-toggle-button" data-target="userManagement">User</button>
            <button class="button admin-toggle-button" data-target="tagsManagement">Tags</button>
            <button class="button admin-toggle-button" data-target="ingredientManagement">Ingredient</button>
        </nav>
        
        <!-- Sections -->
        <section id="userManagement" class="admin-section" style="display: none;">
            <?php include __DIR__ . '/manage_users.php'; ?>
        </section>
        <section id="tagsManagement" class="admin-section" style="display: none;">
            <?php include __DIR__ . '/manage_tags.php'; ?>
        </section>
        <section id="ingredientManagement" class="admin-section" style="display: none;">
            <?php include __DIR__ . '/manage_ingredients.php'; ?>
        </section>
    <?php endif; ?>
</div>
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>