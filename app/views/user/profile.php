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
            <img class="" src="<?= asset('/../uploads/users/' . htmlspecialchars($profile->getProfilePicture())); ?>"
                alt="Profile Picture" class="profile-img">
        <?php else: ?>
            <img src="<?= asset('/../uploads/cocktails/kian.jpg'); ?>" alt="Default Profile Picture" class="profile-img">
        <?php endif; ?>
    </div>

    <!-- User Info -->
    <div class="profile-info">
        <h2><?= htmlspecialchars($profile->getFirstName() ?? 'No First Name') . ' ' . htmlspecialchars($profile->getLastName() ?? 'No Last Name'); ?>
        </h2>
        <p>Username: <?= htmlspecialchars($profile->getUsername() ?? 'Unknown'); ?></p>
        <p class="bio"><?= htmlspecialchars($profile->getBio() ?? 'This user has not set a bio yet.'); ?></p>
    </div>

    <!-- Edit Profile Button -->
    <a href="#" class="btn btn-primary" onclick="toggleEditMode()">Edit Profile</a>
</div>

<!-- Profile Edit Form -->
<div id="edit-profile-form" style="display:none;">
    <?php include __DIR__ . '/form.php'; ?>
</div>

<!-- User's Recipes Section -->
<h3>Uploaded Recipes</h3>
<div class="wrapper">
    <div class="profile-recipes">
        <?php if (!empty($userRecipes)): ?>
            <div class="container">
                <?php foreach ($userRecipes as $recipe): ?>
                    <article class="cocktailCard">
                        <!-- Show the Edit button if the logged-in user is the owner of the recipe -->
                        <?php if (isset($loggedInUserId) && $loggedInUserId === $recipe->getUserId()): ?>
                            <button>
                                <a href="/?action=edit&cocktail_id=<?= $recipe->getCocktailId() ?>"
                                    class="text-blue-500 hover:underline">Edit Cocktail</a>
                            </button>
                        <?php endif; ?>

                        <!-- Cocktail Link -->
                        <a
                            href="/cocktails/<?= htmlspecialchars($recipe->getCocktailId()) ?>-<?= urlencode($recipe->getTitle()) ?>">
                            <img src="<?= asset('/../uploads/cocktails/' . htmlspecialchars($recipe->getImage())); ?>"
                                alt="<?= htmlspecialchars($recipe->getTitle()); ?>" class="cocktailImage">
                        </a>

                        <!-- Cocktail Info -->
                        <div class="cocktailInfo">
                            <h3><?= htmlspecialchars($recipe->getTitle()); ?></h3>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No recipes uploaded yet.</p>
        <?php endif; ?>
    </div>
</div>

<!-- User's Badges Section -->
<div class="profile-badges">
    <h3>Achievements</h3>
    <?php if (!empty($userBadges)): ?>
        <div class="badge-grid">
            <?php foreach ($userBadges as $badge): ?>
                <div class="badge-card">
                    <img src="<?= asset('uploads/badges/' . htmlspecialchars($badge['badge_image'])); ?>"
                        alt="<?= htmlspecialchars($badge['badge_name']); ?>" class="badge-img">
                    <p><?= htmlspecialchars($badge['badge_name']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
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

<script>
    function toggleEditMode() {
        const form = document.getElementById('edit-profile-form');
        form.style.display = form.style.display === "none" ? "block" : "none";
    }
</script>