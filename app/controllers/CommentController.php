<?php
class CommentController
{
    private $commentService;
    private $cocktailService;

    public function __construct(CommentService $commentService, CocktailService $cocktailService)
    {
        $this->commentService = $commentService;
        $this->cocktailService = $cocktailService; // Initialize cocktailService
    }


    // Add a comment or reply
    public function addComment($cocktailId)
    {
        // Ensure user is logged in
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            $_SESSION['error'] = 'You must be logged in to comment.';
            header("Location: /login");
            exit();
        }

        // Get comment data from POST
        $commentText = sanitize($_POST['commentText'] ?? '');
        $parentCommentId = isset($_POST['parent_comment_id']) ? sanitize($_POST['parent_comment_id']) : null;

        if (empty($commentText)) {
            $_SESSION['error'] = 'Comment cannot be empty.';
            header("Location: /cocktails/{$cocktailId}");
            exit();
        }

        // Ensure parent_comment_id is null for top-level comments
        $parentCommentId = empty($parentCommentId) ? null : $parentCommentId;

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
    
        if ($_SESSION['user']['id'] !== $comment->getUserId() && !AuthController::isAdmin()) {
            header("HTTP/1.1 403 Forbidden");
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit();
        }
    
        // Update comment with AJAX data
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newCommentText = $_POST['comment'] ?? '';
            if ($this->commentService->updateComment($commentId, $newCommentText)) {
                echo json_encode(['success' => true, 'comment' => $newCommentText]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update comment']);
            }
            exit();
        }
    }

    public function update($commentId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newCommentText = $_POST['comment'] ?? '';
            
            // Perform the update
            $updated = $this->commentService->updateComment($commentId, $newCommentText);

            // Redirect or send response based on success
            if ($updated) {
                header('Location: /cocktails');
            } else {
                echo json_encode(['error' => 'Failed to update comment']);
            }
        } else {
            header("HTTP/1.1 405 Method Not Allowed");
        }
    }
    public function delete($commentId)
    {
        $comment = $this->commentService->getCommentById($commentId);

        if ($_SESSION['user']['id'] !== $comment->getUserId() && !AuthController::isAdmin()) {
            header("Location: /cocktails");
            exit();
        }

        // Get cocktail ID and title for redirect
        $cocktailId = $comment->getCocktailId();
        $cocktail = $this->cocktailService->getCocktailById($cocktailId); // Retrieve cocktail using cocktailService
        $cocktailTitle = $cocktail ? urlencode($cocktail->getTitle()) : 'Unknown';

        // Delete the comment
        $this->commentService->deleteComment($commentId);

        // Redirect back to the cocktail page
        header("Location: /cocktails/{$cocktailId}-{$cocktailTitle}");
        exit();
    }

    public function reply($commentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'] ?? null;
            $commentText = $_POST['comment'] ?? '';
            $cocktailId = $_POST['cocktail_id'] ?? null; // Retrieve cocktail_id from the form

            // Validate inputs
            if ($userId && $commentText && $cocktailId) {
                $parentCommentId = $commentId;

                // Add reply to the database
                $this->commentService->addComment($userId, $cocktailId, $commentText, $parentCommentId);

                // Redirect back to the cocktail page with title
                $cocktailTitle = urlencode($_POST['cocktailTitle'] ?? '');
                header("Location: /cocktails/{$cocktailId}-{$cocktailTitle}");
                exit;
            } else {
                // Handle invalid input
                $_SESSION['errors'] = ["Failed to post reply. Please try again."];
                header("Location: /cocktails/{$cocktailId}");
                exit;
            }
        }
    }
}
