<div class="form-container">
    <h1><?= $isEditing ? 'Edit' : 'Add' ?> Cocktail Recipe</h1>
    <form action="/cocktails/<?= $isEditing ? 'update/' . $cocktail->getCocktailId() : 'add' ?>" method="post" enctype="multipart/form-data">
        <div class="recipeHeader">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?= $isEditing ? htmlspecialchars($cocktail->getTitle()) : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" required><?= $isEditing ? htmlspecialchars($cocktail->getDescription()) : '' ?></textarea>
            </div>
        </div>

        <label for="image">Image</label>
        <input type="file" name="image" id="image" <?= $isEditing ? '' : 'required' ?>>
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
                        <label for="ingredient<?= $i + 1 ?>">Ingredient <?= $i + 1 ?>:</label>
                        <input type="text" name="ingredients[]" id="ingredient<?= $i + 1 ?>" value="<?= htmlspecialchars($ingredient->getName()) ?>" required>

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
            <?php endif; ?>
        </div>

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
                        <!-- Trash icon button to delete this step -->
                        <button class="delete-step-button" data-step-id="<?= htmlspecialchars($step->getStepId()) ?>">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No preparation steps available for this cocktail.</p>
            <?php endif; ?>
        </div>

        <button type="button" id="addStepButton">Add New Step</button>

        <label for="difficulty_id">Difficulty</label>
        <select name="difficulty_id" id="difficulty_id" required>
            <option value="">Select Difficulty</option>
            <option value="1" <?= $isEditing && $cocktail->getDifficultyId() == 1 ? 'selected' : '' ?>>Easy</option>
            <option value="2" <?= $isEditing && $cocktail->getDifficultyId() == 2 ? 'selected' : '' ?>>Medium</option>
            <option value="3" <?= $isEditing && $cocktail->getDifficultyId() == 3 ? 'selected' : '' ?>>Hard</option>
        </select>
        <button type="submit">Submit</button>
    </form>
</div>