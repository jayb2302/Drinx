$(document).ready(function() {
    $('.like-button').on('click', function() {
        const likeButton = $(this);
        const cocktailId = likeButton.data('cocktail-id'); // Get cocktail ID from button data
        const isLiked = likeButton.data('liked') === 'true'; // Check if already liked
        
        // Determine the action URL
        const actionUrl = `/cocktails/${cocktailId}/toggle-like`;
        
        // Send the AJAX request
        $.ajax({
            url: actionUrl,
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json', // Expect JSON response
            success: function(response) {
                console.log("Response from server:", response); // Debugging line
                if (response.success) {
                    // Update the button state based on the action
                    likeButton.data('liked', response.action === 'like'); // Toggle the liked status based on the response
                    likeButton.find('.like-icon').html(response.action === 'like' ? '♥️' : '🤍'); // Change icon based on the action
                } else {
                    alert(response.error || 'An error occurred while updating like status.');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert('Failed to update like status. Please try again.');
            }
        });
    });
});