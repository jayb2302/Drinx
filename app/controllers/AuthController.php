<?php

require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../helpers/helpers.php'; // Include your helpers file for global utility functions


class AuthController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();

        // Check if session has already started, if not, start it
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Login page display
    public function login() {
        // If the user is already logged in, return an error message or redirect logic can be handled in the JS
        if ($this->isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'User is already logged in.']);
            return;
        }
        
        // Display the login form
        include __DIR__ . '/../views/auth/login.php';
    }

    // Handle user authentication
    public function authenticate() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Authenticate method called"); // Add this line for debugging

            $email = sanitize($_POST['email']);
            $password = $_POST['password'];

            // Authenticate user through the UserService
            $user = $this->userService->authenticateUser($email, $password);

            if ($user) {
                // Set session data for the logged-in user
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['login_time'] = time(); // Store login time for session expiration checks
                $_SESSION['sessionDate'] = date('Y-m-d H:i:s'); // Store login date/time
                session_regenerate_id(true); // Prevent session fixation attacks

                echo json_encode(['success' => true, 'message' => 'Login successful.']); // Return success message
            } 
            else {
                // This will help identify if the method is not being called
                error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
            }
            exit; // Ensure the script ends after handling the request
        }
    }


    // Display the registration page
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleRegistration(); // Handle registration form submission
        } else {
            unset($_SESSION['error']); // Clear previous error messages, if any
            include __DIR__ . '/../views/auth/register.php'; // Include the registration form view
        }
    }

    // Handle user registration (via AJAX or form submission)
    private function handleRegistration() {
        header('Content-Type: application/json');

        $username = sanitize($_POST['username']);
        $email = sanitize($_POST['email']);
        $password = $_POST['password'];
        $account_status_id = 1; // Default account status to "active"

        // Validate form inputs
        if (empty($username) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            return;
        }

        // Call UserService to register the user
        $result = $this->userService->registerUser($username, $email, $password, $account_status_id);

        if (!$result['success']) {
            echo json_encode(['success' => false, 'message' => $result['message']]);
        } else {
            echo json_encode(['success' => true, 'message' => 'Registration successful!']);
        }
    }

    // Log the user out and destroy the session
    public function logout() {
        // Destroy the session and redirect to the homepage
        $_SESSION = [];
        session_destroy();
        redirect('/');
    }

    // Check if the user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}