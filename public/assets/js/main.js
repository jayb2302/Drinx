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
        initializeIngredients();
        initializeTags();
    } else if (pageType === 'cocktail') {
        initializeCocktail();
        initializeComments();
        initializeLikes();
    }
});