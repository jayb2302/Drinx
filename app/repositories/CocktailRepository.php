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

    // Method to create a Cocktail object from database result data
    private function createCocktailObject($data)
    {
        $ingredients = $this->getIngredientsByCocktailId($data['cocktail_id'] ?? null);
        $steps = $this->getStepsByCocktailId($data['cocktail_id'] ?? null);
        $tags = $data['tags'] ?? [];

        return new Cocktail(
            $data['cocktail_id'] ?? 0,
            $data['user_id'] ?? 0,
            $data['title'] ?? 'Unknown',
            $data['description'] ?? 'No description available',
            $data['image'] ?? 'default-image.webp',
            (bool)($data['is_sticky'] ?? false),
            $data['category_id'] ?? null,
            $data['difficulty_id'] ?? null,
            $ingredients,
            $steps,
            $tags,
            $data['like_count'] ?? 0,
            $data['difficulty_name'] ?? null,
        );
    }

    // Fetch cocktail by ID
    public function getById($cocktail_id)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, d.difficulty_name 
            FROM cocktails c 
            LEFT JOIN difficulty_levels d ON c.difficulty_id = d.difficulty_id 
            WHERE c.cocktail_id = :id
        ");
        $stmt->bindParam(':id', $cocktail_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $this->createCocktailObject($result) : null;
    }

    // Fetch all cocktails
    public function getAll()
    {
        $query = $this->db->query("SELECT * FROM cocktails");
        $cocktailsData = $query->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'createCocktailObject'], $cocktailsData);
    }
    public function countCocktails()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM cocktails");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    public function getMostPopularCocktail()
    {
        $query = "
            SELECT c.title, COUNT(l.like_id) AS like_count
            FROM cocktails c
            JOIN likes l ON c.cocktail_id = l.cocktail_id
            GROUP BY c.cocktail_id
            ORDER BY like_count DESC
            LIMIT 1";
        return $this->db->query($query)->fetch();
    }
    public function countCocktailsWithoutComments()
    {
        $query = "
        SELECT COUNT(*) 
        FROM cocktails c
        LEFT JOIN comments cm ON c.cocktail_id = cm.cocktail_id
        WHERE cm.comment_id IS NULL";
        return $this->db->query($query)->fetchColumn();
    }
    // Fetch cocktail by name
    public function getByName($name)
    {
        $stmt = $this->db->prepare('SELECT * FROM cocktails WHERE title = :name');
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $this->createCocktailObject($result) : null;
    }

    // Create a new cocktail
    public function create($cocktailData)
    {
        $stmt = $this->db->prepare("
            INSERT INTO cocktails (user_id, title, description, image, is_sticky, category_id, difficulty_id, created_at) 
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

        return $stmt->execute();
    }

    // Delete a cocktail
    public function delete($cocktailId)
    {
        $stmt = $this->db->prepare("DELETE FROM cocktails WHERE cocktail_id = :cocktailId");
        $stmt->bindParam(':cocktailId', $cocktailId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Fetch cocktails by user ID
    public function findByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM cocktails WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $cocktailsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'createCocktailObject'], $cocktailsData);
    }
    public function searchCocktails($query)
    {
        $stmt = $this->db->prepare("
            SELECT cocktail_id, title, image 
            FROM cocktails 
            WHERE title LIKE :query
        ");
        $searchTerm = '%' . $query . '%';
        $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();

        $cocktailsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Map the results directly to prevent adding default attributes like hasLiked
        return array_map(function ($data) {
            return [
                'cocktail_id' => $data['cocktail_id'],
                'title' => $data['title'],
                'image' => $data['image'] ?? 'default-image.webp'
            ];
        }, $cocktailsData);
    }

    // Fetch cocktails sorted by creation date
    public function getAllSortedByDate()
    {
        $stmt = $this->db->query("SELECT * FROM cocktails ORDER BY created_at DESC");
        $cocktailsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'createCocktailObject'], $cocktailsData);
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
        $cocktailsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'createCocktailObject'], $cocktailsData);
    }

    public function getAllHotCocktails()
    {
        $stmt = $this->db->prepare("
            SELECT c.*, (2 * COUNT(l.like_id) + COUNT(com.comment_id)) AS hot_score
            FROM cocktails c
            LEFT JOIN likes l ON c.cocktail_id = l.cocktail_id AND l.created_at >= NOW() - INTERVAL 7 DAY
            LEFT JOIN comments com ON c.cocktail_id = com.cocktail_id 
            AND com.created_at >= NOW() - INTERVAL 7 DAY
            WHERE l.like_id IS NOT NULL OR com.comment_id IS NOT NULL
            GROUP BY c.cocktail_id
            ORDER BY hot_score DESC;
        ");
        $stmt->execute();
        $cocktailsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'createCocktailObject'], $cocktailsData);
    }
    // Cocktails sorted by creation date within a category
    public function getCocktailsByCategorySortedByDate($categoryId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM cocktails
        WHERE category_id = :category_id
        ORDER BY created_at DESC
    ");
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return array_map([$this, 'createCocktailObject'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Cocktails sorted by likes within a category
    public function getCocktailsByCategorySortedByLikes($categoryId)
    {
        $stmt = $this->db->prepare("
        SELECT c.*, COUNT(l.like_id) AS like_count
        FROM cocktails c
        LEFT JOIN likes l ON c.cocktail_id = l.cocktail_id
        WHERE c.category_id = :category_id
        GROUP BY c.cocktail_id
        ORDER BY like_count DESC
    ");
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return array_map([$this, 'createCocktailObject'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Hot cocktails within a category
    public function getHotCocktailsByCategory($categoryId)
    {
        $stmt = $this->db->prepare("
        SELECT c.*, (2 * COUNT(l.like_id) + COUNT(com.comment_id)) AS hot_score
        FROM cocktails c
        LEFT JOIN likes l ON c.cocktail_id = l.cocktail_id AND l.created_at >= NOW() - INTERVAL 7 DAY
        LEFT JOIN comments com ON c.cocktail_id = com.cocktail_id AND com.created_at >= NOW() - INTERVAL 7 DAY
        WHERE c.category_id = :category_id
        AND (l.created_at >= NOW() - INTERVAL 7 DAY OR com.created_at >= NOW() - INTERVAL 7 DAY)
        GROUP BY c.cocktail_id
        ORDER BY hot_score DESC;

    ");
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return array_map([$this, 'createCocktailObject'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }


    // Fetch cocktails by category
    public function getCocktailsByCategory($categoryId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM cocktails 
            WHERE category_id = :category_id
            ORDER BY created_at DESC
        ");
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        $cocktailsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'createCocktailObject'], $cocktailsData);
    }

    private function getIngredientsByCocktailId($cocktailId)
    {
        $stmt = $this->db->prepare('SELECT * FROM cocktail_ingredients WHERE cocktail_id = :cocktail_id');
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Ingredient');
    }

    private function getStepsByCocktailId($cocktailId)
    {
        $stmt = $this->db->prepare("SELECT * FROM cocktail_steps WHERE cocktail_id = :cocktail_id");
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getRandomCocktail()
    {
        $stmt = $this->db->prepare("SELECT * FROM cocktails ORDER BY RAND() LIMIT 1");
        $stmt->execute();
        $cocktailData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $cocktailData ? $this->createCocktailObject($cocktailData) : null;
    }

    public function getStickyCocktail()
    {
        $stmt = $this->db->prepare("SELECT * FROM cocktails WHERE is_sticky = 1 LIMIT 1");
        $stmt->execute();
        $cocktailData = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single result as an associative array

        return $cocktailData ? $this->createCocktailObject($cocktailData) : null;
    }

    // Clears the sticky status from any cocktail
    public function clearStickyCocktail()
    {
        $stmt = $this->db->prepare("UPDATE cocktails SET is_sticky = 0 WHERE is_sticky = 1");
        return $stmt->execute();
    }

    // Sets a specific cocktail as sticky by clearing any existing sticky cocktail and then marking the selected one
    public function setStickyCocktail($cocktailId)
    {
        // First, clear any existing sticky cocktail
        $this->clearStickyCocktail();

        // Now set the specified cocktail as sticky
        $stmt = $this->db->prepare("UPDATE cocktails SET is_sticky = 1 WHERE cocktail_id = :cocktail_id");
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
