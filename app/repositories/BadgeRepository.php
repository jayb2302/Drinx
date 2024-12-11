<?php
require_once __DIR__ . '/../models/Badge.php';

class BadgeRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Fetch badges earned by a specific user
    public function findBadgesByUserId($userId)
    {
        $stmt = $this->db->prepare("
            SELECT b.badge_id, b.name, b.description 
            FROM badges b 
            JOIN user_badges ub ON b.badge_id = ub.badge_id 
            WHERE ub.user_id = :user_id AND ub.is_current = 1
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $badges = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $badges[] = new Badge($row['badge_id'], $row['name'], $row['description']);
        }
        return $badges;
    }

    // Fetch a specific badge by its ID
    public function findBadgeById($badgeId)
    {
        $stmt = $this->db->prepare("SELECT badge_id, name, description FROM badges WHERE badge_id = :badge_id");
        $stmt->bindParam(':badge_id', $badgeId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Badge($row['badge_id'], $row['name'], $row['description']) : null;
    }

    // Fetch all badges (optional, for admin views or display purposes)
    public function findAllBadges()
    {
        $stmt = $this->db->query("SELECT badge_id, name, description FROM badges");
        $badges = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $badges[] = new Badge($row['badge_id'], $row['name'], $row['description']);
        }
        return $badges;
    }

    // Check if a badge is already earned
    public function isBadgeAlreadyEarned($userId, $badgeId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM user_badges 
            WHERE user_id = :user_id AND badge_id = :badge_id
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':badge_id', $badgeId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchColumn();
      //  error_log("isBadgeAlreadyEarned: User ID: $userId, Badge ID: $badgeId, Result: $result");
        return $result > 0;
    }


    // Get a new badge based on the cocktail count
    public function getNewBadgeForCocktailCount($cocktailCount)
    {
        $stmt = $this->db->prepare("
            SELECT badge_id, name, description 
            FROM badges 
            WHERE badge_id = (
                CASE
                    WHEN :cocktail_count >= 100 THEN 10
                    WHEN :cocktail_count >= 70 THEN 9
                    WHEN :cocktail_count >= 50 THEN 8
                    WHEN :cocktail_count >= 40 THEN 7
                    WHEN :cocktail_count >= 30 THEN 6
                    WHEN :cocktail_count >= 20 THEN 5
                    WHEN :cocktail_count >= 15 THEN 4
                    WHEN :cocktail_count >= 10 THEN 3
                    WHEN :cocktail_count >= 5 THEN 2
                    ELSE 1
                END
            )
        ");
        $stmt->bindParam(':cocktail_count', $cocktailCount, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     //   error_log("findBadgeByCocktailCount: Cocktail Count: $cocktailCount | Badge: " . print_r($row, true));
        return $row ? new Badge($row['badge_id'], $row['name'], $row['description']) : null;
    }
    public function findBadgeByCocktailCount($cocktailCount)
    {
        // Find the highest badge that the user qualifies for based on cocktail count
        $stmt = $this->db->prepare("
        SELECT badge_id, name, description
        FROM badges
        WHERE badge_id = (
            CASE
                WHEN :cocktailCount >= 100 THEN 10
                WHEN :cocktailCount >= 70 THEN 9
                WHEN :cocktailCount >= 50 THEN 8
                WHEN :cocktailCount >= 40 THEN 7
                WHEN :cocktailCount >= 30 THEN 6
                WHEN :cocktailCount >= 20 THEN 5
                WHEN :cocktailCount >= 15 THEN 4
                WHEN :cocktailCount >= 10 THEN 3
                WHEN :cocktailCount >= 5 THEN 2
                ELSE 1
            END
        )
    ");
        $stmt->bindParam(':cocktailCount', $cocktailCount, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Badge($row['badge_id'], $row['name'], $row['description']) : null;
    }
    public function getLatestBadgeForUser($userId)
    {
        $stmt = $this->db->prepare("
        SELECT b.badge_id, b.name, b.description
        FROM user_badges ub
        JOIN badges b ON ub.badge_id = b.badge_id
        WHERE ub.user_id = :user_id
        ORDER BY b.badge_id DESC
        LIMIT 1
    ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Badge($row['badge_id'], $row['name'], $row['description']) : null;
    }

}