<div class="sort-options">
    <a href="recent" class="<?php echo (($_GET['sort'] ?? 'recent') === 'recent') ? 'active' : ''; ?>">Recent</a>
    |
    <a href="popular" class="<?php echo ($_GET['sort'] ?? '') === 'popular' ? 'active' : ''; ?>">Popular</a>
    |
    <a href="hot" class="<?php echo ($_GET['sort'] ?? '') === 'hot' ? 'active' : ''; ?>">Hot</a>
</div>
