document.addEventListener('DOMContentLoaded', function() {
    const dotsButtons = document.querySelectorAll('.dots-button');

    dotsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const menu = this.nextElementSibling;
            menu.classList.toggle('active'); // Toggle menu visibility
        });
    });

    // Close the menu if clicked outside
    document.addEventListener('click', function(event) {
        if (!event.target.matches('.dots-button')) {
            const menus = document.querySelectorAll('.menu');
            menus.forEach(menu => {
                menu.classList.remove('active'); // Hide other menus
            });
        }
    });
});