<?php
require_once __DIR__ . '/../config/database.php';

class CocktailRepository {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getById($cocktail_id) {
        $stmt = $this->db->prepare('SELECT * FROM cocktails WHERE cocktail_id = :id');
        $stmt->bindParam(':id', $cocktail_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT * FROM cocktails";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($cocktailData) {
        $stmt = $this->db->prepare('INSERT INTO cocktails (title, description, image, category_id, created_at) VALUES (:title, :description, :image, :category_id, NOW())');
        $stmt->bindParam(':title', $cocktailData['title']);
        $stmt->bindParam(':description', $cocktailData['description']);
        $stmt->bindParam(':image', $cocktailData['image']);
        $stmt->bindParam(':category_id', $cocktailData['category_id']);
        return $stmt->execute();
    }

    public function update($cocktail_id, $cocktailData) {
        $stmt = $this->db->prepare('UPDATE cocktails SET title = :title, description = :description, image = :image, category_id = :category_id, updated_at = NOW() WHERE cocktail_id = :id');
        $stmt->bindParam(':id', $cocktail_id);
        $stmt->bindParam(':title', $cocktailData['title']);
        $stmt->bindParam(':description', $cocktailData['description']);
        $stmt->bindParam(':image', $cocktailData['image']);
        $stmt->bindParam(':category_id', $cocktailData['category_id']);
        return $stmt->execute();
    }

    public function delete($cocktail_id) {
        $stmt = $this->db->prepare('DELETE FROM cocktails WHERE cocktail_id = :id');
        $stmt->bindParam(':id', $cocktail_id);
        return $stmt->execute();
    }
}
?>