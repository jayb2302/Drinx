///// Cocktail
export function initializeCocktail() {
    // Toggle edit form visibility
    document.getElementById('editCocktailButton')?.addEventListener('click', () => {
        const editForm = document.getElementById('editFormContainer');
        if (editForm) {
            editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
        }
    });

    // Delete step functionality
    document.querySelectorAll('.delete-step-button').forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault();
            const stepId = button.getAttribute('data-step-id');

            if (confirm('Do you really want to delete this step?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/cocktails/update/${cocktailId}`;
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_steps[]';
                input.value = stepId;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Add new ingredient functionality
    const ingredientsContainer = document.getElementById('ingredientsContainer');
    const addIngredientButton = document.getElementById('addIngredientButton');

    if (ingredientsContainer && addIngredientButton) {
        let ingredientCount = ingredientsContainer.querySelectorAll('.ingredient-input').length;

        addIngredientButton.addEventListener('click', () => {
            ingredientCount++;
            const newIngredientDiv = `
                <div class="ingredient-input" id="ingredientGroup${ingredientCount}">
                    <label for="ingredient${ingredientCount}">${ingredientCount}:</label>
                    <input type="text" name="ingredients[]" id="ingredient${ingredientCount}" required>
                    
                    <label for="quantity${ingredientCount}">Quantity:</label>
                    <input type="number" name="quantities[]" id="quantity${ingredientCount}" required>
                    
                    <label for="unit${ingredientCount}">Unit:</label>
                    <select name="units[]" id="unit${ingredientCount}" required>
                        ${getUnitOptions()} <!-- Populate unit options dynamically -->
                    </select>
                    
                    <button type="button" class="remove-ingredient-button" data-ingredient-id="${ingredientCount}">Remove</button>
                </div>
            `;

            ingredientsContainer.insertAdjacentHTML('beforeend', newIngredientDiv);
        });
    }

    // Add new step functionality
    const stepsContainer = document.getElementById('stepsContainer');
    const addStepButton = document.getElementById('addStepButton');

    if (stepsContainer && addStepButton) {
        let stepCount = stepsContainer.querySelectorAll('.step-input').length;

        addStepButton.addEventListener('click', () => {
            stepCount++;
            const newStepDiv = `
                <div class="step-input">
                    <div class="form-group">
                        <label for="step${stepCount}">Step ${stepCount}:</label>
                        <textarea name="steps[]" id="step${stepCount}" required></textarea>
                    </div>
                    <button class="delete-step-button" data-step-id="${stepCount}">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            `;

            stepsContainer.insertAdjacentHTML('beforeend', newStepDiv);
        });
    }

    // Function to get unit options dynamically
    function getUnitOptions() {
        const units = JSON.parse(document.getElementById('unitOptions')?.value || '[]');
        return units.map(unit => `<option value="${unit.id}">${unit.name}</option>`).join('');
    }
};
