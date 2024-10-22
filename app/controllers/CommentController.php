<?php
class CommentController
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    // Add a comment or reply
    public function addComment($cocktailId)
    {
        error_log("addComment method called for cocktail ID: " . $cocktailId); // Add logging

        // Ensure user is logged in
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            $_SESSION['error'] = 'You must be logged in to comment.';
            header("Location: /login");
            exit();
        }

        // Get comment data from POST
        $commentText = $_POST['comment'] ?? '';
        $parentCommentId = $_POST['parent_comment_id'] ?? null;

        if (empty($commentText)) {
            $_SESSION['error'] = 'Comment cannot be empty.';
            header("Location: /cocktails/{$cocktailId}");
            exit();
        }

        // Make sure parent_comment_id is null for top-level comments
        if (empty($parentCommentId)) {
            $parentCommentId = null; // Treat top-level comments as having no parent
        }
        // Add the comment using service
        $this->commentService->addComment($userId, $cocktailId, $commentText, $parentCommentId);

        // Redirect back to the cocktail view
        $cocktailTitle = urlencode($_POST['cocktailTitle']);
        header("Location: /cocktails/{$cocktailId}-{$cocktailTitle}");
        exit();
    }

    // Edit comment
    public function edit($commentId)
    {
        $comment = $this->commentService->getCommentById($commentId);

        // Ensure the user owns the comment or is an admin
        if ($_SESSION['user']['id'] !== $comment->getUserId() && !AuthController::isAdmin()) {
            header("Location: /cocktails");
            exit();
        }

        // Display edit form (you can load a view here)
    }
    public function delete($commentId)
    {
        $comment = $this->commentService->getCommentById($commentId);

        // Ensure the user owns the comment or is an admin
        if ($_SESSION['user']['id'] !== $comment->getUserId() && !AuthController::isAdmin()) {
            header("Location: /cocktails");
            exit();
        }

        // Delete the comment
        $this->commentService->deleteComment($commentId);

        // Redirect back to the cocktail page
        header("Location: /cocktails");
        exit();
    }
}
