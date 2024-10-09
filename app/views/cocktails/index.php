<?php
require_once __DIR__ . '/../../services/CocktailService.php';
require_once __DIR__ . '/../layout/header.php'; // Correct the path by adding a leading slash
$cocktailService = new CocktailService();
$cocktails = $cocktailService->getAllCocktails();
?>

<h1>Cocktails</h1>
<a href="/cocktails/add.php">Add New Cocktail</a>

<?php if (!empty($cocktails)): ?>
    <ul>
        <?php foreach ($cocktails as $cocktail): ?>
            <li>
                <a href="/cocktails/view.php?id=<?= htmlspecialchars($cocktail['cocktail_id']) ?>">
                    <?= htmlspecialchars($cocktail['title']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No cocktails available.</p>
<?php endif; ?>