<?php
require_once __DIR__ . '/../repositories/UserRepository.php';

class UserService {
    private $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function registerUser($username, $email, $password, $account_status_id) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = new User($username, $email, $hashedPassword, $account_status_id);
        return $this->userRepository->create($user);
    }

    public function authenticateUser($email, $password) {
        $user = $this->userRepository->getByEmail($email);
        return $user && password_verify($password, $user['password']) ? $user : null; // Access password from the associative array
    }
}
