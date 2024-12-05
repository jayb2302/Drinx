<?php
require_once 'BaseController.php';
class AdminController extends BaseController
{
    private $adminService;
    private $ingredientService;

    public function __construct(
        AuthService $authService,
        UserService $userService,
        CocktailService $cocktailService,
        AdminService $adminService,
        IngredientService $ingredientService
    ) {
        parent::__construct($authService, $userService, $cocktailService);
        $this->adminService = $adminService;
        $this->ingredientService = $ingredientService;
    }

    public function dashboard()
    {


        // Check if the user is an admin
        if (empty($_SESSION['user']['is_admin'])) {
            http_response_code(403);
            echo "Access denied.";
            exit();
        }

        // Fetch the dashboard data, including tags
        $dashboardData = $this->adminService->getDashboardData();


        // Extract data and add checks
        $stats = $dashboardData['stats'] ?? [];
        $users = $dashboardData['users'] ?? [];
        $cocktails = $dashboardData['cocktails'] ?? [];

        $categorizedIngredients = $this->ingredientService->getIngredientsByTags();

        $groupedTags = $dashboardData['groupedTags'] ?? [];
        $categories = $dashboardData['tagCategories'] ?? [];

        // Log any missing data for debugging
        error_log('Dashboard Data: ' . print_r($dashboardData, true));

        // Ensure the required data is present
        if (empty($stats) || empty($users) || empty($cocktails)) {
            error_log('Some expected data is missing in dashboard: ' . print_r($dashboardData, true));
            // Handle error or redirect if data is incomplete
            echo "Missing required dashboard data.";
            exit();
        }

        // Load the dashboard view and pass the data
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    // Update user status (e.g., active, banned, suspended)
    public function updateUserStatus()
    {
        // Debugging: Log session data at the start
        // error_log("Session Data at Start: " . print_r($_SESSION, true));
    
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Debugging: Log POST data
            // error_log("POST Data: " . print_r($_POST, true));
    
            if (!$this->authService->isAdmin()) {
                // error_log("Admin Check Failed: User is not an admin.");
                http_response_code(403);
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
                exit();
            }
    
            // Retrieve and log CSRF tokens
            $csrfToken = $_POST['csrf_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';
    
            // error_log("CSRF Token from POST: " . $csrfToken);
            // error_log("CSRF Token from Session: " . $sessionToken);
    
            // Validate CSRF tokens
            if (!$sessionToken || !hash_equals($sessionToken, $csrfToken)) {
                // error_log("CSRF Token Validation Failed");
                http_response_code(403);
                echo json_encode(['error' => 'Invalid or missing CSRF token.']);
                exit;
            }
    
            // Sanitize and log user inputs
            $userId = sanitize($_POST['user_id']);
            $statusId = sanitize($_POST['status_id']);
            
            // Update user status in the database
            $result = $this->adminService->updateUserStatus($userId, $statusId);
            // error_log("Update Result: " . ($result ? "Success" : "Failure"));
    
            // Respond with success
            echo json_encode(['status' => 'success', 'message' => 'User status updated successfully.']);
            exit();
        }
    
        // Debugging: Log if request method is not POST
        error_log("Request Method Not Allowed: " . $_SERVER['REQUEST_METHOD']);
    
        // Respond with an error for unauthorized access or invalid request method
        http_response_code(405); // Method Not Allowed
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
        exit();
    }
    

    public function setStickyCocktail()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin']) {
            $csrfToken = $_POST['csrf_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';

            // Log the CSRF tokens for debugging
            error_log("Incoming CSRF Token: " . $csrfToken);
            error_log("Session CSRF Token: " . $sessionToken);

            if (!$sessionToken || !hash_equals($sessionToken, $csrfToken)) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid or missing CSRF token.']);
                exit;
            }
            // Get the raw POST data
            $data = json_decode(file_get_contents('php://input'), true);
            $cocktailId = intval($data['cocktail_id'] ?? 0);

            if ($cocktailId) {
                try {
                    // Set the sticky cocktail using CocktailService
                    $this->cocktailService->setStickyCocktail($cocktailId);

                    echo json_encode(['success' => true, 'message' => 'Sticky cocktail set successfully.']);
                    exit;
                } catch (Exception $e) {
                    error_log("Failed to set sticky cocktail: " . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => 'Failed to set sticky cocktail.']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid cocktail ID.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access or invalid request method.']);
            exit;
        }
    }

    public function getStickyCocktail()
    {
        $stickyCocktail = $this->cocktailService->getStickyCocktail();

        if ($stickyCocktail) {
            echo json_encode([
                'success' => true,
                'id' => $stickyCocktail->getCocktailId(),
                'title' => $stickyCocktail->getTitle(),
                'description' => $stickyCocktail->getDescription(),
                'image' => $stickyCocktail->getImage(),
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No sticky cocktail set.']);
        }
        exit;
    }
    // Toggle sticky cocktail via AJAX
    public function toggleStickyCocktail()
    {
        // Get CSRF token from request body
        $data = json_decode(file_get_contents('php://input'), true);
        $csrfToken = $data['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';

        // Log both the CSRF tokens to debug
        error_log("Incoming CSRF Token: " . $csrfToken);
        error_log("Session CSRF Token: " . $sessionToken);

        // Validate CSRF token
        if (!$sessionToken || !hash_equals($sessionToken, $csrfToken)) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid or missing CSRF token.']);
            exit;
        }

        // Get cocktail ID from request data
        $cocktailId = $data['cocktail_id'] ?? null;

        if ($cocktailId) {
            try {
                $this->cocktailService->clearStickyCocktail(); // Clear current sticky

                $isSticky = false;
                if (!$this->cocktailService->getStickyCocktail() || !$this->cocktailService->getStickyCocktail()->getCocktailId() === $cocktailId) {
                    $this->cocktailService->setStickyCocktail($cocktailId);
                    $isSticky = true;
                }

                echo json_encode([
                    'success' => true,
                    'is_sticky' => $isSticky,
                    'message' => $isSticky ? 'Cocktail set as sticky.' : 'Cocktail unset as sticky.'
                ]);
            } catch (Exception $e) {
                error_log("Failed to toggle sticky cocktail: " . $e->getMessage());
                echo json_encode(['error' => 'Failed to toggle sticky cocktail.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid cocktail ID.']);
        }
        exit;
    }
    public function manageIngredients()
    {
        $categorizedIngredients = $this->ingredientService->getIngredientsByTags();
        require_once __DIR__ . '/../views/admin/manage_ingredients.php';
    }
    // Manage Tags Page
    public function manageTags()
    {
        try {
            $csrfToken = $_SESSION['csrf_token'] ?? '';
            // Get the tags and categories data from the service
            $data = $this->adminService->getTagsAndCategories();
            $groupedTags = $data['groupedTags'] ?? [];
            $categories = $data['categories'] ?? [];

            // Return the grouped tags and categories as a JSON response
            echo json_encode([
                'status' => 'success',
                'csrf_token' => $csrfToken,
                'groupedTags' => $groupedTags,
                'categories' => $categories,
            ]);
        } catch (Exception $e) {
            // If there is an error, return a failure message
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'An unexpected error occurred while fetching the tags and categories.',
            ]);
        }
    }
}
