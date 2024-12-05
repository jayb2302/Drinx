export function initializeComments() {
    const commentsSection = document.querySelector('.commentsSection');

    if (!commentsSection) {
        console.error("Comments section not found.");
        return;
    }
    // Clear existing event listeners to avoid duplicates
    const newCommentsSection = commentsSection.cloneNode(true);
    commentsSection.replaceWith(newCommentsSection);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Top-level comment submission
    const commentForm = newCommentsSection.querySelector('#TopLevelCommentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(commentForm);

            // Add CSRF token to the form data
            formData.append('csrf_token', csrfToken);

            try {
                const response = await fetch(commentForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json', // Expect JSON response from the server
                    },
                });

                // Validate the response
                const contentType = response.headers.get('Content-Type');
                if (!response.ok || !contentType || !contentType.includes('application/json')) {
                    const rawText = await response.text();
                    console.error('Unexpected Response:', rawText);
                    alert('Failed to add comment. Please try again.');
                    return;
                }

                const data = await response.json();
                if (data.success) {
                    newCommentsSection.innerHTML = data.html; // Replace comments section with updated HTML
                    document.dispatchEvent(new Event('Drinx.DOMUpdated')); // Trigger any reinitialization if needed
                } else {
                    alert(data.error || 'Failed to add comment.');
                }
            } catch (error) {
                console.error('Unexpected Error:', error);
                alert('An unexpected error occurred. Please try again.');
            }
        });
    }
    // Event delegation for dynamically added elements
    newCommentsSection.addEventListener('click', async (event) => {
        const target = event.target;

        // Handle delete comment/reply
        if (target.matches('.delete') && target.textContent.includes('🗑️')) {
            event.preventDefault();
            const form = target.closest('form');
            if (!form) {
                console.error("Delete form not found.");
                return;
            }

            const confirmed = confirm('Are you sure you want to delete this?');
            if (!confirmed) return;

            const formData = new FormData(form);
            formData.append('csrf_token', csrfToken);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' },
                });

                const data = await response.json();
                if (data.success) {
                    newCommentsSection.outerHTML = data.html;
                    document.dispatchEvent(new Event('Drinx.DOMUpdated'));
                } else {
                    alert(data.error || 'Failed to delete comment.');
                }
            } catch (error) {
                console.error('Error deleting comment:', error);
            }
        }

        // Handle reply button toggle
        if (target.matches('.replyButton')) {
            const replyForm = newCommentsSection.querySelector(`#replyForm-${target.dataset.commentId}`);
            if (replyForm) {
                replyForm.classList.toggle('hidden');
            }
        }

        // Handle dots menu toggle
        if (target.matches('.dotsButton')) {
            const menu = target.nextElementSibling;
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }
    });

    // Handle reply submission
    newCommentsSection.addEventListener('submit', async (event) => {
        if (event.target.matches('.replyForm form')) {
            event.preventDefault();
            const formData = new FormData(event.target);

            formData.append('csrf_token', csrfToken);

            try {
                const response = await fetch(event.target.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' },
                });

                const data = await response.json();
                if (data.success) {
                    newCommentsSection.outerHTML = data.html;
                    document.dispatchEvent(new Event('Drinx.DOMUpdated'));
                } else {
                    alert(data.error || 'Failed to post reply.');
                }
            } catch (error) {
                console.error('Error posting reply:', error);
            }
        }
    });

    // Handle edit comment submission
    newCommentsSection.addEventListener('submit', async (event) => {
        if (event.target.matches('.editCommentForm')) {
            event.preventDefault();
            const formData = new FormData(event.target);

            formData.append('csrf_token', csrfToken);
            try {
                const response = await fetch(event.target.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' },
                });

                if (!response.ok) {
                    console.error("Edit request failed with status:", response.status);
                    alert('Failed to edit comment. Please try again.');
                    return;
                }

                const data = await response.json();
                if (data.success) {
                    newCommentsSection.outerHTML = data.html;
                    document.dispatchEvent(new Event('Drinx.DOMUpdated'));
                } else {
                    alert(data.error || 'Failed to edit comment.');
                }
            } catch (error) {
                console.error('Error editing comment:', error);
            }
        }
    });

    // Handle toggling edit form
    newCommentsSection.addEventListener('click', (event) => {
        if (event.target.matches('.editCommentButton')) {
            const commentId = event.target.dataset.commentId;
            const editForm = newCommentsSection.querySelector(`#editForm-${commentId}`);
            if (editForm) {
                editForm.classList.toggle('hidden');
            } else {
                console.error(`Edit form with ID editForm-${commentId} not found.`);
            }
        }
    });

    // Handle cancel button in edit form
    newCommentsSection.addEventListener('click', (event) => {
        if (event.target.matches('.editCommentForm button[type="button"]')) {
            const editForm = event.target.closest('.editCommentForm');
            if (editForm) {
                editForm.classList.add('hidden'); // Hide the edit form

                // Hide the dots menu when canceling the edit
                const dotsMenu = editForm.closest('.commentBox').querySelector('.dotsMenu .menu');
                if (dotsMenu) {
                    dotsMenu.classList.add('hidden');
                }
            } else {
                console.error('Edit form not found.');
            }
        }
    });
    // Add a global listener for DOM updates
    document.addEventListener('Drinx.DOMUpdated', () => {
        initializeComments();
    });
}