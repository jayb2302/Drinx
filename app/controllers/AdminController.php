<?php
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/CocktailService.php';
require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../helpers/helpers.php';

class AdminController
{
    private $userRepository;
    private $authController;
    private $cocktailService;

    public function __construct(CocktailService $cocktailService, AuthController $authController)
    {
        $dbConnection = Database::getConnection();
        $this->userRepository = new UserRepository($dbConnection);
        $this->cocktailService = $cocktailService;
        $this->authController = $authController;
    }

    // Update user status (e.g., active, banned, suspended)
    public function updateUserStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && AuthController::isAdmin()) {
            $userId = sanitize($_POST['user_id']);
            $statusId = sanitize($_POST['status_id']);

            // Update account status in the database
            $this->userRepository->updateAccountStatus($userId, $statusId);

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
            $cocktailId = intval($data['cocktail_id'] ?? 0); // Ensure you're using the correct variable

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

    public function manageTags()
    {
        $tagRepository = new TagRepository(Database::getConnection());


        // Pass the $tags variable to the view
        require_once __DIR__ . '/../views/admin/manage_tags.php';
    }
}
