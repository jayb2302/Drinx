<?php
class Cocktail
{
    private $cocktail_id;
    private $user_id;
    public $hasLiked = false;
    private $is_sticky = false;
    private $title;
    private $description;
    private $prep_time;
    private $image;
    private $category_id;
    private $difficulty_id;
    private $ingredients = [];
    private $steps = [];
    private $tags = [];
    private $likeCount = 0;
    private $difficulty_name;
    private $createdAt; 
    private $updatedAt;

    public $commentCount = 0;
    public $comments = [];


    public function __construct(
        $cocktail_id = null,
        $user_id = null,
        $title = '',
        $description = '',
        $image = '',
        $prep_time = null,
        $is_sticky = false,
        $category_id = null,
        $difficulty_id = null,
        array $ingredients = [], 
        array $steps = [], 
        array $tags = [], 
        $likeCount = 0,
        $difficulty_name = null,
        $createdAt = null,
        $updatedAt = null 
    ) {
        $this->cocktail_id = $cocktail_id;
        $this->user_id = $user_id;
        $this->title = $title;
        $this->description = $description;
        $this->prep_time = $prep_time;
        $this->image = $image;
        $this->is_sticky = $is_sticky; // Initialize sticky status
        $this->category_id = $category_id;
        $this->difficulty_id = $difficulty_id;
        $this->ingredients = $ingredients;
        $this->steps = $steps; // Initialize steps
        $this->tags = $tags; 
        $this->likeCount = $likeCount; 
        $this->difficulty_name = $difficulty_name;
        $this->createdAt = $createdAt; 
        $this->updatedAt = $updatedAt;
    }
    // Getter and setter for user_id
    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    // Other getters
    public function getCocktailId()
    {
        return $this->cocktail_id;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPrepTime()
    {
        return $this->prep_time;
    }

    public function setPrepTime($prep_time)
    {
        $this->prep_time = $prep_time;
    }

    public function getImage()
    {
        return $this->image;
    }
    public function setImage($image)
    {
        $this->image = $image;
    }
   
public function getDifficultyName()
{
    $difficultyMap = [
        1 => 'Easy',
        2 => 'Medium',
        3 => 'Hard'
    ];
    return $difficultyMap[$this->difficulty_id] ?? 'Unknown';
}


    public function setDifficultyName($difficulty_name)
    {
        $this->difficulty_name = $difficulty_name;
    }
    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function isSticky(): bool
    {
        return $this->is_sticky;
    }

    public function setSticky(bool $is_sticky)
    {
        $this->is_sticky = $is_sticky;
    }

    // Utility methods
    public function addIngredient($ingredient)
    {
        $this->ingredients[] = $ingredient;
    }

    public function addStep(Step $step)
    {
        $this->steps[] = $step;
    }

    public function addTag($tag)
    {
        $this->tags[] = $tag;
    }

    public function getLikeCount()
    {
        return $this->likeCount;
    }

    public function setLikeCount($count)
    {
        $this->likeCount = $count;
    }

    public function getDifficultyId()
    {
        return $this->difficulty_id;
    }
    public function setDifficultyId($difficulty_id)
    {
        $this->difficulty_id = $difficulty_id;
    }
    public function getDifficultyIconHtml()
    {
        switch (strtolower($this->getDifficultyName())) {
            case 'easy':
                return '<i class="fa-solid fa-glass-cheers easy" title="Easy"></i>';
            case 'medium':
                return '<i class="fa-solid fa-wine-glass-alt medium" title="Medium"></i>';
            case 'hard':
                return '<i class="fa-solid fa-wine-bottle hard" title="Hard"></i>';
            default:
                return '<i class="fa-solid fa-question unknown" title="Unknown"></i>';
        }
    }

    public function getCommentCount() {
        return $this->commentCount;
    }

    public function getComments() {
        return $this->comments;
    }
}
