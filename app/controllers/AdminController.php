<?php
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../helpers/helpers.php';

class AdminController
{
    private $userRepository;

    public function __construct()
    {
        $dbConnection = Database::getConnection();
        $this->userRepository = new UserRepository($dbConnection);
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

}
