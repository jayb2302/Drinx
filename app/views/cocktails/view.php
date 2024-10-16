<?php
include __DIR__ . '/../layout/header.php'; // Include header

// Ensure all variables ($cocktail, $ingredients, $steps, $category, $tags) are passed correctly
$categoryName = $category ? htmlspecialchars($category['category_name']) : 'Unknown';
$tagsArray = is_array($tags) ? array_column($tags, 'name') : [];
error_log("In view.php: Is Editing? " . ($isEditing ? 'true' : 'false'));

// Check if we are in edit mode
if ($isEditing) {
    // Load the form for editing the cocktail
    include __DIR__ . '/form.php';
} else {
    ?>
   
    <div class="cocktailGrid">
        <div class="cocktail-actions">
            <button>
                <a href="/cocktails" class="">Back to Cocktails</a>
            </button>
        </div>

        <!-- Debugging: Check if we are in edit mode -->
        <?php
        echo $isEditing ? 'Editing Mode' : 'View Mode'; // Display the mode for debugging
        ?>

        <!-- Viewing Mode: Show the cocktail details -->
        <div class="recipeContainer">
            <!-- Category and Tags -->
            <div class="orderby">
                <p class="tag font-semibold"><?= $categoryName ?></p>
                <p class="tag text-lg font-semibold"><?= htmlspecialchars(implode(', ', $tagsArray)) ?></p>
            </div>

            <!-- Display the cocktail image -->
            <div class="cocktailImage">
                <?php $imageSrc = $cocktail->getImage(); ?>
                <?php if ($imageSrc): ?>
                    <img src="<?= htmlspecialchars($imageSrc) ?>" alt="<?= htmlspecialchars($cocktail->getTitle()) ?>" class="mb-4">
                <?php else: ?>
                    <p>No image available for this cocktail.</p>
                <?php endif; ?>
            </div>

            <!-- Display the cocktail title -->
            <h1 class="title"><?= htmlspecialchars($cocktail->getTitle()) ?></h1>
        </div>

        <div class="recipeHeader">
            <!-- Display the cocktail description -->
            <p><?= htmlspecialchars($cocktail->getDescription()) ?></p>

            <!-- Ingredients section -->
            <h2 class="text-2xl font-semibold mt-6 mb-2">Ingredients</h2>
            <ul class="list-disc ml-5">
                <?php foreach ($ingredients as $ingredient): ?>
                    <li><?= htmlspecialchars($ingredient['ingredient_name']) ?>: <?= htmlspecialchars($ingredient['quantity']) ?> <?= htmlspecialchars($ingredient['unit_name']) ?></li>
                <?php endforeach; ?>
            </ul>

            <!-- Steps section -->
            <h2 class="text-2xl font-semibold mt-6 mb-2">Preparation Steps</h2>
            <ol class="list-decimal ml-5">
                <?php foreach ($steps as $step): ?>
                    <li><?= htmlspecialchars($step['instruction']) ?></li>
                <?php endforeach; ?>
            </ol>

        </div> <!-- End recipeHeader -->
    </div> <!-- End cocktailGrid -->

    <button>
        <a href="/cocktails/<?= $cocktail->getCocktailId() ?>-<?= urlencode($cocktail->getTitle()) ?>/edit" class="text-blue-500 hover:underline">Edit Cocktail</a>
    </button>
<?php
} // End of if-else for edit mode
?>
