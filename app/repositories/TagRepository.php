<?php
require_once __DIR__ . '/../models/Tag.php';
class TagRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Get all tags with their category names
    public function getAllTags()
    {
        $sql = "
        SELECT tags.*, tag_categories.category_name 
        FROM tags
        LEFT JOIN tag_categories ON tags.tag_category_id = tag_categories.tag_category_id
        ORDER BY tag_categories.category_name, tags.name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // This returns both tag and category data
    }

    public function getTagById($tagId)
    {
        $sql = "SELECT tag_id, name, tag_category_id FROM tags WHERE tag_id = :tag_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tag_id' => $tagId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTagsByCocktailId($cocktailId)
    {
        $sql = "SELECT t.tag_id, t.name, t.tag_category_id
                FROM tags t
                INNER JOIN cocktail_tags ct ON t.tag_id = ct.tag_id
                WHERE ct.cocktail_id = :cocktailId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cocktailId', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($tagName, $tagCategoryId, $tagId = null)
    {
        try {
            if ($tagId) {
                // Update tag
                $sql = "UPDATE tags SET name = :name, tag_category_id = :tag_category_id WHERE tag_id = :tag_id";
                $stmt = $this->db->prepare($sql);

                return $stmt->execute([
                    'name' => $tagName,
                    'tag_category_id' => $tagCategoryId,
                    'tag_id' => $tagId
                ]);
            } else {
                // Insert new tag
                $sql = "INSERT INTO tags (name, tag_category_id) VALUES (:name, :tag_category_id)";
                $stmt = $this->db->prepare($sql);

                return $stmt->execute([
                    'name' => $tagName,
                    'tag_category_id' => $tagCategoryId
                ]);
            }
        } catch (Exception $e) {
            throw new Exception("Error saving tag: " . $e->getMessage());
        }
    }

    // Delete a tag by ID
    public function deleteTag($tagId)
    {
        // Check if the tag is used in any cocktail
        $sql = "SELECT COUNT(*) FROM cocktail_tags WHERE tag_id = :tag_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tag_id' => $tagId]);

        if ($stmt->fetchColumn() > 0) {
            // If tag is in use, do not delete
            echo json_encode(['status' => 'error', 'message' => 'Tag is in use and cannot be deleted.']);
            exit();
        }

        // Delete the tag (not category)
        $deleteSql = "DELETE FROM tags WHERE tag_id = :tag_id";
        $deleteStmt = $this->db->prepare($deleteSql);
        $result = $deleteStmt->execute(['tag_id' => $tagId]);

        if ($result) {
            // Success
            echo json_encode(['status' => 'success', 'message' => 'Tag deleted successfully.']);
        } else {
            // Error
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete tag.']);
        }
        exit();
    }

    // Get all tag categories
    public function getAllTagCategories()
    {
        $sql = "SELECT * FROM tag_categories";
        $stmt = $this->db->prepare($sql);

        if (!$stmt->execute()) {
            throw new Exception('Failed to fetch all tag categories.');
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a tag by its name
    public function getTagByName($name)
    {
        $sql = "SELECT * FROM tags WHERE name = :name";
        $stmt = $this->db->prepare($sql);

        if (!$stmt->execute(['name' => $name])) {
            throw new Exception("Failed to fetch tag with name: $name");
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a tag to a cocktail
    public function addTagToCocktail($cocktailId, $tagName)
    {
        try {
            // Check if tag exists by name
            $tag = $this->getTagByName($tagName);
            if (!$tag) {
                // If the tag doesn't exist, insert it with 'Uncategorized'
                $this->save($tagName, null, null);  // null for tag_category_id
                $tag = $this->getTagByName($tagName);  // Fetch newly created tag
            }

            // Insert the tag ID into cocktail_tags
            $sql = "INSERT IGNORE INTO cocktail_tags (cocktail_id, tag_id) VALUES (:cocktail_id, :tag_id)";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                'cocktail_id' => $cocktailId,
                'tag_id' => $tag['tag_id']
            ]);
        } catch (Exception $e) {
            throw new Exception("Error adding tag to cocktail: " . $e->getMessage());
        }
    }

    // Assign tags based on ingredients for a specific cocktail
    public function assignTagsByIngredients($cocktailId)
    {
        try {
            // Get all ingredients for the cocktail
            $sql = "
                SELECT i.name
                FROM cocktail_ingredients ci
                JOIN ingredients i ON ci.ingredient_id = i.ingredient_id
                WHERE ci.cocktail_id = :cocktail_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['cocktail_id' => $cocktailId]);

            $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Loop through ingredients and add corresponding tags
            foreach ($ingredients as $ingredient) {
                if (in_array(strtolower($ingredient['name']), ['lime', 'lemon', 'orange', 'grapefruit'])) {
                    $this->addTagToCocktail($cocktailId, 'Citrus'); // Example tag name
                } elseif (in_array(strtolower($ingredient['name']), ['vodka', 'rum', 'gin', 'whiskey'])) {
                    $this->addTagToCocktail($cocktailId, 'Strong');
                } elseif (in_array(strtolower($ingredient['name']), ['mint', 'soda'])) {
                    $this->addTagToCocktail($cocktailId, 'Refreshing');
                }
                // More conditions for other tags as needed
            }
        } catch (Exception $e) {
            throw new Exception("Error assigning tags by ingredients: " . $e->getMessage());
        }
    }
    public function doesTagExist($tagId)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM tags WHERE tag_id = :id');
        $stmt->bindParam(':id', $tagId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
