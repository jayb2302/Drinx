<div class="controlPanel">
    <?php if ($userProfile): ?>
        <!-- Profile Header with Display Name and Logout -->
        <?php
        // Determine display name
        $firstName = $_SESSION['user']['first_name'] ?? null;
        $lastName = $_SESSION['user']['last_name'] ?? null;
        $username = htmlspecialchars($_SESSION['user']['username'] ?? 'User'); // Fallback to "User" if username is missing

        if ($firstName && $lastName) {
            $displayName = htmlspecialchars($firstName) . ' ' . htmlspecialchars($lastName);
        } else {
            $displayName = $username;
        }

        // Fetch follower and following counts from $userProfile
        $followersCount = $userProfile->getFollowersCount() ?? 0;
        $followingCount = $userProfile->getFollowingCount() ?? 0;
        ?>

        <!-- Profile Header with Display Name and Logout Button -->
        <div class="control-profile-header">
            <span class="display-name"><?= $displayName; ?></span>
            <a href="/logout" class="logout-icon">🚪</a>
        </div>

        <!-- Profile Info with Follower and Following Counts -->
        <div class="control-profile-info">
            <img src="<?= asset('/../uploads/users/' . htmlspecialchars($userProfile->getProfilePicture() ?? 'user-default.svg')); ?>"
                alt="<?= htmlspecialchars($userProfile->getUsername() ?? 'Unknown User'); ?>" class="control-user-picture">
            <h3><?= htmlspecialchars($userProfile->getUsername() ?? 'Unknown User'); ?></h3>
            <p><?= htmlspecialchars($userProfile->getRank() ?? 'Member'); ?></p>

            <!-- Display Follower and Following Counts -->
            <div class="follow-stats">
                <p>Following: <?= htmlspecialchars($followingCount); ?></p>
                <p>Followers: <?= htmlspecialchars($followersCount); ?></p>
            </div>
        </div>

        <!-- Control Buttons Section -->
        <div class="control-buttons">
            <?php if (AuthController::isLoggedIn()): ?>
                <a href="/profile/<?= htmlspecialchars($username); ?>" class="button-primary">View Profile</a>
            <?php endif; ?>

            <!-- User Management Button (only for admins) -->
            <?php if ($_SESSION['user']['is_admin'] ?? false): ?>
                <button class="button" onclick="toggleUserManagement()">User Management</button>
                <div id="userManagement" style="display: none;">
                    <?php include __DIR__ . '/../admin/manage_users.php'; ?>
                </div>
            <?php endif; ?>
            <!-- User Management Button (only for admins) -->
            <?php if ($_SESSION['user']['is_admin'] ?? false): ?>
                <button id="toggleTagsManagementButton" class="button">Tags Management</button>
            <?php endif; ?>
            <?php if ($_SESSION['user']['is_admin'] ?? false): ?>
                <button id="toggleIngredientManagementButton" class="button">Ingredient Management</button>
            <?php endif; ?>

            <!-- Link to Add New Cocktail (only for logged-in users) -->
            <?php if (AuthController::isLoggedIn() && $currentUser->canAddCocktail($currentUser->getId())): ?>
                <a href="/cocktails/add" class="button-secondary">Add New Cocktail</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- <p>User profile not available.</p> -->
    <?php endif; ?>
</div>