// Main.js for handling all the JS modules
import { initializeProfile } from './modules/profile.js';
import { initializeLikes } from './modules/likes.js';
import { initializeSearch } from './modules/search.js';
import { initializeSticky } from './modules/sticky.js';
import { initializeRandomCocktail } from './modules/random.js';
import { initializeSortAndCategories } from './modules/sort-category.js';
import { initializeComments } from './modules/comments.js';
import { initializeAdmin } from './modules/admin.js';
import { initializeCocktail } from './modules/cocktail.js';
import { initializeIngredients } from './modules/ingredients.js';
import { initializeTags } from './modules/tags.js';
import { initializeUserManagement } from './modules/user-management.js';
import { initializeSidebars } from './modules/sidebars.js';
import { initializeImageValidation } from './modules/image-handler.js';
import { initializeSocialMedia } from './modules/socials.js';
import { initializeThemeSwitcher } from './modules/theme.js';
// Fetch CSRF token from the meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Set up AJAX requests to include CSRF token globally
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': csrfToken
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const pageType = window.pageType || document.querySelector('meta[name="page-type"]')?.content;

    initializeSidebars();
    initializeSearch();
    initializeThemeSwitcher();
    switch (pageType) {
        case 'home':
            // Initialize features for the home page
            initializeSortAndCategories();
            initializeLikes();
            initializeSticky();
            initializeRandomCocktail();
            initializeCocktail();
            break;

        case 'profile':
            // Initialize profile page features
            initializeProfile();
            initializeImageValidation('profile_picture', 'image-preview', 'file-error');
            initializeSocialMedia();
            break;

        case 'admin':
            // Initialize admin panel features
            initializeAdmin();
            initializeIngredients();
            initializeTags();
            initializeUserManagement();
            break;

        case 'cocktail':
            // Initialize cocktail detail page features
            initializeCocktail();
            initializeComments();
            initializeLikes();
            initializeImageValidation('image', 'cocktail-image-preview', 'cocktail-file-error');
            break;

        default:
            console.warn('Unknown page type:', pageType);
            break;
    }
});
