<div class="admin-container">
    <h1 class="admin-control-tile">Manage Ingredients</h1>
    <!-- Search Bar -->
    <!-- <div class="ingredient-search-bar">
        <input
            type="text"
            id="ingredientSearchInput"
            placeholder="Search ingredients..." />
        <div id="ingredientSearchResults" class="search-results"></div>
    </div> -->
    <!-- Button to open the Add New Ingredient Modal -->
    <button id="openAddIngredientButton">Add Ingredient</button>

    <!-- Add New Ingredient Modal -->
    <div id="addIngredientDialog" title="Add Ingredient" style="display: none;">
        <form id="addIngredientForm">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
            <label for="ingredientNameInput">Ingredient Name:</label>
            <input type="text" id="ingredientNameInput" name="ingredient_name" required />
        </form>
    </div>

    <!-- Ingredient Management Section -->
    <div id="ingredientManagement">
        <div class="uncategorized-section">
            <h2 class="section-title">Uncategorized Ingredients</h2>
            <!-- This will be dynamically populated by JS -->
            <ol id="uncategorizedIngredients" class="ingredient-card">
                <!-- <li data-ingredient-id="23">
                <span class="ingredient-name">Soda Water</span>
                <div class="ingredient-buttons">
                    <button class="assignTagButton">🏷️</button>
                    <button class="editIngredientButton">🖊️</button>
                    <button class="deleteIngredientButton">🗑️</button>
                </div>
        </li> -->
            </ol>
        </div>
        <div class="ingredient-section">
            <h2 class="section-title">Categorized Ingredients</h2>
            <?php if (!empty($categorizedIngredients['categorized'])): ?>
                <div class="accordion ingredient-card">
                    <?php foreach ($categorizedIngredients['categorized'] as $tagName => $ingredients): ?>
                        <div class="accordion-item">
                            <button class="accordion-header">
                                <h3><?= htmlspecialchars($tagName); ?> <span class="ingredient-count">(<?= count($ingredients); ?>)</span></h3>
                            </button>
                            <div class="accordion-body">
                                <ul class="ingredient-list">
                                    <?php foreach ($ingredients as $ingredient): ?>
                                        <li data-ingredient-id="<?= htmlspecialchars($ingredient['ingredient_id']); ?>"> <!-- Correctly setting ingredient_id -->
                                            <span class="ingredient-name"><?= htmlspecialchars($ingredient['ingredient_name']); ?></span>
                                            <div class="ingredient-actions">
                                                <button class="editIngredientButton"> <i class="fa-solid fa-pencil"></i> </button>
                                                <button class="deleteIngredientButton"> <i class="fa-solid fa-trash"></i> </button>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No categorized ingredients found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tag Assignment Dialog -->
    <div id="assignTagDialog" title="Assign Tag" style="display: none;">
        <div id="assignTagCard">
            <form id="assignTagForm">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()); ?>" />
                <input type="hidden" id="ingredientId" name="ingredient_id">

                <!-- Display Ingredient Name here -->
                <p><strong>Ingredient: </strong><span id="ingredientName"></span></p>
                <label for="tag">Select Tag:</label>
                <select id="tag" name="tag_id"></select>
            </form>
        </div>
    </div>
</div>