<?php
class IngredientService {
    private $ingredientRepository;
    private $unitRepository;

    public function __construct(IngredientRepository $ingredientRepository, UnitRepository $unitRepository) {
        $this->ingredientRepository = $ingredientRepository;
        $this->unitRepository = $unitRepository;
    }

    public function updateIngredients($cocktailId, $ingredients, $quantities, $units) {
        // Clear existing ingredients before adding new ones
        $this->clearIngredientsByCocktailId($cocktailId);
    
        foreach ($ingredients as $index => $ingredientName) {
            $quantity = $quantities[$index] ?? null; 
            $unitId = $units[$index] ?? null;
    
            // Check if the ingredient exists and add it via the service
            $ingredientId = $this->getIngredientIdByName($ingredientName);
    
            // If the ingredient doesn't exist, create it via the service
            if (!$ingredientId) {
                $ingredientId = $this->createIngredient($ingredientName);
            }
    
            // Add the ingredient to the cocktail via the service
            $this->addIngredient($cocktailId, $ingredientId, $quantity, $unitId);
        }
    }

    public function getIngredientIdByName($ingredientName) {
        return $this->ingredientRepository->getIngredientIdByName($ingredientName);
    }

    public function createIngredient($ingredientName) {
        return $this->ingredientRepository->createIngredient($ingredientName);
    }

    public function addIngredient($cocktailId, $ingredientName, $quantity, $unitId) {
        return $this->ingredientRepository->addIngredient($cocktailId, $ingredientName, $quantity, $unitId);
    }
    
    public function getIngredientsByCocktailId($cocktailId)
    {
        return $this->ingredientRepository->fetchIngredientsForCocktail($cocktailId); // Call public method
    }

    public function getAllUnits() {
        return $this->ingredientRepository->getAllUnits();
    }
    public function clearIngredientsByCocktailId($cocktailId) {
        return $this->ingredientRepository->clearIngredientsByCocktailId($cocktailId);
    }

    public function deleteIngredient($ingredientId) {
        return $this->ingredientRepository->deleteIngredient($ingredientId);
    }
}