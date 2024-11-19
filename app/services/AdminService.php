<?php
class AdminService
{
    private $userRepository;
    private $cocktailRepository;
    private $ingredientRepository;
    private $tagRepository;
    private $commentRepository;

    public function __construct(
        UserRepository $userRepository,
        CocktailRepository $cocktailRepository,
        IngredientRepository $ingredientRepository,
        TagRepository $tagRepository,
        CommentRepository $commentRepository
    ) {
        $this->userRepository = $userRepository;
        $this->cocktailRepository = $cocktailRepository;
        $this->ingredientRepository = $ingredientRepository;
        $this->tagRepository = $tagRepository;
        $this->commentRepository = $commentRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function updateUserStatus($userId, $statusId)
    {
        return $this->userRepository->updateAccountStatus($userId, $statusId);
    }
    public function getTagsAndCategories()
    {
        $tags = $this->tagRepository->getAllTags();
        $categories = $this->tagRepository->getAllTagCategories();

        error_log('Tags: ' . print_r($tags, true)); // Debugging
        error_log('Categories: ' . print_r($categories, true)); // Debugging

        return [
            'groupedTags' => $this->groupTagsByCategory($tags),
            'categories' => $categories,
        ];
    }
    private function groupTagsByCategory(array $tags)
    {
        $grouped = [];
        foreach ($tags as $tag) {
            // Ensure category_name exists in the tag
            $category = $tag['category_name'] ?? 'Uncategorized';
            $grouped[$category][] = $tag;
        }
        return $grouped;
    }
    public function getDashboardData()
    {
        // Fetch stats for the dashboard
        $stats = $this->getDashboardStats();

        // Fetch all users
        $users = $this->userRepository->getAllUsers();
        $cocktails = $this->cocktailRepository->getAll();
        // Fetch all tags grouped by category
        $tags = $this->tagRepository->getAllTags();
        $tagCategories = $this->tagRepository->getAllTagCategories();
        // Handle Top Creator logic
        if ($stats['userWithMostRecipes'] instanceof User) {
            $topCreator = $stats['userWithMostRecipes'];
            $stats['userWithMostRecipes'] = [
                'username' => $topCreator->getUsername(),
                'profile_picture' => $topCreator->getProfilePicture() ?? '/uploads/users/user-default.svg',
                'recipes_count' => $topCreator->getRecipeCount(),
            ];
        } else {
            $stats['userWithMostRecipes'] = [
                'username' => 'N/A',
                'profile_picture' => '/uploads/users/user-default.svg',
                'recipes_count' => 0,
            ];
        }


        return [
            'stats' => $stats,
            'users' => $users,
            'cocktails' => $cocktails,
            'groupedTags' => $this->groupTagsByCategory($tags),
            'tagCategories' => $tagCategories,
        ];
    }
    public function getDashboardStats()
    {
        $topCreator = $this->userRepository->getUserWithMostRecipes();
        return [
            'totalUsers' => $this->userRepository->countUsers(),
            'totalCocktails' => $this->cocktailRepository->countCocktails(),
            'totalIngredients' => $this->ingredientRepository->countIngredients(),
            'totalTags' => $this->tagRepository->countTags(),
            'totalComments' => $this->commentRepository->countComments(),
            'mostUsedIngredient' => $this->ingredientRepository->getMostUsedIngredient()['name'] ?? 'N/A',
            'mostPopularCocktail' => $this->cocktailRepository->getMostPopularCocktail()['title'] ?? 'N/A',
            'unusedTags' => $this->tagRepository->countUnusedTags(),
            'cocktailsWithoutComments' => $this->cocktailRepository->countCocktailsWithoutComments(),
            'userWithMostRecipes' => $this->userRepository->getUserWithMostRecipes(), // Return User object
        ];
    }
}
