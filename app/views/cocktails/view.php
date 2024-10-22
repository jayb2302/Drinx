<?php
include __DIR__ . '/../layout/header.php';
include_once __DIR__ . '/../../helpers/helpers.php';

if (!isset($isEditing)) {
    $isEditing = false;
}

$cocktail = $cocktail ?? new Cocktail();
$steps = $steps ?? [];
$ingredients = $ingredients ?? [];
$categories = $categories ?? [];
$units = $units ?? [];

?>
<?php if (isset($_SESSION['errors'])): ?>
    <div class="error-messages">
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<div class="cocktail-actions">
    <button>
        <a href="/" class="">Back to Cocktails</a>
    </button>
</div>

<div class="wrapper">
    <!-- Cocktail Details -->
    <div class="recipeContainer">
        <div class="orderby">
            <p class="tag font-semibold"><?= htmlspecialchars($category['category_name'] ?? 'Unknown') ?></p>
            <p class="tag text-lg font-semibold"><?= htmlspecialchars(implode(', ', array_column($tags ?? [], 'name'))) ?></p>
        </div>

        <div class="cocktailImage">
            <?php if ($cocktail->getImage()): ?>
                <img src="/uploads/cocktails/<?= htmlspecialchars($cocktail->getImage()) ?>" alt="<?= htmlspecialchars($cocktail->getTitle()) ?>" class="mb-4 cocktailImage">
            <?php else: ?>
                <p>No image available for this cocktail.</p>
            <?php endif; ?>
        </div>
        <h1 class="title"><?= htmlspecialchars($cocktail->getTitle() ?? 'Untitled') ?></h1>

        <p><?= htmlspecialchars($cocktail->getDescription() ?? 'No description available') ?></p>

        <h2 class="text-2xl font-semibold mt-6 mb-2">Ingredients</h2>
        <ul class="list-disc ml-5">
            <?php if (!empty($ingredients)): ?>
                <?php foreach ($ingredients as $ingredient): ?>
                    <li>
                        <?= htmlspecialchars($ingredient->getName()) ?>:
                        <?= htmlspecialchars($ingredient->getQuantity()) ?> <?= htmlspecialchars($ingredient->getUnitName()) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No ingredients listed for this cocktail.</li>
            <?php endif; ?>
        </ul>

        <h2 class="text-2xl font-semibold mt-6 mb-2">Preparation Steps</h2>
        <ol class="list-decimal ml-5">
            <?php if (!empty($steps)): ?>
                <?php foreach ($steps as $step): ?>
                    <li><?= htmlspecialchars($step->getInstruction() ?? 'No instruction provided') ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No preparation steps available for this cocktail.</li>
            <?php endif; ?>
        </ol>
    </div>

    <!-- Edit Button -->
    <?php if (isset($_SESSION['user']['id']) && ($_SESSION['user']['id'] === $cocktail->getUserId() || AuthController::isAdmin())): ?>
        <button id="editCocktailButton" class="text-blue-500 hover:underline">
            Edit Cocktail
        </button>
        <!-- Delete Button -->
        <form action="/cocktails/delete/<?= $cocktail->getCocktailId() ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this cocktail?');">
            <button type="submit" class="text-red-500 hover:underline">Delete Cocktail</button>
        </form>
    <?php endif; ?>

    <!-- Hidden Form (inline editing) -->
    <div id="editFormContainer" style="display: none;">
        <?php
        $isEditing = true;
        include __DIR__ . '/form.php';
        ?>
    </div>
  
    <!-- Display Comments Section -->
    <h2 class="text-2xl font-semibold mt-6 mb-2">Comments</h2>
    <div class="comments-section">
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-box">
                    <div class="comment">
                        <p><strong><?= htmlspecialchars($comment->getUsername() ?? 'Unknown User') ?>:</strong></p>
                        <p><?= htmlspecialchars($comment->getCommentText() ?? 'No comment text available') ?></p>
                        <p class="comment-date"><small>Posted on <?= htmlspecialchars($comment->getCreatedAt() ?? 'Unknown date') ?></small></p>

                        <!-- Dots Menu -->
                        <?php if (isset($_SESSION['user']['id']) && ($_SESSION['user']['id'] === $comment->getUserId() || AuthController::isAdmin())): ?>
                            <div class="dots-menu">
                                <button class="dots-button">⋮</button>
                                <div class="menu hidden">
                                    <a href="/comments/<?= $comment->getCommentId() ?>/edit" class="menu-item">Edit</a>
                                    <form action="/comments/<?= $comment->getCommentId() ?>/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        <button type="submit" class="menu-item">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Replies Section -->
                    <?php if (!empty($comment->replies)): ?>
                        <div class="replies-section" style="margin-left: 20px;"> <!-- Indentation for replies -->
                            <?php foreach ($comment->replies as $reply): ?>
                                <div class="reply">
                                    <p><strong><?= htmlspecialchars($reply->getUsername() ?? 'Unknown User') ?>:</strong></p>
                                    <p><?= htmlspecialchars($reply->getCommentText() ?? 'No reply text available') ?></p>
                                    <p class="comment-date"><small>Posted on <?= htmlspecialchars($reply->getCreatedAt() ?? 'Unknown date') ?></small></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Reply Form -->
                    <form action="/cocktails/<?= htmlspecialchars($cocktail->getCocktailId()) ?>-<?= urlencode($cocktail->getTitle()) ?>/comments" method="POST">
                        <textarea name="comment" placeholder="Write your reply here..." required></textarea>
                        <input type="hidden" name="parent_comment_id" value="<?= htmlspecialchars($comment->getCommentId()) ?>">
                        <input type="hidden" name="cocktailTitle" value="<?= htmlspecialchars($cocktail->getTitle()) ?>">
                        <button type="submit">Reply</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>
    </div>

    <!-- Add Top-level Comment Form -->
    <h3>Add a New Comment</h3>
    <form action="/cocktails/<?= htmlspecialchars($cocktail->getCocktailId()) ?>-<?= urlencode($cocktail->getTitle()) ?>/comments" method="POST">
        <textarea name="comment" placeholder="Write your comment here..." required></textarea>
        <input type="hidden" name="parent_comment_id" value=""> 
        <input type="hidden" name="cocktailTitle" value="<?= htmlspecialchars($cocktail->getTitle()) ?>">
        <button type="submit">Submit</button>
    </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>