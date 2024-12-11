<?php include __DIR__ . '/head.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="header">
    <a class="header-brand" href="<?php echo url('/'); ?>">
        <img src="<?= asset('assets/brand/LogoIdea.svg'); ?>" alt="Drinx Logo" width="" height="50" class="logo align-top">
    </a>
    <!-- Search Bar -->
    <div class="search-container">
    <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="searchInput" placeholder="Search for cocktails or users..." autocomplete="off" />
        <div id="searchResults" class="search-results" style="display: none;">
            <div id="userSuggestions"></div>
            <div id="cocktailSuggestions"></div>
        </div>
    </div>
    <button id="theme-toggle" class="theme-toggle-button">
        <i class="fa-solid fa-sun" id="icon-sun"></i>
        <i class="fa-solid fa-moon" id="icon-moon" style="display:none;"></i>
    </button>
</header>