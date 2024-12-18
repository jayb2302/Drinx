<?php
$metaTitle = isset($cocktail) ? htmlspecialchars($cocktail->getTitle()) . " - Drinx" : "Cocktail - Drinx";
$page = 'cocktail';
include __DIR__ . '/../layout/header.php';
include_once __DIR__ . '/../../helpers/helpers.php';

?>
<?php if (isset($_SESSION['badge_notification'])): ?>
    <!-- <?php error_log("Displaying badge notification: " . print_r($_SESSION['badge_notification'], true)); ?> -->
    <div id="message" class="success">
        <i class="fa-solid fa-bell success"></i><h4><?= htmlspecialchars($_SESSION['badge_notification']['message']); ?></h4>
        <!-- <p><?= htmlspecialchars($_SESSION['badge_notification']['badge_description']); ?></p> -->
    </div>
    <?php unset($_SESSION['badge_notification']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['errors'])): ?>
    <ul class="error-messages">
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <li><?= htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>
<div class="recipe__container">
    <main class="recipe__main">
        <div class="cocktailActions">
            <?php if ($authController->isLoggedIn() && ($currentUser->canEditCocktail($cocktail->getUserId()) || $authController->isAdmin())): ?>
                <button id="editCocktailButton" class="primary"> <i class="fa-solid fa-pencil"></i> </button>
                <!-- Delete Button -->
                <form action="/cocktails/delete/<?= $cocktail->getCocktailId() ?>" method="post"
                    onsubmit="return confirm('Are you sure you want to delete this cocktail?');">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()) ?>">
                    <button type="submit" class="delete"> <i class="fa-solid fa-trash"></i> </button>
                </form>
            <?php endif; ?>
            <a href="/" class="button-secondary">Back</a>
        </div>

        <!-- Hidden Form (inline editing) -->
        <div id="editFormContainer" style="display: none;">
            <?php
            $isEditing = true;
            include __DIR__ . '/form.php';
            ?>
        </div>

        <!-- Like/Unlike Button -->
        <div class="like-section">
            <!-- Category -->
            <div class="recipe-category">
                <p class="category font-semibold">
                    <?= htmlspecialchars($categoryName) ?>
                </p>
            </div>
            <?php if ($loggedInUserId): ?>
                <button class="like-button"
                    data-cocktail-id="<?= htmlspecialchars($cocktail->getCocktailId()) ?>"
                    data-liked="<?= $cocktail->hasLiked ? 'true' : 'false' ?>">
                    <span class="like-icon">
                        <i class="<?= $cocktail->hasLiked ? 'fa-solid fa-heart' : 'fa-regular fa-heart' ?>"></i>
                    </span>
                    <span class="like-count"><?= htmlspecialchars($totalLikes) ?></span>
                </button>
            <?php else: ?>
                <!-- Disabled button for logged-out users -->
                <div class="like-button-container">
                    <button class="like-button disabled" data-disabled="true">
                        <span class="like-icon">
                            <i class="fa-regular fa-heart"></i>
                        </span>
                        <span class="like-count"><?= htmlspecialchars($totalLikes) ?></span>
                    </button>
                    <span class="login-message">
                        Please <a href="/login" class="login-link">log in</a> to like this cocktail..
                    </span>
                </div>
            <?php endif; ?>
        </div>
        <h1 class="title"><?= ucwords(strtolower($cocktailTitle)) ?></h1>
        <!-- Creator Info -->
        <div class="creatorInfo">
            <?php if ($creator->getProfilePicture()): ?>
                <img class="creatorPicture"
                    src="<?= asset('/../uploads/users/' . htmlspecialchars($creator->getProfilePicture())); ?>"
                    alt="Profile picture of <?= htmlspecialchars($creator->getUsername()); ?>">
            <?php else: ?>
                <img src="<?= asset('/../uploads/users/user-default.svg'); ?>" alt="Default Profile Picture"
                    class="creatorPicture">
            <?php endif; ?>
            <div class="creatorDetails">
                <a href="/profile/<?= htmlspecialchars($creator->getUsername()); ?>">
                    <?= htmlspecialchars($creator->getFirstName() ?? $creator->getUsername()); ?>
                </a>
                <span><i class="fa-solid fa-calendar"></i> <?= formatDate($cocktail->getCreatedAt() ?? 'Unknown date') ?></span>
            </div>
        </div>

        <div class="recipeContainer">
            <!-- Image -->
            <div class="recipeImage">
                <?php if ($cocktail->getImage()): ?>
                    <img src="/uploads/cocktails/<?= htmlspecialchars($cocktail->getImage()) ?>"
                        alt="<?= htmlspecialchars($cocktail->getTitle()) ?>" class="mb-4 cocktailImage">
                <?php else: ?>
                    <p>No image available for this cocktail.</p>
                <?php endif; ?>
                <div class="tag-list">
                    <?php foreach ($tags ?? [] as $tag): ?>
                        <span class="tag"><?= htmlspecialchars($tag['name']) ?></span>
                    <?php endforeach; ?>
                </div>
                <p><strong><i class="fa-solid fa-stopwatch"></i></strong> <?= formatPrepTime($cocktail->getPrepTime()) ?></p>
                <p>
                    <?= htmlspecialchars($cocktail->getDescription() ?? 'No description available') ?>
                </p>
                <p class="recipeDifficulty">
                    <?= $cocktail->getDifficultyIconHtml() ?>
                    <?= ucfirst($cocktail->getDifficultyName()) ?>
                </p>

            </div>
            <div class="recipeInstructions">
                <!-- Ingredients -->
                <h2 class="">Ingredients</h2>
                <ul class="list-disc ml-5">
                    <?php if (!empty($processedIngredients)): ?>
                        <?php foreach ($processedIngredients as $ingredient): ?>
                            <li>
                                <?= htmlspecialchars($ingredient['quantity']) ?>
                                <?= htmlspecialchars($ingredient['unit']) ?> -
                                <?= htmlspecialchars($ingredient['name']) ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No ingredients listed for this cocktail.</li>
                    <?php endif; ?>
                </ul>

                <!-- Preparation Steps -->
                <h2 class="">Preparation Steps</h2>
                <ol class="list-decimal ml-5">
                    <?php if (!empty($steps)): ?>
                        <?php foreach ($steps as $index => $step): ?>
                            <li>
                                <strong>Step <?= $index + 1 ?>:</strong> <?= htmlspecialchars($step->getInstruction() ?? 'No instruction provided') ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No preparation steps available for this cocktail.</li>
                    <?php endif; ?>
                </ol>
            </div>
        </div>
    </main>

    <aside class="container__comments">
        <?php
        require_once __DIR__ . '/comment_section.php';
        ?>
    </aside>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>