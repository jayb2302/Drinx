<?php
// Include the header
if (isset($isStandalone) && $isStandalone) {
    include_once __DIR__ . '/../layout/header.php';
}
$metaTitle = "Cocktails";
$pageTitle = "Cocktails";

// Start the session (if not already started)
$loggedInUserId = $_SESSION['user']['id'] ?? null;
?>
<!-- admin password not working, so I am using the password_hash() function 
     to generate a new password hash, then update admin password in database -->
<?php
// echo password_hash('12345678', PASSWORD_BCRYPT); 
?>

<?php if (!empty($cocktails)): ?>
    <?php foreach ($cocktails as $cocktail): ?>
        <?php
        $cocktailUserId = $cocktail->getUserId();
        $imageSrc = $cocktail->getImage();
        $imagePath = !empty($imageSrc) ? "/uploads/cocktails/$imageSrc" : '/uploads/cocktails/default-image.webp';
        $cocktailTitle = htmlspecialchars($cocktail->getTitle() ?? 'Unknown Cocktail');
        $totalLikes = $this->cocktailService->getLikesForCocktail($cocktail->getCocktailId());

        // Get comment count
        $commentCount = $cocktail->getCommentCount(); // Fetch the comment count from the object

        // Get top-level comments
        $comments = $cocktail->getComments(); // Fetch the comments fr

        // Creator Info
        $creator = $this->userService->getUserWithProfile($cocktailUserId);
        $creatorName = htmlspecialchars($creator->getUsername() ?? 'Unknown User');
        $creatorPicture = htmlspecialchars($creator->getProfilePicture() ?? 'user-default.svg');
        $tags = $this->cocktailService->getTagsForCocktail($cocktail->getCocktailId());

        ?>

        <div class="cocktailContainer">
            <article class="cocktailCard">
                <!-- User Info Section -->
                <div class="creatorInfo">
                    <img src="/uploads/users/<?= htmlspecialchars($creatorPicture) ?>" alt="<?= htmlspecialchars($creatorName) ?>'s Profile Picture" class="creatorPicture">
                    <a href="/profile/<?= urlencode($creatorName) ?>" title="View <?= htmlspecialchars($creatorName) ?>'s profile">
                        @<?= htmlspecialchars($creatorName) ?>
                    </a>
                </div>

                <!-- Cocktail Card Content -->
                <div class="buttonWrapper">
                    <?php if ($_SESSION['user']['is_admin'] ?? false): ?>
                        <button class="set-sticky <?= $cocktail->isSticky() ? 'active' : '' ?>"
                            data-cocktail-id="<?= $cocktail->getCocktailId() ?>"
                            data-sticky-status="<?= $cocktail->isSticky() ? 'true' : 'false' ?>"
                            aria-pressed="<?= $cocktail->isSticky() ? 'true' : 'false' ?>"
                            title="<?= $cocktail->isSticky() ? 'Remove Sticky' : '📌' ?>">
                            <?= $cocktail->isSticky() ? '📌' : '📌' ?>
                        </button>
                    <?php endif; ?>

                    <div class="like-section">
                        <!-- Display the like count for all users -->
                        <?php if ($loggedInUserId): ?>
                            <!-- If the user is logged in, show the like button -->
                            <button class="like-button" data-cocktail-id="<?= $cocktail->getCocktailId() ?>"
                                data-liked="<?= $cocktail->hasLiked ? 'true' : 'false' ?>">
                                <?= $totalLikes ?>
                                <span class="like-icon">
                                    <?= $cocktail->hasLiked ? '❤️' : '🤍' ?>
                                </span>
                            </button>
                        <?php else: ?>
                            <!-- If the user is not logged in, show only the like count and a non-functional like button -->
                            <button class="like-button" onclick="showLoginPopup(event)" title="Log in to like this cocktail">
                                <span class="like-icon">🤍</span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Hidden login popup -->
                    <div id="loginPopup" style="display:none; position:fixed; top:20%; left:50%; transform:translateX(-50%); background-color:#fff; padding:20px; border:1px solid #ccc; box-shadow: 0 2px 10px rgba(0,0,0,0.2); z-index:1000;">
                        <p>Please log in to like this cocktail.</p>
                        <button onclick="closeLoginPopup()">Close</button>
                    </div>

                </div>

                <!-- Cocktail Image and Title -->
                <a href="/cocktails/<?= htmlspecialchars($cocktail->getCocktailId()) ?>-<?= urlencode($cocktail->getTitle()) ?>">
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= $cocktailTitle ?>" class="cocktailImage">
                    <div class="cocktailInfo">
                        <h3><?= ucwords(strtolower($cocktailTitle)) ?> <?= $totalLikes ?>♥️ </h3>
                    </div>
                </a>
                <div class="orderby">
                    <?php foreach ($tags ?? [] as $tag): ?>
                        <span class="tag"><?= htmlspecialchars($tag['name']) ?></span>
                    <?php endforeach; ?>
                </div>

                <!-- Comment Count and Recent Comments -->
                <div class="commentSection">
                    <?php if ($cocktail->commentCount > 0): ?>
                        <div class="recentComments">
                            <p class="commentCount"> <?= $cocktail->commentCount ?> comments</p>
                            <ul>
                                <?php foreach ($cocktail->comments as $comment): ?>
                                    <li class="commentBox">
                                        <div class="comment">
                                            <div class="creatorInfo">
                                                <!-- Display the profile picture of the user -->
                                                <?php if ($comment->getProfilePicture()): ?>
                                                    <img class="creatorPicture" src="<?= asset('/../uploads/users/' . htmlspecialchars($comment->getProfilePicture())); ?>"
                                                        alt="Profile picture of <?= htmlspecialchars($comment->getUsername()); ?>">
                                                <?php else: ?>
                                                    <img src="<?= asset('/../uploads/users/user-default.svg'); ?>" alt="Default Profile Picture" class="creatorPicture">
                                                <?php endif; ?>
                                                <!-- Display the username of the comment creator -->
                                                <p><strong><?= htmlspecialchars($comment->getUsername() ?? 'Unknown User') ?>:</strong></p>

                                                <!-- Optionally add dots menu for edit/delete if user is logged in and is the comment creator or an admin -->
                                                <?php if ($_SESSION['user']['id'] === $comment->getUserId() || AuthController::isAdmin()): ?>
                                                    <div class="dotsMenu">
                                                        <button class="dotsButton">⋮</button>
                                                        <div class="menu hidden">
                                                            <button type="button" class="menuItem editCommentButton" data-comment-id="<?= $comment->getCommentId() ?>">🖊️</button>
                                                            <form action="/comments/<?= $comment->getCommentId() ?>/delete" method="POST">
                                                                <button type="submit" class="menuItem">🗑️</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Display the comment text -->
                                            <p><?= htmlspecialchars($comment->getCommentText() ?? 'No comment text available') ?></p>

                                            <!-- Display the comment creation date -->
                                            <p class="commentDate"><small><?= htmlspecialchars($comment->getCreatedAt() ?? 'Unknown date') ?></small></p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <p class="commentCount">No comments yet.</p>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    <?php endforeach; ?>

<?php else: ?>
    <p>No cocktails available.</p>
<?php endif; ?>
<?php if (isset($isStandalone) && $isStandalone) {
    include_once __DIR__ . '/../layout/footer.php';
}
?>