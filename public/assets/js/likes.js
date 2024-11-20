$(document).ready(function(event) {
    function showLoginPopup() {
        // Show login modal
        document.getElementById('loginModal').style.display = 'block';
    }
    $('.like-button').on('click', function() {
        const likeButton = $(this);
        const cocktailId = likeButton.data('cocktail-id'); // Get cocktail ID from button data
        const isLiked = likeButton.data('liked') === 'true'; // Check if already liked
         // If the user is not logged in, show the login popup and stop the like process
         if (!isLoggedIn()) {
            event.preventDefault();  
            showLoginPopup();  
            return;  
        }
        // Determine the action URL
        const actionUrl = `/cocktails/${cocktailId}/toggle-like`;
        
        // Send the AJAX request
        $.ajax({
            url: actionUrl,
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                console.log("Response from server:", response); // Debugging line
                if (response.success) {
                    // Update the button state based on the action
                    likeButton.data('liked', response.action === 'like'); // Toggle the liked status based on the response
                    likeButton.find('.like-icon').html(response.action === 'like' ? '❤️' : '🤍'); // Change icon based on the action
                    
                    // Update the like count displayed for this specific cocktail
                    likeButton.closest('.like-section').find('.like-count').text(`${response.likeCount}`);
                } else {
                    alert(response.message || 'An error occurred while updating like status.');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert('Failed to update like status. Please try again.');
            }
        });
    });
    // Function to check if the user is logged in (can be adjusted based on your app's session management)
    function isLoggedIn() {
        return $('#userId').val() !== '';  // Assuming you store the logged-in user's ID in a hidden input field
    }

    // Function to show the login popup
    function showLoginPopup() {
        // Display the login modal or popup
        document.getElementById('loginModal').style.display = 'block';
    }
});