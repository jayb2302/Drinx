<?php
require_once __DIR__ . '/../models/User.php';

class UserRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    // Get all users
    public function getAllUsers()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $result) {
            $users[] = $this->mapToUser($result);
        }
        return $users;
    }

    // Count the number of users
    public function countUsers()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    public function getUserWithMostRecipes()
    {
        $query = "
            SELECT 
                u.user_id,
                u.username,
                p.profile_picture,
                COUNT(c.cocktail_id) AS recipe_count
            FROM users u
            LEFT JOIN cocktails c ON u.user_id = c.user_id
            LEFT JOIN user_profile p ON u.user_id = p.user_id
            GROUP BY u.user_id
            ORDER BY recipe_count DESC
            LIMIT 1
        ";

        $result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $user = new User();
            $user->setId($result['user_id']);
            $user->setUsername($result['username']);
            $user->setProfilePicture($result['profile_picture']);
            $user->setRecipeCount($result['recipe_count']);
            return $user;
        }

        return null; // No top creator found
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
        $query = "
        UPDATE user_profile 
        SET first_name = :first_name, 
            last_name = :last_name, 
            bio = :bio";

        // Append profile_picture only if it's provided
        if ($profilePicture !== null) {
            $query .= ", profile_picture = :profile_picture";
        }

        $query .= " WHERE user_id = :user_id";

        $stmt = $this->db->prepare($query);

        // Bind required parameters
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        // Bind profile_picture only if it's provided
        if ($profilePicture !== null) {
            $stmt->bindParam(':profile_picture', $profilePicture);
        }

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
        $stmt = $this->db->prepare("
            SELECT u.user_id, u.username, 
                   COALESCE(p.profile_picture, 'user-default.svg') AS profile_picture
            FROM users u
            LEFT JOIN user_profile p ON u.user_id = p.user_id
            WHERE u.username LIKE :query
            LIMIT 5
        ");
        $stmt->execute(['query' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return users as an associative array
    }
    public function searchAllUsers($query = null)
    {
        $sql = "
            SELECT u.user_id, u.username, u.email, u.account_status_id,
                   CASE 
                       WHEN u.account_status_id = 1 THEN 'Active'
                       WHEN u.account_status_id = 2 THEN 'Suspended'
                       WHEN u.account_status_id = 3 THEN 'Banned'
                       ELSE 'Unknown'
                   END AS account_status_name,
                   COALESCE(p.profile_picture, 'user-default.svg') AS profile_picture
            FROM users u
            LEFT JOIN user_profile p ON u.user_id = p.user_id
        ";

        // Add filtering if a query is provided
        if ($query) {
            $sql .= " WHERE u.username LIKE :query";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($query ? ['query' => "%$query%"] : []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    // Count the number of users a user is following
    public function getFollowingCount($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM follows WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // Count the number of followers a user has
    public function getFollowersCount($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM follows WHERE followed_user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
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
