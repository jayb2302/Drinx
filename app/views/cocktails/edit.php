<!-- <h1>Edit Cocktail</h1>
<form method="POST" action="/cocktails/update/<?= htmlspecialchars($cocktail->cocktail_id) ?>">
    <label for="title">Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($cocktail->title) ?>" required>

    <label for="description">Description:</label>
    <textarea name="description" required><?= htmlspecialchars($cocktail->description) ?></textarea>

    <label for="image">Image:</label>
    <input type="text" name="image" value="<?= htmlspecialchars($cocktail->image) ?>">

    <label for="category_id">Category:</label>
    <input type="number" name="category_id" value="<?= htmlspecialchars($cocktail->category_id) ?>" required>

    <input type="submit" value="Update Cocktail">
</form> -->