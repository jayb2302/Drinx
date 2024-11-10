<?php
class CommentController
{
    private $commentService;
    private $commentRepository;
    private $cocktailService;

    public function __construct(CommentService $commentService, CommentRepository $commentRepository, CocktailService $cocktailService)
    {
        $this->commentService = $commentService;
        $this->commentRepository = $commentRepository;
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
        $commentText = sanitize($_POST['comment'] ?? '');
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
                $this->commentRepository->addComment($userId, $cocktailId, $commentText, $parentCommentId);

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
