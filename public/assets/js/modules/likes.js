///// Likes
export function initializeLikes() {
    document.addEventListener('click', function (event) {
        const likeButton = event.target.closest('.like-button');
        if (!likeButton) return;
        // Check if the button is disabled (not logged in)
        if (likeButton.hasAttribute('data-disabled')) {
            alert("Please log in to like this cocktail.");
            return; // Prevent further execution
        }

        const cocktailId = likeButton.getAttribute('data-cocktail-id');
        const actionUrl = `/cocktails/${cocktailId}/toggle-like`;

        fetch(actionUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({}),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Update the button state
                    likeButton.setAttribute('data-liked', data.action === 'like');
                    const likeIcon = likeButton.querySelector('.like-icon i');

                    if (data.action === 'like') {
                        likeIcon.classList.remove('fa-regular');
                        likeIcon.classList.add('fa-solid');
                    } else {
                        likeIcon.classList.remove('fa-solid');
                        likeIcon.classList.add('fa-regular');
                    }

                    // Locate the like count element
                    let likeCount = likeButton.querySelector('.like-count');
                    if (likeCount) {
                        likeCount.textContent = data.likeCount;
                    } else {
                        console.warn('.like-count not found for like button:', likeButton);
                    }

                    // Dispatch DOMUpdated event to notify other modules of the changes
                    document.dispatchEvent(new Event('Likes.DOMUpdated'));
                } else {
                    console.error('Error toggling like:', data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });
};