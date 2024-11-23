<div class="admin-container">
<h1 class="admin-control-tile">Manage Ingredients</h1>

<!-- Button to open the Add New Ingredient Modal -->
<button id="openAddIngredientButton">Add Ingredient</button>

<!-- Add New Ingredient Modal -->
<div id="addIngredientDialog" title="Add Ingredient" style="display: none;">
    <form id="addIngredientForm">
        <label for="ingredientNameInput">Ingredient Name:</label>
        <input type="text" id="ingredientNameInput" name="ingredient_name" required />
    </form>
</div>  

<!-- Ingredient Management Section -->
<div id="ingredientManagement">
    <h2>Uncategorized Ingredients</h2>
    <!-- This will be dynamically populated by JS -->
    <ul id="uncategorizedIngredients" class="ingredient-card">
        <!-- <li data-ingredient-id="23">
                <span class="ingredient-name">Soda Water</span>
                <div class="ingredient-buttons">
                    <button class="assignTagButton">🏷️</button>
                    <button class="editIngredientButton">🖊️</button>
                    <button class="deleteIngredientButton">🗑️</button>
                </div>
        </li> -->
    </ul>
</div>

<!-- Tag Assignment Dialog -->
<div id="assignTagDialog" title="Assign Tag" style="display: none;">
    <div id="assignTagCard">

        <form id="assignTagForm">
        <input type="hidden" id="ingredientId" name="ingredient_id">            
            <!-- Display Ingredient Name here -->
            <p><strong>Ingredient: </strong><span id="ingredientName"></span></p>
            <label for="tag">Select Tag:</label>
            <select id="tag" name="tag_id"></select>
        </form>
    </div>
</div>
</div>