///// Search
export function initializeSearch() {
    function debounce(func, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    function performSearch(query) {
        query = query.trim();
        if (query.length < 3) {
            $('#searchResults').hide().empty();
            return;
        }
        $.ajax({
            url: '/search',
            type: 'GET',
            data: { query },
            success: handleSearchSuccess,
            error: () => console.error('Search request failed.'),
        });
    }

    function handleSearchSuccess(data) {
        const resultsContainer = $('#searchResults');
        resultsContainer.empty();

        // Check if users or cocktails exist in the response
        if (data?.users && data.users.length > 0) {
            displayUserSuggestions(data.users, resultsContainer);
        }
        if (data?.cocktails && data.cocktails.length > 0) {
            displayCocktailSuggestions(data.cocktails, resultsContainer);
        }

        resultsContainer.toggle(resultsContainer.children().length > 0);
    }

    function displayUserSuggestions(users, container) {
        users.forEach(user => {
            const profilePicture = user.profile_picture
                ? `/uploads/users/${encodeURIComponent(user.profile_picture)}`
                : '/uploads/users/user-default.svg';
            container.append(`
                <a href="/profile/${encodeURIComponent(user.username)}">
                    <div class="user-suggestion">
                        <img src="${profilePicture}" alt="${user.username}'s profile picture" style="width: 40px; height: 40px;" class="profile-pic"/>
                        ${user.username}
                    </div>
                </a>
            `);
        });
    }

    function displayCocktailSuggestions(cocktails, container) {
        cocktails.forEach(cocktail => {
            const imagePath = cocktail.image ? `/uploads/cocktails/${cocktail.image}` : '/uploads/cocktails/default-image.webp';
            const urlTitle = encodeURIComponent(cocktail.title.replace(/\s+/g, '+'));
            container.append(`
                <a href="/cocktails/${cocktail.cocktail_id}-${urlTitle}">
                    <div class="cocktail-suggestion">
                        <img src="${imagePath}" alt="${cocktail.title}" style="width: 40px; height: 40px;"/>
                        ${cocktail.title}
                    </div>
                </a>
            `);
        });
    }

    $('#searchInput').on('input', debounce(function () {
        const query = $(this).val();
        if (query.length >= 3) performSearch(query);
        else $('#searchResults').hide().empty();
    }, 300));
    
    $(document).on('keydown', function (event) {
        if (event.key === 'Escape') {
            $('#searchResults').hide(); 
        }
    });
}
