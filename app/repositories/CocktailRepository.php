<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cocktail.php';
require_once __DIR__ . '/../models/Ingredient.php';
require_once __DIR__ . '/../models/Step.php';

class CocktailRepository
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Fetch cocktail by ID
    public function getById($cocktail_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                c.*, 
                d.difficulty_name 
            FROM 
                cocktails c 
            LEFT JOIN 
                difficulty_levels d ON c.difficulty_id = d.difficulty_id 
            WHERE 
                c.cocktail_id = :id
        ");
        $stmt->bindParam(':id', $cocktail_id, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $ingredients = $this->getIngredientsByCocktailId($cocktail_id);
            $steps = $this->getStepsByCocktailId($cocktail_id);
            $tags = []; // Initialize with empty tags or fetch as needed
    
            $cocktail = new Cocktail(
                $result['cocktail_id'],
                $result['user_id'], // User ID should be correctly passed here
                $result['title'],
                $result['description'],
                $result['image'],
                (bool) $result['is_sticky'],
                $result['category_id'],
                $result['difficulty_id'],
                $ingredients,
                $steps,
                $tags,
                $result['like_count'] ?? 0,
                $result['difficulty_name'] ?? null
            );
    
            return $cocktail;
        }
    
        return null;
    }

    // Fetch all cocktails
    public function getAll()
    {
        $query = $this->db->query("SELECT * FROM cocktails");
        $cocktailsData = $query->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($cocktailData) {
            // Fetch ingredients and steps related to the cocktail
            $ingredients = $this->getIngredientsByCocktailId($cocktailData['cocktail_id']); // Returns an array of Ingredient objects
            $steps = $this->getStepsByCocktailId($cocktailData['cocktail_id']); // Returns an array of Step objects
            $tags = []; // You can populate tags similarly

            // Create and return a Cocktail object
            return new Cocktail(
                $cocktailData['cocktail_id'],
                $cocktailData['user_id'],
                $cocktailData['title'],
                $cocktailData['description'],
                $cocktailData['image'],
                (bool) $cocktailData['is_sticky'],
                $cocktailData['category_id'],
                $cocktailData['difficulty_id'],
                $ingredients, 
                $steps, 
                $tags,
                $cocktailData['like_count'] ?? 0,
                $cocktailData['difficulty_name'] ?? null
            );
        }, $cocktailsData);
    }

    private function getIngredientsByCocktailId($cocktailId)
    {
        // Query the cocktail_ingredients table
        $stmt = $this->db->prepare('SELECT * FROM cocktail_ingredients WHERE cocktail_id = :cocktail_id');
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Ingredient');
    }

    public function getStepsByCocktailId($cocktailId)
    {
        $stmt = $this->db->prepare("SELECT * FROM cocktail_steps WHERE cocktail_id = :cocktail_id");
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();

        // Return the steps as an array, or an empty array if none found
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; // Fetch all steps or return an empty array
    }

    // Fetch cocktail by name
    public function getByName($name)
    {
        $stmt = $this->db->prepare('SELECT * FROM cocktails WHERE title = :name');
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new Cocktail(
                $result['cocktail_id'],
                $result['user_id'],
                $result['title'],
                $result['description'],
                $result['image'],
                (bool) $result['is_sticky'],
                $result['category_id'],
                $result['difficulty_id'],
                $result['created_at'],
                $result['updated_at'],
                $result['like_count'] ?? 0
            );
        }

        return null; // Return null if no cocktail found
    }

    // Create a new cocktail
    public function create($cocktailData)
    {
        $stmt = $this->db->prepare("
            INSERT INTO cocktails (user_id, title, description, image, is_sticky, category_id, difficulty_id,  created_at) 
            VALUES (:user_id, :title, :description, :image, :is_sticky, :category_id, :difficulty_id, NOW())
        ");

        $stmt->bindParam(':user_id', $cocktailData['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $cocktailData['title']);
        $stmt->bindParam(':description', $cocktailData['description']);
        $stmt->bindParam(':image', $cocktailData['image']);
        $stmt->bindParam(':is_sticky', $cocktailData['is_sticky'], PDO::PARAM_BOOL);
        $stmt->bindParam(':category_id', $cocktailData['category_id'], PDO::PARAM_INT);
        $stmt->bindParam(':difficulty_id', $cocktailData['difficulty_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }

        throw new Exception("Failed to create cocktail.");
    }

    // Update an existing cocktail
    public function update($cocktail_id, $cocktailData)
    {
        $stmt = $this->db->prepare('
            UPDATE cocktails 
            SET title = :title, 
                description = :description, 
                image = :image, 
                is_sticky = :is_sticky, 
                category_id = :category_id, 
                difficulty_id = :difficulty_id, 
                updated_at = NOW() 
            WHERE cocktail_id = :id
        ');
        $stmt->bindParam(':id', $cocktail_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $cocktailData['title']);
        $stmt->bindParam(':description', $cocktailData['description']);
        $stmt->bindParam(':image', $cocktailData['image']);
        $stmt->bindParam(':is_sticky', $cocktailData['is_sticky'], PDO::PARAM_BOOL);
        $stmt->bindParam(':category_id', $cocktailData['category_id']);
        $stmt->bindParam(':difficulty_id', $cocktailData['difficulty_id']);

        if ($stmt->execute()) {
            return true; // Successfully updated
        }
        return false; // Update failed
    }

    // Delete a cocktail
    public function delete($cocktailId)
    {
        $stmt = $this->db->prepare("DELETE FROM cocktails WHERE cocktail_id = :cocktailId");
        $stmt->bindParam(':cocktailId', $cocktailId, PDO::PARAM_INT);
        return $stmt->execute(); // Returns true on success
    }

    // Fetch all categories
    public function getCategories()
    {
        $stmt = $this->db->query('SELECT * FROM categories');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch category by cocktail ID
    public function getCategoryByCocktailId($cocktailId)
    {
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
    public function findByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM cocktails WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $cocktailsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Map the results to Cocktail objects
        return array_map(function ($cocktailData) {
            $ingredients = $this->getIngredientsByCocktailId($cocktailData['cocktail_id']);
            $steps = $this->getStepsByCocktailId($cocktailData['cocktail_id']);

            return new Cocktail(
                $cocktailData['cocktail_id'],
                $cocktailData['user_id'],
                $cocktailData['title'],
                $cocktailData['description'],
                $cocktailData['image'],
                $cocktailData['category_id'],
                $ingredients,
                $steps
            );
        }, $cocktailsData);
    }

    // Fetch cocktails sorted by creation date
    public function getAllSortedByDate()
    {
        $stmt = $this->db->query("
    SELECT * 
    FROM cocktails 
    ORDER BY created_at DESC");
        return $this->mapCocktails($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Fetch cocktails sorted by like count
    public function getAllSortedByLikes()
    {
        $stmt = $this->db->query("
        SELECT c.*, COUNT(l.like_id) AS like_count
        FROM cocktails c
        LEFT JOIN likes l ON c.cocktail_id = l.cocktail_id
        GROUP BY c.cocktail_id
        ORDER BY like_count DESC
    ");
    return $this->mapCocktails($stmt->fetchAll(PDO::FETCH_ASSOC));
}

public function getAllHotCocktails() {
    $stmt = $this->db->prepare("
        SELECT c.*, 
               (2 * COUNT(l.like_id) + COUNT(com.comment_id)) AS hot_score
        FROM cocktails c
        LEFT JOIN likes l ON c.cocktail_id = l.cocktail_id 
            AND l.created_at >= NOW() - INTERVAL 7 DAY
        LEFT JOIN comments com ON c.cocktail_id = com.cocktail_id 
            AND com.created_at >= NOW() - INTERVAL 7 DAY
        WHERE l.like_id IS NOT NULL OR com.comment_id IS NOT NULL
        GROUP BY c.cocktail_id
        ORDER BY hot_score DESC;
    ");
    $stmt->execute();
    return $this->mapCocktails($stmt->fetchAll(PDO::FETCH_ASSOC));
}

    
    
    


    public function getCocktailsByCategory($categoryId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM cocktails 
            WHERE category_id = :category_id
            ORDER BY created_at DESC
        ");
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $this->mapCocktails($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    


    // Helper function to map raw cocktail data to Cocktail objects
    private function mapCocktails($cocktailsData)
    {
        return array_map(function ($cocktailData) {
            $ingredients = $this->getIngredientsByCocktailId($cocktailData['cocktail_id']);
            $steps = $this->getStepsByCocktailId($cocktailData['cocktail_id']);

            return new Cocktail(
                $cocktailData['cocktail_id'],
                $cocktailData['user_id'],
                $cocktailData['title'],
                $cocktailData['description'],
                $cocktailData['image'],
                (bool)($cocktailData['is_sticky'] ?? 0),
                $cocktailData['category_id'],
                $cocktailData['difficulty_id'],
                $ingredients,
                $steps
            );
        }, $cocktailsData);
    }

    public function searchCocktails($query)
    {
        $stmt = $this->db->prepare("SELECT cocktail_id, title, image FROM cocktails WHERE title LIKE :query LIMIT 5");
        $stmt->execute(['query' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Ensure 'image' is included in the result set
    }

    public function getRandomCocktail()
    {
        $stmt = $this->db->prepare("SELECT * FROM cocktails ORDER BY RAND() LIMIT 1");
        $stmt->execute();
        $cocktailData = $stmt->fetchAll(PDO::FETCH_ASSOC); // Ensure this returns an array of arrays

        // Check if $cocktailData is not empty before mapping
        if (!empty($cocktailData)) {
            $cocktails = $this->mapCocktails($cocktailData); // Use mapCocktails to create the Cocktail object
            return $cocktails[0] ?? null; // Return the first cocktail or null if empty
        }

        return null; // Return null if no cocktails found
    }

    public function getStickyCocktail()
    {
        $stmt = $this->db->prepare("SELECT * FROM cocktails WHERE is_sticky = 1 LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return a Cocktail object or null if no sticky cocktail found
        return $result ? $this->mapCocktails([$result])[0] : null; // Return the first cocktail or null
    }

    public function clearStickyCocktail()
    {
        $stmt = $this->db->prepare("UPDATE cocktails SET is_sticky = 0 WHERE is_sticky = 1");
        return $stmt->execute();
    }

    public function setStickyCocktail($cocktailId)
    {
        // Clear the current sticky cocktail
        $this->db->prepare("UPDATE cocktails SET is_sticky = 0 WHERE is_sticky = 1")->execute();

        // Mark the new cocktail as sticky
        $stmt = $this->db->prepare("UPDATE cocktails SET is_sticky = 1 WHERE cocktail_id = :cocktail_id");
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
