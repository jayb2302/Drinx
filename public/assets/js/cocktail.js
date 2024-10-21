$(document).ready(function() {
    // Get the cocktail ID from PHP
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
});