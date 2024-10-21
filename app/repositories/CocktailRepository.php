<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cocktail.php'; 
require_once __DIR__ . '/../models/Ingredient.php';
require_once __DIR__ . '/../models/Step.php';

class CocktailRepository {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Fetch cocktail by ID
    public function getById($cocktail_id)
    {
        $stmt = $this->db->prepare('SELECT * FROM cocktails WHERE cocktail_id = :id');
        $stmt->bindParam(':id', $cocktail_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch the cocktail data
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Fetch ingredients, steps, and tags
            $ingredients = $this->getIngredientsByCocktailId($cocktail_id); // Ensure this returns an array
            $steps = $this->getStepsByCocktailId($cocktail_id); // Ensure this returns an array
            // $tags = $this->getTagsByCocktailId($cocktail_id); // Ensure this returns an array
    
            // Create a Cocktail object with the fetched data
            return new Cocktail(
                $result['cocktail_id'],
                $result['title'],
                $result['description'],
                $result['image'],
                $result['category_id'],
                $result['user_id'],
                $ingredients, // Pass ingredients array
                $steps, // Pass steps array
                // $tags // Pass tags array
            );
        }
    
        return null; // Return null if no cocktail found
    }

    // Fetch all cocktails
    public function getAll() {
        $query = $this->db->query("SELECT * FROM cocktails");
        $cocktailsData = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return array_map(function($cocktailData) {
            // Fetch ingredients and steps related to the cocktail
            $ingredients = $this->getIngredientsByCocktailId($cocktailData['cocktail_id']); // Returns an array of Ingredient objects
            $steps = $this->getStepsByCocktailId($cocktailData['cocktail_id']); // Returns an array of Step objects
            $tags = []; // You can populate tags similarly
    
            // Create and return a Cocktail object
            return new Cocktail(
                $cocktailData['cocktail_id'],
                $cocktailData['title'],
                $cocktailData['description'],
                $cocktailData['image'],
                $cocktailData['category_id'],
                $cocktailData['user_id'],
                $ingredients, // Pass ingredients array
                $steps, // Pass steps array
                $tags // Pass tags array
            );
        }, $cocktailsData);
    }
    
    private function getIngredientsByCocktailId($cocktailId) {
        // Query the cocktail_ingredients table
        $stmt = $this->db->prepare('SELECT * FROM cocktail_ingredients WHERE cocktail_id = :cocktail_id');
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Ingredient');
    }

    private function getStepsByCocktailId($cocktailId) {
        // Query the cocktail_steps table
        $stmt = $this->db->prepare('SELECT * FROM cocktail_steps WHERE cocktail_id = :cocktail_id');
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Step');
    }

    // Fetch cocktail by name
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
                $result['user_id'],
                $result['created_at'],
                $result['updated_at']
            );
        }
        
        return null; // Return null if no cocktail found
    }

    // Create a new cocktail
    public function create($cocktailData) {
        $stmt = $this->db->prepare("
            INSERT INTO cocktails (user_id, title, description, image, category_id, difficulty_id, created_at) 
            VALUES (:user_id, :title, :description, :image, :category_id, :difficulty_id, NOW())
        ");
        
        $stmt->bindParam(':user_id', $cocktailData['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $cocktailData['title']);
        $stmt->bindParam(':description', $cocktailData['description']);
        $stmt->bindParam(':image', $cocktailData['image']);
        $stmt->bindParam(':category_id', $cocktailData['category_id'], PDO::PARAM_INT);
        $stmt->bindParam(':difficulty_id', $cocktailData['difficulty_id'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Update an existing cocktail
    public function update($cocktail_id, $cocktailData) {
        $stmt = $this->db->prepare('UPDATE cocktails SET title = :title, description = :description, image = :image, category_id = :category_id, updated_at = NOW() WHERE cocktail_id = :id');
        $stmt->bindParam(':id', $cocktail_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $cocktailData['title']);
        $stmt->bindParam(':description', $cocktailData['description']);
        $stmt->bindParam(':image', $cocktailData['image']);
        $stmt->bindParam(':category_id', $cocktailData['category_id']);
        
        if ($stmt->execute()) {
            return true; // Successfully updated
        }
        return false; // Update failed
    }
    

    // Delete a cocktail
    public function delete($cocktail_id) {
        $stmt = $this->db->prepare('DELETE FROM cocktails WHERE cocktail_id = :id');
        $stmt->bindParam(':id', $cocktail_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Fetch all categories
    public function getCategories() {
        $stmt = $this->db->query('SELECT * FROM categories');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch category by cocktail ID
    public function getCategoryByCocktailId($cocktailId) {
        $stmt = $this->db->prepare('
            SELECT c.category_id, cat.name AS category_name 
            FROM cocktails c
            JOIN categories cat ON c.category_id = cat.category_id
            WHERE c.cocktail_id = :cocktail_id
        ');
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch cocktails by user ID
    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM cocktails WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);  // Return as an array of objects
    }
}