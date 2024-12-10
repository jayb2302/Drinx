<div class="form-container">
    <form action="/cocktails/<?= $isEditing ? 'update/' . $cocktail->getCocktailId() : 'store' ?>" method="post" enctype="multipart/form-data">

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

        <h1><?= $isEditing ? 'Edit' : 'Add' ?> Cocktail Recipe</h1>
        <div class="form-group">

            <label for="image">Cocktail Image</label>
            <input type="file" name="image" id="image" accept="image/*" <?= $isEditing ? '' : 'required' ?>>
            <span id="cocktail-file-error" style="color: red; display: none;"></span>
            <?php if ($isEditing && $cocktail->getImage()): ?>
                <span>Current Image:</span>
                <!-- <img src="/uploads/cocktails/<?= htmlspecialchars($cocktail->getImage()) ?>" alt="Current Image" style="width:100px;"> -->
                <img id="cocktail-image-preview"
                    src="<?= $isEditing && $cocktail->getImage() ? '/uploads/cocktails/' . htmlspecialchars($cocktail->getImage()) : ''; ?>"
                    alt="Cocktail Image Preview"
                    style="display: <?= $isEditing && $cocktail->getImage() ? 'block' : 'none'; ?>; width: 100px;">

            <?php endif; ?>

        </div>
        <div class="recipeHeader">
            <div class="form-group">
                <label for="title">Cocktail Name</label>
                <input type="text" name="title" id="title"
                    value="<?= $isEditing ? sanitize($cocktail->getTitle()) : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" maxlength="500" id="description" required> <?= $isEditing && $cocktail->getDescription() ? sanitizeTrim($cocktail->getDescription()) : '' ?> </textarea>
            </div>
        </div>
        <!-- Category and Difficulty -->
        <div class="category-difficulty-container">
            <!-- <label for="category_id">Category</label> -->
            <select name="category_id" id="category_id" required>
                <option value="">Choose Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['category_id']) ?>" <?= ($isEditing && $cocktail->getCategoryId() == $category['category_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <!-- <label for="difficulty_id">Difficulty</label> -->
            <select name="difficulty_id" id="difficulty_id" required>
                <option value="">Set Difficulty</option>
                <?php foreach ($difficulties as $difficulty): ?>
                    <option value="<?= htmlspecialchars($difficulty['difficulty_id']); ?>"
                        <?= ($isEditing && $cocktail->getDifficultyId() == $difficulty['difficulty_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($difficulty['difficulty_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select id="prep_time" name="prep_time" required>
                <option value="">Select Preparation Time</option>
                <option value="<15" <?= $isEditing && $cocktail->getPrepTime() === 10 ? 'selected' : '' ?>>Less than 15 minutes</option>
                <option value="15–30" <?= $isEditing && $cocktail->getPrepTime() === 20 ? 'selected' : '' ?>>15–30 minutes</option>
                <option value="30–60" <?= $isEditing && $cocktail->getPrepTime() === 45 ? 'selected' : '' ?>>30–60 minutes</option>
                <option value=">60" <?= $isEditing && $cocktail->getPrepTime() === 90 ? 'selected' : '' ?>>More than 60 minutes</option>
            </select>
            <?php if ($authController->isAdmin()): ?>
                <div class="sticky-cocktail">
                    <label for="isSticky" class="tooltip" data-tooltip="Mark this cocktail as sticky to feature it prominently.">
                        <i class="fa-solid fa-paperclip"></i>
                    </label>
                    <input type="checkbox" name="isSticky" id="isSticky" value="1" <?= $isEditing && $cocktail->isSticky() ? 'checked' : '' ?>>
                </div>
            <?php endif; ?>
        </div>

        <h3>Ingredients</h3>
        <div id="ingredientsContainer" class="ingredientsContainer">
            <?php if ($isEditing && !empty($ingredients)): ?>
                <?php foreach ($ingredients as $i => $ingredient): ?>
                    <div class="ingredient-input">
                        <div class="ingredient-name-container">
                            <label for="ingredient<?= $i + 1 ?>">Ingredient <?= $i + 1 ?>:</label>
                            <input type="text" name="ingredients[]" id="ingredient<?= $i + 1 ?>"
                                value="<?= htmlspecialchars($ingredient->getName()) ?>" list="ingredientList" required>
                        </div>

                        <div class="quantity-unit-container">
                            <label for="quantity<?= $i + 1 ?>">Quantity:</label>
                            <input type="text" name="quantities[]" id="quantity<?= $i + 1 ?>"
                                value="<?= htmlspecialchars($ingredient->getQuantity()) ?>" placeholder="e.g., 1/2 or 3.5" required>

                            <label for="unit<?= $i + 1 ?>">Unit:</label>
                            <select name="units[]" id="unit<?= $i + 1 ?>" required>
                                <option value="">Select Unit</option>
                                <?php foreach ($units as $unit): ?>
                                    <option value="<?= $unit['unit_id'] ?>"
                                        <?= $unit['unit_id'] == $ingredient->getUnitId() ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($unit['unit_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (count($ingredients) > 1): ?> <!-- Only show delete button if more than one ingredient -->
                            <button type="button" class="delete-ingredient-button">❌</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="ingredient-input">
                    <div class="ingredient-name-container">
                        <!-- <label for="ingredient1">Ingredient 1:</label> -->
                        <input
                            type="text"
                            name="ingredients[]"
                            id="ingredient1"
                            placeholder="Enter ingredient name"
                            list="ingredientList"
                            required>
                    </div>
                    <div class="quantity-unit-container">
                        <!-- <label for="quantity1">Quantity:</label> -->
                        <input type="text" name="quantities[]" id="quantity1" placeholder="Quantity e.g., 1/2 or 3.5" required>
                        <!-- <label for="unit1">Unit:</label> -->
                        <select name="units[]" id="unit1" required>
                            <option value="">Select Unit</option>
                            <?php foreach ($units as $unit): ?>
                                <option value="<?= $unit['unit_id'] ?>"><?= htmlspecialchars($unit['unit_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" id="addIngredientButton">New Ingredient</button>
        <input
            type="hidden"
            id="unitOptions"
            value='<?php
                    $options = [];
                    foreach ($units as $unit) {
                        $options[] = [
                            'id' => $unit['unit_id'],
                            'name' => htmlspecialchars($unit['unit_name'])
                        ];
                    }
                    echo json_encode($options); ?>'>
        <h3>Preparation Steps</h3>
        <div id="stepsContainer" class="stepContainer">
            <?php if (!empty($steps)): ?>
                <?php foreach ($steps as $i => $step): ?>
                    <div class="step-input">
                        <div class="form-group">
                            <label for="step<?= $i + 1 ?>">Step <?= $i + 1 ?>:</label>
                            <textarea name="steps[]" id="step<?= $i + 1 ?>" required><?= htmlspecialchars($step->getInstruction()) ?></textarea>
                        </div>
                        <?php if ($i > 0): ?> <!-- Hide delete button for the first step -->
                            <button type="button" class="delete-step-button" data-step-id="<?= htmlspecialchars($step->getStepId()) ?>">❌</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="step-input">
                    <div class="form-group">
                        <label for="step1">Step 1:</label>
                        <textarea name="steps[]" id="step1" required></textarea>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" id="addStepButton">New Step</button>


        <div class="submit-container">
            <button type="submit">Submit</button>
        </div>
    </form>
</div>

<?php
if (isset($_SESSION['errors'])) {
    echo '<ul class="error-messages">';
    foreach ($_SESSION['errors'] as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '</ul>';
    // Clear the errors after displaying them
    unset($_SESSION['errors']);
}
?>