<div class="form-container">
    <h1><?= $isEditing ? 'Edit' : 'Add' ?> Cocktail Recipe</h1>
    <form action="/cocktails/<?= $isEditing ? 'update/' . $cocktail->getCocktailId() : 'store' ?>" method="post" enctype="multipart/form-data">
        <div class="recipeHeader">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?= $isEditing ? ($cocktail->getTitle()) : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" required><?= $isEditing ? htmlspecialchars($cocktail->getDescription()) : '' ?></textarea>
            </div>
        </div>
        <?php if (AuthController::isAdmin()): ?>
            <div class="form-group">
                <label for="isSticky">Set as Sticky</label>
                <input type="checkbox" name="isSticky" id="isSticky" value="1" <?= $isEditing && $cocktail->isSticky() ? 'checked' : '' ?>>
            </div>
        <?php endif; ?>
        <label for="image">Image</label>
        <input type="file" name="image" id="image" accept="image/*" <?= $isEditing ? '' : 'required' ?>>
        <?php if ($isEditing && $cocktail->getImage()): ?>
            <p>Current Image:</p>
            <img src="/uploads/cocktails/<?= htmlspecialchars($cocktail->getImage()) ?>" alt="Current Image" style="width:100px;">
        <?php endif; ?>

        <label for="category_id">Category</label>
        <select name="category_id" id="category_id" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category['category_id']) ?>" <?= ($isEditing && $cocktail->getCategoryId() == $category['category_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <h3>Ingredients</h3>
        <div id="ingredientsContainer">
            <?php if ($isEditing): ?>
                <?php foreach ($ingredients as $i => $ingredient): ?>
                    <div class="ingredient-input">
                        <input type="text" name="ingredients[]" id="ingredient"
                            value="<?= isset($ingredient) ? htmlspecialchars($ingredient->getName()) : '' ?>"
                            list="ingredientList" required>


                        <!-- Datalist for suggestions -->
                        <datalist id="ingredientList">
                            <?php foreach ($ingredients as $ingredient): ?>
                                <option value="<?= htmlspecialchars($ingredient->getName()); ?>"></option>
                            <?php endforeach; ?>
                        </datalist>

                        <label for="quantity<?= $i + 1 ?>">Quantity:</label>
                        <input type="number" name="quantities[]" id="quantity<?= $i + 1 ?>" value="<?= htmlspecialchars($ingredient->getQuantity()) ?>" required>

                        <label for="unit<?= $i + 1 ?>">Unit:</label>
                        <select name="units[]" id="unit<?= $i + 1 ?>" required>
                            <?php foreach ($units as $unit): ?>
                                <option value="<?= $unit['unit_id'] ?>" <?= $unit['unit_id'] == $ingredient->getUnitId() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($unit['unit_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="ingredient-input">
                    <label for="ingredient1">Ingredient 1:</label>
                    <input type="text" name="ingredients[]" id="ingredient1" required>

                    <label for="quantity1">Quantity:</label>
                    <input type="number" name="quantities[]" id="quantity1" required>

                    <label for="unit1">Unit:</label>
                    <select name="units[]" id="unit1" required>
                        <?php foreach ($units as $unit): ?>
                            <option value="<?= $unit['unit_id'] ?>"><?= htmlspecialchars($unit['unit_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
        </div>

        <input type="hidden" id="unitOptions" value='<?php
                                                        $options = [];
                                                        foreach ($units as $unit) {
                                                            $options[] = [
                                                                'id' => $unit['unit_id'],
                                                                'name' => htmlspecialchars($unit['unit_name'])
                                                            ];
                                                        }
                                                        echo json_encode($options);
                                                        ?>'>

        <button type="button" id="addIngredientButton">Add New Ingredient</button>

        <h3>Preparation Steps</h3>
        <div id="stepsContainer">
            <?php if (!empty($steps)): ?>
                <?php foreach ($steps as $i => $step): ?>
                    <div class="step-input">
                        <div class="form-group">
                            <label for="step<?= $i + 1 ?>">Step <?= $i + 1 ?>:</label>
                            <textarea name="steps[]" id="step<?= $i + 1 ?>" required><?= htmlspecialchars($step->getInstruction()) ?></textarea>
                        </div>
                        <button class="delete-step-button" data-step-id="<?= htmlspecialchars($step->getStepId()) ?>">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="step-input">
                    <div class="form-group">
                        <label for="step1">Step 1:</label>
                        <textarea name="steps[]" id="step1" required></textarea>
                    </div>
                    <button class="delete-step-button" data-step-id="1">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <button type="button" id="addStepButton">Add New Step</button>

        <label for="difficulty_id">Difficulty</label>
        <select name="difficulty_id" id="difficulty_id" required>
            <option value="">Select Difficulty</option>
            <?php foreach ($difficulties as $difficulty): ?>
                <option value="<?= htmlspecialchars($difficulty['difficulty_id']); ?>"
                    <?= ($isEditing && $cocktail->getDifficultyId() == $difficulty['difficulty_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($difficulty['difficulty_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Submit</button>
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