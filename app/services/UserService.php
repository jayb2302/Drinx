<?php
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../models/User.php';

class UserService {
    private $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function registerUser($username, $email, $password, $account_status_id) {
        // Check if username or email already exists
        if ($this->userRepository->usernameExists($username)) {
            return ['success' => false, 'message' => 'Username already exists.'];
        }
    
        if ($this->userRepository->emailExists($email)) {
            return ['success' => false, 'message' => 'Email already exists.'];
        }
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = new User($username, $email, $hashedPassword, $account_status_id);
        
        $isCreated = $this->userRepository->create($user);
        
        if ($isCreated) {
            return ['success' => true, 'message' => 'Registration successful!'];
        } else {
            return ['success' => false, 'message' => 'Failed to register user. Please try again.'];
        }
    }
    

    public function authenticateUser($email, $password) {
        // Retrieve the user by email
        $user = $this->userRepository->getByEmail($email);
    
        // Check if user exists and password matches
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Return user data on success
        }
    
        return false; // Return false if authentication fails
    }
}
