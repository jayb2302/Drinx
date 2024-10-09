<?php
require_once __DIR__ . '/router.php';

// The header
require_once __DIR__ . '/app/views/layout/header.php';  


?>

<h1>Welcome to Drinx!</h1>

<?php if (isset($_SESSION['user_id'])): ?>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <a href="<?php echo base_url('logout'); ?>">Logout</a>
<?php else: ?>
    <a href="<?php echo base_url('login'); ?>">Login</a>
    <a href="<?php echo base_url('register'); ?>">Register</a>
<?php endif; ?>

<?php
// Include the footer at the end
require_once __DIR__ . '/app/views/layout/footer.php';  // Correct path to footer.php