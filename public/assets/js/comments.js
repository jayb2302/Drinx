document.addEventListener('DOMContentLoaded', function() {
    // Handle dots menu toggling
    document.querySelectorAll('.dotsButton').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const menu = button.nextElementSibling;
            menu.classList.toggle('active');
        });
    });

    // Close all dots menus if clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dotsButton') && !event.target.closest('.menu')) {
            document.querySelectorAll('.menu.active').forEach(menu => {
                menu.classList.remove('active');
            });
        }
    });

    // Toggle edit form for comments
    document.querySelectorAll('.editCommentButton').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const editForm = document.getElementById(`editForm-${commentId}`);
            editForm.classList.toggle('hidden');
        });
    });

    // Handle edit form submission via AJAX
    document.querySelectorAll('.editForm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const commentId = this.getAttribute('data-comment-id');
            const formData = new FormData(this);

            fetch(`/comments/${commentId}/update`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`#comment-${commentId}`).innerText = data.commentText;
                    document.getElementById(`editForm-${commentId}`).classList.add('hidden');
                } else {
                    alert('Failed to update comment');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});