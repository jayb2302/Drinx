<?php
require_once __DIR__ . '/../config/database.php';

class CategoryRepository {
    private $db;

    public function __construct($db) 
    {
        $this->db = $db;
    }

    public function getAllCategories() {
        $stmt = $this->db->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    

    public function getCategoryByCocktailId($cocktailId)
    {
        $stmt = $this->db->prepare("
            SELECT cat.* 
            FROM categories cat
            JOIN cocktails c ON c.category_id = cat.category_id
            WHERE c.cocktail_id = :cocktail_id
        ");
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getCategoryIdByName($categoryName)
    {
        $stmt = $this->db->prepare("SELECT category_id FROM categories WHERE LOWER(name) = LOWER(:name)");
        $stmt->execute(['name' => $categoryName]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['category_id'] : null;
    }

}