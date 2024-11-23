<?php
require_once '/../app/services/CocktailService.php'; 

header('Content-Type: application/json');

// Fetch the current sticky cocktail
$stickyCocktail = $cocktailService->getStickyCocktail();

if ($stickyCocktail) {
    echo json_encode([
        'success' => true,
        'id' => $stickyCocktail->getCocktailId(),
        'title' => $stickyCocktail->getTitle(),
        'description' => $stickyCocktail->getDescription(),
        'image' => '/uploads/cocktails/' . $stickyCocktail->getImage(), // Include the full path for the image
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No sticky cocktail set.'
    ]);
}

exit;