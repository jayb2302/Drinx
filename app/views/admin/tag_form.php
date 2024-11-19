<div id="tagDialog" title="Manage Tag" style="display: none;">
    <form id="tagForm">
        <input type="hidden" id="tagId">
        <label for="tagName">Tag Name:</label>
        <input type="text" id="tagName" required>
        <br><br>
        <label for="tagCategory">Category:</label>
        <select id="tagCategory" name="tagCategory">
            <option value="">Select Category</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['tag_category_id']); ?>">
                        <?= htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option>No categories available</option>
            <?php endif; ?>
        </select>
    </form>
</div>