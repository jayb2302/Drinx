document.addEventListener('DOMContentLoaded', function() {
    // Handle dots menu toggling
    document.querySelectorAll('.dotsButton').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent click from bubbling up
            const menu = button.nextElementSibling;
            menu.classList.toggle('active');
        });
    });

    // Close the menu if clicked outside
    document.addEventListener('click', function(event) {
        if (!event.target.matches('.dots-button')) {
            const menus = document.querySelectorAll('.menu');
            menus.forEach(menu => {
                menu.classList.remove('active');
            });
        }
    });

    // Handle reply button functionality
    const replyButtons = document.querySelectorAll('.reply-button');
    replyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.classList.toggle('hidden'); // Toggle visibility of reply form
        });
    });
});