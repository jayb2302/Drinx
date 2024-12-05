<?php
require_once 'BaseController.php';

class UserController extends BaseController
{
    private $imageService;
    private $badgeService;

    public function __construct(
        AuthService $authService,
        UserService $userService,
        CocktailService $cocktailService,
        ImageService $imageService,
        BadgeService $badgeService
    ) {
        parent::__construct($authService, $userService, $cocktailService);
        $this->imageService = $imageService;
        $this->badgeService = $badgeService;
    }

    // Show the user profile
    public function profile($profileUserId)
    {
        if (!$this->authService->isAdmin()) {
            redirect('login');
        }
        $userId = $_SESSION['user']['id'] ?? null; // Use null coalescing operator to avoid undefined error
        $loggedInUserId = $userId;

        // Fetch user profile data with user ID
        $profile = $this->userService->getUserWithProfile($profileUserId);
        if ($profile) {
            error_log('Profile fetched: ' . print_r($profile, true));
        } else {
            error_log('Profile not found for User ID: ' . $profileUserId);
        }
        $userRecipes = $this->cocktailService->getUserRecipes($profileUserId);
        $badges = $this->badgeService->getUserBadges($profileUserId);
        $profileStats = $this->userService->getUserStats($profileUserId);
        $isFollowing = $this->userService->isFollowing($loggedInUserId, $profileUserId); // Check if current user is following the profile user

        // Fetch cocktail count and progress to next badge
        $cocktailCount = $this->cocktailService->getCocktailCountByUserId($profileUserId);
        // error_log("Cocktail Count: $cocktailCount");

        $progressData = $this->badgeService->getUserProgressToNextBadge($profileUserId, $cocktailCount);
        // error_log("Progress Data: " . print_r($progressData, true));


        // Pass the profile data to the view
        require_once __DIR__ . '/../views/user/profile.php';
    }

    // Show user settings
    public function settings()
    {
        if (!$this->authService->isLoggedIn()) {
            redirect('login');  // Redirect to login if not logged in
        }

        $user = $this->userService->getUserById($_SESSION['user']['id']);  // Fetch user data
        require_once __DIR__ . '/../views/user/settings.php';  // Show settings view
    }

    // Delete user account
    public function deleteAccount()
    {
        if (!$this->authService->isLoggedIn()) {
            redirect('login');
        }

        $userId = $_SESSION['user']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = sanitize($_POST['password']);

            // Verify the user's password before proceeding
            if ($this->userService->verifyPassword($userId, $password)) {
                // Delete the user and associated data
                if ($this->userService->deleteUser($userId)) {
                    setcookie('account_deleted_success', 'Account deleted successfully.', time() + 10, "/");
                    session_destroy(); // End session after deletion
                    redirect('/');
                } else {
                    $_SESSION['error'] = 'Failed to delete the account.';
                }
            } else {
                $_SESSION['error'] = 'Incorrect password. Please try again.';
            }
        }

        // Redirect back to profile if deletion failed or password was incorrect
        redirect('profile');
    }

    // Update user profile (username, email)
    public function updateProfile()
    {
        if (!$this->authService->isLoggedIn()) {
            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'];

            // Fetch the user's current username
            $user = $this->userService->getUserById($userId);
            $username = $user->getUsername(); // Ensure this method exists in the User model
            $currentProfilePicture = $user->getProfilePicture(); // Get the current profile picture

            $firstName = sanitize($_POST['first_name']);
            $lastName = sanitize($_POST['last_name']);
            $bio = sanitize($_POST['bio']);

            // Handle file upload if a new profile picture is uploaded
            $profilePicture = $currentProfilePicture;
            // Handle file upload if a new profile picture is uploaded
            if (!empty($_FILES['profile_picture']['name'])) {
                $uploadedPicture = $this->uploadProfilePicture($_FILES['profile_picture']);
                if ($uploadedPicture) {
                    $profilePicture = $uploadedPicture; // Use the new profile picture
                } else {
                    $_SESSION['error'] = "Failed to upload profile picture.";
                    redirect("profile/$username");
                    return; // Exit to avoid overwriting the current picture
                }
            }

            // Call the service to update the profile
            if ($this->userService->updateUserProfile($userId, $firstName, $lastName, $bio, $profilePicture)) {
                $_SESSION['success'] = "Profile updated successfully.";
                redirect("profile/$username"); // Redirect to the username-based profile page
            } else {
                $_SESSION['error'] = "Failed to update profile.";
                redirect("profile/$username"); // Redirect back to the profile
            }
        }
    }


    private function uploadProfilePicture($file)
    {
        try {
            if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception("No valid file uploaded.");
            }
            // Validate file size
            if ($file['size'] > $this->imageService->maxFileSize) {
                throw new \Exception("The file size exceeds the allowed limit of 5MB.");
            }

            // Generate a unique file name
            $uniqueFileName = uniqid() . '.webp';
            $targetPath = __DIR__ . '/../../public/uploads/users/' . $uniqueFileName;

            $width = 400; // Set your desired width
            $height = 400; // Set your desired height
            // Process and save the image
            $this->imageService->processImage($file, $width, $height, $targetPath);
            return $uniqueFileName; // Return the unique file name
        } catch (\Exception $e) {
            $_SESSION['error'] = "Failed to upload profile picture: " . $e->getMessage();

            // Optional: Log the detailed error for debugging
            // error_log("Profile picture upload error: " . $e->getMessage());

            return null; // Return null if image processing fails
        }
    }


    public function changePassword()
    {
        if (!$this->authService->isLoggedIn()) {
            redirect('login');  // Redirect to login if not logged in
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = sanitize($_POST['current_password']);
            $newPassword = sanitize($_POST['new_password']);

            // Attempt to change password using the service
            if ($this->userService->changeUserPassword($_SESSION['user']['id'], $currentPassword, $newPassword)) {
                $_SESSION['success'] = "Password changed successfully.";  // Success message
            } else {
                $_SESSION['error'] = "Failed to change password. Please try again.";  // Error message
            }

            redirect('settings');  // Redirect back to settings page
        }
    }

    public function profileByUsername($username)
    {
        if (!$this->authService->isLoggedIn()) {
            redirect('login');
        }
        $username = sanitize($username);
        // Fetch user profile by username
        $profile = $this->userService->getUserByUsername($username);

        if (!$profile) {
            // If no profile is found, you can redirect to a 404 page or show a message
            http_response_code(404);
            include '../app/views/404.php'; // Load a 404 error page
            return;
        }

        $profileUserId = $profile->getId(); // Get the profile user's ID
        $userId = $_SESSION['user']['id']; // Get the current user's ID

        // Check if current user is following the profile user
        $isFollowing = $this->userService->isFollowing($userId, $profileUserId);

        $userRecipes = $this->cocktailService->getUserRecipes($profileUserId);
        $userBadges = $this->badgeService->getUserBadges($profileUserId);
        $profileStats = $this->userService->getUserStats($profileUserId);

        // Fetch cocktail count and progress to next badge
        $cocktailCount = $this->cocktailService->getCocktailCountByUserId($profileUserId);
        // error_log("Cocktail Count: $cocktailCount");

        $progressData = $this->badgeService->getUserProgressToNextBadge($profileUserId, $cocktailCount);
        // error_log("Progress Data: " . print_r($progressData, true));

        // Pass the profile data to the view
        require_once __DIR__ . '/../views/user/profile.php';
    }

    // Follow a user
    public function follow($followedUserId)
    {
        if (!$this->authService->isLoggedIn()) {
            redirect('login');
        }

        $userId = $_SESSION['user']['id'];

        // Debugging output
        echo "Attempting to follow: UserID = $userId, FollowedUserID = $followedUserId";

        if ($userId === $followedUserId) {
            $_SESSION['error'] = "You cannot follow yourself.";
            redirect("profile/$userId");
            return;
        }

        $followSuccess = $this->userService->followUser($userId, $followedUserId);
        if ($followSuccess) {
            $_SESSION['success'] = "User followed successfully.";
        } else {
            $_SESSION['error'] = "Failed to follow user or already following.";
        }

        redirect("profile/$followedUserId");
    }

    // Unfollow a user
    public function unfollow($followedUserId)
    {
        if (!$this->authService->isLoggedIn()) {
            redirect('login');
        }

        $userId = $_SESSION['user']['id'];
        if ($this->userService->unfollowUser($userId, $followedUserId)) {
            $_SESSION['success'] = "User unfollowed successfully.";
        } else {
            $_SESSION['error'] = "Failed to unfollow user or not currently following.";
        }

        redirect("profile/$followedUserId");
    }
}
