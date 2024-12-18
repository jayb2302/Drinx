<div class="commentsSection">
    <!-- Top-level Comment Form -->
    <h2 class=""> <i class="fa-brands fa-slideshare"></i> Bar Talk</h2>
    <?php if ($authController->isLoggedIn() && $currentUser->canComment()): ?>
        <div class="top-level-comment">
            <form id="TopLevelCommentForm"
                action="/cocktails/<?= $cocktail->getCocktailId() ?>-<?= urlencode($cocktail->getTitle()) ?>/comments"
                method="POST">
                <textarea
                    id="commentText"
                    name="commentText"
                    placeholder="Write your comment here..."
                    rows="4"
                    required
                    ></textarea>
                <input type="hidden" name="cocktailTitle" value="<?= htmlspecialchars($cocktail->getTitle()) ?>">
                <button type="submit">Submit</button>
            </form>
        </div>
    <?php else: ?>
        <p class="loginPrompt">Please <a href="/login">log in</a> to add a comment.</p>
    <?php endif; ?>
    <?php foreach ($comments as $comment): ?>
        <div class="commentBox">
            <div class="comment">
                <div class="creatorInfo">
                    <?php if ($comment->getProfilePicture()): ?>
                        <img class="creatorPicture"
                            src="<?= asset('/../uploads/users/' . htmlspecialchars($comment->getProfilePicture())); ?>"
                            alt="Profile picture of <?= htmlspecialchars($comment->getUsername()); ?>">
                    <?php else: ?>
                        <img src="<?= asset('/../uploads/users/user-default.svg'); ?>" alt="Default Profile Picture"
                            class="creatorPicture">
                    <?php endif; ?>

                    <span>
                        <strong><?= htmlspecialchars($comment->getUsername() ?? 'Unknown User') ?>:</strong> <br>
                        <small><?= htmlspecialchars(formatDate($comment->getCreatedAt())) ?></small>
                    </span>
                    <!-- Dots Menu for Edit/Delete -->
                    <?php if ($authController->isLoggedIn() && ($currentUser->canEditComment($comment->getUserId()) || $authController->isAdmin())): ?>
                        <div class="dotsMenu">
                            <button class="dotsButton">⋮</button>
                            <div class="menu hidden">
                                <button type="button" class="menuItem editCommentButton"
                                    data-comment-id="<?= $comment->getCommentId() ?>"> <i class="fa-solid fa-pencil"></i> </button>
                                <form action="/comments/<?= $comment->getCommentId() ?>/delete" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()) ?>">
                                    <button type="submit" class="delete"> <i class="fa-solid fa-trash"></i> </button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                    <?php endif; ?>
                </div>
                <!-- Inline edit form (initially hidden) -->
                <form id="editForm-<?= $comment->getCommentId() ?>" class="editCommentForm hidden"
                    action="/comments/<?= $comment->getCommentId() ?>/edit" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()) ?>">
                    <textarea name="commentText"><?= htmlspecialchars($comment->getCommentText()) ?></textarea>
                    <button type="submit">Save</button>
                    <button type="button" class="cancelEditButton">Cancel</button>
                </form>
                
                <p><?= htmlspecialchars($comment->getCommentText() ?? 'No comment text available') ?></p>
                <!-- Display comment text here -->

                <!-- Replies Section -->
                <?php if (!empty($comment->replies)): ?>
                    <div class="repliesSection" style="margin-left: 20px;">
                        <?php foreach ($comment->replies as $reply): ?>
                            <div class="reply">
                                <p><strong><?= htmlspecialchars($reply->getUsername() ?? 'Unknown User') ?>:</strong></p>
                                <p><?= htmlspecialchars($reply->getCommentText() ?? 'No reply text available') ?></p>
                                <p class="comment-date">
                                    <small> <?= htmlspecialchars(formatDate($reply->getCreatedAt())) ?></small>
                                </p>

                                <?php if (isset($_SESSION['user']['id']) && ($_SESSION['user']['id'] === $reply->getUserId() || $authController->isAdmin())): ?>
                                    <form action="/comments/<?= $reply->getCommentId() ?>/delete" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this reply?');">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()) ?>">
                                        <button type="submit" class="delete"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Reply Form -->
                <?php if ($authController->isLoggedIn()): ?>
                    <button class="replyButton" data-comment-id="<?= $comment->getCommentId() ?>">Reply</button>
                    <div id="replyForm-<?= $comment->getCommentId() ?>" class="replyForm hidden">
                        <form class="replyCommentForm" action="/comments/<?= $comment->getCommentId() ?>/reply" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()) ?>">
                            <textarea name="comment" placeholder="Write your reply here..." required></textarea>
                            <input type="hidden" name="parent_comment_id" value="<?= $comment->getCommentId() ?>">
                            <input type="hidden" name="cocktail_id" value="<?= $cocktailId ?>">
                            <input type="hidden" name="cocktailTitle" value="<?= htmlspecialchars($cocktail->getTitle()) ?>">
                            <button type="submit" class="secondary">Submit</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    <?php endforeach; ?>
</div>