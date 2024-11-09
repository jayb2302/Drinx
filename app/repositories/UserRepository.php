<?php
require_once __DIR__ . '/../models/User.php';

class UserRepository
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    // Delete a user and associated cocktails
    public function deleteUser($userId)
    {
        try {
            $this->db->beginTransaction();

            // Delete the user's cocktails
            $stmt = $this->db->prepare("DELETE FROM cocktails WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Delete the user
            $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Create a new user profile after registration
    public function createUserProfile($userId)
    {
        $stmt = $this->db->prepare("
            INSERT INTO user_profile (user_id) VALUES (:user_id)
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Find a user by email
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $this->mapToUser($result);
        }
        return null;
    }

    // Find a user by ID
    public function findById($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $this->mapToUser($result);
        }
        return null;
    }

    // Fetch user and profile by user ID
    public function findByIdWithProfile($userId)
    {
        $stmt = $this->db->prepare("
                SELECT u.*, p.first_name, p.last_name, p.profile_picture, p.bio 
                FROM users u 
                LEFT JOIN user_profile p ON u.user_id = p.user_id 
                WHERE u.user_id = :user_id
            ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $this->mapToUserWithProfile($result); // Map to User object with profile data
        }
        return null;
    }

    // Save a new user to the database and return the user ID
    public function save(User $user)
    {
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $accountStatusId = $user->getAccountStatusId();
        $isAdmin = $user->isAdmin();

        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password, account_status_id, join_date, is_admin) 
            VALUES (:username, :email, :password, :account_status_id, CURRENT_TIMESTAMP, :is_admin)
        ");

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':account_status_id', $accountStatusId);
        $stmt->bindParam(':is_admin', $isAdmin, PDO::PARAM_BOOL);

        if ($stmt->execute()) {
            // Fetch the last inserted ID and set it to the user object
            $user->setId($this->db->lastInsertId());
            return true;
        }

        return false;
    }

    // Update an existing user
    public function update(User $user)
    {
        $stmt = $this->db->prepare("
            UPDATE users SET 
                username = :username, 
                email = :email, 
                password = :password, 
                account_status_id = :account_status_id, 
                last_login = :last_login 
            WHERE user_id = :user_id
        ");
        $stmt->bindParam(':username', $user->getUsername());
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':password', $user->getPassword());
        $stmt->bindParam(':account_status_id', $user->getAccountStatusId());
        $stmt->bindParam(':last_login', $user->getLastLogin());
        $stmt->bindParam(':user_id', $user->getId());

        return $stmt->execute();
    }

    // Update profile information
    public function updateProfile($userId, $firstName, $lastName, $bio, $profilePicture)
    {
        $stmt = $this->db->prepare("
            UPDATE user_profile 
            SET first_name = :first_name, last_name = :last_name, bio = :bio, profile_picture = :profile_picture 
            WHERE user_id = :user_id
        ");
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':profile_picture', $profilePicture);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    // Helper function to map DB result to User object
    private function mapToUser($result)
    {
        $user = new User();
        $user->setId($result['user_id']);
        $user->setUsername($result['username']);
        $user->setEmail($result['email']);
        $user->setPassword($result['password']);
        $user->setAccountStatusId($result['account_status_id']);
        $user->setJoinDate($result['join_date']);
        $user->setLastLogin($result['last_login']);
        $user->setIsAdmin($result['is_admin']);
        return $user;
    }
    // Helper function to map DB result to User object (with profile data)
    private function mapToUserWithProfile($result)
    {
        $user = $this->mapToUser($result); // Reuse mapToUser for core fields
        $user->setFirstName($result['first_name'] ?? null);
        $user->setLastName($result['last_name'] ?? null);
        $user->setProfilePicture($result['profile_picture'] ?? null);
        $user->setBio($result['bio'] ?? null);
        return $user;
    }



    public function getUserStats($userId)
    {
        $stmt = $this->db->prepare("
        SELECT 
        COUNT(DISTINCT l.like_id) AS likes_received, 
        COUNT(DISTINCT c.comment_id) AS comments_received
        FROM cocktails ct
        LEFT JOIN likes l ON l.cocktail_id = ct.cocktail_id
        LEFT JOIN comments c ON c.cocktail_id = ct.cocktail_id
        WHERE ct.user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Return as an array
    }

    // Find a user by username
    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, p.first_name, p.last_name, p.profile_picture, p.bio 
            FROM users u 
            LEFT JOIN user_profile p ON u.user_id = p.user_id 
            WHERE u.username = :username
        ");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $this->mapToUserWithProfile($result);
        }
        return null;
    }

    public function searchUsers($query)
    {
        $stmt = $this->db->prepare("SELECT user_id, username FROM users WHERE username LIKE :query LIMIT 5");
        $stmt->execute(['query' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return users as an associative array
    }

    // Method to follow a user
    public function followUser($userId, $followedUserId)
    {
        $stmt = $this->db->prepare("INSERT INTO follows (user_id, followed_user_id) VALUES (:user_id, :followed_user_id)");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':followed_user_id', $followedUserId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Method to unfollow a user
    public function unfollowUser($userId, $followedUserId)
    {
        $stmt = $this->db->prepare("DELETE FROM follows WHERE user_id = :user_id AND followed_user_id = :followed_user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':followed_user_id', $followedUserId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Check if a user is following another user
    public function isFollowing($userId, $followedUserId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM follows WHERE user_id = :user_id AND followed_user_id = :followed_user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':followed_user_id', $followedUserId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Update a user's account status
    public function updateAccountStatus($userId, $statusId)
    {
        $stmt = $this->db->prepare("UPDATE users SET account_status_id = :status_id WHERE user_id = :user_id");
        $stmt->bindParam(':status_id', $statusId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        $result = $stmt->execute();
        if (!$result) {
            error_log("Failed to update account status: user_id = $userId, status_id = $statusId");
        }
        return $result;
    }

    public function findAllWithStatus()
    {
        $stmt = $this->db->prepare("
            SELECT u.user_id, u.username, u.email, u.account_status_id, a.status_name AS account_status
            FROM users u
            JOIN account_status a ON u.account_status_id = a.account_status_id
        ");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $result) {
            $users[] = $this->mapToUserWithStatus($result);
        }
        return $users;
    }


    // Helper function to map user data with status
    private function mapToUserWithStatus($result)
    {
        $user = new User();
        $user->setId($result['user_id']);
        $user->setUsername($result['username']);
        $user->setEmail($result['email']);
        $user->setAccountStatusId($result['account_status_id']);
        $user->setAccountStatusName($result['account_status']);
        return $user;
    }
}
