<?php 
// Include header
require_once __DIR__ . '/layout/header.php';

// Include the necessary repository classes
require_once __DIR__ . '/../repositories/CocktailRepository.php';  
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';

// Create database connection (make sure this is properly defined)
$db = Database::getConnection();

// Instantiate the repositories
$cocktailRepository = new CocktailRepository($db);
$categoryRepository = new CategoryRepository($db);
$ingredientRepository = new IngredientRepository($db);
$stepRepository = new StepRepository($db);

// Instantiate the CocktailService with the repositories
$cocktailService = new CocktailService(
    $cocktailRepository,
    $categoryRepository,
    $ingredientRepository,
    $stepRepository
);

$cocktails = $cocktailService->getAllCocktails(); // Fetch all cocktails
?>

<!-- home.php -->
<h1>Welcome to Drinx</h1>

<!-- Links to show forms -->
<nav>
    <a href="?action=login">Login</a>
    <a href="?action=register">Register</a>
</nav>

<!-- Logic to Include Forms -->
<?php 
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'login') {
        require_once __DIR__ . '/auth/login.php'; 
    } elseif ($_GET['action'] === 'register') {
        require_once __DIR__ . '/auth/register.php'; 
    }
}
?>

<!-- Display Cocktails -->
<h2>All Cocktails</h2>
<?php if (!empty($cocktails)): ?>
    <ul>
        <?php foreach ($cocktails as $cocktail): ?>
            <li>
                <a href="/cocktails/<?= htmlspecialchars($cocktail->getCocktailId()) ?>"> <!-- Use getter for cocktail ID -->
                    <?= htmlspecialchars($cocktail->getTitle()) ?> <!-- Use getter for title -->
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No cocktails available.</p>
<?php endif; ?>

<!-- Include footer -->
<?php require_once __DIR__ . '/layout/footer.php'; ?>