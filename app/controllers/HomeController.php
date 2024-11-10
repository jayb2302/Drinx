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

    public function __construct(
        CocktailService $cocktailService,
        IngredientService $ingredientService,
        LikeService $likeService,
        UserService $userService,
        CategoryRepository $categoryRepository
    ) {
        $this->cocktailService = $cocktailService;
        $this->ingredientService = $ingredientService;
        $this->likeService = $likeService;
        $this->userService = $userService;
        $this->categoryRepository = $categoryRepository;
    }

    public function index($categoryName = null, $sortOption = 'recent')
    {
        $loggedInUserId = $_SESSION['user']['id'] ?? null;
        $isAdmin = $_SESSION['user']['is_admin'] ?? false;

        // Check if there's a sort option in the query, default to 'recent'
        $sortOption = $_GET['sort'] ?? 'recent';
        if (strpos($_SERVER['REQUEST_URI'], '/popular') !== false) {
            $sortOption = 'popular';
        } elseif (strpos($_SERVER['REQUEST_URI'], '/hot') !== false) {
            $sortOption = 'hot';
        }

        // Fetch cocktails based on the sort option
        if ($sortOption === 'popular') {
            $cocktails = $this->cocktailService->getCocktailsSortedByLikes();
        } elseif ($sortOption === 'hot') {
            $cocktails = $this->cocktailService->getHotCocktails();
        } else {
            $cocktails = $this->cocktailService->getCocktailsSortedByDate();
        }

        // Fetch other necessary data
        $categories = $this->categoryRepository->getAllCategories();
        $randomCocktail = $this->cocktailService->getRandomCocktail();
        $stickyCocktail = $this->cocktailService->getStickyCocktail();
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

        // Load the view
        require_once __DIR__ . '/../views/home.php';
    }

    public function filterByCategory($categoryName)
    {
        // Decode the category name
        $categoryName = str_replace('-', ' ', urldecode($categoryName));

        // Fetch category by name to get its ID
        $categories = $this->categoryRepository->getAllCategories();
        $categoryId = null;

        foreach ($categories as $category) {
            if (strtolower($category['name']) === strtolower($categoryName)) {
                $categoryId = $category['category_id'];
                break;
            }
        }

        // If no matching category is found, display an error or redirect
        if ($categoryId === null) {
            http_response_code(404);
            echo "Category not found";
            return;
        }

        // Fetch cocktails by category ID and include sticky cocktail for display
        $cocktails = $this->cocktailService->getCocktailsByCategory($categoryId);
        $stickyCocktail = $this->cocktailService->getStickyCocktail();
        $categories = $this->categoryRepository->getAllCategories();

        // Pass data to the view
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
