// Main.js for handling all the JS modules

///// Likes
function initializeLikes() {
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


///// Search
export function initializeSearch() {
    function debounce(func, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    function performSearch(query) {
        $.ajax({
            url: '/search',
            type: 'GET',
            data: { query },
            success: handleSearchSuccess,
            error: () => console.error('Search request failed.'),
        });
    }

    function handleSearchSuccess(data) {
        const resultsContainer = $('#searchResults');
        resultsContainer.empty();

        if (data?.users) displayUserSuggestions(data.users, resultsContainer);
        if (data?.cocktails) displayCocktailSuggestions(data.cocktails, resultsContainer);

        resultsContainer.toggle(resultsContainer.children().length > 0);
    }

    function displayUserSuggestions(users, container) {
        users.forEach(user => {
            const profilePicture = user.profile_picture
                ? `/uploads/users/${encodeURIComponent(user.profile_picture)}`
                : '/uploads/users/user-default.svg';
            container.append(`
                <div class="user-suggestion">
                    <a href="/profile/${encodeURIComponent(user.username)}">
                        <img src="${profilePicture}" alt="${user.username}'s profile picture" style="width: 40px; height: 40px;"/>
                        ${user.username}
                    </a>
                </div>
            `);
        });
    }

    function displayCocktailSuggestions(cocktails, container) {
        cocktails.forEach(cocktail => {
            const imagePath = cocktail.image ? `/uploads/cocktails/${cocktail.image}` : '/uploads/cocktails/default-image.webp';
            const urlTitle = encodeURIComponent(cocktail.title.replace(/\s+/g, '+'));
            container.append(`
                <div class="cocktail-suggestion">
                    <a href="/cocktails/${cocktail.cocktail_id}-${urlTitle}">
                        <img src="${imagePath}" alt="${cocktail.title}" style="width: 40px; height: 40px;"/>
                        ${cocktail.title}
                    </a>
                </div>
            `);
        });
    }

    $('#searchInput').on('input', debounce(function () {
        const query = $(this).val();
        if (query.length >= 3) performSearch(query);
        else $('#searchResults').hide().empty();
    }, 300));
}


///// Sticky
function initializeSticky() {
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
                            <h2>📌Sticky Cocktail</h2>
                            <div class="stickyMediaWrapper">
                                <img src="/uploads/cocktails/${data.image}" 
                                     alt="${data.title}" class="cocktail-image">
                            </div>
                            <div class="stickyContent">
                                <h3 class="cocktail-title">${data.title}</h3>
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


///// Random
function initializeRandomCocktail() {
    const cocktailContainer = document.querySelector('.aboutContainer');
    if (!cocktailContainer) return;

    function updateCocktailContent({ title, image, description, id }) {
        cocktailContainer.innerHTML = `
            <h1 class="aboutHeading">Welcome to Drinx,</h1>
            <h3 class="aboutIntro">The cocktail library that’s got social flair!</h3>
            <p class="aboutDescription">Cheers to your next masterpiece — let’s make it one for the books!</p>
            <div class="recipeContainer">
                <div class="recipeCard">
                    <h3>${title}</h3>
                    <div>
                        <img src="/uploads/cocktails/${image}" alt="Random Cocktail Image" class="cocktailImage">
                        <p>${description}</p>
                        <a href="/cocktails/${id}-${encodeURIComponent(title)}" class="viewRecipe">View Recipe</a>
                    </div>
                </div>
                <div class="randomButton">
                    <a href="#" class="randomRecipeButton">Shake It Up!</a>
                </div>
            </div>
        `;
    }

    function fetchRandomCocktail() {
        fetch('/cocktails/random')
            .then(response => response.json())
            .then(data => {
                if (data.title) updateCocktailContent(data);
                else showError('No cocktail data available.');
            })
            .catch(() => showError('Sorry, we couldn’t load a new cocktail right now. Please try again later.'));
    }

    function showError(message) {
        cocktailContainer.innerHTML = `<p>${message}</p>`;
    }

    cocktailContainer.addEventListener('click', event => {
        if (event.target.classList.contains('randomRecipeButton')) {
            event.preventDefault();
            fetchRandomCocktail();
        }
    });
}


///// Sort and Category
function initializeSortAndCategories() {
    const state = { currentCategory: '', currentSort: 'recent' };

    const urlPathParts = window.location.pathname.split('/').filter(Boolean);
    if (urlPathParts[0] === 'category' && urlPathParts.length >= 2) {
        state.currentCategory = urlPathParts[1];
        state.currentSort = urlPathParts[2] || 'recent';
    } else if (urlPathParts.length === 1) {
        state.currentSort = urlPathParts[0];
    }

    function updateURL() {
        const basePath = state.currentCategory ? `/category/${state.currentCategory}` : '';
        const newPath = state.currentCategory
            ? `${basePath}/${state.currentSort}`
            : `/${state.currentSort}`;
        history.pushState({ category: state.currentCategory, sort: state.currentSort }, '', newPath);
    }

    function updateSortIndicator() {
        // Clear the "active" class from all sort options
        document.querySelectorAll('.sort-options a').forEach(option => {
            option.classList.remove('active');
        });

        // Add the "active" class to the current sort option
        const currentSortOption = document.querySelector(`.sort-options a[href$="/${state.currentSort}"]`);
        if (currentSortOption) {
            currentSortOption.classList.add('active');
        } else {
            // console.warn('Sort indicator not found for:', state.currentSort);
        }
    }

    function fetchCocktails() {
        const url = state.currentCategory
            ? `/category/${state.currentCategory}/${state.currentSort}`
            : `/${state.currentSort}`;
    
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error fetching cocktails: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const wrapper = document.querySelector('.wrapper');
                if (wrapper) {
                    wrapper.innerHTML = data.content;
    
                    // console.log('Cocktails content updated, reinitializing functionality...');
    
                    // Update active sort option
                    updateSortIndicator();
    
                    // Dispatch a DOMUpdated event for all modules to reinitialize
                    document.dispatchEvent(new Event('Drinx.DOMUpdated'));
                } else {
                    console.error('Wrapper element not found!');
                }
            })
            .catch(error => console.error('Error fetching cocktails:', error));
    }

    document.querySelectorAll('.category-sidebar a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const hrefParts = this.getAttribute('href').split('/').filter(Boolean);
            state.currentCategory = hrefParts[1] || '';
            state.currentSort = hrefParts[2] || 'recent';
            updateURL();
            fetchCocktails();
        });
    });

    document.querySelectorAll('.sort-options a').forEach(option => {
        option.addEventListener('click', function (e) {
            e.preventDefault();
            const hrefParts = this.getAttribute('href').split('/').filter(Boolean);
            state.currentSort = hrefParts.pop();
            updateURL();
            fetchCocktails();
        });
    });

    window.onpopstate = function (event) {
        if (event.state) {
            state.currentCategory = event.state.category || '';
            state.currentSort = event.state.sort || 'recent';
            fetchCocktails();
        }
    };

    // Initial update of the sort indicator on page load
    updateSortIndicator();
}


///// Comments
export function initializeComments() {
    const commentsSection = document.querySelector('.commentsSection');

    // Clear existing event listeners to avoid duplicates
    const newCommentsSection = commentsSection.cloneNode(true);
    commentsSection.replaceWith(newCommentsSection);

    // Top-level comment submission
    const commentForm = newCommentsSection.querySelector('#TopLevelCommentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(commentForm);

            try {
                const response = await fetch(commentForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' },
                });

                const data = await response.json();
                if (data.success) {
                    newCommentsSection.outerHTML = data.html;
                    document.dispatchEvent(new Event('Drinx.DOMUpdated'));
                } else {
                    alert(data.error || 'Failed to add comment.');
                }
            } catch (error) {
                console.error('Error adding comment:', error);
            }
        });
    }

    // Event delegation for dynamically added elements
    newCommentsSection.addEventListener('click', async (event) => {
        const target = event.target;

        // Handle delete comment/reply
        if (target.matches('.menuItem') && target.textContent.includes('🗑️')) {
            event.preventDefault();
            const form = target.closest('form');
            if (!form) {
                console.error("Delete form not found.");
                return;
            }

            const confirmed = confirm('Are you sure you want to delete this?');
            if (!confirmed) return;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
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


///// Profile
function initializeProfile() {
    document.getElementById('edit-profile-button')?.addEventListener('click', () => {
        const form = document.getElementById('edit-profile-form');
        if (form) {
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    });

    document.getElementById('deleteAccountButton')?.addEventListener('click', () => {
        const section = document.getElementById('deleteConfirmSection');
        if (section) {
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }
    });
}

///// Admin
// function initializeAdmin() {
//     function fetchUsers() {
//         fetch('/searchAllUsers')
//             .then(response => response.json())
//             .then(data => {
//                 const users = data.users || [];
//                 renderUsers(users);
//             })
//             .catch(error => console.error('Error fetching users:', error));
//     }

//     function renderUsers(users) {
//         const userTableBody = document.getElementById('userTableBody');
//         userTableBody.innerHTML = '';
//         users.forEach(user => {
//             const row = document.createElement('tr');
//             row.innerHTML = `
//                 <td>${user.username}</td>
//                 <td>${user.email}</td>
//                 <td>${user.status}</td>
//                 <td>
//                     <form class="update-status-form">
//                         <input type="hidden" name="user_id" value="${user.id}">
//                         <select name="status">${getStatusOptions(user.status)}</select>
//                         <button type="submit">Update</button>
//                     </form>
//                 </td>
//             `;
//             userTableBody.appendChild(row);
//         });

//         attachFormListeners();
//     }

//     function attachFormListeners() {
//         document.querySelectorAll('.update-status-form').forEach(form => {
//             form.addEventListener('submit', function (e) {
//                 e.preventDefault();
//                 const formData = new FormData(this);
//                 fetch('/admin/update-status', {
//                     method: 'POST',
//                     body: formData,
//                 })
//                     .then(response => response.json())
//                     .then(data => {
//                         if (data.success) alert('User status updated');
//                         else alert('Failed to update user status');
//                     });
//             });
//         });
//     }

//     fetchUsers();
// }


///// Cocktail
function initializeCocktail() {
    // Toggle edit form visibility
    document.getElementById('editCocktailButton')?.addEventListener('click', () => {
        const editForm = document.getElementById('editFormContainer');
        if (editForm) {
            editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
        }
    });

    // Delete step functionality
    document.querySelectorAll('.delete-step-button').forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault();
            const stepId = button.getAttribute('data-step-id');

            if (confirm('Do you really want to delete this step?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/cocktails/update/${cocktailId}`;
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_steps[]';
                input.value = stepId;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Add new ingredient functionality
    const ingredientsContainer = document.getElementById('ingredientsContainer');
    const addIngredientButton = document.getElementById('addIngredientButton');

    if (ingredientsContainer && addIngredientButton) {
        let ingredientCount = ingredientsContainer.querySelectorAll('.ingredient-input').length;

        addIngredientButton.addEventListener('click', () => {
            ingredientCount++;
            const newIngredientDiv = `
                <div class="ingredient-input" id="ingredientGroup${ingredientCount}">
                    <label for="ingredient${ingredientCount}">Ingredient ${ingredientCount}:</label>
                    <input type="text" name="ingredients[]" id="ingredient${ingredientCount}" required>
                    
                    <label for="quantity${ingredientCount}">Quantity:</label>
                    <input type="number" name="quantities[]" id="quantity${ingredientCount}" required>
                    
                    <label for="unit${ingredientCount}">Unit:</label>
                    <select name="units[]" id="unit${ingredientCount}" required>
                        ${getUnitOptions()} <!-- Populate unit options dynamically -->
                    </select>
                    
                    <button type="button" class="remove-ingredient-button" data-ingredient-id="${ingredientCount}">Remove</button>
                </div>
            `;

            ingredientsContainer.insertAdjacentHTML('beforeend', newIngredientDiv);
        });
    }

    // Add new step functionality
    const stepsContainer = document.getElementById('stepsContainer');
    const addStepButton = document.getElementById('addStepButton');

    if (stepsContainer && addStepButton) {
        let stepCount = stepsContainer.querySelectorAll('.step-input').length;

        addStepButton.addEventListener('click', () => {
            stepCount++;
            const newStepDiv = `
                <div class="step-input">
                    <div class="form-group">
                        <label for="step${stepCount}">Step ${stepCount}:</label>
                        <textarea name="steps[]" id="step${stepCount}" required></textarea>
                    </div>
                    <button class="delete-step-button" data-step-id="${stepCount}">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            `;

            stepsContainer.insertAdjacentHTML('beforeend', newStepDiv);
        });
    }

    // Function to get unit options dynamically
    function getUnitOptions() {
        const units = JSON.parse(document.getElementById('unitOptions')?.value || '[]');
        return units.map(unit => `<option value="${unit.id}">${unit.name}</option>`).join('');
    }
};





// document.addEventListener('DOMContentLoaded', function () {
//     // Your initialization code here
//     initializeProfile();
//     initializeLikes();
//     initializeSearch();
//     initializeSticky();
//     initializeRandomCocktail();
//     initializeSortAndCategories();
//     initializeComments();
//     initializeAdmin();
//     initializeCocktail();
// });

document.addEventListener('DOMContentLoaded', function () {
    const pageType = window.pageType || document.querySelector('meta[name="page-type"]')?.content;

    if (pageType === 'home') {
        initializeSortAndCategories();
        initializeLikes();
        // initializeComments();
        initializeSticky();
        initializeRandomCocktail();
        initializeSearch();
        initializeCocktail();
    } else if (pageType === 'profile') {
        initializeProfile();
    } else if (pageType === 'admin') {
        initializeAdmin();
    } else if (pageType === 'cocktail') {
        initializeCocktail();
        initializeComments();
        initializeLikes();
    }

    // Common initializations for all pages, if any
});

// // Reinitialize modules on DOM updates
// document.addEventListener('Likes.DOMUpdated', () => {
//     initializeLikes();
// });
// // Reinitialize modules on DOM updates
// document.addEventListener('Search.DOMUpdated', () => {
//     initializeSearch();
// });
// // Reinitialize modules on DOM updates
// document.addEventListener('Random.DOMUpdated', () => {
//     initializeRandomCocktail();
// });
// // Reinitialize modules on DOM updates
// document.addEventListener('Sticky.DOMUpdated', () => {
//     initializeSticky();
// });
// // Reinitialize modules on DOM updates
// document.addEventListener('Sort-Category.DOMUpdated', () => {
//     initializeSortAndCategories();

// });

// // Reinitialize modules on DOM updates
// document.addEventListener('Comments.DOMUpdated', () => {
//     initializeComments();
// });
// // Reinitialize modules on DOM updates
// document.addEventListener('Admin.DOMUpdated', () => {
//     initializeAdmin();
// });
// // Reinitialize modules on DOM updates
// document.addEventListener('Cocktail.DOMUpdated', () => {
//     initializeCocktail();
// });

// // Initialization Logic
// document.addEventListener('Profile.DOMUpdated', () => {
//     initializeProfile();

// });