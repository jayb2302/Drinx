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

    function updateSortIndicator() {
        // Clear the "active" class from all sort options
        document.querySelectorAll('.sort-options a').forEach(option => {
            option.classList.remove('active');
        });

        // Add the "active" class to the current sort option
        const currentSortOption = document.querySelector(`.sort-options a[href$="/${state.currentSort}"]`);
        if (currentSortOption) {
            currentSortOption.classList.add('active');
        } else {
            // console.warn('Sort indicator not found for:', state.currentSort);
        }
    }

    function fetchCocktails() {
        const url = state.currentCategory
            ? `/category/${state.currentCategory}/${state.currentSort}`
            : `/${state.currentSort}`;
    
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error fetching cocktails: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const wrapper = document.querySelector('.wrapper');
                if (wrapper) {
                    wrapper.innerHTML = data.content;
    
                    // console.log('Cocktails content updated, reinitializing functionality...');
                        wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });

                    // Update active sort option
                    updateSortIndicator();
    
                    // Dispatch a DOMUpdated event for all modules to reinitialize
                    document.dispatchEvent(new Event('Drinx.DOMUpdated'));
                } else {
                    console.error('Wrapper element not found!');
                }
            })
            .catch(error => console.error('Error fetching cocktails:', error));
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

    // Initial update of the sort indicator on page load
    updateSortIndicator();
}
