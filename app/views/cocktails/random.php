<?php
// Ensure $cocktail is set
if (isset($cocktail) && $cocktail) {
    echo '<div class="recipe-wrapper">';  // Container for styling
    echo '<h2 class="recipe-card">' . sanitizeTrim($cocktail->getTitle()) . '</h2>';
    echo '<div class="recipe-card">';
    echo '<img src="' . asset('/uploads/cocktails/' . sanitizeTrim($cocktail->getImage())) . '" alt="Random Cocktail Image" class="cocktailImage">';
    echo '<p>' . sanitizeTrim(getFirstSentence($cocktail->getDescription())) . '</p>'; // Use first sentence and sanitize
    echo '<a href="/cocktails/' . $cocktail->getId() . '-' . urlencode($cocktail->getTitle()) . '" class="btn btn-primary">View Recipe</a>';
    echo '</div>';
    echo '</div>'; // End of container
    echo '<div class="randomButton">';
    echo '<a href="/" class="btn btn-secondary">Get Another Random Cocktail</a>';
    echo '</div>';
} else {
    echo '<p>No cocktail found. Try again!</p>';
}
?>