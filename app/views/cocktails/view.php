<?php
$metaTitle = isset($cocktail) ? htmlspecialchars($cocktail->getTitle()) . " - Drinx" : "Cocktail - Drinx";
$page = 'cocktail';
include __DIR__ . '/../layout/header.php';
include_once __DIR__ . '/../../helpers/helpers.php';

?>
<?php if (isset($_SESSION['errors'])): ?>
    <div class="error-messages">
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<div class="recipe__container">
    <main class="recipe__main">
        <div class="cocktailActions">
            <?php if (AuthController::isLoggedIn() && ($currentUser->canEditCocktail($cocktail->getUserId()) || AuthController::isAdmin())): ?>
                <button id="editCocktailButton" class="primary"> 🖊️ </button>
                <!-- Delete Button -->
                <form action="/cocktails/delete/<?= $cocktail->getCocktailId() ?>" method="post"
                    onsubmit="return confirm('Are you sure you want to delete this cocktail?');">
                    <button type="submit" class="delete">🗑️</button>
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
            <?php if ($loggedInUserId): ?>
                <button class="like-button"
                    data-cocktail-id="<?= $cocktail->getCocktailId() ?>"
                    data-liked="<?= $cocktail->hasLiked ? 'true' : 'false' ?>">
                    <span class="like-icon">
                        <?= $cocktail->hasLiked ? '❤️' : '🤍' ?>
                    </span>
                </button>
                <span class="like-count"><?= $totalLikes ?>  </span>
            <?php else: ?>
                <button class="like-button" onclick="showLoginPopup(event)">
                    <span class="like-icon">🤍</span>
                </button>
            <?php endif; ?>
            <!-- Display the like count -->
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
            <a href="/profile/<?= htmlspecialchars($creator->getUsername()); ?>">
                <?= htmlspecialchars($creator->getFirstName() ?? $creator->getUsername()); ?>
            </a>
            </p>
        </div>
        <!-- Tags -->
        <div class="recipe-category">
            <p class="tag font-semibold">
                <?= htmlspecialchars($categoryName) ?>
            </p>
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
                <p>
                    <strong>Difficulty:</strong> <?= htmlspecialchars($cocktail->getDifficultyName() ?? 'Not specified') ?>
                </p>
                <p>
                    <?= htmlspecialchars($cocktail->getDescription() ?? 'No description available') ?>
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