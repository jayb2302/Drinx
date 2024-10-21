<?php

class StepRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Get steps by cocktail ID
    public function getStepsByCocktailId($cocktailId) {
        $stmt = $this->db->prepare('SELECT * FROM cocktail_steps WHERE cocktail_id = :cocktail_id ORDER BY step_number');
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->execute();
    
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch data as associative array
    
        $steps = [];
        foreach ($results as $row) {
            $step = new Step();
            $step->setStepId($row['step_id']);
            $step->setCocktailId($row['cocktail_id']);
            $step->setStepNumber($row['step_number']);
            $step->setInstruction($row['instruction']);
            $steps[] = $step;
        }
    
        return $steps;  // Return array of Step objects
    }

    // Add a new step
    public function addStep($cocktailId, $instruction, $stepNumber)
    {
        $stmt = $this->db->prepare('
            INSERT INTO cocktail_steps (cocktail_id, step_number, instruction)
            VALUES (:cocktail_id, :step_number, :instruction)
        ');
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->bindParam(':step_number', $stepNumber, PDO::PARAM_INT);
        $stmt->bindParam(':instruction', $instruction, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Delete a step
    public function deleteStep($cocktailId, $stepId) {
        $stmt = $this->db->prepare('DELETE FROM cocktail_steps WHERE cocktail_id = :cocktail_id AND step_id = :step_id');
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->bindParam(':step_id', $stepId, PDO::PARAM_INT);
        
        if (!$stmt->execute()) {
            // Log or handle the error
            throw new Exception("Failed to delete step: " . implode(", ", $stmt->errorInfo()));
        }
        return true; // Return true if deletion was successful
    }

    public function getStepByCocktailIdAndStepNumber($cocktailId, $stepNumber)
    {
        $stmt = $this->db->prepare('SELECT * FROM cocktail_steps WHERE cocktail_id = :cocktail_id AND step_number = :step_number');
        

        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        $stmt->bindParam(':step_number', $stepNumber, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Fetch steps for a given cocktail
    public function fetchStepsForCocktail($cocktailId)
    {
        return $this->getStepsByCocktailId($cocktailId);
    }

    // Delete all steps for a given cocktail
    public function deleteStepsByCocktailId($cocktailId)
    {
        $stmt = $this->db->prepare('DELETE FROM cocktail_steps WHERE cocktail_id = :cocktail_id');
        $stmt->bindParam(':cocktail_id', $cocktailId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
