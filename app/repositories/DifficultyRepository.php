<?php
class DifficultyRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllDifficulties() {
        $stmt = $this->db->prepare("SELECT * FROM difficulties");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>