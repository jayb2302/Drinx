<?php
class LikeService
{
    private $likeRepository;

    public function __construct(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    public function userHasLikedCocktail($userId, $cocktailId)
    {
        return $this->likeRepository->userHasLikedCocktail($userId, $cocktailId);
    }

    public function addLike($userId, $cocktailId)
    {
        // Check if the user has already liked the cocktail
        if ($this->likeRepository->userHasLikedCocktail($userId, $cocktailId)) {
            return false; 
        }
    
        // Add the like to the database
        return $this->likeRepository->addLike($userId, $cocktailId);
    }
    
    public function removeLike($userId, $cocktailId)
    {
        return $this->likeRepository->removeLike($userId, $cocktailId);
    }
    public function getLikesForCocktail($cocktailId)
    {
        return $this->likeRepository->getLikesForCocktail($cocktailId);
    }
}
