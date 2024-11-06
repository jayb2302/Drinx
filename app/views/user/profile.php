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
<?php if (isset($isFollowing) && $isFollowing): ?>
    <form action="/user/unfollow/<?= htmlspecialchars($profileUserId); ?>" method="POST">
        <button type="submit" class="btn btn-danger">Unfollow</button>
    </form>
<?php else: ?>
    <form action="/user/follow/<?= htmlspecialchars($profileUserId); ?>" method="POST">
        <button type="submit" class="btn btn-primary">Follow</button>
    </form>
<?php endif; ?>
<div class="profile-header">
    <!-- Profile Picture -->
    <div class="profile-picture">
        <?php if ($profile->getProfilePicture()): ?>
            <img class="" src="<?= asset('/../uploads/users/' . htmlspecialchars($profile->getProfilePicture())); ?>"
                alt="Profile Picture" class="profile-img">
        <?php else: ?>
            <img src="<?= asset('/../uploads/users/user-default.svg'); ?>" alt="Default Profile Picture" class="profile-img">
        <?php endif; ?>
    </div>

    <!-- User Info -->
    <div class="profile-info">
    <h2><?= htmlspecialchars($profile->getFirstName() ?? 'No First Name') . ' ' . htmlspecialchars($profile->getLastName() ?? 'No Last Name'); ?></h2>        <p>@ <?= htmlspecialchars($profile->getUsername() ?? 'Unknown'); ?></p>
        <p class="bio"><?= htmlspecialchars($profile->getBio() ?? 'This user has not set a bio yet.'); ?></p>
    </div>

    <!-- Edit Profile Button - Only show if user is viewing their own profile -->
    <?php if ($userId === $profileUserId): ?>
        <a href="#" class="btn btn-primary" onclick="toggleEditMode()">Edit Profile</a>
    <?php endif; ?>
</div>

<!-- Profile Edit Form -->
<div id="edit-profile-form" style="display:none;">
    <?php include __DIR__ . '/form.php'; ?>
</div>

<!-- User's Recipes Section -->
<div class="recipe-wrapper">
        <h3>My Recipes</h3>
        <?php if (!empty($userRecipes)): ?>
            <div class="recipe-container">
                <?php foreach ($userRecipes as $recipe): ?>
                    <article class="recipe-card">
                        <!-- Show the Edit button if the logged-in user is the owner of the recipe -->
                        <?php if (isset($loggedInUserId) && $loggedInUserId === $recipe->getUserId()): ?>
                            <button>
                                <a href="/?action=edit&cocktail_id=<?= htmlspecialchars($recipe->getCocktailId()) ?>"
                                    class="text-blue-500 hover:underline">Edit Cocktail</a>
                            </button>
                        <?php endif; ?>


                        <a href="/cocktails/<?= htmlspecialchars($recipe->getCocktailId() ?? '0') ?>-<?= urlencode($recipe->getTitle() ?? 'Untitled Cocktail') ?>">
                            <img src="<?= asset('/../uploads/cocktails/' . htmlspecialchars($recipe->getImage() ?? 'default-image.svg')); ?>"
                                alt="<?= htmlspecialchars($recipe->getTitle() ?? 'Cocktail Image') ?>" class="cocktailImage">
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
<!-- Delete Account Button -->
<div class="delete-account-section">
    <button onclick="toggleDeleteSection()">Delete Account</button>

    <div id="deleteConfirmSection" style="display: none;">
        <p class="warning">Warning: This action will permanently delete your account and cannot be undone!</p>

        <form action="/profile/delete" method="POST">
            <label for="password">Confirm Password:</label>
            <input type="password" name="password" required>
            <button type="submit" class="confirm-delete">Confirm Deletion</button>
        </form>

        <!-- Display any error messages related to deletion -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>
</div>
<?php include_once __DIR__ . '/../layout/footer.php'; ?>