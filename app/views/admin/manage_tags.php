<?php
$tags = $tagRepository->getAllTags();
$tagCategories = $tagRepository->getAllTagCategories();
?>

<h1>Manage Tags</h1>

<!-- Add Tag -->
<button id="addTagButton">Add Tag</button>

<!-- Include the form as a module -->
<?php include 'tag_form.php'; ?>

<!-- Tags grouped by category -->
<?php if (!empty($tags)): ?>
    <?php
    $currentCategory = '';
    foreach ($tags as $tag):
        // Start a new list for a new category
        if ($tag['category_name'] !== $currentCategory):
            if ($currentCategory !== '') echo '</ul>'; // Close the previous category's list
            $currentCategory = $tag['category_name'];
            echo '<h2>' . htmlspecialchars($currentCategory) . '</h2><ul>';
        endif;
    ?>
        <li>
            <?= htmlspecialchars($tag['name']) ?>
            <button class="editTagButton" data-tag-id="<?= htmlspecialchars($tag['tag_id']) ?>"
                data-tag-name="<?= htmlspecialchars($tag['name']) ?>"
                data-tag-category-id="<?= htmlspecialchars($tag['tag_category_id']) ?>">
                Edit
            </button>
            <button class="deleteTagButton" data-tag-id="<?= htmlspecialchars($tag['tag_id']) ?>" data-tag-name="<?= htmlspecialchars($tag['name']) ?>">Delete</button>        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No tags found.</p>
<?php endif; ?>

<!-- Notification Dialog for success/error messages -->
<div id="notificationDialog" title="Notification" style="display: none;">
    <p id="notificationMessage"></p>
</div>