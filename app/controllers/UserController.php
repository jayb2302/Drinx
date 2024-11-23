<?php
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/CocktailService.php';
require_once __DIR__ . '/../services/BadgeService.php';
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../repositories/DifficultyRepository.php';
require_once __DIR__ . '/../repositories/UnitRepository.php';  // Add this
require_once __DIR__ . '/../services/IngredientService.php';   // Add this
require_once __DIR__ . '/../services/StepService.php';         // Add this

class UserController
{
    private $userService;
    private $cocktailService;
    private $badgeService;

    public function __construct()
    {
        $dbConnection = Database::getConnection(); // Assuming you have a method to get DB connection

        // Initialize repositories
        $cocktailRepository = new CocktailRepository($dbConnection);
        $categoryRepository = new CategoryRepository($dbConnection);
        $ingredientRepository = new IngredientRepository($dbConnection);
        $stepRepository = new StepRepository($dbConnection);
        $tagRepository = new TagRepository($dbConnection);
        $difficultyRepository = new DifficultyRepository($dbConnection);
        $unitRepository = new UnitRepository($dbConnection);  // Instantiate UnitRepository
        $likeRepository = new LikeRepository($dbConnection);  
        $userRepository = new UserRepository($dbConnection);
        $commentRepository = new CommentRepository($dbConnection);  // Instantiate CommentRepository


        // Initialize services
        $ingredientService = new IngredientService($ingredientRepository, $unitRepository);  // Use service instead of repository
        $stepService = new StepService($stepRepository);  // Use service instead of repository

        // Pass repositories and services into CocktailService
        $this->cocktailService = new CocktailService(
            $cocktailRepository,
            $categoryRepository,
            $ingredientService,   // Pass IngredientService
            $stepService,         // Pass StepService
            $tagRepository,
            $difficultyRepository,
            $likeRepository,
            $userRepository,
            $commentRepository
            
        );

        $this->userService = new UserService();
        $this->badgeService = new BadgeService();
    }

    // Show the user profile
    public function profile($profileUserId)
    {
        if (!AuthController::isLoggedIn()) {
            redirect('login');
        }

        $userId = $_SESSION['user']['id'];
        // Fetch user profile data with user ID
        $profile = $this->userService->getUserWithProfile($profileUserId);
        if ($profile) {
            error_log('Profile fetched: ' . print_r($profile, true));
        } else {
            error_log('Profile not found for User ID: ' . $profileUserId);
        }
        $userRecipes = $this->cocktailService->getUserRecipes($userId);
        $userBadges = $this->badgeService->getUserBadges($userId);
        $profileStats = $this->userService->getUserStats($userId);
        $isFollowing = $this->userService->isFollowing($userId, $profileUserId); // Check if current user is following the profile user

        // Pass the profile data to the view
        require_once __DIR__ . '/../views/user/profile.php';
    }

    // Show user settings
    public function settings()
    {
        if (!AuthController::isLoggedIn()) {
            redirect('login');  // Redirect to login if not logged in
        }

        $user = $this->userService->getUserById($_SESSION['user']['id']);  // Fetch user data
        require_once __DIR__ . '/../views/user/settings.php';  // Show settings view
    }

    // Delete user account
    public function deleteAccount()
    {
        if (!AuthController::isLoggedIn()) {
            redirect('login');
        }

        $userId = $_SESSION['user']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = sanitize($_POST['password']);

            // Verify the user's password before proceeding
            if ($this->userService->verifyPassword($userId, $password)) {
                // Delete the user and associated data
                if ($this->userService->deleteUser($userId)) {
                    session_destroy();  // End session after deletion
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
        if (!AuthController::isLoggedIn()) {
            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = sanitize($_POST['first_name']);
            $lastName = sanitize($_POST['last_name']);
            $bio = sanitize($_POST['bio']);

            // Handle file upload if a new profile picture is uploaded
            $profilePicture = null;
            if (!empty($_FILES['profile_picture']['name'])) {
                $profilePicture = $this->uploadProfilePicture($_FILES['profile_picture']);
                if (!$profilePicture) {
                    // Handle the error from uploadProfilePicture if needed
                    $_SESSION['error'] = "Failed to upload profile picture.";
                    redirect('profile/' . $_SESSION['user']['id']); // Redirect back to the profile
                    return; // Exit the method
                }
            }
            $userId = $_SESSION['user']['id'];
            // Call the service to update the profile
            if ($this->userService->updateUserProfile($userId, $firstName, $lastName, $bio, $profilePicture)) {
                $_SESSION['success'] = "Profile updated successfully.";
                
                // Fetch the updated user information to get the username
                $updatedUser = $this->userService->getUserById($userId);
                $username = $updatedUser->getUsername(); // Assuming getUsername() retrieves the username
    
                // Redirect to the profile using the username
                redirect("profile/$username");
            } else {
                $_SESSION['error'] = "Failed to update profile.";
            }
    
            // If there was an error, redirect back to the profile
            redirect("profile/$userId"); // Fallback to user ID in case of failure
        }
    }

    private function uploadProfilePicture($file)
    {
        $targetDir = __DIR__ . '/../../public/uploads/users/';

        // Step 1: Sanitize and validate the original filename
        $fileName = sanitize($file['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Step 2: Check if the file extension is allowed
        $allowedTypes = ['jpeg', 'jpg', 'png', 'webp'];
        if (!in_array($fileExtension, $allowedTypes)) {
            $_SESSION['error'] = "Invalid image format. Allowed formats are JPEG, PNG, and WEBP.";
            return null; // Exit if the file type is not allowed
        }

        // Step 3: Generate a unique filename to avoid conflicts
        $uniqueFileName = bin2hex(random_bytes(8)) . '.' . $fileExtension;
        $targetFile = $targetDir . $uniqueFileName;

        // Step 4: Move the uploaded file to the target directory
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $uniqueFileName; // Return the unique filename for storage in the database
        }

        // Return null if the upload failed
        $_SESSION['error'] = "Failed to upload profile picture.";
        return null;
    }

    // 5. Change user password
    public function changePassword()
    {
        if (!AuthController::isLoggedIn()) {
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
        if (!AuthController::isLoggedIn()) {
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

        $userRecipes = $this->cocktailService->getUserRecipes($userId);
        $userBadges = $this->badgeService->getUserBadges($userId);
        $profileStats = $this->userService->getUserStats($userId);

        // Pass the profile data to the view
        require_once __DIR__ . '/../views/user/profile.php';
    }

    // Follow a user
    public function follow($followedUserId)
    {
        if (!AuthController::isLoggedIn()) {
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
        if (!AuthController::isLoggedIn()) {
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
