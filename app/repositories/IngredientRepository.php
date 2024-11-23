<?php
class IngredientRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getIngredientsByCocktailId($cocktailId)
    {
        $stmt = $this->db->prepare('
            SELECT i.ingredient_id, i.name, ci.cocktail_id, ci.quantity, ci.unit_id, u.unit_name
            FROM cocktail_ingredients ci
            JOIN ingredients i ON ci.ingredient_id = i.ingredient_id
            JOIN ingredient_unit u ON ci.unit_id = u.unit_id
            WHERE ci.cocktail_id = :cocktail_id
        ');
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch data and map manually to Ingredient instances
        $ingredients = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ingredient = new Ingredient(
                $row['ingredient_id'],
                $row['name'],
                $row['cocktail_id'],
                $row['quantity'],
                $row['unit_id'],
                $row['unit_name']
            );
            $ingredients[] = $ingredient;
        }

        return $ingredients;
    }

    public function fetchIngredientsForCocktail($cocktailId)
    {
        return $this->getIngredientsByCocktailId($cocktailId);
    }

    public function countIngredients()
    {
        $query = "SELECT COUNT(*) FROM ingredients";
        return $this->db->query($query)->fetchColumn();
    }

    public function getMostUsedIngredient()
    {
        $query = "
        SELECT i.name, COUNT(ci.ingredient_id) AS usage_count
        FROM ingredients i
        JOIN cocktail_ingredients ci ON i.ingredient_id = ci.ingredient_id
        GROUP BY i.ingredient_id
        ORDER BY usage_count DESC
        LIMIT 1";
        return $this->db->query($query)->fetch();
    }
    public function addIngredient($cocktailId, $ingredientId, $quantity, $unitId)
    {
        // Prepare the SQL statement to insert into the cocktail_ingredients table
        $stmt = $this->db->prepare('
            INSERT INTO cocktail_ingredients (cocktail_id, ingredient_id, quantity, unit_id) 
            VALUES (:cocktail_id, :ingredient_id, :quantity, :unit_id)
        ');

        // Bind parameters
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->bindParam(':ingredient_id', $ingredientId, PDO::PARAM_INT); // Using ingredient ID
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $stmt->bindParam(':unit_id', $unitId, PDO::PARAM_INT);

        // Execute the statement and return the result
        return $stmt->execute();
    }

    // Method to fetch ingredient_id by name
    public function getIngredientIdByName($ingredientName)
    {
        $stmt = $this->db->prepare('SELECT ingredient_id FROM ingredients WHERE name = :ingredient_name');
        $stmt->bindParam(':ingredient_name', $ingredientName, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the ingredient ID
        return $stmt->fetchColumn();
    }

    public function createIngredient($ingredientName)
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO ingredients (name) VALUES (:name)');
            $stmt->bindParam(':name', $ingredientName, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $this->db->lastInsertId(); // Return the ID of the new ingredient
            }

            // If insertion fails, return false and log error
            error_log("Failed to insert ingredient: " . $ingredientName);
            return false;
        } catch (PDOException $e) {
            // Log the exception
            error_log("Error inserting ingredient: " . $e->getMessage());
            return false; // Return false if there's an exception
        }
    }

    public function updateIngredientName($ingredientId, $ingredientName)
    {
        $stmt = $this->db->prepare("UPDATE ingredients SET name = :ingredient_name WHERE ingredient_id = :ingredient_id");
        $stmt->bindParam(':ingredient_name', $ingredientName);
        $stmt->bindParam(':ingredient_id', $ingredientId);

        return $stmt->execute();
    }
    
    // Get all available units
    public function getAllUnits()
    {
        $stmt = $this->db->prepare("SELECT unit_id, unit_name FROM ingredient_unit");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Clear all ingredients for a cocktail
    public function clearIngredientsByCocktailId($cocktailId)
    {
        $stmt = $this->db->prepare('DELETE FROM cocktail_ingredients WHERE cocktail_id = :cocktail_id');
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Delete an ingredient
    public function deleteIngredient($ingredientId)
    {
        $stmt = $this->db->prepare('DELETE FROM ingredients WHERE ingredient_id = :ingredient_id');
        $stmt->bindParam(':ingredient_id', $ingredientId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function fetchUncategorizedIngredients($uncategorizedTag = 'Uncategorized')
    {
        $stmt = $this->db->prepare('
        SELECT i.ingredient_id, i.name
        FROM ingredients i
        LEFT JOIN ingredient_tags it ON i.ingredient_id = it.ingredient_id
        LEFT JOIN tags t ON it.tag_id = t.tag_id
        WHERE t.name = :uncategorizedTag OR t.tag_id IS NULL
        ');
        $stmt->bindParam(':uncategorizedTag', $uncategorizedTag, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignTag($ingredientId, $tagId)
    {
        $query = "INSERT INTO ingredient_tags (ingredient_id, tag_id) VALUES (:ingredientId, :tagId)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ingredientId', $ingredientId, PDO::PARAM_INT);
        $stmt->bindParam(':tagId', $tagId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function doesIngredientExist($ingredientId)
    {
        $query = "SELECT COUNT(*) FROM ingredients WHERE ingredient_id = :ingredientId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ingredientId', $ingredientId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    public function getUncategorizedTagId()
    {
        $stmt = $this->db->prepare('SELECT tag_id FROM tags WHERE name = "Uncategorized"');
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
