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
        $cocktailsData = $this->cocktailService->searchCocktails($query);
        
        $cocktails = array_map(function ($cocktailData) {
            $cocktail = new Cocktail(
                $cocktailData['cocktail_id'],
                null,
                $cocktailData['title'],
                null,
                $cocktailData['image'],
                $cocktailData['prep_time'],
                false,
                null,
                $cocktailData['difficulty_id']
            );
    
            return [
                'cocktail_id' => $cocktail->getCocktailId(),
                'title' => $cocktail->getTitle(),
                'image' => $cocktail->getImage(),
                'prep_time' => $cocktail->getPrepTime(),
                'difficulty_name' => $cocktail->getDifficultyName(),
                'difficulty_icon_html' => $cocktail->getDifficultyIconHtml() // Include HTML for difficulty icon

            ];
        }, $cocktailsData);
        
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