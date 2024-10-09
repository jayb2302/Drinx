<?php
require_once __DIR__ . '/../../services/CocktailService.php';

$cocktailService = new CocktailService();

if (isset($_GET['id'])) {
    $cocktailId = intval($_GET['id']); // Make sure to sanitize input
    $cocktail = $cocktailService->getCocktailById($cocktailId);

    if ($cocktail) {
        ?>
        <h1><?= htmlspecialchars($cocktail['title']) ?></h1>
        <p><?= htmlspecialchars($cocktail['description']) ?></p>
        <img src="<?= htmlspecialchars($cocktail['image']) ?>" alt="<?= htmlspecialchars($cocktail['title']) ?>">
        <?php
    } else {
        echo "<p>Cocktail not found.</p>";
    }
} else {
    echo "<p>Invalid cocktail ID.</p>";
}
?>