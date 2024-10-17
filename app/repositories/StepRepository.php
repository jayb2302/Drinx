<?php

class StepRepository {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Method to get steps by cocktail ID
    public function deleteStepsByCocktailId($cocktailId) {
        $stmt = $this->db->prepare('DELETE FROM cocktail_steps WHERE cocktail_id = :cocktail_id');
        $stmt->bindParam(':cocktail_id', $cocktailId);
        return $stmt->execute();
    }

    public function addStep($cocktail_id, $instruction) {
        // Get the current number of steps to determine the next step number
        $stmt = $this->db->prepare('SELECT COUNT(*) AS step_count FROM cocktail_steps WHERE cocktail_id = :cocktail_id');
        $stmt->bindParam(':cocktail_id', $cocktail_id);
        $stmt->execute();
        $stepCount = $stmt->fetchColumn();
    
        // Now insert the new step with the appropriate step_number
        $stmt = $this->db->prepare('INSERT INTO cocktail_steps (cocktail_id, instruction, step_number) VALUES (:cocktail_id, :instruction, :step_number)');
        $stmt->bindParam(':cocktail_id', $cocktail_id);
        $stmt->bindParam(':instruction', $instruction);
        $stepNumber = $stepCount + 1; // Assuming step numbers are sequential
        $stmt->bindParam(':step_number', $stepNumber);
        return $stmt->execute();
    }

    public function deleteStep($step_id) {
        $stmt = $this->db->prepare("DELETE FROM cocktail_steps WHERE step_id = :id");
        $stmt->bindParam(':id', $step_id);
        return $stmt->execute();
    }
    
    public function getStepsByCocktailId($cocktailId) {
        $stmt = $this->db->prepare('
            SELECT instruction 
            FROM cocktail_steps 
            WHERE cocktail_id = :cocktail_id
        ');
        $stmt->bindParam(':cocktail_id', $cocktailId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}