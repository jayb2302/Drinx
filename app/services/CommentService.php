<?php
class CommentService
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    // Fetch a comment by its ID
    public function getCommentById($commentId)
    {
        return $this->commentRepository->getCommentById($commentId);
    }
    // Fetch all comments, including replies
    public function getCommentsWithReplies($cocktailId)
    {
        // Fetch top-level comments
        $comments = $this->commentRepository->getTopLevelCommentsByCocktailId($cocktailId);

        // For each top-level comment, fetch its replies and assign them
        foreach ($comments as $comment) {
            $replies = $this->commentRepository->getRepliesByCommentId($comment->getCommentId());
            $comment->replies = $replies; // Assign replies to the Comment object
        }

        return $comments;
    }

    // Add a comment
    public function addComment($userId, $cocktailId, $commentText, $parentCommentId = null) {
        return $this->commentRepository->addComment($userId, $cocktailId, $commentText, $parentCommentId);
    }
    // Delete a comment by its ID
    public function deleteComment($commentId)
    {
        return $this->commentRepository->deleteComment($commentId);
    }
}
