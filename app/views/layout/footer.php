<footer class="footer bg-light">
    <div class="container">
        <span class="text-muted">© 2024 Drinx. All rights reserved.</span>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Get the cocktail ID from PHP and make it available to JavaScript
    const cocktailId = <?= isset($cocktailId) ? json_encode($cocktailId) : 'null'; ?>;
</script>
<script src="<?= asset('assets/js/cocktail.js'); ?>"></script>

</body>
</html>