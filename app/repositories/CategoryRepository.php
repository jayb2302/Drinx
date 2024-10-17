<?php
require_once __DIR__ . '/../config/database.php';

class CategoryRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllCategories() {
        $stmt = $this->db->query('SELECT * FROM categories');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryByCocktailId($cocktailId) {
        $stmt = $this->db->prepare('
            SELECT c.category_id, cat.name AS category_name 
            FROM cocktails c
            JOIN categories cat ON c.category_id = cat.category_id
            WHERE c.cocktail_id = :cocktail_id
        ');
        $stmt->bindParam(':cocktail_id', $cocktailId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}