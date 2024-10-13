<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';  
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';

class CocktailService {
    private $cocktailRepository;
    private $categoryRepository;
    private $ingredientRepository;
    private $stepRepository;

    public function __construct(
        CocktailRepository $cocktailRepository,
        CategoryRepository $categoryRepository,
        IngredientRepository $ingredientRepository,
        StepRepository $stepRepository
    ) {
        $this->cocktailRepository = $cocktailRepository;
        $this->categoryRepository = $categoryRepository;
        $this->ingredientRepository = $ingredientRepository;
        $this->stepRepository = $stepRepository;
    }

    // Cocktail CRUD operations
    public function getCocktailById($cocktailId) {
        return $this->cocktailRepository->getById($cocktailId);
    }

    public function getCocktailByName($name) {
        return $this->cocktailRepository->getByName($name);
    }

    public function getAllCocktails() {
        return $this->cocktailRepository->getAll(); 
    }

    public function createCocktail($cocktailData) {
        return $this->cocktailRepository->create($cocktailData);
    }

    public function updateCocktail($cocktailId, $cocktailData) {
        return $this->cocktailRepository->update($cocktailId, $cocktailData);
    }

    public function deleteCocktail($cocktailId) {
        return $this->cocktailRepository->delete($cocktailId);
    }

    // Ingredient-related operations
    public function addIngredient($cocktailId, $ingredientId, $quantity, $unitId) {
        return $this->ingredientRepository->addIngredient($cocktailId, $ingredientId, $quantity, $unitId);
    }

    public function getCocktailIngredients($cocktailId) {
        return $this->ingredientRepository->getIngredientsByCocktailId($cocktailId);
    }

    // Step-related operations
    public function addStep($cocktailId, $instruction) {
        return $this->stepRepository->addStep($cocktailId, $instruction);
    }

    public function clearSteps($cocktailId) {
        return $this->stepRepository->deleteStepsByCocktailId($cocktailId);
    }

    public function getCocktailSteps($cocktailId) {
        return $this->stepRepository->getStepsByCocktailId($cocktailId);
    }

    public function updateCocktailSteps($cocktailId, $steps) {
        // First, clear existing steps
        $this->clearSteps($cocktailId);

        // Then add new steps
        foreach ($steps as $step) {
            $this->addStep($cocktailId, $step);
        }
    }

    public function deleteCocktailStep($stepId) {
        return $this->stepRepository->deleteStep($stepId);
    }

    // Category-related operations
    public function getCategories() {
        return $this->categoryRepository->getAllCategories();
    }

    public function getCategoryByCocktailId($cocktailId) {
        return $this->categoryRepository->getCategoryByCocktailId($cocktailId);
    }

   
}
?>