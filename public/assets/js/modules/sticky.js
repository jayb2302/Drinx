export function initializeSticky() {
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
                        } else {
                            alert(data.message);
                        }
                    });
            });
        });
    }

    attachStickyListeners();

    document.addEventListener('Drinx.DOMUpdated', attachStickyListeners);
}
