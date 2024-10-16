<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cocktail.php'; 

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
        
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new Cocktail(
                $result['cocktail_id'],
                $result['title'],
                $result['description'],
                $result['image'],
                $result['category_id'],
                $result['created_at'],
                $result['updated_at']
            );
        }
        
        return null; // Return null if no cocktail found
    }

    public function getAll() {
        $sql = "SELECT * FROM cocktails";
        $stmt = $this->db->query($sql);
        $cocktails = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(function($cocktailData) {
            return new Cocktail(
                $cocktailData['cocktail_id'],
                $cocktailData['title'],
                $cocktailData['description'],
                $cocktailData['image'],
                $cocktailData['category_id'],
                $cocktailData['created_at'],
                $cocktailData['updated_at']
            );
        }, $cocktails);
    }
  
    public function getByName($name) {
        $stmt = $this->db->prepare('SELECT * FROM cocktails WHERE title = :name');
        $stmt->bindParam(':name', $name);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return new Cocktail(
                $result['cocktail_id'],
                $result['title'],
                $result['description'],
                $result['image'],
                $result['category_id'],
                $result['created_at'],
                $result['updated_at']
            );
        }
        
        return null; // Return null if no cocktail found
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

    public function addStep($cocktail_id, $instruction) {
        $stmt = $this->db->prepare('INSERT INTO cocktail_steps (cocktail_id, instruction) VALUES (:cocktail_id, :instruction)');
        $stmt->bindParam(':cocktail_id', $cocktail_id);
        $stmt->bindParam(':instruction', $instruction);
        return $stmt->execute();
    }

    public function addIngredient($cocktail_id, $ingredient_id, $quantity, $unit_id) {
        $stmt = $this->db->prepare('INSERT INTO cocktail_ingredients (cocktail_id, ingredient_id, quantity, unit_id) VALUES (:cocktail_id, :ingredient_id, :quantity, :unit_id)');
        $stmt->bindParam(':cocktail_id', $cocktail_id);
        $stmt->bindParam(':ingredient_id', $ingredient_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':unit_id', $unit_id);
        return $stmt->execute();
    }

    public function deleteStep($step_id) {
        $stmt = $this->db->prepare('DELETE FROM cocktail_steps WHERE step_id = :id');
        $stmt->bindParam(':id', $step_id);
        return $stmt->execute();
    }

    public function getCocktailIngredients($cocktailId) {
        $stmt = $this->db->prepare('
            SELECT 
                ci.quantity, 
                i.name AS ingredient_name, 
                u.unit_name AS unit_name 
            FROM 
                cocktail_ingredients ci
            JOIN 
                ingredients i ON ci.ingredient_id = i.ingredient_id
            JOIN 
                ingredient_unit u ON ci.unit_id = u.unit_id
            WHERE 
                ci.cocktail_id = :cocktail_id
        ');
        $stmt->bindParam(':cocktail_id', $cocktailId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCocktailSteps($cocktailId) {
        $stmt = $this->db->prepare('SELECT * FROM cocktail_steps WHERE cocktail_id = :cocktail_id');
        $stmt->bindParam(':cocktail_id', $cocktailId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCategories() {
        $stmt = $this->db->query('SELECT * FROM categories'); // Fetch all categories
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

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM cocktails WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);  // Return as an array of objects
    }

}
?>