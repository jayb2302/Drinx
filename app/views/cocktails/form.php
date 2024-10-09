<?php
// This file contains the form for both adding and editing cocktail recipes.
// If editing, the form is pre-filled with the existing cocktail's data.

$isEditing = isset($cocktail);  // Check if we're editing an existing cocktail.
?>

<h1><?= $isEditing ? 'Edit' : 'Add' ?> Cocktail</h1>
<form action="/cocktails/<?= $isEditing ? 'update.php?id=' . $cocktail['cocktail_id'] : 'store.php' ?>" method="post">
    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="<?= $isEditing ? $cocktail['title'] : '' ?>" required>

    <label for="description">Description</label>
    <textarea name="description" id="description" required><?= $isEditing ? $cocktail['description'] : '' ?></textarea>

    <label for="image">Image URL</label>
    <input type="text" name="image" id="image" value="<?= $isEditing ? $cocktail['image'] : '' ?>">

    <label for="category_id">Category</label>
    <input type="text" name="category_id" id="category_id" value="<?= $isEditing ? $cocktail['category_id'] : '' ?>" required>

    <button type="submit">Submit</button>
</form>
