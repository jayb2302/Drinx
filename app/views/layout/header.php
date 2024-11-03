<?php include __DIR__ . '/head.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar">
    <a class="navbar-brand" href="<?php echo url('/'); ?>">
        <img src="<?= asset('assets/brand/DrinxLogo.svg'); ?>" alt="Drinx Logo" width="" height="50" class="d-inline-block align-top">
    </a>
    <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button> -->
    <!-- Links to show forms -->
    <?php if (!isset($_SESSION['user'])): ?>
        <a href="/login">Login</a>
        <a href="/register">Register</a>
    <?php else: ?>
        <!-- Check if first_name and last_name exist, otherwise use the username -->
        <?php
        $firstName = $_SESSION['user']['first_name'] ?? null;
        $lastName = $_SESSION['user']['last_name'] ?? null;
        $username = htmlspecialchars($_SESSION['user']['username']);  // Fallback to username

        if ($firstName && $lastName) {
            $displayName = htmlspecialchars($firstName) . ' ' . htmlspecialchars($lastName);
        } else {
            $displayName = $username;
        }
        ?>
        <div class="nav-links">
            <a href="/profile/<?php echo htmlspecialchars($_SESSION['user']['id']); ?>">Profile</a>
            <a href="/logout">Logout</a>
        </div>
        <span>Hello, <?= $displayName; ?></span>
    <?php endif; ?>
</nav>