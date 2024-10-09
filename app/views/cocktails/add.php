<?php
require_once __DIR__ . '/../../services/CocktailService.php';
?>
<h1>Add New Cocktail</h1>
<form action="/cocktails/store.php" method="post">
    <label for="title">Title</label>
    <input type="text" name="title" id="title" required>

    <label for="description">Description</label>
    <textarea name="description" id="description" required></textarea>

    <label for="image">Image URL</label>
    <input type="text" name="image" id="image">

    <label for="category_id">Category</label>
    <input type="text" name="category_id" id="category_id" required>

    <button type="submit">Submit</button>
</form>
