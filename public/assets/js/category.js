document.addEventListener('DOMContentLoaded', function () {
    const categoryLinks = document.querySelectorAll('.category-sidebar a');

    categoryLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const categoryName = this.getAttribute('href').split('/').pop();

            fetch(`/category/${categoryName}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Identify as AJAX
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.content) {
                    // Update the cocktail list
                    document.querySelector('.wrapper').innerHTML = data.content;

                    // Update the URL without refreshing the page
                    history.pushState(null, '', `/category/${categoryName}`);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
