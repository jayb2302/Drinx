///// Sticky
export function initializeSticky() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    function updateStickyCocktail() {
        fetch('/admin/sticky-cocktail', {
            method: 'GET',  
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken  
            },
        })
            .then(response => response.json())
            .then(data => {
                const stickyContainer = document.querySelector('.stickyContainer');
                if (stickyContainer && data.success) {
                    stickyContainer.innerHTML = `
                        <div class="stickyCard">
                            <h2><i class="fa-solid fa-paperclip"></i> Sticky Cocktail</h2>
                            <div class="stickyMediaWrapper">
                                <img src="/uploads/cocktails/${data.image}" alt="${data.title}" class="cocktail-image">
                                <div class="stickyContent">
                                    <a href="/cocktails/${data.id}-${encodeURIComponent(data.title)}">
                                        <h3 class="cocktail-title">${data.title}</h3>
                                    </a>
                                    <p class="cocktail-description">${data.description}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    console.log('Sticky cocktail updated:', data);
                } else {
                    console.warn('No sticky cocktail found or returned:', data);
                }
            })
            .catch(error => console.error('Error updating sticky cocktail:', error));
    }

    function attachStickyListeners() {
        document.querySelectorAll('.set-sticky').forEach(button => {
            button.addEventListener('click', function () {
                const cocktailId = this.dataset.cocktailId;

                fetch('/admin/toggle-sticky', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken // Pass CSRF token in the header for POST request
                    },
                    body: JSON.stringify({
                        cocktail_id: cocktailId,
                        csrf_token: csrfToken // Pass CSRF token in the body too
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reset all sticky buttons
                            document.querySelectorAll('.set-sticky').forEach(btn => {
                                btn.classList.remove('active');
                                btn.innerHTML = '<i class="fa-solid fa-paperclip"></i>';
                            });

                            // Update the clicked button
                            if (data.is_sticky) {
                                this.classList.add('active');
                                this.innerHTML = '<i class="fa-solid fa-paperclip"></i>';
                            }

                            updateStickyCocktail();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error toggling sticky cocktail:', error));
            });
        });
    }

    attachStickyListeners();

    // Ensure sticky functionality reinitializes on DOM updates
    document.addEventListener('Drinx.DOMUpdated', attachStickyListeners);
}