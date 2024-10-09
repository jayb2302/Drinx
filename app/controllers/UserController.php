<?php
require_once __DIR__ . '/../services/UserService.php';

class UserController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function login() {
        include __DIR__ . '/../views/auth/login.php'; // Ensure this path is correct
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];

            $user = $this->userService->authenticateUser($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['user_id']; // Make sure this key matches your user array
                $_SESSION['user_name'] = $user['username'];
                redirect('/'); // Redirect to home after successful login
            } else {
                $_SESSION['error'] = "Invalid email or password.";
                redirect('login'); // Redirect back to login on failure
            }
        }
    }

    public function register() {
        include __DIR__ . '/../views/auth/register.php'; // Ensure this path is correct
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitize($_POST['username']);
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];
            $account_status_id = 1; // Default to active status

            if ($this->userService->registerUser($username, $email, $password, $account_status_id)) {
                redirect('login'); // Redirect to login after successful registration
            } else {
                $_SESSION['error'] = "Registration failed. Please try again.";
                redirect('register'); // Redirect back to register on failure
            }
        }
    }

    public function logout() {
        session_destroy();
        redirect('/'); // Redirect to home after logout
    }
}