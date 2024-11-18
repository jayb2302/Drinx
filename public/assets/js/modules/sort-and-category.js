export function initializeSortAndCategories() {
    const state = { currentCategory: '', currentSort: 'recent' };

    const urlPathParts = window.location.pathname.split('/').filter(Boolean);
    if (urlPathParts[0] === 'category' && urlPathParts.length >= 2) {
        state.currentCategory = urlPathParts[1];
        state.currentSort = urlPathParts[2] || 'recent';
    } else if (urlPathParts.length === 1) {
        state.currentSort = urlPathParts[0];
    }

    function updateURL() {
        const basePath = state.currentCategory ? `/category/${state.currentCategory}` : '';
        const newPath = state.currentCategory
            ? `${basePath}/${state.currentSort}`
            : `/${state.currentSort}`;
        history.pushState({ category: state.currentCategory, sort: state.currentSort }, '', newPath);
    }

    function fetchCocktails() {
        const url = state.currentCategory
            ? `/category/${state.currentCategory}/${state.currentSort}`
            : `/${state.currentSort}`;
        console.log("Fetching URL:", url);
    
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then((response) => response.json())
            .then((data) => {
                document.querySelector('.wrapper').innerHTML = data.content;
                initializeLikes(); // Reinitialize event listeners for like buttons
            })
            .catch((error) => console.error('Error fetching cocktails:', error));
    }
    

    document.querySelectorAll('.category-sidebar a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const hrefParts = this.getAttribute('href').split('/').filter(Boolean);
            state.currentCategory = hrefParts[1] || '';
            state.currentSort = hrefParts[2] || 'recent';
            updateURL();
            fetchCocktails();
        });
    });

    document.querySelectorAll('.sort-options a').forEach(option => {
        option.addEventListener('click', function (e) {
            e.preventDefault();
            const hrefParts = this.getAttribute('href').split('/').filter(Boolean);
            state.currentSort = hrefParts.pop();
            updateURL();
            fetchCocktails();
        });
    });

    window.onpopstate = function (event) {
        if (event.state) {
            state.currentCategory = event.state.category || '';
            state.currentSort = event.state.sort || 'recent';
            fetchCocktails();
        }
    };
}
