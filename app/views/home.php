<?php 
//header 
include __DIR__ . '/layout/header.php';
?>


<!-- home.php -->
<h1>Welcome to Drinx</h1>


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



