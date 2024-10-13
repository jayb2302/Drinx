<?php
require_once __DIR__ . '/../../services/CocktailService.php';
require_once __DIR__ . '/../../repositories/CocktailRepository.php';
require_once __DIR__ . '/../../repositories/CategoryRepository.php';
require_once __DIR__ . '/../../repositories/StepRepository.php';
require_once __DIR__ . '/../../repositories/IngredientRepository.php';
require_once __DIR__ . '/../../config/database.php'; // For database connection

// Get the database connection
$db = Database::getConnection();

// Create the repository instances
$cocktailRepository = new CocktailRepository($db);
$categoryRepository = new CategoryRepository($db);
$stepRepository = new StepRepository($db);
$ingredientRepository = new IngredientRepository($db);

// Pass the repositories to the CocktailService
$cocktailService = new CocktailService(
    $cocktailRepository,
    $categoryRepository,
    $ingredientRepository,
    $stepRepository
);

// Fetch all cocktails
$cocktails = $cocktailService->getAllCocktails();
include __DIR__ . '/../layout/header.php'; // Include header
?>

<h1>Cocktails</h1>
<a href="/cocktails/add">Add New Cocktail</a>

<?php if (!empty($cocktails)): ?>
    <ul>
        <?php foreach ($cocktails as $cocktail): ?>
            <li>
                <!-- Assuming Cocktail is an object, use getter methods -->
                <a href="/cocktails/<?= htmlspecialchars($cocktail->getCocktailId()) ?>-<?= urlencode($cocktail->getTitle()) ?>">
                    <?= htmlspecialchars($cocktail->getTitle()) ?>
                </a>
                <!-- Link to edit -->
                <a href="/cocktails/edit/<?= htmlspecialchars($cocktail->getCocktailId()) ?>">Edit</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No cocktails available.</p>
<?php endif; ?>