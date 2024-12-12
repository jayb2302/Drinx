<?php
require_once __DIR__ . '/BaseController.php';

class SearchController extends BaseController
{
    public function __construct(
        UserService $userService,
        CocktailService $cocktailService
    ) {
        parent::__construct(null,$userService, $cocktailService);
    }

    public function search() {
        $query = $_GET['query'] ?? '';
        $sanitizedQuery = sanitizeQuery($query);

        // Fetch users and cocktails that match the query
        $users = $this->userService->searchUsers($sanitizedQuery);
        $cocktails = $this->cocktailService->searchCocktails($query);
        
        
        // Return results as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'users' => $users,
            'cocktails' => $cocktails,
        ]);
    }
    public function adminUserSearch() {
        $query = $_GET['query'] ?? '';
        $sanitizedQuery = sanitizeQuery($query);
        // Fetch all users that match the query without a limit
        $users = $this->userService->searchAllUsers($sanitizedQuery);
    
        // Return results as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'users' => $users]);
    }
    
}