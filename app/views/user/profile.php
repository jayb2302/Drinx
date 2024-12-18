<?php
$page = 'profile';
//profile name is the title
$metaTitle = htmlspecialchars($profile->getFirstName() ?? 'User Profile');
include_once __DIR__ . '/../layout/header.php'; ?>

<!-- <div id="message" class="success"> <i class="fa-solid fa-bell success"></i> <h4> this is a test message </h4> </div> --> <!-- Test message -->
<div class="profile__container">
    <!-- User Profile Header -->
    <aside class="profile__main">
        <!-- User Info -->
        <div class="profile-info">
            <?php if ($userId === $profileUserId): ?>
                <a href="/logout" class="logout-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
            <?php endif; ?>
            <!-- Profile Picture -->
            <div class="profile-picture">
                <?php if ($userId !== $profileUserId): ?>
                    <?php if (isset($isFollowing) && $isFollowing): ?>
                        <form action="/user/unfollow/<?= htmlspecialchars($profileUserId); ?>" method="POST">
                            <button type="submit" class="btn btn-danger unfollow">Unfollow</button>
                        </form>
                    <?php else: ?>
                        <form action="/user/follow/<?= htmlspecialchars($profileUserId); ?>" method="POST">
                            <button type="submit" class="btn btn-primary follow">Follow</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?> 
                <?php if ($profile->getProfilePicture()): ?>
                    <img class="profile-img"
                        src="<?= asset('/../uploads/users/' . htmlspecialchars($profile->getProfilePicture())); ?>"
                        alt="Profile picture of <?= htmlspecialchars($profile->getUsername()); ?>" class="profile-img">
                <?php else: ?>
                    <img src="<?= asset('/../uploads/users/user-default.svg'); ?>" alt="Default Profile Picture"
                        class="profile-img">
                <?php endif; ?>
                <h2>
                    <?= htmlspecialchars($profile->getFirstName() ?? 'No First Name') . ' ' . htmlspecialchars($profile->getLastName() ?? 'No Last Name'); ?>
                </h2>
                <small>@ <?= htmlspecialchars($profile->getUsername() ?? 'Unknown'); ?></small>

                <p class="bio">
                    <span class="bio-content">
                        <?= htmlspecialchars($profile->getBio() ?? 'This user has not set a bio yet.'); ?>
                    </span>
                    <span class="bio-toggle"> <a href="#" class="read-more">Read more</a></span>
                </p>

                <div class="follow-stats">
                    <small>Following: <?= htmlspecialchars($userProfile->getFollowingCount() ?? 0); ?></small>
                    <div>|</div>
                    <small>Followers: <?= htmlspecialchars($userProfile->getFollowersCount() ?? 0); ?></small>
                </div>
            </div>

            <!-- User's Badges Section -->
            <div class="profile-badges">
                <?php if (!empty($userBadges)): ?>
                    <div class="badge-grid">
                        <?php foreach ($userBadges as $badge): ?>
                            <div class="badge-card">
                                <!-- <img src="<?= asset('uploads/badges/' . htmlspecialchars(string: $badge->getBadgeImage() ?? 'default-badge.svg')); ?>"
                                alt="<?= htmlspecialchars($badge->getName()); ?>" class="badge-img"> -->
                                <p title="<?= htmlspecialchars($badge->getDescription()); ?>">
                                    <i class="fa-solid fa-trophy badge"></i> <?= htmlspecialchars($badge->getName()); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No badges earned yet.</p>
                <?php endif; ?>
                <!-- Progress to next badge -->
                <?php if ($userId === $profileUserId): ?>
                    <?php if (isset($progressData) && !empty($progressData['nextBadge'])): ?>
                        <div class="badge-progress-container">
                            <div class="badge-progress">
                                <!-- <h4>Progress:</h4> -->
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: <?= $progressData['progress']; ?>%;"></div>
                                </div>
                            </div>
                            <p>
                                <?= ($progressData['nextMilestone'] - $cocktailCount); ?> more
                                cocktail<?= ($progressData['nextMilestone'] - $cocktailCount) > 1 ? 's' : ''; ?>
                                to become <?= htmlspecialchars($progressData['nextBadge']->getName()); ?>.
                            </p>
                        </div>
                    <?php else: ?>
                        <p>You have earned all available badges. Keep creating amazing cocktails!</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Social Profile Statistics (Optional) -->
            <div class="profile-stats">
                <h3>Statistics</h3>
                <ul>
                    <li>Total Recipes: <?= $profileStats['cocktail_count'] ?? 0; ?></li>
                    <li>Likes Received: <?= $profileStats['likes_received'] ?? 0; ?></li>
                    <li>Comments Received: <?= $profileStats['comments_received'] ?? 0; ?></li>
                </ul>
            </div>
            <div class="socials-container">
                <h3>Socials</h3>
                <div class="social-icons">
                    <?php if (!empty($socialLinks)): ?>
                        <?php foreach ($socialLinks as $link): ?>
                            <a href="<?= htmlspecialchars($link['url']); ?>" target="_blank" rel="noopener noreferrer"
                                title="<?= htmlspecialchars($link['platform_name']); ?>" class="social-icon-link">
                                <i class="fa-brands <?= htmlspecialchars($link['icon_class']); ?>"></i>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No social media links added yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="edit-delete">
                <?php if ($userId === $profileUserId): ?>
                    <div id="overlay" style="display:none;"></div>
                    <a id="edit-profile-button" class="button-secondary">
                        <span>
                            Edit Profile
                        </span>
                    </a>
                <?php endif; ?>
                <?php if ($userId === $profileUserId or $authController->isAdmin()): ?>
                    <div class="delete-account-section">
                        <button id="deleteAccountButton" class="button-secondary">Delete Account</button>
                        <div id="deleteConfirmSection" style="display:none;">
                            <p class="warning">Warning: This action will permanently delete the account and cannot be
                                undone!</p>
                            <form action="/profile/<?= urlencode($profile->getUsername()) ?>/delete" method="POST">
                                <?= '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCsrfToken()) . '">' ?>
                                <label for="password">
                                    <?= $authController->isAdmin() ? 'Admin Password' : 'Confirm Password' ?>:
                                </label>
                                <input type="password" name="password" required>
                                <?php if ($authController->isAdmin()): ?>
                                    <input type="hidden" name="delete_user_id" value="<?= htmlspecialchars($profileUserId) ?>">
                                <?php endif; ?>
                                <button type="submit" class="confirm-delete">Confirm Deletion</button>
                            </form>
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); ?></div>
                                <?php unset($_SESSION['error']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </aside>
    <!-- Recipe Section -->
    <div id="edit-profile-form" style="display:none;">
        <?php include __DIR__ . '/form.php'; ?>
    </div>
    <main class="profile__recipes">
        <div class="cocktailActions">
            <a href="/" class="button-secondary">Back</a>
        </div>
        <!-- Profile Edit Form - Only show if user is viewing their own profile -->
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

                        <!-- Cocktail Info -->
                        <a
                            href="/cocktails/<?= htmlspecialchars($recipe->getCocktailId() ?? '0') ?>-<?= urlencode($recipe->getTitle() ?? 'Untitled Cocktail') ?>">
                            <img src="<?= asset('/../uploads/cocktails/' . htmlspecialchars($recipe->getImage() ?? 'default-image.svg')); ?>"
                                alt="<?= htmlspecialchars($recipe->getTitle() ?? 'Cocktail Image') ?>" class="cocktailImage">
                            <h3><?= htmlspecialchars($recipe->getTitle()); ?></h3>
                        </a>
                        <div class="cocktailInfo">
                            <p class="date">
                                <i class="fa-solid fa-calendar"></i>
                                <?= formatDate($recipe->getCreatedAt()); ?>
                            </p>
                            <p class="date">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <?= formatDate($recipe->getUpdatedAt()); ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No recipes uploaded yet.</p>
        <?php endif; ?>

        </main>
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>