<?php
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../repositories/DifficultyRepository.php';
require_once __DIR__ . '/../services/CocktailService.php';
require_once __DIR__ . '/../services/IngredientService.php';
require_once __DIR__ . '/../services/StepService.php';
require_once __DIR__ . '/../repositories/UnitRepository.php';
require_once __DIR__ . '/../services/LikeService.php';
require_once __DIR__ . '/../repositories/LikeRepository.php';
require_once __DIR__ . '/../services/UserService.php';

class HomeController
{
    private $cocktailService;
    private $ingredientService;
    private $likeService;
    private $userService;

    public function __construct(
        CocktailService $cocktailService,
        IngredientService $ingredientService,
        LikeService $likeService,
        UserService $userService
    ) {
        $this->cocktailService = $cocktailService;
        $this->ingredientService = $ingredientService;
        $this->likeService = $likeService;
        $this->userService = $userService;
    }

    public function index()
    {
        $loggedInUserId = $_SESSION['user']['id'] ?? null;
        $isAdmin = $_SESSION['user']['is_admin'] ?? false;

        // Check if there's a sort option in the query, default to 'recent'
        $sortOption = $_GET['sort'] ?? 'recent';

        // Fetch cocktails based on the sort option
        if ($sortOption === 'popular') {
            $cocktails = $this->cocktailService->getCocktailsSortedByLikes();
        } else {
            $cocktails = $this->cocktailService->getCocktailsSortedByDate();
        }

        // Sanitize and determine if we should show a specific form
        $action = isset($_GET['action']) ? sanitize($_GET['action']) : null;
        $isAdding = $action === 'add';
        $isLoggingIn = $action === 'login';
        $isRegistering = $action === 'register';

        // Add 'hasLiked' status to each cocktail
        foreach ($cocktails as $cocktail) {
            $cocktail->hasLiked = $loggedInUserId ? $this->likeService->userHasLikedCocktail($loggedInUserId, $cocktail->getCocktailId()) : false;
        }

        // Fetch categories and units if needed for forms
        $categories = $this->cocktailService->getCategories();
        $units = $this->ingredientService->getAllUnits();

        // Fetch users if admin is logged in
        $users = ($isAdmin) ? $this->userService->getAllUsersWithStatus() : null;

        // Pass the necessary data to the view
        require_once __DIR__ . '/../views/home.php';
    }
}