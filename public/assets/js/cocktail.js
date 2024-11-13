$(document).ready(function() {
    // Get the cocktail ID from a hidden input field or similar
    const cocktailId = $('#cocktailId').val(); // Ensure this input exists and has the correct value

    // Toggle edit form visibility
    $('#editCocktailButton').on('click', function() {
        const editForm = $('#editFormContainer');
        console.log('Edit button clicked'); // Log when the button is clicked

        // Toggle form visibility
        editForm.toggle(); // Simplified visibility toggle

        // Log the visibility state
        console.log('Form display state: ', editForm.is(':visible') ? 'Visible' : 'Hidden');
    });

    // Delete step functionality
    $('.delete-step-button').on('click', function(event) {
        event.preventDefault();
        const stepId = $(this).data('step-id');

        if (confirm("Do you really want to delete this step?")) {
            $('<form>', {
                'method': 'POST',
                'action': '/cocktails/update/' + cocktailId // Make sure cocktailId is defined correctly
            })
            .append($('<input>', {
                'type': 'hidden',
                'name': 'delete_steps[]',
                'value': stepId // Include the step ID in the request
            }))
            .appendTo('body')
            .submit();
        }
    });

    // Add new ingredient functionality
    let ingredientCount = $('#ingredientsContainer .ingredient-input').length;

    $('#addIngredientButton').on('click', function() {
        ingredientCount++; // Increment the count

        // Create new ingredient input fields
        const newIngredientDiv = `
            <div class="ingredient-input" id="ingredientGroup${ingredientCount}">
                <label for="ingredient${ingredientCount}">Ingredient ${ingredientCount}:</label>
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
        
        // Append the new ingredient fields to the ingredients container
        $('#ingredientsContainer').append(newIngredientDiv);
        console.log("Added new ingredient input fields"); // Log when a new ingredient is added
    });

    // Add new step functionality
    let stepCount = $('#stepsContainer .step-input').length;

    $('#addStepButton').on('click', function() {
        stepCount++; // Increment the count

        // Create new step input fields
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

        // Append the new step fields to the steps container
        $('#stepsContainer').append(newStepDiv);
        console.log("Added new step input fields"); // Log when a new step is added
    });

    // Function to get the unit options for the select dropdown
    function getUnitOptions() {
        let options = '';
        const units = JSON.parse($('#unitOptions').val()); // Get the unit options from the hidden input
        units.forEach(unit => {
            options += `<option value="${unit.id}">${unit.name}</option>`;
        });
        return options;
    }
  
});