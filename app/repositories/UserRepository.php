<?php
require_once __DIR__ . '/../config/database.php';

class UserRepository {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getById($user_id) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE user_id = :id');
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByEmail($email) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($userData) {
        $stmt = $this->db->prepare('INSERT INTO users (username, email, password, account_status_id) VALUES (:username, :email, :password, :account_status_id)');
        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password', $userData['password']);
        $stmt->bindParam(':account_status_id', $userData['account_status_id']);
        return $stmt->execute();
    }

    public function update($user_id, $userData) {
        $stmt = $this->db->prepare('UPDATE users SET username = :username, email = :email, password = :password, account_status_id = :account_status_id WHERE user_id = :id');
        $stmt->bindParam(':id', $user_id);
        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password', $userData['password']);
        $stmt->bindParam(':account_status_id', $userData['account_status_id']);
        return $stmt->execute();
    }

    public function delete($user_id) {
        $stmt = $this->db->prepare('DELETE FROM users WHERE user_id = :id');
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }
}