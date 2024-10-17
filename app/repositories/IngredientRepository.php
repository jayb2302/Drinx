<?php
class IngredientRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getIngredientsByCocktailId($cocktailId) {
        $stmt = $this->db->prepare('
            SELECT 
                ci.quantity, 
                i.name AS ingredient_name, 
                u.unit_name AS unit_name,
                ci.unit_id AS unit_id  -- Include the unit_id
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

    public function addIngredient($cocktail_id, $ingredient_id, $quantity, $unit_id) {
        $stmt = $this->db->prepare('INSERT INTO cocktail_ingredients (cocktail_id, ingredient_id, quantity, unit_id) VALUES (:cocktail_id, :ingredient_id, :quantity, :unit_id)');
        $stmt->bindParam(':cocktail_id', $cocktail_id);
        $stmt->bindParam(':ingredient_id', $ingredient_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':unit_id', $unit_id);
        return $stmt->execute();
    }

    // Add this method to get all units
    public function getAllUnits() {
        $stmt = $this->db->prepare("SELECT unit_id, unit_name FROM ingredient_unit");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>