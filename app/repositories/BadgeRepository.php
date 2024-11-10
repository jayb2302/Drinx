<?php
class BadgeRepository {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function findBadgesByUserId($userId) {
        $stmt = $this->db->prepare("SELECT b.* FROM badges b JOIN user_badges ub ON b.badge_id = ub.badge_id WHERE ub.user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}