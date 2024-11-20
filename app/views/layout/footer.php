<footer class="footer bg-light">
    <div class="container">
        <span class="text-muted">© 2024 Drinx. All rights reserved.</span>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script type="module">
    import { initializeLikes } from '/assets/js/modules/likes.js';
    import { initializeAdmin } from '/assets/js/modules/admin.js';
    import { initializeRandomCocktail } from '/assets/js/modules/random.js';
    import { initializeComments } from '/assets/js/modules/comments.js';
    import { initializeProfile } from '/assets/js/modules/profile.js';
    import { initializeSearch } from '/assets/js/modules/search.js';
    import { initializeSticky } from '/assets/js/modules/sticky.js';
    import { initializeCocktail } from '/assets/js/modules/cocktail.js';
    import { initializeSortAndCategories } from '/assets/js/modules/sort-category.js';


    if (document.querySelector('.category-sidebar')) initializeSortAndCategories();
    if (document.querySelector('.like-button')) initializeLikes();
    if (document.querySelector('#userTableBody')) initializeAdmin();
    if (document.querySelector('.aboutContainer')) initializeRandomCocktail();
    if (document.querySelector('.commentsSection')) initializeComments();
    if (document.getElementById('edit-profile-form')) initializeProfile();
    if (document.querySelector('#searchInput')) initializeSearch();
    if (document.querySelector('.set-sticky')) initializeSticky();
    if (document.getElementById('cocktailId')) initializeCocktail();
</script>

</body>
</html>