<h3><?php echo htmlspecialchars($category); ?> Tags</h3>
<ul>
    <?php if (!empty($tags)): ?>
        <?php foreach ($tags as $tag): ?>
            <li><?php echo htmlspecialchars($tag['tag_name']); ?></li>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tags found in this category.</p>
    <?php endif; ?>
</ul>