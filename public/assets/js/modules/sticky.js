///// Sticky
export function initializeSticky() {
    function updateStickyCocktail() {
        fetch('/admin/sticky-cocktail', {
            headers: { 'Content-Type': 'application/json' },
        })
            .then(response => response.json())
            .then(data => {
                const stickyContainer = document.querySelector('.stickyContainer');
                if (stickyContainer && data.success) {
                    stickyContainer.innerHTML = `
                        <div class="stickyCard">
                            <h2>📌 Sticky Cocktail</h2>
                            <div class="stickyMediaWrapper">
                                    <img src="${data.image}" alt="${data.title}" class="cocktail-image">
                            </div>
                            <div class="stickyContent">
                                <a href="/cocktails/${data.id}-${encodeURIComponent(data.title)}">
                                    <h3 class="cocktail-title">
                                        ${data.title}
                                    </h3>
                                </a>
                                <p class="cocktail-description">${data.description}</p>
                            </div>
                        </div>
                    `;
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
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cocktail_id: cocktailId }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelectorAll('.set-sticky').forEach(btn => {
                                btn.classList.remove('active');
                                btn.textContent = '📌';
                            });

                            if (data.is_sticky) {
                                this.classList.add('active');
                                this.textContent = '📌';
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