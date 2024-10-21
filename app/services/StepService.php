<?php
class StepService {
    private $stepRepository;

    public function __construct(StepRepository $stepRepository) {
        $this->stepRepository = $stepRepository;
    }

    // Retrieve steps by cocktail ID
    public function getStepsByCocktailId($cocktailId) {
        return $this->stepRepository->getStepsByCocktailId($cocktailId);
    }

    // Add a new step to a cocktail
    public function addStep($cocktailId, $instruction, $stepNumber) {
        return $this->stepRepository->addStep($cocktailId, $instruction, $stepNumber);
    }
    
    // Delete a step
    public function deleteStep($cocktailId, $stepId) {
        return $this->stepRepository->deleteStep($cocktailId, $stepId);
    }

    // Clear all steps for a given cocktail
    public function clearStepsByCocktailId($cocktailId) {
        return $this->stepRepository->deleteStepsByCocktailId($cocktailId);
    }

    // Update steps for a cocktail (clear existing steps and add new ones)
    public function updateSteps($cocktailId, $steps) {
        // Clear existing steps before adding new ones
        $this->clearStepsByCocktailId($cocktailId);
    
        foreach ($steps as $stepNumber => $instruction) {
            // Check if the instruction is not empty
            if (!empty($instruction)) {
                // Before adding, check if the step already exists
                $existingStep = $this->stepRepository->getStepByCocktailIdAndStepNumber($cocktailId, $stepNumber);
                if (!$existingStep) {
                    // Only add the step if it does not exist
                    $this->addStep($cocktailId, $instruction, $stepNumber); // Use the addStep method for adding
                }
            }
        }
    }
}