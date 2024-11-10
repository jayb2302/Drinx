function debounce(func, delay) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
}
$(document).ready(function() {
    $('#searchInput').on('input', function() {
        var query = $(this).val();

        // Only send a request if the query length is greater than or equal to 3
        if (query.length >= 3) {
            performSearch(query);
        } else {
            clearSearchResults();
        }
    });
});

function performSearch(query) {
    $.ajax({
        url: '/search',  // Ensure this is the correct endpoint
        type: 'GET',
        data: { query: query },
        success: handleSearchSuccess,
        error: handleSearchError
    });
}

function handleSearchSuccess(data) {
    console.log(data); // Log the response to see its structure
    
    // Clear previous results
    $('#searchResults').empty();

    // Check and display user suggestions
    if (data && data.users && data.users.length > 0) {
        displayUserSuggestions(data.users);
    }

    // Check and display cocktail suggestions
    if (data && data.cocktails && data.cocktails.length > 0) {
        displayCocktailSuggestions(data.cocktails);
    }

    // Show results if any suggestions are found
    if ($('#searchResults').children().length > 0) {
        $('#searchResults').show();
    } else {
        $('#searchResults').hide(); // Hide if no results
    }
}

function handleSearchError(jqXHR, textStatus, errorThrown) {
    console.error("Search request failed:", textStatus, errorThrown);
}

function displayUserSuggestions(users) {
    $('#searchResults').empty(); // Clear previous results

    users.forEach(user => {
        const profilePicture = user.profile_picture 
            ? `/uploads/users/${encodeURIComponent(user.profile_picture)}`
            : '/uploads/users/user-default.svg'; // Ensure this matches the correct path for the default image

        $('#searchResults').append(`
            <div class="user-suggestion">
                <a href="/profile/${encodeURIComponent(user.username)}">
                    <img src="${profilePicture}" alt="${user.username}'s profile picture" 
                         style="width: 40px; height: 40px; border-radius: 50%;"/>
                    ${user.username}
                </a>
            </div>
        `);
    });
}

function displayCocktailSuggestions(cocktails) {
    $('#searchResults').empty(); // Clear previous results
    cocktails.forEach(function(cocktail) {
        // Construct the image path
        let imagePath = cocktail.image ? `/uploads/cocktails/${cocktail.image}` : '/uploads/cocktails/default-image.webp';

        // Generate the URL-friendly title
        let urlTitle = encodeURIComponent(cocktail.title.replace(/\s+/g, '+')); // Replace spaces with '+' for URL encoding

        $('#searchResults').append(`
            <div>
                <a href="/cocktails/${cocktail.cocktail_id}-${urlTitle}">
                    <img src="${imagePath}" alt="${cocktail.title} image" style="width: 40px; height: 40px;"/>
                    ${cocktail.title}
                </a>
            </div>
        `);
    });

    // Show results if any suggestions are found
    if ($('#searchResults').children().length > 0) {
        $('#searchResults').show();
    } else {
        $('#searchResults').hide(); // Hide if no results
    }
}

function clearSearchResults() {
    $('#searchResults').empty().hide();
}