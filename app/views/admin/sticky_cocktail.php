<?php
require_once '/../app/services/CocktailService.php'; 

header('Content-Type: application/json');

// Fetch the current sticky cocktail
$stickyCocktail = $cocktailService->getStickyCocktail();

if ($stickyCocktail) {
    $cocktailId = htmlspecialchars($stickyCocktail->getCocktailId());
    $cocktailTitle = htmlspecialchars($stickyCocktail->getTitle());
    $cocktailDescription = htmlspecialchars($stickyCocktail->getDescription());
    $cocktailImage = $stickyCocktail->getImage();

    if (!empty($cocktailImage)) {
        $cocktailImage = '/uploads/cocktails/' . htmlspecialchars($cocktailImage);
    } else {
        $cocktailImage = '/uploads/cocktails/default-cocktail.jpeg'; // Fallback image
    }

    $cocktailLink = "/cocktails/{$cocktailId}-{$cocktailTitle}";

    echo json_encode([
        'success' => true,
        'id' => $cocktailId,
        'title' => $cocktailTitle,
        'description' => $cocktailDescription,
        'image' => $cocktailImage,
        'link' => $cocktailLink,
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No sticky cocktail set.'
    ]);
}

exit;