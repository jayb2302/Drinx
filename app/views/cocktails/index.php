<?php
// Include the header
include_once __DIR__ . '/../layout/header.php';
$metaTitle = "Cocktails";
$pageTitle = "Cocktails";
?>

<?php if (!empty($cocktails)): ?>
    <?php foreach ($cocktails as $cocktail): ?>
        <?php
        $imageSrc = $cocktail->getImage();

        // Ensure correct path construction
        if (!empty($imageSrc)) {
            // If the imageSrc already contains '/uploads/cocktails/', use it directly, else prepend it
            $imagePath = strpos($imageSrc, '/uploads/cocktails/') === false ? "/uploads/cocktails/$imageSrc" : $imageSrc;
        } else {
            // Default image if none exists
            $imagePath = '/uploads/cocktails/default-image.webp';
        }

        // Prevent htmlspecialchars from receiving null
        $cocktailTitle = htmlspecialchars($cocktail->getTitle() ?? 'Unknown Cocktail');
        ?>

        <!-- Individual cocktail card -->

 <div class="cocktailGrid">

     <article class="cocktailCard">
         <a href="/cocktails/<?= htmlspecialchars($cocktail->getCocktailId()) ?>-<?= urlencode($cocktail->getTitle()) ?>">
             <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= $cocktailTitle ?>" class="cocktailImage">
            </a>
            <div class="cocktailInfo">
                <h3><?= $cocktailTitle ?></h3>
            </div>
            <button>
                <a href="/cocktails/<?= $cocktail->getCocktailId() ?>-<?= urlencode($cocktail->getTitle()) ?>/edit" class="text-blue-500 hover:underline">Edit Cocktail</a>
            </button>
        </article>
    </div>


    <?php endforeach; ?>

<?php else: ?>
    <p>No cocktails available.</p>
<?php endif; ?>



<!-- Include footer -->
<?php require_once __DIR__ . '/../layout/footer.php'; ?>