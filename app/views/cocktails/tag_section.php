<h3>Flavor Tags</h3>
<ul>
    <?php if (!empty($flavorTags)): ?>
        <?php foreach ($flavorTags as $tag): ?>
            <li><?php echo htmlspecialchars($tag['tag_name']); ?></li>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No flavor tags available.</p>
    <?php endif; ?>
</ul>