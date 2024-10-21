<?php
include __DIR__ . '/../layout/header.php';
include_once __DIR__ . '/../../helpers/helpers.php';

if (!isset($isEditing)) {
    $isEditing = false;
}

$cocktail = $cocktail ?? new Cocktail();
$steps = $steps ?? [];
$ingredients = $ingredients ?? [];
$categories = $categories ?? [];
$units = $units ?? [];

?>
<?php if (isset($_SESSION['errors'])): ?>
    <div class="error-messages">
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); // Clear errors after displaying 
    ?>
<?php endif; ?>

<div class="cocktail-actions">
    <button>
        <a href="/" class="">Back to Cocktails</a>
    </button>
</div>

<div class="wrapper">
    <!-- Cocktail Details -->
    <div class="recipeContainer">
        <div class="orderby">
            <p class="tag font-semibold"><?= htmlspecialchars($category['category_name'] ?? 'Unknown') ?></p>
            <p class="tag text-lg font-semibold"><?= htmlspecialchars(implode(', ', array_column($tags ?? [], 'name'))) ?></p>
        </div>

        <div class="cocktailImage">
            <?php if ($cocktail->getImage()): ?>
                <img src="/uploads/cocktails/<?= htmlspecialchars($cocktail->getImage()) ?>" alt="<?= htmlspecialchars($cocktail->getTitle()) ?>" class="mb-4 cocktailImage">
            <?php else: ?>
                <p>No image available for this cocktail.</p>
            <?php endif; ?>
        </div>
        <h1 class="title"><?= htmlspecialchars($cocktail->getTitle() ?? 'Untitled') ?></h1>

        <p><?= htmlspecialchars($cocktail->getDescription() ?? 'No description available') ?></p>

        <h2 class="text-2xl font-semibold mt-6 mb-2">Ingredients</h2>
        <ul class="list-disc ml-5">
            <?php if (!empty($ingredients)): ?>
                <?php foreach ($ingredients as $ingredient): ?>
                    <li>
                        <?= htmlspecialchars($ingredient->getName()) ?>:
                        <?= htmlspecialchars($ingredient->getQuantity()) ?> <?= htmlspecialchars($ingredient->getUnitName()) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No ingredients listed for this cocktail.</li>
            <?php endif; ?>
        </ul>

        <h2 class="text-2xl font-semibold mt-6 mb-2">Preparation Steps</h2>
        <ol class="list-decimal ml-5">
            <?php if (!empty($steps)): ?>
                <?php foreach ($steps as $step): ?>
                    <li><?= htmlspecialchars($step->getInstruction() ?? 'No instruction provided') ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No preparation steps available for this cocktail.</li>
            <?php endif; ?>
        </ol>
    </div>
    <?php
var_dump($cocktailId); // Check the value of cocktailId
?>
    <!-- Edit Button -->
    <?php if (isset($_SESSION['user']['id']) && ($_SESSION['user']['id'] === $cocktail->getUserId() || AuthController::isAdmin())): ?>
        <button id="editCocktailButton" class="text-blue-500 hover:underline">
            Edit Cocktail
        </button>
    <?php endif; ?>

    <!-- Hidden Form (inline editing) -->
    <div id="editFormContainer" style="display: none;">
        <?php
        // Ensure $isEditing is true and include the form
        $isEditing = true;
        include __DIR__ . '/form.php';
        ?>
    </div>

</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>