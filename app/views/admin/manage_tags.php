<div class="admin-container">
  <h1 class="admin-control-title">Manage Tags</h1>

  <!-- Add Tag Button -->
  <button id="addTagButton" class="button-primary">Add Tag</button>

  <!-- Include the form as a module -->
  <?php include 'tag_form.php'; ?>

  <!-- Tags grouped by category -->
  <?php if (!empty($groupedTags)): ?>
    <div class="categories-container">
        <?php foreach ($groupedTags as $categoryName => $tags): ?>
            <div class="category-block">
                <h2 class="category-title"><?= htmlspecialchars($categoryName); ?></h2>
                <ul>
                    <?php foreach ($tags as $tag): ?>
                        <li class="tag-item">
                            <span class="tag-name"><?= htmlspecialchars($tag['name']); ?></span>
                            <div class="button-group">
                                <button class="button-secondary editTagButton" 
                                    data-tag-id="<?= htmlspecialchars($tag['tag_id']); ?>"
                                    data-tag-name="<?= htmlspecialchars($tag['name']); ?>"
                                    data-tag-category-id="<?= htmlspecialchars($tag['tag_category_id']); ?>">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <button class="button-error deleteTagButton" 
                                    data-tag-id="<?= htmlspecialchars($tag['tag_id']); ?>" 
                                    data-tag-name="<?= htmlspecialchars($tag['name']); ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="no-tags-message">No tags found.</p>
<?php endif; ?>

  <!-- Notification Dialog -->
  <div id="notificationDialog" title="Notification" style="display: none;">
    <p id="notificationMessage"></p>
  </div>
</div>