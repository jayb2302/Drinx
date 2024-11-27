<?php
require_once '/../app/services/CocktailService.php'; 

header('Content-Type: application/json');

// Fetch the current sticky cocktail
$stickyCocktail = $cocktailService->getStickyCocktail();

if ($stickyCocktail) {
    $cocktailId = htmlspecialchars($stickyCocktail->getCocktailId());
    $cocktailTitle = urlencode($stickyCocktail->getTitle());
    
    $cocktailLink = "/cocktails/{$cocktailId}-{$cocktailTitle}";

    echo json_encode([
        'success' => true,
        'id' => $stickyCocktail->getCocktailId(),
        'title' => $stickyCocktail->getTitle(),
        'description' => $stickyCocktail->getDescription(),
        'image' => '/uploads/cocktails/' . $stickyCocktail->getImage(),
        'link' => $cocktailLink,
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No sticky cocktail set.'
    ]);
}

exit;