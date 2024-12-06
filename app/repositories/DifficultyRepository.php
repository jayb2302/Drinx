<?php
class DifficultyRepository {
    private $db;

    public function __construct($db) 
    {
        $this->db = $db;
    }

    public function getAllDifficulties()
    {
        $stmt = $this->db->query("SELECT difficulty_id, difficulty_name FROM difficulty_levels ORDER BY difficulty_id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDifficultyNameById($difficultyId) {
        $stmt = $this->db->prepare("SELECT difficulty_name FROM difficulty_levels WHERE difficulty_id = :id");
        $stmt->bindParam(':id', $difficultyId, PDO::PARAM_INT);
        $stmt->execute();
        
        $difficultyName = $stmt->fetchColumn();
        
        return $difficultyName ? $difficultyName : 'Not specified'; // Return 'Not specified' if null
    }
}
?>