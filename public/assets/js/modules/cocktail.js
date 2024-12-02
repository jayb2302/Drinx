export function initializeCocktail() {
    // Toggle edit form visibility
    const editCocktailButton = document.getElementById("editCocktailButton");
    if (editCocktailButton) {
        editCocktailButton.addEventListener("click", () => {
            const editForm = document.getElementById("editFormContainer");
            if (editForm) {
                editForm.style.display = editForm.style.display === "none" ? "block" : "none";
            }
        });
    }
    // Ingredients Section
    const ingredientsContainer = document.getElementById('ingredientsContainer');
    const addIngredientButton = document.getElementById('addIngredientButton');

    if (ingredientsContainer && addIngredientButton) {
        let ingredientCount = ingredientsContainer.querySelectorAll('.ingredient-input').length;

        // Add new ingredient functionality
        addIngredientButton.addEventListener('click', () => {
            ingredientCount++;
            const newIngredientDiv = `
                <div class="ingredient-input" id="ingredientGroup${ingredientCount}">
                     <div class="ingredient-name-container">
                        <input type="text" name="ingredients[]" id="ingredient${ingredientCount}" required>
                    </div>
                    <div class="quantity-unit-container">
                        
                        <input type="number" name="quantities[]" id="quantity${ingredientCount}" step="any" required>                    
                        
                        <select name="units[]" id="unit${ingredientCount}" required>
                            ${getUnitOptions()} <!-- Populate unit options dynamically -->
                        </select>
                    </div>
                    <button type="button" class="delete-ingredient-button" data-ingredient-id="${ingredientCount}">❌</button>
                </div>
            `;

            ingredientsContainer.insertAdjacentHTML('beforeend', newIngredientDiv);
        });

        // Delete ingredient functionality
        ingredientsContainer.addEventListener('click', (event) => {
            if (event.target.classList.contains('delete-ingredient-button')) {
                const ingredientGroup = event.target.closest('.ingredient-input');
                if (ingredientGroup) {
                    ingredientGroup.remove();
                    renumberIngredients();
                    updateDeleteButtonVisibility();
                }
            }
        });
        function renumberIngredients() {
            ingredientsContainer.querySelectorAll('.ingredient-input').forEach((ingredient, index) => {
                const ingredientNumber = index + 1;
        
                const label = ingredient.querySelector('label[for^="ingredient"]');
                const input = ingredient.querySelector('input[name="ingredients[]"]');
                const quantity = ingredient.querySelector('input[name="quantities[]"]');
                const unit = ingredient.querySelector('select[name="units[]"]');
        
                // Update labels and IDs
                if (label) label.textContent = `Ingredient ${ingredientNumber}:`;
                if (input) input.id = `ingredient${ingredientNumber}`;
                if (quantity) quantity.id = `quantity${ingredientNumber}`;
                if (unit) unit.id = `unit${ingredientNumber}`;
            });
        }
        function updateDeleteButtonVisibility() {
            const deleteButtons = ingredientsContainer.querySelectorAll('.remove-ingredient-button');
            deleteButtons.forEach(button => (button.style.display = 'inline')); // Show all delete buttons
        
            if (deleteButtons.length === 1) {
                deleteButtons[0].style.display = 'none'; // Hide delete button if only one ingredient
            }
        }
        updateDeleteButtonVisibility();
    }
    // Steps Section
    const stepsContainer = document.getElementById("stepsContainer");
    const addStepButton = document.getElementById("addStepButton");

    if (stepsContainer && addStepButton) {
        let stepCount = stepsContainer.querySelectorAll(".step-input").length;

        // Add new step functionality
        addStepButton.addEventListener("click", () => {
            stepCount++;
            const newStepDiv = `
             <div class="step-input" data-step-index="${stepCount}">
                 <div class="form-group">
                     <label for="step${stepCount}">Step ${stepCount}:</label>
                     <textarea name="steps[]" id="step${stepCount}" required></textarea>
                 </div>
                 <button type="button" class="delete-step-button" data-step-id="${stepCount}">❌</button>
             </div>
         `;
            stepsContainer.insertAdjacentHTML("beforeend", newStepDiv);
        });

        // Delete step functionality
        stepsContainer.addEventListener("click", (event) => {
            if (event.target.classList.contains("delete-step-button")) {
                const deleteButton = event.target;
                const stepInput = deleteButton.closest(".step-input");

                if (stepInput) {
                    stepInput.remove();

                    // Re-number remaining steps
                    stepCount = 0;
                    stepsContainer.querySelectorAll(".step-input").forEach((step, index) => {
                        stepCount = index + 1;
                        const label = step.querySelector("label");
                        const textarea = step.querySelector("textarea");
                        const deleteBtn = step.querySelector(".delete-step-button");

                        // Update step numbers
                        if (label) {
                            label.textContent = `Step ${stepCount}:`;
                            label.setAttribute("for", `step${stepCount}`);
                        }
                        if (textarea) {
                            textarea.id = `step${stepCount}`;
                        }
                        if (deleteBtn) {
                            deleteBtn.setAttribute("data-step-id", stepCount);
                            if (stepCount === 1) {
                                deleteBtn.remove(); // Remove delete button for the first step
                            }
                        }
                    });
                }
            }
        });
    }
    // Function to get unit options dynamically
    function getUnitOptions() {
        const units = JSON.parse(document.getElementById('unitOptions')?.value || '[]');
        return units.map(unit => `<option value="${unit.id}">${unit.name}</option>`).join('');
    }
};
