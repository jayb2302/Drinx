<?php
class CommentService
{
    private $commentRepository;
    private $userService;

    public function __construct(CommentRepository $commentRepository, UserService $userService)
    {
        $this->commentRepository = $commentRepository;
        $this->userService = $userService;
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
            $userProfile = $this->userService->getUserWithProfile($comment->getUserId());
            $comment->setProfilePicture($userProfile ? $userProfile->getProfilePicture() : 'user-default.svg');
    
            $replies = $this->commentRepository->getRepliesByCommentId($comment->getCommentId());
            foreach ($replies as $reply) {
                $replyUserProfile = $this->userService->getUserWithProfile($reply->getUserId());
                $reply->setProfilePicture($replyUserProfile ? $replyUserProfile->getProfilePicture() : 'user-default.svg');
            }
            $comment->replies = $replies;
        }
        return $comments;
    }

    // Add a comment
    public function addComment($userId, $cocktailId, $commentText, $parentCommentId = null)
    {
        return $this->commentRepository->addComment($userId, $cocktailId, $commentText, $parentCommentId);
    }

    public function updateComment($commentId, $newCommentText)
    {
        if (empty($newCommentText)) {
            throw new InvalidArgumentException("Comment cannot be empty.");
        }
        return $this->commentRepository->updateComment($commentId, $newCommentText);
    }
    // Delete a comment by its ID
    public function deleteComment($commentId)
    {
        return $this->commentRepository->deleteComment($commentId);
    }
}
