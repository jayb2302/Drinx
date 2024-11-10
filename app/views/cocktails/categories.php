<div class="category-sidebar">
    <h3>Categories</h3>
    <?php foreach ($categories as $category): ?>
        <?php $categoryName = urlencode(strtolower(str_replace(' ', '-', $category['name']))); ?>
        <a href="/category/<?php echo $categoryName; ?>" 
           onclick="filterByCategory('<?php echo $categoryName; ?>'); return false;" 
           class="<?php echo (($_GET['category'] ?? '') === $categoryName) ? 'active' : ''; ?>">
            <?php echo htmlspecialchars($category['name']); ?>
        </a><br>
    <?php endforeach; ?>
</div>
