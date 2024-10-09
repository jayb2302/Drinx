$(document).ready(function() {
    // Handle cocktail like/unlike actions
    $('[id^=like-btn-]').on('click', function() {
        var buttonId = $(this).attr('id');  // Get the button ID (e.g., like-btn-1)
        var cocktailId = buttonId.split('-')[2];  // Extract the cocktail ID (e.g., 1)
        
        $.ajax({
            url: '/Drinx/like_cocktail.php', // Add base URL if applicable
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },  // Set AJAX request header
            data: { cocktail_id: cocktailId },
            success: function(response) {
                var data = JSON.parse(response);
                
                if (data.status === 'liked') {
                    // Change the heart icon to filled and red
                    $('#heart-icon-' + cocktailId).removeClass('far').addClass('fas text-red-500');
                } else if (data.status === 'unliked') {
                    // Change the heart icon to empty
                    $('#heart-icon-' + cocktailId).removeClass('fas text-red-500').addClass('far');
                }

                // Update the like count
                $('#like-count-' + cocktailId).text(data.like_count + ' likes');
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: " + error);
                alert("Something went wrong. Please try again.");  // Display a user-friendly message
            }
        });
    });

    // Debouncing function to limit search AJAX requests
    function debounce(func, delay) {
        let debounceTimer;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(context, args), delay);
        };
    }

    // Handle search suggestions
    $('#search-input').on('input', debounce(function() {
        var query = $(this).val();

        if (query.length > 2) {  // Only search when the input is more than 2 characters
            $.ajax({
                url: '/Drinx/login/search_suggestions.php',  // Add base URL if needed
                type: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },  // Set AJAX request header
                data: { query: query },
                success: function(data) {
                    $('#search-results').html(data).removeClass('hidden');  // Show the results
                },
                error: function(xhr, status, error) {
                    console.error("Search error: " + error);
                }
            });
        } else {
            $('#search-results').addClass('hidden');  // Hide the results if query is too short
        }
    }, 300));  // 300ms delay to debounce search input

    // Hide search results when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('#search-input, #search-results').length) {
            $('#search-results').addClass('hidden');
        }
    });
});