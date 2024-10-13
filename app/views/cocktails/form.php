<?php
$isEditing = isset($cocktail);  // Check if we're editing an existing cocktail.
$steps = $this->cocktailService->getCocktailSteps($cocktailId);
$ingredients = $this->cocktailService->getCocktailIngredients($cocktailId);
?>

<h1><?= $isEditing ? 'Edit' : 'Add' ?> Cocktail</h1>
<form action="/cocktails/<?= $isEditing ? 'update?id=' . $cocktail->getCocktailId() : 'store' ?>" method="post" enctype="multipart/form-data">
    <!-- Title -->
    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="<?= $isEditing ? htmlspecialchars($cocktail->getTitle()) : '' ?>" required>

    <!-- Description -->
    <label for="description">Description</label>
    <textarea name="description" id="description" required><?= $isEditing ? htmlspecialchars($cocktail->getDescription()) : '' ?></textarea>

    <!-- Image -->
    <label for="image">Image</label>
    <input type="file" name="image" id="image" <?= $isEditing ? '' : 'required' ?>>
    <?php if ($isEditing && $cocktail->getImage()): ?>
        <p>Current Image:</p>
        <img src="<?= htmlspecialchars($cocktail->getImage()) ?>" alt="Current Image" style="width:100px;">
    <?php endif; ?>

    <!-- Category -->
    <label for="category_id">Category</label>
    <select name="category_id" id="category_id" required>
        <option value="">Select Category</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= htmlspecialchars($category['category_id']) ?>" <?= ($isEditing && $cocktail->getCategoryId() == $category['category_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($category['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Steps -->
    <h3>Steps</h3>
    <div id="stepsContainer">
        <?php
        // Display existing steps if editing
        foreach ($steps as $i => $step): ?>
            <div class="step-input">
                <label for="step<?= $i + 1 ?>">Step <?= $i + 1 ?>:</label>
                <textarea name="steps[]" id="step<?= $i + 1 ?>" required><?= htmlspecialchars($step['instruction']) ?></textarea>
            </div>
        <?php endforeach; ?>
        
        <!-- New step input -->
        <div class="step-input">
            <label for="newStep">Add New Step:</label>
            <textarea name="steps[]" id="newStep" placeholder="Enter a new step here..."></textarea>
        </div>
    </div>

    <!-- Ingredients -->
    <h3>Ingredients</h3>
    <div id="ingredientsContainer">
        <?php
        // Display existing ingredients
        foreach ($ingredients as $i => $ingredient): ?>
            <div class="ingredient-input">
                <label for="ingredient<?= $i + 1 ?>">Ingredient <?= $i + 1 ?>:</label>
                <input type="text" name="ingredients[]" id="ingredient<?= $i + 1 ?>" value="<?= htmlspecialchars($ingredient['ingredient_name']) ?>" required>
                <label for="quantity<?= $i + 1 ?>">Quantity:</label>
                <input type="number" name="quantities[]" id="quantity<?= $i + 1 ?>" value="<?= htmlspecialchars($ingredient['quantity']) ?>" required>
                <label for="unit<?= $i + 1 ?>">Unit:</label>
                <select name="units[]" id="unit<?= $i + 1 ?>" required>
                    <?php foreach ($units as $unit): ?>
                        <option value="<?= $unit['unit_id'] ?>" <?= $unit['unit_id'] == $ingredient['unit_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($unit['unit_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endforeach; ?>

        <!-- New ingredient input -->
        <div class="ingredient-input">
            <label for="newIngredient">Add New Ingredient:</label>
            <input type="text" name="ingredients[]" id="newIngredient" placeholder="Enter a new ingredient here...">
            <label for="newQuantity">Quantity:</label>
            <input type="number" name="quantities[]" id="newQuantity" placeholder="Quantity...">
            <label for="newUnit">Unit:</label>
            <select name="units[]" id="newUnit">
                <?php foreach ($units as $unit): ?>
                    <option value="<?= $unit['unit_id'] ?>"><?= htmlspecialchars($unit['unit_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <button type="submit">Submit</button>
</form>