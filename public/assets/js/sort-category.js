document.addEventListener('DOMContentLoaded', function () {
    const state = {
        currentCategory: '',
        currentSort: 'recent',
    };

    // Parse the current URL to set the initial state
    const urlPathParts = window.location.pathname.split('/').filter(Boolean); // Split and remove empty parts
    console.log("URL Path Parts:", urlPathParts);

    if (urlPathParts[0] === 'category' && urlPathParts.length >= 2) {
        state.currentCategory = urlPathParts[1]; // e.g., 'fruity-cocktails'
        state.currentSort = urlPathParts[2] || 'recent'; // Default to 'recent' if no sort option
    } else if (urlPathParts.length === 1) {
        state.currentSort = urlPathParts[0]; // e.g., 'popular' or 'hot'
    }

    console.log("Initialized State:", state);

    function updateURL() {
        const basePath = state.currentCategory ? `/category/${state.currentCategory}` : '';
        const newPath = state.currentCategory
            ? `${basePath}/${state.currentSort}`
            : `/${state.currentSort}`;
        console.log("New Path:", newPath);
        history.pushState({ category: state.currentCategory, sort: state.currentSort }, '', newPath);
    }

    function fetchCocktails() {
        const url = state.currentCategory
            ? `/category/${state.currentCategory}/${state.currentSort}`
            : `/${state.currentSort}`;
        console.log("Fetching URL:", url);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then((response) => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then((data) => {
                if (data.content) {
                    document.querySelector('.wrapper').innerHTML = data.content;
                    updateSortIndicator();
                }
            })
            .catch((error) => console.error('Error fetching cocktails:', error));
    }

    function updateSortIndicator() {
        document.querySelectorAll('.sort-options a').forEach((option) => {
            const sortOption = option.getAttribute('href').split('/').pop();
            option.classList.toggle('active', sortOption === state.currentSort);
        });
    }

    document.querySelectorAll('.category-sidebar a').forEach((link) => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const hrefParts = this.getAttribute('href').split('/').filter(Boolean); // Remove empty parts
            state.currentCategory = hrefParts[1] || ''; // Extract category
            state.currentSort = hrefParts[2] || 'recent'; // Extract sort
            console.log("State After Category Click:", state);
            updateURL();
            fetchCocktails();
        });
    });

    document.querySelectorAll('.sort-options a').forEach((option) => {
        option.addEventListener('click', function (event) {
            event.preventDefault();
            const hrefParts = this.getAttribute('href').split('/').filter(Boolean);
            state.currentSort = hrefParts.pop(); // Extract the sort option
            console.log("State After Sorting Click:", state);
            updateURL();
            fetchCocktails();
        });
    });

    window.onpopstate = function (event) {
        if (event.state) {
            state.currentCategory = event.state.category || '';
            state.currentSort = event.state.sort || 'recent';
            console.log("State After Popstate:", state);
            fetchCocktails();
        }
    };

    // Fetch initial cocktails based on the URL
    fetchCocktails();
});
