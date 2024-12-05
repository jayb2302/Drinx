<?php
require_once 'BaseController.php';

class HomeController extends BaseController
{
    private $ingredientService;
    private $likeService;

    public function __construct(
        AuthService $authService,
        UserService $userService,
        CocktailService $cocktailService,
        IngredientService $ingredientService,
        LikeService $likeService
    ) {
        parent::__construct($authService, $userService, $cocktailService);
        $this->ingredientService = $ingredientService;
        $this->likeService = $likeService;
    }

    public function index($categoryName = null, $sortOption = 'recent')
    {
       
        $loggedInUserId = $_SESSION['user']['id'] ?? null;
        $isAdmin = $_SESSION['user']['is_admin'] ?? false;
        $authController = new AuthController($this->authService, $this->userService);
        $currentUser = $this->userService->getUserWithProfile($loggedInUserId);
        $cocktails = $this->cocktailService->getAllCocktails();
        $user = $_SESSION['user'] ?? null; 
        $data['csrf_token'] = $_SESSION['csrf_token'] ?? '';
        // Pass data to the view
        $data = [
            'cocktails' => $cocktails,
            'user' => $user,
        ];

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
            $categoryId = $this->cocktailService->getCategoryIdByName($categoryName);
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

        // Additional data needed for the page
        $categories = $this->cocktailService->getCategories();
        $randomCocktail = $this->cocktailService->getRandomCocktail();
        $stickyCocktail = $this->cocktailService->getStickyCocktail();
        $units = $this->ingredientService->getAllUnits();
        $difficulties = $this->cocktailService->getAllDifficulties();
        
        // Add 'hasLiked' status to each cocktail if the user is logged in
        foreach ($cocktails as $cocktail) {
            $cocktail->hasLiked = $loggedInUserId
                ? $this->likeService->userHasLikedCocktail($loggedInUserId, $cocktail->getCocktailId())
                : false;
            // Get the comment count and top-level comments for the cocktail
            $commentCount = $this->cocktailService->getCommentCountForCocktail($cocktail->getCocktailId());
            $comments = $this->cocktailService->getTopLevelCommentsForCocktail($cocktail->getCocktailId(), 3);

            // Set the properties on the cocktail object
            $cocktail->commentCount = $commentCount;
            $cocktail->comments = $comments;
        }
        // Check for AJAX request
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            ob_start();
            include __DIR__ . '/../views/cocktails/index.php';  // This will include the comments
            $content = ob_get_clean();
            header('Content-Type: application/json');
            echo json_encode(['content' => $content]);  // Make sure the response has content
            return;
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
                http_response_code(400);
            }
        } else {
            // Handle method not allowed
            http_response_code(405); 
        }
    }

    public function about()
    {
        require_once __DIR__ . '/../views/about.php';
    }
}
