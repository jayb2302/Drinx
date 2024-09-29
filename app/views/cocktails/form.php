<?php
// This file contains the form for both adding and editing cocktail recipes.
// If editing, the form is pre-filled with the existing cocktail's data.

$isEditing = isset($cocktail);  // Check if we're editing an existing cocktail.
?>

<form action="<?php echo $isEditing ? '/cocktails/update.php' : '/cocktails/store.php'; ?>" method="post">
    <input type="text" name="title" value="<?php echo $isEditing ? $cocktail['title'] : ''; ?>" placeholder="Cocktail Title">
    <!-- Add other form fields like ingredients, instructions, etc. -->
    <button type="submit"><?php echo $isEditing ? 'Update Cocktail' : 'Add Cocktail'; ?></button>
</form>