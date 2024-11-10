<?php

class UnitRepository {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllUnits() {
        $stmt = $this->db->prepare("SELECT * FROM ingredient_unit");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>