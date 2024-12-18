<?php

class UserService
{
    private $userRepository;
    private $badgeService;
    private $socialLinkService;

    public function __construct(
        UserRepository $userRepository = null,
        BadgeService $badgeService,
        SocialLinkService $socialLinkService
    ) {
        $this->userRepository = $userRepository ?? new UserRepository(Database::getConnection());
        $this->badgeService = $badgeService;
        $this->socialLinkService = $socialLinkService;
    }


    public function authenticateUser($email, $password)
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            // Update the last_login timestamp
            $user->setLastLogin(date('Y-m-d H:i:s'));
            $this->userRepository->update($user);  // Save the updated login time

            return $user;
        }

        return null;
    }

    // Verify password
    public function verifyPassword($userId, $password)
    {
        $user = $this->userRepository->findById($userId);
        return $user && password_verify($password, $user->getPassword());
    }

    // Delete a user by ID and their associated data (cocktails)
    public function deleteUser($userId)
    {
        return $this->userRepository->deleteUser($userId);
    }

    // Register a new user
    public function registerUser($username, $email, $password, $accountStatusId)
    {
        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Create a new user object and save it to the database
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        $user->setAccountStatusId($accountStatusId);  // Ensure account_status_id is set
        $user->setIsAdmin(false); // Default to a regular user

        if ($this->userRepository->save($user)) {
            // After saving the user, create an entry in the user_profile table
            $userId = $user->getId();
            return $this->userRepository->createUserProfile($userId); // Create user profile with user_id
        }

        return false;
    }

    // Fetch a user by ID
    public function getUserById($userId)
    {
        return $this->userRepository->findById($userId);
    }

    // Fetch user and profile by user ID
    public function getUserWithProfile($userId)
    {
        return $this->userRepository->findByIdWithProfile($userId);
    }

    // Update user details (e.g., profile)
    public function updateUser($userId, $username, $email)
    {
        $user = $this->userRepository->findById($userId);
        if ($user) {
            $user->setUsername($username);
            $user->setEmail($email);
            return $this->userRepository->update($user);
        }
        return false;
    }

    public function getLoggedInUser()
    {
        $userId = $_SESSION['user']['id'] ?? null;
        if ($userId) {
            return $this->getUserById($userId);
        }
        return null; // No user is logged in
    }

    // Change user password
    public function changeUserPassword($userId, $currentPassword, $newPassword)
    {
        $user = $this->userRepository->findById($userId);
        if ($user && password_verify($currentPassword, $user->getPassword())) {
            $user->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
            return $this->userRepository->update($user);
        }
        return false;
    }

    // Update user profile (first name, last name, bio, profile picture)
    public function updateUserProfile($userId, $firstName, $lastName, $bio, $profilePicture = null)
    {
        return $this->userRepository->updateProfile($userId, $firstName, $lastName, $bio, $profilePicture);
    }

    public function getUserByUsername($username)
    {
        return $this->userRepository->findByUsername($username);
    }

    public function getUserStats($userId)
    {
        return $this->userRepository->getUserStats($userId);
    }

    public function searchUsers($query)
    {
        return $this->userRepository->searchUsers($query);
    }
    public function searchAllUsers($query)
    {
        return $this->userRepository->searchAllUsers($query);
    }
    public function followUser($userId, $followedUserId)
    {
        // Print debugging info
        echo "UserService: UserID = $userId, FollowedUserID = $followedUserId";

        if ($userId === $followedUserId) {
            echo "Self-follow attempt in UserService";
            return false; // Prevent self-following
        }

        if (!$this->userRepository->isFollowing($userId, $followedUserId)) {
            return $this->userRepository->followUser($userId, $followedUserId);
        }

        return false; // Already following
    }

    public function unfollowUser($userId, $followedUserId)
    {
        if ($this->userRepository->isFollowing($userId, $followedUserId)) {
            return $this->userRepository->unfollowUser($userId, $followedUserId);
        }
        return false; // Not following
    }

    public function isFollowing($userId, $followedUserId)
    {
        return $this->userRepository->isFollowing($userId, $followedUserId);
    }

    // Get the number of users the user is following
    public function getFollowingCount($userId)
    {
        return $this->userRepository->getFollowingCount($userId);
    }

    // Get the number of followers the user has
    public function getFollowersCount($userId)
    {
        return $this->userRepository->getFollowersCount($userId);
    }

    public function getUserWithFollowCounts($userId)
    {
        $user = $this->userRepository->findByIdWithProfile($userId); // Fetch the user object
    
        if ($user) {
            // Fetch and set the counts
            $user->setFollowingCount($this->userRepository->getFollowingCount($userId));
            $user->setFollowersCount($this->userRepository->getFollowersCount($userId));
        }
    
        return $user;
    }

    public function getAllUsersWithStatus()
    {
        return $this->userRepository->findAllWithStatus();
    }

    public function updateUserAccountStatus($userId, $statusId)
    {
        return $this->userRepository->updateAccountStatus($userId, $statusId);
    }

    // badges
    public function checkAndNotifyNewBadge($userId, $cocktailCount)
    {
        $this->badgeService->checkAndNotifyNewBadge($userId, $cocktailCount);
    }
    public function getUserProgressToNextBadge($profileUserId, $cocktailCount)
    {
        return $this->badgeService->getUserProgressToNextBadge($profileUserId, $cocktailCount);
    }

    // social Media
    public function getAllPlatforms()
    {
        return $this->socialLinkService->getAllPlatforms();
    }
    public function getUserSocialLinks($userId) {
        return $this->socialLinkService->getUserSocialLinks($userId);
    }
    public function updateUserSocialLink($userId, $platformId, $url) {
        return $this->socialLinkService->updateSocialLink($userId, $platformId, $url);
    }
    public function deleteUserSocialLink( $userId, $platformId) {
        return $this->socialLinkService->deleteSocialLink($userId, $platformId);
    }
    public function getSocialFormData($userId)
    {
        return $this->socialLinkService->getSocialFormData($userId);
    }
}
