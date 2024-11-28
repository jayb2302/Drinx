<?php 
class TagService
{
    private $tagRepository;
    private $ingredientRepository;

    public function __construct(TagRepository $tagRepository, IngredientRepository $ingredientRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->ingredientRepository = $ingredientRepository;
    }

    public function assignTagsByIngredients($cocktailId)
    {
        try {
            // Fetch ingredients for the cocktail
            $ingredients = $this->ingredientRepository->fetchIngredientsForCocktail($cocktailId);

            // Define tag categories based on ingredients
            $tagMapping = [
                'Citrus' => ['lime', 'lemon', 'orange', 'grapefruit'],
                'Strong' => ['vodka', 'rum', 'gin', 'whiskey'],
                'Refreshing' => ['mint', 'soda']
            ];

            // Loop through ingredients and assign tags
            foreach ($ingredients as $ingredient) {
                $ingredientName = strtolower($ingredient['name']);
                foreach ($tagMapping as $tagName => $ingredientKeywords) {
                    if (in_array($ingredientName, $ingredientKeywords)) {
                        $this->tagRepository->addTagToCocktail($cocktailId, $tagName);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception("Error assigning tags by ingredients: " . $e->getMessage());
        }
    }
}