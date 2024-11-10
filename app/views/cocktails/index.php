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

        // Creator Info
        $creator = $this->userService->getUserWithProfile($cocktailUserId);
        $creatorName = htmlspecialchars($creator->getUsername() ?? 'Unknown User');
        $creatorPicture = htmlspecialchars($creator->getProfilePicture() ?? 'user-default.svg');
        ?>

        <div class="container">
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

                    <?php if ($loggedInUserId): ?>
                        <button class="like-button" data-cocktail-id="<?= $cocktail->getCocktailId() ?>"
                            data-liked="<?= $cocktail->hasLiked ? 'true' : 'false' ?>">
                            <span class="like-icon"><?= $cocktail->hasLiked ? '♥️' : '🤍' ?></span>
                            <span class="like-count"><?= $totalLikes ?> </span>
                        </button>
                    <?php else: ?>
                        <p><a href="/login">Log in to like</a></p>
                    <?php endif; ?>

                </div>



                <!-- Cocktail Image and Title -->
                <a href="/cocktails/<?= htmlspecialchars($cocktail->getCocktailId()) ?>-<?= urlencode($cocktail->getTitle()) ?>">
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= $cocktailTitle ?>" class="cocktailImage">
                </a>
                <div class="cocktailInfo">
                    <h3><?= $cocktailTitle ?> <?= $totalLikes ?>♥️ </h3>
                </div>

            </article>
        </div>
    <?php endforeach; ?>

<?php else: ?>
    <p>No cocktails available.</p>
<?php endif; ?>