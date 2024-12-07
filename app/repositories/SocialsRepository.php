<?php
require_once __DIR__ . '/../models/SocialLink.php';
require_once __DIR__ . '/../models/Platform.php';
class SocialsRepository {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

        // Fetch all social platforms
        public function getAllPlatforms() {
            $sql = "SELECT * FROM social_platforms";
            $stmt = $this->db->query($sql);
    
            $platforms = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $platforms[] = new Platform($row['platform_id'], $row['platform_name'], $row['icon_class']);
            }
            return $platforms;
        }
    // Fetch all social links for a user
    public function getSocialLinksByUserId($userId): array
    {
        $sql = "
            SELECT sl.*, sp.platform_name, sp.icon_class
            FROM user_social_links sl
            JOIN social_platforms sp ON sl.platform_id = sp.platform_id
            WHERE sl.user_id = :user_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
    
        $socialLinks = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $platform = new Platform($row['platform_id'], $row['platform_name'], $row['icon_class']);
            $socialLinks[] = new SocialLink($row['social_link_id'], $row['user_id'], $platform, $row['url'], $row['created_at']);
        }
    
        return $socialLinks; // Array of SocialLink objects
    }
    // Update an existing social link
    public function updateSocialLink($userId, $platformId, $url) {
        $sql = "
            INSERT INTO user_social_links (user_id, platform_id, url, created_at)
            VALUES (:user_id, :platform_id, :url, NOW())
            ON DUPLICATE KEY UPDATE
            url = :url
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'platform_id' => $platformId,
            'url' => $url,
        ]);
    }
    // Delete a social link
    public function deleteSocialLink($userId, $platformId) {
        $sql = "
            DELETE FROM user_social_links
            WHERE user_id = :user_id AND platform_id = :platform_id
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'platform_id' => $platformId,
        ]);
    }
}