<?php
require_once __DIR__ . '/../config/database.php'; // Ensure you include your database connection

class TagRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Get all tags
    public function getAllTags() {
        $stmt = $this->db->prepare("SELECT * FROM tags");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get tags associated with a cocktail
    public function getTagsByCocktailId($cocktailId) {
        $stmt = $this->db->prepare("
            SELECT t.tag_id, t.name 
            FROM cocktail_tags ct
            JOIN tags t ON ct.tag_id = t.tag_id
            WHERE ct.cocktail_id = :cocktail_id
        ");
        $stmt->bindParam(':cocktail_id', $cocktailId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a tag to a cocktail
    public function addTagToCocktail($cocktailId, $tagId) {
        $stmt = $this->db->prepare("
            INSERT INTO cocktail_tags (cocktail_id, tag_id) 
            VALUES (:cocktail_id, :tag_id)
        ");
        $stmt->bindParam(':cocktail_id', $cocktailId);
        $stmt->bindParam(':tag_id', $tagId);
        return $stmt->execute();
    }

    // Remove a tag from a cocktail
    public function removeTagFromCocktail($cocktailId, $tagId) {
        $stmt = $this->db->prepare("
            DELETE FROM cocktail_tags 
            WHERE cocktail_id = :cocktail_id AND tag_id = :tag_id
        ");
        $stmt->bindParam(':cocktail_id', $cocktailId);
        $stmt->bindParam(':tag_id', $tagId);
        return $stmt->execute();
    }
}
?>