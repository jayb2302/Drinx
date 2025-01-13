<?php include __DIR__ . '/head.php';

// Ensure the session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check for the session-expired cookie
if (isset($_COOKIE['session_expired'])) {
    echo '<div id="message" class="alert alert-danger"><i class="fa-solid fa-bell error"></i><h4>' . htmlspecialchars($_COOKIE['session_expired']) . '</h4></div>';
    setcookie('session_expired', '', time() - 3600, '/'); // Clear the cookie
}
if (isset($_SESSION['success'])) {
    echo '<div id="message" class="success"><i class="fa-solid fa-bell success"></i><h4>' . htmlspecialchars($_SESSION['success']) . '</h4></div>';
    unset($_SESSION['success']); 
}
if (isset($_SESSION['error'])) {
    echo '<div id="message" class="alert alert-danger"><i class="fa-solid fa-bell error"></i><h4>' . htmlspecialchars($_SESSION['error']) . '</h4></div>';
    unset($_SESSION['error']); 
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