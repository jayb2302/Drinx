<div class="category-sidebar">
    <h3>Categories</h3>
    <a href="/" class="clear-filters">All</a>
    <?php
    $currentCategory = $_GET['category'] ?? ''; // Get the current category from the URL
    $sortOption = $_GET['sort'] ?? 'recent'; // Default to 'recent'

    foreach ($categories as $category):
        $categoryName = urlencode(strtolower(str_replace(' ', '-', $category['name'])));
        $isActive = ($categoryName === $currentCategory); // Check if this category is active
    ?>
        <a href="/category/<?php echo $categoryName; ?>/<?php echo $sortOption; ?>"
            class="category-link <?php echo ($categoryName === ($_GET['category'] ?? '')) ? 'active' : ''; ?>">
            <?php echo htmlspecialchars($category['name']); ?>
        </a>
    <?php endforeach; ?>
</div>