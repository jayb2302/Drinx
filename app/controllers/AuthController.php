<?php
require_once __DIR__ . '/../services/UserService.php';

class AuthController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    // Handle user authentication
    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];

            $user = $this->userService->authenticateUser($email, $password);

            if ($user) {
                // Create session for the user
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'is_admin' => $user->isAdmin(),
                ];

                // Redirect to the home page after successful login
                header("Location: /"); 
                exit(); 
            } else {
                // Handle invalid credentials
                $_SESSION['error'] = "Invalid email or password.";
                header("Location: /login");  // Redirect back to the login form
                exit();
            }
        }
    }

    // Show the login form
    public function showLogin()
    {
        return require_once __DIR__ . '/../views/auth/login.php';

    }
    // Show the registration form
    public function showRegister()
    {
        return require_once __DIR__ . '/../views/auth/register.php';
    }


    // Handle user registration
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitize($_POST['username']);
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];

            try {
                if ($this->userService->registerUser($username, $email, $password, 1)) {
                    // Set success message in session
                    $_SESSION['success'] = "Welcome $username! Please log in.";
                    header("Location: /login");
                    exit();
                }
            } catch (PDOException $e) {
                // Check if the error is a duplicate entry for username or email
                if ($e->getCode() == 23000) { // Integrity constraint violation (duplicate entry)
                    // Check which field is causing the issue
                    if (strpos($e->getMessage(), 'username') !== false) {
                        $_SESSION['error'] = "The username '$username' is already taken. Please choose another one.";
                    } elseif (strpos($e->getMessage(), 'email') !== false) {
                        $_SESSION['error'] = "The email '$email' is already registered. Please use a different email.";
                    } else {
                        $_SESSION['error'] = "Username or email already exists. Please try a different one.";
                    }
                } else {
                    $_SESSION['error'] = "Something went wrong. Please try again.";
                }
                header("Location: /egister");
                exit();
            }
        }
    }

    public function logout()
    {
        // Store the success message temporarily in a session variable
        setcookie('logout_success', 'You have been logged out successfully.', time() + 10, "/");

        // Destroy the current session
        session_destroy();

        // Ensure the session cookie is cleared
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Start a new session to display the success message
        session_start();

        // Move the temp_success message to a new success message
        $_SESSION['success'] = $_SESSION['temp_success'];
        unset($_SESSION['temp_success']); // Remove the temporary session

        // Redirect to the home 
        header("Location: /");
        exit();
    }

    // Check if the user is logged in
    public static function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }

    // Check if the current user is an admin
    public static function isAdmin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['is_admin'];
    }
}
