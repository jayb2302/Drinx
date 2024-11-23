<div class="category-sidebar">
    <h3>Categories</h3>
    <?php foreach ($categories as $category): ?>
        <?php 
        $categoryName = urlencode(strtolower(str_replace(' ', '-', $category['name']))); 
        $sortOption = $_GET['sort'] ?? 'recent'; // Default to 'recent'
        ?>
        <a href="/category/<?php echo $categoryName; ?>/<?php echo $sortOption; ?>"
           class="<?php echo ($categoryName === ($_GET['category'] ?? '')) ? 'active' : ''; ?>">
            <?php echo htmlspecialchars($category['name']); ?>
        </a><br>
    <?php endforeach; ?>
</div>
