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
    private $categoryRepository;
    private $difficultyRepository;
    private $tagRepository;

    public function __construct(
        CocktailService $cocktailService,
        IngredientService $ingredientService,
        LikeService $likeService,
        UserService $userService,
        CategoryRepository $categoryRepository,
        DifficultyRepository $difficultyRepository,
        TagRepository $tagRepository
    ) {
        $this->cocktailService = $cocktailService;
        $this->ingredientService = $ingredientService;
        $this->likeService = $likeService;
        $this->userService = $userService;
        $this->categoryRepository = $categoryRepository;
        $this->difficultyRepository = $difficultyRepository;
        $this->tagRepository = $tagRepository;
    }

    public function index($categoryName = null, $sortOption = 'recent')
    {
        $loggedInUserId = $_SESSION['user']['id'] ?? null;
        $isAdmin = $_SESSION['user']['is_admin'] ?? false;
        $isStandalone = false; // When rendering the homepage, set as false

        // Checks if $categoryName is one of the sort options (recent, popular, hot)
        if (in_array($categoryName, ['recent', 'popular', 'hot'])) {
            $sortOption = $categoryName;
            $categoryName = null;
        }

        // Resolve sorting option (default to 'recent')
        $sortOption = $sortOption ?? ($_GET['sort'] ?? 'recent');

        // Fetch cocktails globally or by category
        if ($categoryName) {
            $categoryName = str_replace('-', ' ', urldecode($categoryName));
            $categoryId = $this->categoryRepository->getCategoryIdByName($categoryName);
            if (!$categoryId) {
                http_response_code(404);
                echo "Category not found.";
                return;
            }
            $cocktails = match ($sortOption) {
                'popular' => $this->cocktailService->getCocktailsByCategorySortedByLikes($categoryId),
                'hot' => $this->cocktailService->getHotCocktailsByCategory($categoryId),
                default => $this->cocktailService->getCocktailsByCategorySortedByDate($categoryId),
            };
        } else {
            // No category selected, fetch cocktails globally
            $cocktails = match ($sortOption) {
                'popular' => $this->cocktailService->getCocktailsSortedByLikes(),
                'hot' => $this->cocktailService->getHotCocktails(),
                default => $this->cocktailService->getCocktailsSortedByDate(),
            };
        }

        // Check for AJAX request
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            ob_start();
            include __DIR__ . '/../views/cocktails/index.php';
            $content = ob_get_clean();
            header('Content-Type: application/json');
            echo json_encode(['content' => $content]);
            return;
        }

        // Additional data needed for the page
        $categories = $this->cocktailService->getCategories();
        $randomCocktail = $this->cocktailService->getRandomCocktail();
        $stickyCocktail = $this->cocktailService->getStickyCocktail();
        $units = $this->ingredientService->getAllUnits();
        $difficulties = $this->difficultyRepository->getAllDifficulties();
        $tags = $this->tagRepository->getAllTags();

        // Add 'hasLiked' status to each cocktail if the user is logged in
        foreach ($cocktails as $cocktail) {
            $cocktail->hasLiked = $loggedInUserId
                ? $this->likeService->userHasLikedCocktail($loggedInUserId, $cocktail->getCocktailId())
                : false;
        }

        // Prepare user profile and admin data
        $userProfile = $loggedInUserId ? $this->userService->getUserWithFollowCounts($loggedInUserId) : null;
        $users = $isAdmin ? $this->userService->getAllUsersWithStatus() : null;

        // Determine if specific forms should be shown based on the action query
        $action = isset($_GET['action']) ? sanitize($_GET['action']) : null;
        $isAdding = $action === 'add';
        $isLoggingIn = $action === 'login';
        $isRegistering = $action === 'register';
        // $includeScripts = [
        //     asset('assets/js/sort-category.js')
        // ];  
        // Load the view
        require_once __DIR__ . '/../views/home.php';
    }




    public function setSticky()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $cocktailId = $data['cocktail_id'] ?? null;

            if ($cocktailId) {
                try {
                    // Call the repository to set this cocktail as sticky
                    $this->cocktailService->setStickyCocktail($cocktailId);
                    // Return a JSON success response
                    echo json_encode(['success' => true, 'message' => 'Cocktail set as sticky!']);
                    http_response_code(200);
                } catch (Exception $e) {
                    // Return an error response
                    echo json_encode(['success' => false, 'message' => 'Failed to set cocktail as sticky.']);
                    http_response_code(500); // Internal server error
                }
            } else {
                // Return a bad request response
                echo json_encode(['success' => false, 'message' => 'Invalid cocktail ID.']);
                http_response_code(400); // Bad request
            }
        } else {
            // Handle method not allowed
            http_response_code(405); // Method not allowed
        }
    }

    public function about()
    {
        require_once __DIR__ . '/../views/about.php';
    }
}