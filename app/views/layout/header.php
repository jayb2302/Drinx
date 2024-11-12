<?php include __DIR__ . '/head.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$authController = new AuthController();
$currentUser = $authController->getCurrentUser();

?>
<nav class="navbar">
    <a class="navbar-brand" href="<?php echo url('/'); ?>">
        <img src="<?= asset('assets/brand/LogoIdea.svg'); ?>" alt="Drinx Logo" width="" height="50" class="d-inline-block align-top">
    </a>
    <!-- Search Bar -->
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search for cocktails or users..." autocomplete="off" />
        <div id="searchResults" class="search-results" style="display: none;"></div>
    </div>
  
</nav>