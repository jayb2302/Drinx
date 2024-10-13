<?php 
require_once __DIR__ . '/../../config/database.php'; // Ensure this is included for DB connection
require_once __DIR__ . '/../../repositories/CocktailRepository.php'; 
require_once __DIR__ . '/../../repositories/CategoryRepository.php';
require_once __DIR__ . '/../../repositories/IngredientRepository.php';
require_once __DIR__ . '/../../repositories/StepRepository.php';
require_once __DIR__ . '/../../services/CocktailService.php';

// Get the database connection
$db = Database::getConnection();

// Instantiate repositories
$cocktailRepository = new CocktailRepository($db);
$categoryRepository = new CategoryRepository($db);
$ingredientRepository = new IngredientRepository($db);
$stepRepository = new StepRepository($db);

// Instantiate CocktailService with required repositories
$cocktailService = new CocktailService(
    $cocktailRepository,
    $categoryRepository,
    $ingredientRepository,
    $stepRepository
);

// Here, $cocktailId is provided directly from the route, not through $_GET
$cocktailId = intval($cocktailId); // Make sure to sanitize

error_log("Viewing cocktail ID: " . $cocktailId); // Log the cocktail ID

$cocktail = $cocktailService->getCocktailById($cocktailId);

// Check if cocktail exists
if ($cocktail) {
    ?>
    <h1><?= htmlspecialchars($cocktail->getTitle()) ?></h1> <!-- Using getter -->
    <p><?= htmlspecialchars($cocktail->getDescription()) ?></p> <!-- Using getter -->
    <img src="<?= htmlspecialchars($cocktail->getImage()) ?>" alt="<?= htmlspecialchars($cocktail->getTitle()) ?>"> <!-- Using getter -->
    

    <h2>Ingredients</h2>
    <ul>
    <?php
    // Fetch and display ingredients
    $ingredients = $cocktailService->getCocktailIngredients($cocktailId);
    if (!empty($ingredients)) {
        foreach ($ingredients as $ingredient) {
            echo '<li>' . htmlspecialchars($ingredient['ingredient_name']) . ': ' . htmlspecialchars($ingredient['quantity']) . ' ' . htmlspecialchars($ingredient['unit_name']) . '</li>';
        }
    } else {
        echo '<li>No ingredients found for this cocktail.</li>';
    }
    ?>
</ul>

    <h2>Preparation Steps</h2>
    <ol>
        <?php
        // Fetch and display steps
        $steps = $cocktailService->getCocktailSteps($cocktailId);
        if (!empty($steps)) {
            foreach ($steps as $step) {
                echo '<li>' . htmlspecialchars($step['instruction']) . '</li>';
            }
        } else {
            echo '<li>No preparation steps found for this cocktail.</li>';
        }
        ?>
    </ol>

    <a href="/cocktails/edit/<?= $cocktail->getCocktailId() ?>">Edit Cocktail</a> <!-- Using getter -->
    <a href="/cocktails">Back to Cocktails</a>
    <?php
} else {
    // If cocktail not found
    echo "<p>Cocktail not found.</p>";
}
?>