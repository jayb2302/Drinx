<h1>Edit Cocktail</h1>
<form action="/cocktails/update.php?id=<?= $cocktail['cocktail_id'] ?>" method="post">
    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="<?= $cocktail['title'] ?>" required>

    <label for="description">Description</label>
    <textarea name="description" id="description" required><?= $cocktail['description'] ?></textarea>

    <label for="image">Image URL</label>
    <input type="text" name="image" id="image" value="<?= $cocktail['image'] ?>">

    <label for="category_id">Category</label>
    <input type="text" name="category_id" id="category_id" value="<?= $cocktail['category_id'] ?>" required>

    <button type="submit">Submit</button>
</form>