<?php 
//header 
require_once __DIR__ . '/layout/header.php';
?>


<!-- home.php -->
<h1>Welcome to Drinx</h1>

<!-- Links to show forms -->
<a href="?action=login">Login</a>
<a href="?action=register">Register</a>
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
<?php if (!empty($cocktails)): ?>
    <ul>
        <?php foreach ($cocktails as $cocktail): ?>
            <li>
                <a href="/cocktails/view.php?id=<?= htmlspecialchars($cocktail['cocktail_id']) ?>">
                    <?= htmlspecialchars($cocktail['title']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No cocktails available.</p>
<?php endif; ?>



