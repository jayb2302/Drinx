function debounce(func, delay) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
}

$(document).ready(function () {
    $('#searchInput').on('input', debounce(function () {
        const query = $(this).val();

        // Only send a request if the query length is greater than or equal to 3
        if (query.length >= 3) {
            performSearch(query);
        } else {
            clearSearchResults();
        }
    }, 300)); // Add debounce delay
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
    if (data?.users?.length > 0) {
        displayUserSuggestions(data.users);
    }

    // Check and display cocktail suggestions
    if (data?.cocktails?.length > 0) {
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
    users.forEach(user => {
        const profilePicture = user.profile_picture
            ? `/uploads/users/${encodeURIComponent(user.profile_picture)}`
            : '/uploads/users/user-default.svg';
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
    cocktails.forEach(cocktail => {
        // Ensure required properties are available
        const cocktailId = cocktail.cocktail_id || null;
        const title = cocktail.title || 'Untitled Cocktail';
        const imagePath = cocktail.image ? `/uploads/cocktails/${cocktail.image}` : '/uploads/cocktails/default-image.webp';

        // Display cocktail suggestion if it has an ID
        if (cocktailId) {
            const urlTitle = encodeURIComponent(title.replace(/\s+/g, '+'));
            $('#searchResults').append(`
                <div class="cocktail-suggestion">
                    <a href="/cocktails/${cocktailId}-${urlTitle}">
                        <img src="${imagePath}" alt="${title} image" style="width: 40px; height: 40px;"/>
                        ${title}
                    </a>
                </div>
            `);
        }
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