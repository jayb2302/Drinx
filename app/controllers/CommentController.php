<?php
require_once __DIR__ . 'BaseController.php';

class CommentController extends BaseController
{
    private $commentService;

    public function __construct(
        AuthService $authService,
        UserService $userService,
        CocktailService $cocktailService,
        CommentService $commentService
    ) {
        parent::__construct($authService, $userService, $cocktailService);
        $this->commentService = $commentService;
    }

    // Add a comment or reply
    public function addComment($cocktailId)
    {
        $this->prepareJsonResponse();

        if (!$this->validateCsrfToken()) {
            $this->respondWithError('Invalid CSRF token.', 403);
        }

        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            $this->respondWithError('You must be logged in to comment.', 401);
        }

        $commentText = sanitize($_POST['commentText'] ?? '');
        $parentCommentId = isset($_POST['parent_comment_id']) ? sanitize($_POST['parent_comment_id']) : null;

        if (empty($commentText)) {
            $this->respondWithError('Comment cannot be empty.', 400);
        }

        try {
            $this->commentService->addComment($userId, $cocktailId, $commentText, $parentCommentId);
            $this->renderCommentsSection($cocktailId);
        } catch (Exception $e) {
            // error_log($e->getMessage());
            $this->respondWithError('An unexpected error occurred.', 500);
        }
    }

    // Edit comment
    public function edit($commentId)
    {
        $this->prepareJsonResponse();

        if (!$this->validateCsrfToken()) {
            $this->respondWithError('Invalid CSRF token.', 403);
        }

        $comment = $this->commentService->getCommentById($commentId);
        if (!$comment) {
            $this->respondWithError('Comment not found.', 404);
        }

        if ($_SESSION['user']['id'] !== $comment->getUserId() && !$this->authService->isAdmin()) {
            $this->respondWithError('You are not authorized to edit this comment.', 403);
        }

        $newCommentText = sanitize($_POST['commentText'] ?? '');
        if (empty($newCommentText)) {
            $this->respondWithError('Comment text cannot be empty.', 400);
        }

        try {
            $this->commentService->updateComment($commentId, $newCommentText);
            $this->renderCommentsSection($comment->getCocktailId());
        } catch (Exception $e) {
            // error_log($e->getMessage());
            $this->respondWithError('An unexpected error occurred.', 500);
        }
    }

    // Delete comment
    public function delete($commentId)
    {
        // error_log("Delete method called with comment ID: $commentId"); // Add this log
        $this->prepareJsonResponse();
    
        if (!$this->validateCsrfToken()) {
            // error_log("CSRF token validation failed.");
            $this->respondWithError('Invalid CSRF token.', 403);
        }
    
        $comment = $this->commentService->getCommentById($commentId);
        if (!$comment) {
            // error_log("Comment not found for ID: $commentId");
            $this->respondWithError('Comment not found.', 404);
        }
    
        if ($_SESSION['user']['id'] !== $comment->getUserId() && !$this->authService->isAdmin()) {
            // error_log("Authorization failed for user ID: {$_SESSION['user']['id']}");
            $this->respondWithError('You are not authorized to delete this comment.', 403);
        }
    
        try {
            $cocktailId = $comment->getCocktailId();
            // error_log("Attempting to delete comment ID: $commentId for cocktail ID: $cocktailId");
            $this->commentService->deleteComment($commentId);
    
            // error_log("Comment ID $commentId deleted successfully.");
            $this->renderCommentsSection($cocktailId);
        } catch (Exception $e) {
            // error_log("Error deleting comment ID $commentId: " . $e->getMessage());
            $this->respondWithError('An unexpected error occurred.', 500);
        }
    }
    
    

    // Add a reply to a comment
    public function reply($commentId)
    {
        $this->prepareJsonResponse();

        if (!$this->validateCsrfToken()) {
            $this->respondWithError('Invalid CSRF token.', 403);
        }

        $userId = $_SESSION['user']['id'] ?? null;
        $commentText = sanitize($_POST['comment'] ?? '');
        $cocktailId = sanitize($_POST['cocktail_id'] ?? '');

        if (!$userId || !$commentText || !$cocktailId) {
            $this->respondWithError('Invalid input.', 400);
        }

        try {
            $this->commentService->addComment($userId, $cocktailId, $commentText, $commentId);
            $this->renderCommentsSection($cocktailId);
        } catch (Exception $e) {
            // error_log($e->getMessage());
            $this->respondWithError('An unexpected error occurred.', 500);
        }
    }

    // Helper methods
    private function prepareJsonResponse()
    {
        ob_clean(); // Clear any output buffer
        header('Content-Type: application/json');
    }

    private function validateCsrfToken()
    {
        $csrfToken = $_POST['csrf_token'] ?? '';
        return validateCsrfToken($csrfToken);
    }

    private function respondWithError($message, $statusCode)
    {
        http_response_code($statusCode);
        echo json_encode(['error' => $message]);
        exit();
    }

    private function renderCommentsSection($cocktailId)
    {
        $cocktail = $this->cocktailService->getCocktailById($cocktailId);
        $comments = $this->commentService->getCommentsWithReplies($cocktailId);
        $currentUser = $this->authService->getCurrentUser();
        $authController = new AuthController($this->authService, $this->userService);

        ob_start();
        require __DIR__ . '/../views/cocktails/comment_section.php';
        $commentsHtml = ob_get_clean();

        echo json_encode([
            'success' => true,
            'html' => $commentsHtml,
            'new_csrf' => generateCsrfToken(),
        ]);
        exit();
    }
}
