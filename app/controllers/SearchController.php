<?php
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/CocktailService.php';

class SearchController {
    private $cocktailService;
    private $userService;

    public function __construct(UserService $userService, CocktailService $cocktailService) {
        $this->userService = $userService;
        $this->cocktailService = $cocktailService;
    }

    public function search() {
        $query = $_GET['query'] ?? '';
    
        // Fetch users and cocktails that match the query
        $users = $this->userService->searchUsers($query);
        $cocktails = $this->cocktailService->searchCocktails($query);
    
        // Return results as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'users' => $users,
            'cocktails' => $cocktails,
        ]);
    }
}