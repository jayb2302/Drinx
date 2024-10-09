<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';  

class CocktailService {
    private $cocktailRepository;

    public function __construct() {
        $this->cocktailRepository = new CocktailRepository();
    }

    public function getCocktailById($cocktail_id) {
        return $this->cocktailRepository->getById($cocktail_id);
    }
    public function getAllCocktails() {
        return $this->cocktailRepository->getAll();
        
    }

    public function createCocktail($cocktailData) {
        return $this->cocktailRepository->create($cocktailData);
    }

    public function updateCocktail($cocktail_id, $cocktailData) {
        return $this->cocktailRepository->update($cocktail_id, $cocktailData);
    }

    public function deleteCocktail($cocktail_id) {
        return $this->cocktailRepository->delete($cocktail_id);
    }
}
?>