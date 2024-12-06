<div class="controlPanel">
    <?php if ($userProfile): ?>
        <?php
        // Determine display name
        $firstName = $userProfile->getFirstName() ?? null;
        $lastName = $userProfile->getLastName() ?? null;
        $username = htmlspecialchars($userProfile->getUsername() ?? 'User'); // Fallback to "User" if username is missing
        // Display name: Use first and last name, or fall back to username
        $displayName = ($firstName && $lastName)
            ? htmlspecialchars($firstName) . ' ' . htmlspecialchars($lastName)
            : $username;
        // Fetch follower and following counts
        $followersCount = $userProfile->getFollowersCount() ?? 0;
        $followingCount = $userProfile->getFollowingCount() ?? 0;
        ?>
        <!-- Profile Header with Display Name and Logout Button -->
        <div class="control-profile-header">
            <span class="display-name"><?= $displayName; ?></span>
            <a href="/logout" class="logout-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
        </div>
        <!-- Profile Info with Follower and Following Counts -->
        <div class="control-profile-info">
            <img src="<?= asset('/../uploads/users/' . htmlspecialchars($userProfile->getProfilePicture() ?? 'user-default.svg')); ?>"
                alt="<?= htmlspecialchars($userProfile->getUsername() ?? 'Unknown User'); ?>" class="control-user-picture">
            
            <p><?= htmlspecialchars($userProfile->getRank() ?? 'Member'); ?></p>
            <!-- Display Follower and Following Counts -->
            <div class="follow-stats">
                <small>Following: <?= htmlspecialchars($followingCount); ?></small>
                <small>Followers: <?= htmlspecialchars($followersCount); ?></small>
            </div>
        </div>

        <!-- Control Buttons Section -->
        <div class="control-buttons">
            <?php if ($authController->isLoggedIn()): ?>
                <a href="/profile/<?= htmlspecialchars($username); ?>" class="button-secondary" >
                    <span >
                        My Profile
                    </span>
                </a>
            <?php endif; ?>
            <?php if ($_SESSION['user']['is_admin'] ?? false): ?>
                <!-- <button class="button" onclick="toggleUserManagement()">User Management</button> -->
                <div id="userManagement" style="display: none;">
                    <?php include __DIR__ . '/../admin/manage_users.php'; ?>
                </div>
                <!-- 
                <button id="toggleTagsManagementButton" class="button">Tags Management</button> -->
            <?php endif; ?>
            <!-- User Management Button (only for admins) -->
            <?php if ($_SESSION['user']['is_admin'] ?? false): ?>
                <a class="button-secondary" href="/admin/dashboard">
                    <span class="">
                        Dashboard
                    </span>
                </a>
            <?php endif; ?>

            <!-- Link to Add New Cocktail (only for logged-in users) -->
            <?php if ($authController->isLoggedIn() && $currentUser->canAddCocktail($currentUser->getId())): ?>
                    <a href="/cocktails/add" class="button-secondary">
                        <span>
                            Share New Cocktail
                        </span>
                    </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- <p>User profile not available.</p> -->
    <?php endif; ?>
</div>