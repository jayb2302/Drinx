<div id="tagDialog" title="Manage Tag" style="display: none;">
    <form id="tagForm">
        <input type="hidden" id="tagId">
        <label for="tagName">Tag Name:</label>
        <input type="text" id="tagName" required>
        <br><br>
        <label for="tagCategory">Category:</label>
        <select id="tagCategory" required>
            <option value="">Select a category</option>
            <?php foreach ($tagCategories as $category): ?>
                <option value="<?= htmlspecialchars($category['tag_category_id']) ?>">
                    <?= htmlspecialchars($category['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>