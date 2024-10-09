<?php
require_once __DIR__ . '/../services/CocktailService.php';

class CocktailController {
    private $cocktailService;

    public function __construct() {
        $this->cocktailService = new CocktailService();
    }

    public function index() {
        $cocktails = $this->cocktailService->getAllCocktails();  
        require_once __DIR__ . '/../views/cocktails/index.php'; // Load the cocktails view
    }
}
// require_once __DIR__ . '/../services/CocktailService.php';

// $cocktailService = new CocktailService();
// $cocktails = $cocktailService->getAllCocktails();  

// // Load the view and pass the cocktails data to it
// require_once __DIR__ . '/../views/cocktails/index.php';
?>