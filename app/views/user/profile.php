<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php
// Display any error messages (optional)
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}

// Display success message (optional)
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
?>

<!-- User Profile Header -->
<div class="profile-header">
    <!-- Profile Picture -->
    <div class="profile-picture">
        <?php if ($profile->getProfilePicture()): ?>
            <img src="<?= asset('uploads/profile_pictures/' . htmlspecialchars($profile->getProfilePicture())); ?>" alt="Profile Picture" class="profile-img">
        <?php else: ?>
            <img src="<?= asset('/../uploads/cocktails/kian.jpg'); ?>" alt="Default Profile Picture" class="profile-img">
        <?php endif; ?>
    </div>

    <!-- User Info -->
    <div class="profile-info">
    <h2><?= htmlspecialchars($profile->getFirstName() ?? 'No First'); ?> or <?= htmlspecialchars($profile->getLastName() ?? ' Last Name'); ?></h2>
    <p>Username: <?= htmlspecialchars($profile->getUsername() ?? 'Unknown'); ?></p>      
    <p class="bio"><?= htmlspecialchars($profile->getBio() ?? 'This user has not set a bio yet.'); ?></p>
    </div>

    <!-- Edit Profile Button -->
    <a href="/profile/edit" class="btn btn-primary">Edit Profile</a>
</div>

<!-- User's Recipes Section -->
<div class="profile-recipes">
    <h3>Uploaded Recipes</h3>
    <?php if (!empty($userRecipes)): ?>
        <div class="recipe-grid">
            <?php foreach ($userRecipes as $recipe): ?>
                <div class="recipe-card">
                    <a href="/cocktails/<?= $recipe->getCocktailId(); ?>-<?= urlencode($recipe->getTitle()); ?>">
                        <img src="<?= asset('uploads/cocktails/' . htmlspecialchars($recipe->getImage())); ?>" alt="<?= htmlspecialchars($recipe->getTitle()); ?>" class="recipe-img">
                        <h4><?= htmlspecialchars($recipe->getTitle()); ?></h4>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No recipes uploaded yet.</p>
    <?php endif; ?>
</div>

<!-- User's Badges Section -->
<div class="profile-badges">
    <h3>Achievements</h3>
    <?php if (!empty($userBadges)): ?>
        <div class="badge-grid">
            <?php foreach ($userBadges as $badge): ?>
                <div class="badge-card">
                    <img src="<?= asset('uploads/badges/' . htmlspecialchars($badge['badge_image'])); ?>" alt="<?= htmlspecialchars($badge['badge_name']); ?>" class="badge-img">
                    <p><?= htmlspecialchars($badge['badge_name']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Display this message if the user has no badges -->
        <p>No badges earned yet.</p>
    <?php endif; ?>
</div>

<!-- Social Profile Statistics (Optional) -->
<div class="profile-stats">
    <h3>Statistics</h3>
    <ul>
        <li>Total Recipes: <?= count($userRecipes); ?></li>
        <li>Likes Received: <?= $profileStats['likes_received'] ?? 0; ?></li>
        <li>Comments Received: <?= $profileStats['comments_received'] ?? 0; ?></li>
    </ul>
</div>