<?php
require_once __DIR__ . '/../repositories/CommentRepository.php';
require_once __DIR__ . '/../repositories/CocktailRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/StepRepository.php';
require_once __DIR__ . '/../repositories/TagRepository.php';
require_once __DIR__ . '/../repositories/DifficultyRepository.php';
require_once __DIR__ . '/../repositories/LikeRepository.php';


class CocktailService
{
    private $commentRepository;
    private $cocktailRepository;
    private $categoryRepository;
    private $ingredientService;
    private $stepService;
    private $tagRepository;
    private $difficultyRepository;
    private $likeRepository;
    private $userRepository;

    public function __construct(
        CocktailRepository $cocktailRepository,
        CategoryRepository $categoryRepository,
        IngredientService $ingredientService,
        StepService $stepService,
        TagRepository $tagRepository,
        DifficultyRepository $difficultyRepository,
        LikeRepository $likeRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository
    ) {
        $this->cocktailRepository = $cocktailRepository;
        $this->categoryRepository = $categoryRepository;
        $this->ingredientService = $ingredientService;
        $this->stepService = $stepService;
        $this->tagRepository = $tagRepository;
        $this->difficultyRepository = $difficultyRepository;
        $this->likeRepository = $likeRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
    }

    // Cocktail CRUD operations
    public function getCocktailById($cocktailId)
    {
        return $this->cocktailRepository->getById($cocktailId);
    }

    public function getCocktailByName($name)
    {
        return $this->cocktailRepository->getByName($name);
    }

    public function getAllCocktails()
    {
        return $this->cocktailRepository->getAll();
    }

    public function countCocktails()
    {
        return $this->cocktailRepository->countCocktails();
    }

    public function createCocktail($cocktailData)
    {
        return $this->cocktailRepository->create($cocktailData);
    }

    public function updateCocktail($cocktailId, $cocktailData)
    {
        return $this->cocktailRepository->update($cocktailId, $cocktailData);
    }

    public function deleteCocktail($cocktailId)
    {
        return $this->cocktailRepository->delete($cocktailId);
    }

    // Delegate ingredient operations to IngredientService
    public function getCocktailIngredients($cocktailId)
    {
        return $this->ingredientService->getIngredientsByCocktailId($cocktailId);  // Call the service
    }

    public function handleCocktailIngredients($cocktailId, $ingredients, $quantities, $units)
    {
        // Delegate ingredient handling to IngredientService
        $this->ingredientService->updateIngredients($cocktailId, $ingredients, $quantities, $units);
    }

    // Delegate step operations to StepService
    public function getCocktailSteps($cocktailId)
    {
        return $this->stepService->getStepsByCocktailId($cocktailId);  // Call the service
    }

    public function handleCocktailSteps($cocktailId, $steps)
    {
        // Delegate step handling to StepService
        $this->stepService->updateSteps($cocktailId, $steps);
    }

    // Category-related operations
    public function getCategories()
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function getCategoryByCocktailId($cocktailId)
    {
        return $this->categoryRepository->getCategoryByCocktailId($cocktailId);
    }

    public function getUserRecipes($userId)
    {
        return $this->cocktailRepository->findByUserId($userId);
    }

    // Tag-related operations
    public function getCocktailTags($cocktailId)
    {
        return $this->tagRepository->getTagsByCocktailId($cocktailId);
    }

    public function addTagToCocktail($cocktailId, $tagId)
    {
        return $this->tagRepository->addTagToCocktail($cocktailId, $tagId);
    }

    // public function removeTagFromCocktail($cocktailId, $tagId)
    // {
    //     return $this->tagRepository->removeTagFromCocktail($cocktailId, $tagId);
    // }

    public function getAllTags()
    {
        return $this->tagRepository->getAllTags();
    }

    // Delegate ingredient clearing to IngredientService
    public function clearIngredients($cocktailId)
    {
        return $this->ingredientService->clearIngredientsByCocktailId($cocktailId);  // Use the service
    }

    public function getAllUnits()
    {
        return $this->ingredientService->getAllUnits();
    }

    public function getLikesForCocktail($cocktailId)
    {
        return $this->likeRepository->getLikesForCocktail($cocktailId);
    }

    public function getLikeCount($cocktailId)
    {
        return $this->likeRepository->getLikesForCocktail($cocktailId);
    }

    public function getCocktailsSortedByDate()
    {
        return $this->cocktailRepository->getAllSortedByDate();
    }

    public function getCocktailsSortedByLikes()
    {
        return $this->cocktailRepository->getAllSortedByLikes();
    }
    public function getCocktailsByCategorySortedByDate($categoryId)
    {
        return $this->cocktailRepository->getCocktailsByCategorySortedByDate($categoryId);
    }

    public function getCocktailsByCategorySortedByLikes($categoryId)
    {
        return $this->cocktailRepository->getCocktailsByCategorySortedByLikes($categoryId);
    }

    public function getHotCocktailsByCategory($categoryId)
    {
        return $this->cocktailRepository->getHotCocktailsByCategory($categoryId);
    }

    public function searchCocktails($query)
    {
        return $this->cocktailRepository->searchCocktails($query);
    }

    public function getRandomCocktail()
    {
        return $this->cocktailRepository->getRandomCocktail();
    }

    public function getStickyCocktail()
    {
        return $this->cocktailRepository->getStickyCocktail();
    }

    public function setStickyCocktail($cocktailId)
    {
        $this->cocktailRepository->setStickyCocktail($cocktailId);
    }

    public function clearStickyCocktail()
    {
        $this->cocktailRepository->clearStickyCocktail();
    }
    public function getHotCocktails()
    {
        return $this->cocktailRepository->getAllHotCocktails();
    }
    public function getCocktailsByCategory($categoryId)
    {
        return $this->cocktailRepository->getCocktailsByCategory($categoryId);
    }
    public function getTagsForCocktail($cocktailId)
    {
        return $this->tagRepository->getTagsByCocktailId($cocktailId);
    }


    // Get the comment count for a specific cocktail
    public function getTopLevelCommentsForCocktail($cocktailId, $limit = 3)
    {
        $comments = $this->commentRepository->getTopLevelCommentsByCocktailId($cocktailId);

        // Limit the comments to the first 3
        $comments = array_slice($comments, 0, $limit);

        // Now also get the reply count for each comment
        foreach ($comments as $comment) {
            $comment->replyCount = $this->commentRepository->getReplyCountByCommentId($comment->getCommentId());
        }

        return $comments;
    }

    public function getCommentCountForCocktail($cocktailId)
    {
        return $this->commentRepository->getCommentCountByCocktailId($cocktailId);
    }
}
