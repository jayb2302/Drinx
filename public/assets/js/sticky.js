document.querySelectorAll('.set-sticky').forEach(button => {
    button.addEventListener('click', function() {
        const cocktailId = this.getAttribute('data-cocktail-id');

        fetch('/admin/toggle-sticky', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ cocktail_id: cocktailId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Clear the 'active' class from all buttons
                document.querySelectorAll('.set-sticky').forEach(btn => {
                    btn.classList.remove('active');
                    btn.textContent = 'Sticky';
                });

                // Update the clicked button to reflect the new sticky state
                if (data.is_sticky) {
                    this.classList.add('active');
                    this.textContent = 'Unstick';
                }

                // Update the sticky cocktail card
                updateStickyCocktail();
            } else {
                alert(data.message); // Show error message if toggle failed
            }
        })
        .catch(error => {
            console.error('Error with fetch operation:', error);
            alert('An error occurred while setting the sticky cocktail.');
        });
    });
});

function updateStickyCocktail() {
    fetch('/admin/sticky-cocktail')
        .then(response => {
            console.log("Fetching sticky cocktail:", response.status); // Debugging line
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update the sticky card content
                document.querySelector('.stickyContainer .cocktail-title').textContent = data.title;
                document.querySelector('.stickyContainer .cocktail-description').textContent = data.description;
                document.querySelector('.stickyContainer .cocktail-image').src = `/uploads/cocktails/${data.image}`;
            } else {
                console.error('No sticky cocktail set.');
            }
        })
        .catch(error => console.error('Error fetching sticky cocktail:', error));
}
