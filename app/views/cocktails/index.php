<?php
// Include the header
include_once __DIR__ . '/../layout/header.php';
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
        // Get the cocktail's user ID
        $cocktailUserId = $cocktail->getUserId();
        // Get the cocktail's image
        $imageSrc = $cocktail->getImage();

        // Ensure correct path construction
        if (!empty($imageSrc)) {
            // If the imageSrc already contains '/uploads/cocktails/', use it directly, else prepend it
            $imagePath = strpos($imageSrc, '/uploads/cocktails/') === false ? "/uploads/cocktails/$imageSrc" : $imageSrc;
        } else {
            // Default image if none exists
            $imagePath = '/uploads/cocktails/default-image.webp';
        }

        // Prevent htmlspecialchars from receiving null
        $cocktailTitle = htmlspecialchars($cocktail->getTitle() ?? 'Unknown Cocktail');

        ?>

        <!-- Individual cocktail card -->
        <div class="container">
            <article class="cocktailCard">

                <?php if (isset($loggedInUserId) && $loggedInUserId === $cocktailUserId): ?>
                    <button>
                        <a href="/?action=edit&cocktail_id=<?= $cocktail->getCocktailId() ?>" class="text-blue-500 hover:underline">Edit Cocktail</a>
                    </button>
                <?php endif; ?>

                <!-- Like/Unlike Button -->
                <div class="like-section">
                    <?php if ($loggedInUserId): ?>
                        <button class="like-button"
                            data-cocktail-id="<?= $cocktail->getCocktailId() ?>"
                            data-liked="<?= $cocktail->hasLiked ? 'true' : 'false' ?>">
                            <span class="like-icon">
                                <?= $cocktail->hasLiked ? '♥️' : '🤍' ?>
                            </span>
                        </button>
                    <?php else: ?>
                        <p><a href="/login">Log in to like</a></p>
                    <?php endif; ?>
                </div>

                <a href="/cocktails/<?= htmlspecialchars($cocktail->getCocktailId()) ?>-<?= urlencode($cocktail->getTitle()) ?>">
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= $cocktailTitle ?>" class="cocktailImage">
                </a>
                <div class="cocktailInfo">
                    <h3><?= $cocktailTitle ?></h3>
                </div>

            </article>
        </div>
    <?php endforeach; ?>

<?php else: ?>
    <p>No cocktails available.</p>
<?php endif; ?>