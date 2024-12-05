<?php
class IngredientService
{
    private $ingredientRepository;
    private $unitRepository;
    private $tagRepository;

    public function __construct(
        IngredientRepository $ingredientRepository,
        UnitRepository $unitRepository,
        TagRepository $tagRepository
    ) {
        $this->ingredientRepository = $ingredientRepository;
        $this->unitRepository = $unitRepository;
        $this->tagRepository = $tagRepository;
    }

    // search ingredient 
    public function searchIngredients($query) {
        return $this->ingredientRepository->searchByName($query);
    }

    public function updateIngredients($cocktailId, $ingredients, $quantities, $units)
    {
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
    public function updateIngredientName($ingredientId, $ingredientName)
    {
        return $this->ingredientRepository->updateIngredientName($ingredientId, $ingredientName);
    }

    public function getIngredientIdByName($ingredientName)
    {
        return $this->ingredientRepository->getIngredientIdByName($ingredientName);
    }

    public function createIngredient($ingredientName)
    {
        return $this->ingredientRepository->createIngredient($ingredientName);
    }

    public function addIngredient($cocktailId, $ingredientName, $quantity, $unitId)
    {
        return $this->ingredientRepository->addIngredient($cocktailId, $ingredientName, $quantity, $unitId);
    }

    public function getIngredientsByTags()
    {
        $ingredientsGrouped = $this->ingredientRepository->getIngredientsGroupedByTags();
        return $ingredientsGrouped;
    }

    // Fetch Uncategorized Ingredients
    public function fetchUncategorizedIngredients($uncategorizedTag = 'Uncategorized')
    {
        return $this->ingredientRepository->fetchUncategorizedIngredients($uncategorizedTag);
    }
    
    public function assignTagToIngredient($ingredientId, $tagId)
    {
        if (!$this->tagRepository->doesTagExist($tagId)) {
            throw new Exception("Invalid tag ID: $tagId");
        }
        $this->ingredientRepository->assignTag($ingredientId, $tagId);
    }

    public function processQuantities($quantities)
    {
        $parsedQuantities = [];
        foreach ($quantities as $quantity) {
            $parsedQuantities[] = $this->convertFractionToDecimal($quantity); // Call the local method
        }
        return $parsedQuantities;
    }

    private function convertFractionToDecimal($input)
    {
        if (strpos($input, '/') !== false) {
            $parts = explode('/', $input);
            if (count($parts) == 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
                return $parts[0] / $parts[1]; // Convert to decimal
            }
        }
        return floatval($input); // Return as float if not a fraction
    }

    public function convertDecimalToFraction($decimal, $tolerance = 1e-6)
    {
        // Check if the input is a whole number
        if (is_numeric($decimal) && floor($decimal) == $decimal) {
            return (string)intval($decimal);
        }

        // Separate the whole number part
        $wholeNumber = floor($decimal);
        $fractionalPart = $decimal - $wholeNumber;

        // If there is no fractional part, return the whole number
        if ($fractionalPart == 0) {
            return (string)$wholeNumber;
        }

        // Convert fractional part to fraction
        $numerator = 1;
        $denominator = 1;
        $difference = abs($numerator / $denominator - $fractionalPart);

        while ($difference > $tolerance) {
            if ($numerator / $denominator < $fractionalPart) {
                $numerator++;
            } else {
                $denominator++;
                $numerator = round($fractionalPart * $denominator);
            }
            $difference = abs($numerator / $denominator - $fractionalPart);
        }

        // Simplify the fraction
        $gcd = $this->greatestCommonDivisor($numerator, $denominator);
        $numerator /= $gcd;
        $denominator /= $gcd;

        // Combine whole number and fractional part
        if ($wholeNumber > 0) {
            return "{$wholeNumber} & {$numerator}/{$denominator}";
        }

        // If no whole number, return just the fraction
        return "{$numerator}/{$denominator}";
    }

    private function greatestCommonDivisor($a, $b)
    {
        return $b == 0 ? $a : $this->greatestCommonDivisor($b, $a % $b);
    }

    public function getIngredientsByCocktailId($cocktailId)
    {
        return $this->ingredientRepository->fetchIngredientsForCocktail($cocktailId); // Call public method
    }

    public function getAllUnits()
    {
        return $this->ingredientRepository->getAllUnits();
    }

    public function clearIngredientsByCocktailId($cocktailId)
    {
        return $this->ingredientRepository->clearIngredientsByCocktailId($cocktailId);
    }

    public function deleteIngredient($ingredientId)
    {
        return $this->ingredientRepository->deleteIngredient($ingredientId);
    }
    public function doesIngredientExist($ingredientId)
    {
        return $this->ingredientRepository->doesIngredientExist($ingredientId);
    }
    public function assignTag($ingredientId, $tagId)
    {
        return $this->ingredientRepository->assignTag($ingredientId, $tagId);
    }
}
