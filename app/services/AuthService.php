<?php 
require_once __DIR__ . '/../models/User.php';

class AuthService{
    private $userRepository;

    public function __construct(
        userRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

        // Check if the user is logged in
        public function isLoggedIn()
        {
            return isset($_SESSION['user']);
        }
    
        // Check if the current user is an admin
        public function isAdmin()
        {
            return isset($_SESSION['user']) && $_SESSION['user']['is_admin'];
        }
    
        public function getCurrentUser()
        {
            if (!isset($_SESSION['user'])) {
                return null;
            }
    
            // Retrieve user data from the session
            $user = new User();
            $user->setId($_SESSION['user']['id']);
            $user->setUsername($_SESSION['user']['username']);
            $user->setIsAdmin($_SESSION['user']['is_admin']);
            $user->setAccountStatusId($_SESSION['user']['account_status']);
            
            return $user;
        }
}