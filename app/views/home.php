<?php 
//header 
include __DIR__ . '/layout/header.php';

// Check if the logout_success cookie is set and display it
if (isset($_COOKIE['logout_success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_COOKIE['logout_success']) . '</div>';
    // Clear the cookie after displaying the message
    setcookie('logout_success', '', time() - 3600, "/"); // Expire the cookie immediately
}
?>

<!-- home.php -->
<h1>Welcome to Drinx</h1>


<a href="/cocktails/add">Add New Cocktail</a>
<!-- Logic to Include Forms -->
<?php 
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'login') {
        require_once __DIR__ . '/auth/login.php'; 
    } elseif ($_GET['action'] === 'register') {
        require_once __DIR__ . '/auth/register.php'; 
    }
}
?>
<!-- Display Cocktails -->
<h2>All Cocktails</h2>
<div class="wrapper">
    <?php include __DIR__ . '/cocktails/index.php'; ?>
</div>


