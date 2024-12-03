<?php
class CommentController
{
    private $commentService;
    private $cocktailService;

    public function __construct(
        CommentService $commentService, 
        CocktailService $cocktailService
    ) {
        $this->commentService = $commentService;
        $this->cocktailService = $cocktailService;
    }

    // Add a comment or reply
    public function addComment($cocktailId)
    {
        // Ensure user is logged in
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'You must be logged in to comment.']);
            exit();
        }

        // Get comment data from POST
        $commentText = sanitize($_POST['commentText'] ?? '');
        $parentCommentId = isset($_POST['parent_comment_id']) ? sanitize($_POST['parent_comment_id']) : null;

        if (empty($commentText)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Comment cannot be empty.']);
            exit();
        }

        try {
            // Add the comment
            $this->commentService->addComment($userId, $cocktailId, $commentText, $parentCommentId);

            // Fetch updated cocktail and comments
            $cocktail = $this->cocktailService->getCocktailById($cocktailId);
            $comments = $this->commentService->getCommentsWithReplies($cocktailId);
            $currentUser = AuthController::getCurrentUser(); // Ensure current user is available

            // Render the entire comment section
            ob_start();
            require __DIR__ . '/../views/cocktails/comment_section.php';
            $commentsHtml = ob_get_clean();

            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'html' => $commentsHtml]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'An unexpected error occurred.']);

        }
        exit();
    }

    // Edit comment
    public function edit($commentId)
    {
        error_log("Editing comment with ID: $commentId");
        header('Content-Type: application/json');
        $comment = $this->commentService->getCommentById($commentId);

        if (!$comment) {
            error_log("Comment with ID $commentId not found.");
            http_response_code(404);
            echo json_encode(['error' => 'Comment not found.']);
            exit();
        }
        // Ensure the user owns the comment or is an admin
        if ($_SESSION['user']['id'] !== $comment->getUserId() && !AuthController::isAdmin()) {
            error_log("User not authorized to edit comment ID: $commentId");
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'You are not authorized to edit this comment.']);
            exit();
        }
        try {
            $newCommentText = sanitize($_POST['commentText'] ?? '');
            if (empty($newCommentText)) {
                error_log("Comment text is empty for comment ID: $commentId");
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Comment text cannot be empty.']);
                exit();
            }
            // Update the comment
            error_log("Updating comment ID: $commentId with new text: $newCommentText");
            $this->commentService->updateComment($commentId, $newCommentText);

            // Fetch updated comments for the entire section
            $cocktailId = $comment->getCocktailId();
            $updatedComments = $this->commentService->getCommentsWithReplies($cocktailId);

            // Return the updated comments section HTML
            ob_start();
            $cocktail = $this->cocktailService->getCocktailById($cocktailId); // Assuming you have a method to get cocktail info
            $currentUser = AuthController::getCurrentUser();
            $comments = $updatedComments;
            require __DIR__ . '/../views/cocktails/comment_section.php';
            $updatedCommentsHtml = ob_get_clean();

            error_log("Successfully edited comment ID: $commentId");
            echo json_encode(['success' => true, 'html' => $updatedCommentsHtml]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'An unexpected error occurred.']);
        }
        exit();
    }
    public function delete($commentId)
    {
        $comment = $this->commentService->getCommentById($commentId);
        if ($_SESSION['user']['id'] !== $comment->getUserId() && !AuthController::isAdmin()) {
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'You are not authorized to delete this comment.']);
            exit();
        }
        try {
            // Get cocktail ID before deletion
            $cocktailId = $comment->getCocktailId();

            // Delete the comment
            $this->commentService->deleteComment($commentId);

            // Fetch updated comments and render the section
            $cocktail = $this->cocktailService->getCocktailById($cocktailId);
            $comments = $this->commentService->getCommentsWithReplies($cocktailId);
            $currentUser = AuthController::getCurrentUser();
            ob_start();
            require __DIR__ . '/../views/cocktails/comment_section.php';
            $commentsHtml = ob_get_clean();

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'html' => $commentsHtml]); // Send full HTML
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'An unexpected error occurred.']);
        }
        exit();
    }
    public function reply($commentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'] ?? null;
            $commentText = sanitize($_POST['comment'] ?? '');
            $cocktailId = sanitize($_POST['cocktail_id'] ?? null);
            if (!$userId || !$commentText || !$cocktailId) {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Invalid input.']);
                exit();
            }
            try {
                // Add reply to the database
                $this->commentService->addComment($userId, $cocktailId, $commentText, $commentId);

                // Fetch updated cocktail and comments
                $cocktail = $this->cocktailService->getCocktailById($cocktailId);
                $comments = $this->commentService->getCommentsWithReplies($cocktailId);
                $currentUser = AuthController::getCurrentUser();
                // Render the entire comments section
                ob_start();
                require __DIR__ . '/../views/cocktails/comment_section.php';
                $commentsHtml = ob_get_clean();

                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'html' => $commentsHtml]);
            } catch (Exception $e) {
                error_log($e->getMessage());
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'An unexpected error occurred.']);
            }
            exit();
        }
    }
}