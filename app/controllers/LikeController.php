<?php
class LikeController
{
    private $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }
    public function toggleLike($cocktailId)
    {
        error_log("Request to toggle like for cocktailId = $cocktailId");
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            error_log("User not logged in");
            echo json_encode(['success' => false, 'error' => 'You must be logged in to like or unlike a cocktail.']);
            exit();
        }
    
        // Check if the user has already liked the cocktail
        if ($this->likeService->userHasLikedCocktail($userId, $cocktailId)) {
            // User has liked the cocktail, so we will unlike it
            error_log("User wants to unlike cocktailId: $cocktailId");
            if ($this->likeService->removeLike($userId, $cocktailId)) {
                echo json_encode(['success' => true, 'action' => 'unlike', 'message' => 'Successfully unliked the cocktail.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Could not unlike this cocktail.']);
            }
        } else {
            // User has not liked the cocktail, so we will like it
            error_log("User wants to like cocktailId: $cocktailId");
            if ($this->likeService->addLike($userId, $cocktailId)) {
                echo json_encode(['success' => true, 'action' => 'like', 'message' => 'Successfully liked the cocktail.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'You have already liked this cocktail.']);
            }
        }
        exit();
    }
}