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
        $likeRepository = new LikeRepository($dbConnection);  // Add this line if it was missing


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
            $likeRepository
        );

        $this->userService = new UserService();
        $this->badgeService = new BadgeService();
    }

    // 1. Show the user profile
    public function profile()
    {
        if (!AuthController::isLoggedIn()) {
            redirect('login');
        }

        $userId = $_SESSION['user']['id'];
        // Fetch user profile data with user ID
        $profile = $this->userService->getUserWithProfile($userId);
        $userRecipes = $this->cocktailService->getUserRecipes($userId);
        $userBadges = $this->badgeService->getUserBadges($userId);
        $profileStats = $this->userService->getUserStats($userId);

        // Pass the profile data to the view
        require_once __DIR__ . '/../views/user/profile.php';
    }


    // 2. Show user settings
    public function settings()
    {
        if (!AuthController::isLoggedIn()) {
            redirect('login');  // Redirect to login if not logged in
        }

        $user = $this->userService->getUserById($_SESSION['user']['id']);  // Fetch user data
        require_once __DIR__ . '/../views/user/settings.php';  // Show settings view
    }

    // 3. Delete user account
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

    // 4. Update user profile (username, email)
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
            }

            // Call the service to update the profile
            if ($this->userService->updateUserProfile($_SESSION['user']['id'], $firstName, $lastName, $bio, $profilePicture)) {
                $_SESSION['success'] = "Profile updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update profile.";
            }

            redirect('profile');
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
            echo "User not found.";
            return;
        }

        $userId = $profile->getId();
        $userRecipes = $this->cocktailService->getUserRecipes($userId);
        $userBadges = $this->badgeService->getUserBadges($userId);
        $profileStats = $this->userService->getUserStats($userId);

        // Pass the profile data to the view
        require_once __DIR__ . '/../views/user/profile.php';
    }
}