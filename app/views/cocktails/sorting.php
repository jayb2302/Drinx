<div class="sort-options">
    <?php
    $currentSort = $_GET['sort'] ?? 'recent';
    ?>
    <a href="/recent" class="<?= ($currentSort === 'recent') ? 'active' : ''; ?>">Recent</a> |
    <a href="/popular" class="<?= ($currentSort === 'popular') ? 'active' : ''; ?>">Popular</a> |
    <a href="/hot" class="<?= ($currentSort === 'hot') ? 'active' : ''; ?>">Hot</a>
</div>
