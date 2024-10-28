<?php
class Like
{
    private $like_id;
    private $user_id;
    private $cocktail_id;
    private $created_at;

    public function __construct($user_id, $cocktail_id)
    {
        $this->user_id = $user_id;
        $this->cocktail_id = $cocktail_id;
    }

    // Getters
    public function getLikeId()
    {
        return $this->like_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getCocktailId()
    {
        return $this->cocktail_id;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
}