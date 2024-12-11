<?php
require_once __DIR__ . '/../models/Like.php';
class LikeRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function userHasLikedCocktail($userId, $cocktailId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE user_id = :user_id AND cocktail_id = :cocktail_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':cocktail_id', $cocktailId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0; 
    }

    public function addLike($userId, $cocktailId)
    {
        $stmt = $this->db->prepare("INSERT INTO likes (user_id, cocktail_id) VALUES (:user_id, :cocktail_id)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':cocktail_id', $cocktailId);
        return $stmt->execute();
    }

    public function removeLike($userId, $cocktailId)
    {
        $stmt = $this->db->prepare("DELETE FROM likes WHERE user_id = :user_id AND cocktail_id = :cocktail_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':cocktail_id', $cocktailId);
        return $stmt->execute();
    }

    public function getLikesForCocktail($cocktailId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE cocktail_id = :cocktail_id");
        $stmt->bindParam(':cocktail_id', $cocktailId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
