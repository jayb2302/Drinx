<?php
require_once __DIR__ . '/../services/CocktailService.php';

class HomeController {
    private $cocktailService;

    public function __construct() {
        $this->cocktailService = new CocktailService();
    }

    public function index() {
        // Fetching all cocktails
        $cocktails = $this->cocktailService->getAllCocktails();
        
        // Set dynamic titles
        $pageTitle = "Welcome to Drinx"; // Set your dynamic page title
        $metaTitle = "Explore Our Delicious Cocktails"; // Set your dynamic meta title

        // Pass titles and cocktails data to the view
        require_once __DIR__ . '/../views/home.php'; // Load the home view
    }
}
// class HomeController {
//     // Method to load the homepage
//     public function index() {
//         // You can include any logic here for loading data, etc.
//         require_once __DIR__ . '/../views/layout/header.php'; // Include header
//         require_once __DIR__ . '/../views/home.php'; // Load the home view
//         require_once __DIR__ . '/../views/layout/footer.php'; // Include footer
//     }
// }