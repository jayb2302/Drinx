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
$totalLikes = $this->cocktailService->getLikeCount($cocktailId);

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

<div class="recipeWrapper">
    <!-- Hidden Form (inline editing) -->
    <div id="editFormContainer" style="display: none;">
        <?php
        $isEditing = true;
        include __DIR__ . '/form.php';
        ?>
    </div>
    <!-- Cocktail Details -->
    <div class="recipeContainer">
        <div class="orderby">
            <p class="tag font-semibold"><?= htmlspecialchars($category['name'] ?? 'Unknown') ?></p>
            <p class="tag text-lg font-semibold">
                <?= htmlspecialchars(implode(', ', array_column($tags ?? [], 'name'))) ?>
            </p>
        </div>

        <div class="cocktailImage">
            <?php if ($cocktail->getImage()): ?>
                <img src="/uploads/cocktails/<?= htmlspecialchars($cocktail->getImage()) ?>"
                    alt="<?= htmlspecialchars($cocktail->getTitle()) ?>" class="mb-4 cocktailImage">
            <?php else: ?>
                <p>No image available for this cocktail.</p>
            <?php endif; ?>
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
                <p><a href="/login">Log in to like</a></p>
            <?php endif; ?>
            <!-- Display the like count -->
        </div>
        <h1 class="title"><?= htmlspecialchars($cocktail->getTitle() ?? 'Untitled') ?></h1>
        <p><strong>Difficulty:</strong> <?= htmlspecialchars($cocktail->getDifficultyName() ?? 'Not specified') ?></p>
        <p><?= htmlspecialchars($cocktail->getDescription() ?? 'No description available') ?></p>

        <h2 class="text-2xl font-semibold mt-6 mb-2">Ingredients</h2>
        <ul class="list-disc ml-5">
            <?php if (!empty($ingredients)): ?>
                <?php foreach ($ingredients as $ingredient): ?>
                    <li>
                        <?= htmlspecialchars($ingredient->getName()) ?>:
                        <?= htmlspecialchars($ingredient->getQuantity()) ?>
                        <?= htmlspecialchars($ingredient->getUnitName()) ?>
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
    <?php if (AuthController::isLoggedIn() && ($currentUser->canEditCocktail($cocktail->getUserId()) || AuthController::isAdmin())): ?>
        <button id="editCocktailButton" class="text-blue-500 hover:underline">Edit Cocktail</button>
        <!-- Delete Button -->
        <form action="/cocktails/delete/<?= $cocktail->getCocktailId() ?>" method="post"
            onsubmit="return confirm('Are you sure you want to delete this cocktail?');">
            <button type="submit" class="text-red-500 hover:underline">Delete Cocktail</button>
        </form>
    <?php endif; ?>


</div>

<!-- Comments Section -->
<div class="commentsSection">
    <h2 class="">Comments</h2>
    <?php foreach ($comments as $comment): ?>
        <div class="commentBox">
            <div class="comment">
                <div class="creatorInfo">
                    <?php if ($comment->getProfilePicture()): ?>
                        <img class="creatorPicture" src="<?= asset('/../uploads/users/' . htmlspecialchars($comment->getProfilePicture())); ?>"
                            alt="Profile picture of <?= htmlspecialchars($comment->getUsername()); ?>">
                    <?php else: ?>
                        <img src="<?= asset('/../uploads/users/user-default.svg'); ?>" alt="Default Profile Picture" class="creatorPicture">
                    <?php endif; ?>
                    <p><strong><?= htmlspecialchars($comment->getUsername() ?? 'Unknown User') ?>:</strong></p>
                    <!-- Dots Menu for Edit/Delete -->
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
                <!-- Inline edit form (initially hidden) -->
                <form id="editForm-<?= $comment->getCommentId() ?>" class="hidden" action="/comments/<?= $comment->getCommentId() ?>/update" method="POST">
                    <textarea name="commentText"><?= htmlspecialchars($comment->getCommentText()) ?></textarea>
                    <button type="submit">Save</button>
                    <button type="button" onclick="toggleEditForm(<?= $comment->getCommentId() ?>, false)">Cancel</button>
                </form>
                <p class="commentDate"><small><?= htmlspecialchars($comment->getCreatedAt() ?? 'Unknown date') ?></small></p>
                <p><?= htmlspecialchars($comment->getCommentText() ?? 'No comment text available') ?></p> <!-- Display comment text here -->

                <!-- Replies Section -->
                <?php if (!empty($comment->replies)): ?>
                    <div class="repliesSection" style="margin-left: 20px;">
                        <?php foreach ($comment->replies as $reply): ?>
                            <div class="reply">
                                <p><strong><?= htmlspecialchars($reply->getUsername() ?? 'Unknown User') ?>:</strong></p>
                                <p><?= htmlspecialchars($reply->getCommentText() ?? 'No reply text available') ?></p>
                                <p class="comment-date"><small>Posted on <?= htmlspecialchars($reply->getCreatedAt() ?? 'Unknown date') ?></small></p>

                                <?php if (isset($_SESSION['user']['id']) && ($_SESSION['user']['id'] === $reply->getUserId() || AuthController::isAdmin())): ?>
                                    <form action="/comments/<?= $reply->getCommentId() ?>/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this reply?');">
                                        <button type="submit" class="menuItem">🗑️</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Reply Form -->
                <?php if (AuthController::isLoggedIn()): ?>
                    <button class="replyButton" data-comment-id="<?= $comment->getCommentId() ?>">Reply</button>
                    <div id="replyForm-<?= $comment->getCommentId() ?>" class="replyForm hidden">
                        <form action="/comments/<?= $comment->getCommentId() ?>/reply" method="POST">
                            <textarea name="comment" placeholder="Write your reply here..." required></textarea>
                            <input type="hidden" name="parent_comment_id" value="<?= $comment->getCommentId() ?>">
                            <input type="hidden" name="cocktail_id" value="<?= $cocktailId ?>">
                            <input type="hidden" name="cocktailTitle" value="<?= htmlspecialchars($cocktail->getTitle()) ?>">
                            <button type="submit">Submit Reply</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    <?php endforeach; ?>
    <!-- Top-level Comment Form -->
    <?php if (AuthController::isLoggedIn() && $currentUser->canComment()): ?>
        <h3 class="commentHeading">Add a New Comment</h3>
        <form action="/cocktails/<?= $cocktail->getCocktailId() ?>-<?= urlencode($cocktail->getTitle()) ?>/comments" method="POST"> <textarea name="comment" placeholder="Write your comment here..." required></textarea>
            <input type="hidden" name="cocktailTitle" value="<?= htmlspecialchars($cocktail->getTitle()) ?>">
            <button type="submit">Submit</button>
        </form>
    <?php else: ?>
        <p class="loginPrompt">Please <a href="/login">log in</a> to add a comment.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>