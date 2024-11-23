<?php
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/CocktailService.php';
require_once __DIR__ . '/../services/AdminService.php';
require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../helpers/helpers.php';

class AdminController
{
    private $adminService;
    private $authController;
    private $cocktailService;

    public function __construct(
        AdminService $adminService,
        AuthController $authController,
        CocktailService $cocktailService,
    ) {
        $dbConnection = Database::getConnection();
        $this->adminService = $adminService;
        $this->cocktailService = $cocktailService;
        $this->authController = $authController;
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && AuthController::isAdmin()) {
            $userId = sanitize($_POST['user_id']);
            $statusId = sanitize($_POST['status_id']);

            // Update account status in the database
            $result = $this->adminService->updateUserStatus($userId, $statusId);

            // Respond with a success message in JSON format instead of redirecting
            echo json_encode(['status' => 'success', 'message' => 'User status updated successfully.']);
            exit();
        } else {
            // Respond with an error message in JSON format for unauthorized access
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
            exit();
        }
    }

    public function setStickyCocktail()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin']) {
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
        $data = json_decode(file_get_contents('php://input'), true);
        $cocktailId = $data['cocktail_id'] ?? null;

        if ($cocktailId) {
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
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid cocktail ID.']);
        }
        exit;
    }

    // Manage Tags Page
    public function manageTags()
    {
        try {
            // Get the tags and categories data from the service
            $data = $this->adminService->getTagsAndCategories();
            $groupedTags = $data['groupedTags'] ?? [];
            $categories = $data['categories'] ?? [];
    
            // Return the grouped tags and categories as a JSON response
            echo json_encode([
                'status' => 'success',
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
