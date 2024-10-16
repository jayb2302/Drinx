    
<h1><?= $isEditing ? 'Edit' : 'Add' ?> Cocktail Recipe</h1>

<form action="/cocktails/update/<?= $cocktail->getCocktailId() ?>" method="post" enctype="multipart/form-data">        <div class="recipeHeader">     
         <!-- Title -->
         <label for="title">Title</label>
         <input type="text" name="title" id="title" value="<?= $isEditing ? htmlspecialchars($cocktail->getTitle()) : '' ?>" required>
         
         <!-- Description -->
         <label for="description">Description</label>
         <textarea name="description" id="description" required><?= $isEditing ? htmlspecialchars($cocktail->getDescription()) : '' ?></textarea>
    </div>

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
        <?php if ($isEditing): ?>
            <?php foreach ($steps as $i => $step): ?>
                <div class="step-input">
                    <label for="step<?= $i + 1 ?>">Step <?= $i + 1 ?>:</label>
                    <textarea name="steps[]" id="step<?= $i + 1 ?>" required><?= htmlspecialchars($step['instruction']) ?></textarea>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- New step input -->
        <div class="step-input">
            <label for="newStep">Add New Step:</label>
            <textarea name="newStep" id="newStep" placeholder="Enter a new step here..."></textarea>
        </div>
    </div>

    <!-- Ingredients -->
    <h3>Ingredients</h3>
    <div id="ingredientsContainer">
        <?php if ($isEditing): ?>
            <?php foreach ($ingredients as $i => $ingredient): ?>
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
        <?php endif; ?>
        <!-- New ingredient input -->
        <div class="ingredient-input">
            <label for="newIngredient">Add New Ingredient:</label>
            <input type="text" name="newIngredient" id="newIngredient" placeholder="Enter a new ingredient here...">
            <label for="newQuantity">Quantity:</label>
            <input type="number" name="newQuantity" id="newQuantity" placeholder="Quantity...">
            <label for="newUnit">Unit:</label>
            <select name="newUnit" id="newUnit">
                <?php foreach ($units as $unit): ?>
                    <option value="<?= $unit['unit_id'] ?>"><?= htmlspecialchars($unit['unit_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <button type="submit">Submit</button>
</form>