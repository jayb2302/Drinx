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

    // Handle Image Preview and Validation for Cocktail Image
const cocktailFileInput = document.getElementById('image');
const cocktailPreview = document.createElement('img');
const cocktailErrorElement = document.createElement('span');
const maxFileSize = 5 * 1024 * 1024; // 5MB
const allowedExtensions = ['jpeg', 'jpg', 'png', 'webp'];

// Configure the preview image
cocktailPreview.id = 'cocktail-image-preview';
cocktailPreview.style.display = 'none'; // Initially hidden
cocktailPreview.style.width = '100px';
cocktailPreview.style.marginTop = '10px';

// Configure the error element
cocktailErrorElement.id = 'cocktail-file-error';
cocktailErrorElement.style.color = 'red';
cocktailErrorElement.style.display = 'none';

// Insert the preview and error message right after the file input
cocktailFileInput?.parentNode.insertBefore(cocktailPreview, cocktailFileInput.nextSibling);
cocktailFileInput?.parentNode.insertBefore(cocktailErrorElement, cocktailPreview.nextSibling);

cocktailFileInput?.addEventListener('change', function (event) {
    const file = event.target.files[0];
    cocktailErrorElement.style.display = 'none'; // Hide error message initially
    cocktailErrorElement.textContent = ''; // Clear any existing error message
    cocktailPreview.style.display = 'none'; // Hide the preview initially

    if (file) {
        // Validate file size
        if (file.size > maxFileSize) {
            cocktailErrorElement.textContent = `The image size exceeds the allowed limit of ${maxFileSize / (1024 * 1024)} MB.`;
            cocktailErrorElement.style.display = 'block';
            cocktailFileInput.value = ''; // Clear the input
            return;
        }

        // Validate file format
        const fileExtension = file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            cocktailErrorElement.textContent = `Invalid file format. Allowed formats are: ${allowedExtensions.join(', ')}.`;
            cocktailErrorElement.style.display = 'block';
            cocktailFileInput.value = ''; // Clear the input
            return;
        }

        // If valid, display the preview
        const reader = new FileReader();
        reader.onload = function (e) {
            cocktailPreview.src = e.target.result;
            cocktailPreview.style.display = 'block'; // Make the preview visible
        };
        reader.readAsDataURL(file);
    }
});

};
