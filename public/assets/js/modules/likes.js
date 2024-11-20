export function initializeLikes() {
    document.addEventListener('click', function (event) {
        const likeButton = event.target.closest('.like-button');
        if (!likeButton) return;

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
                    likeButton.querySelector('.like-icon').textContent =
                        data.action === 'like' ? '❤️' : '🤍';

                    // Locate the like count element
                    let likeCount = null;

                    // Try finding the `.like-count` next to the `.like-icon`
                    const likeIcon = likeButton.querySelector('.like-icon');
                    if (likeIcon) {
                        likeCount = likeIcon.nextElementSibling;
                    }

                    // If not found, try the broader context of the `.like-section`
                    if (!likeCount) {
                        const likeSection = likeButton.closest('.like-section');
                        if (likeSection) {
                            likeCount = likeSection.querySelector('.like-count');
                        }
                    }

                    // Update the like count if found
                    if (likeCount) {
                        likeCount.textContent = data.likeCount;
                    } else {
                        console.warn('.like-count not found for like button:', likeButton);
                    }
                } else {
                    console.error('Error toggling like:', data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });
}