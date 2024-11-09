<?php
class Cocktail
{
    private $cocktail_id;
    private $user_id;
    public $hasLiked = false;
    private $is_sticky = false;
    private $title;
    private $description;
    private $image;
    private $category_id;
    private $difficulty_id;
    private $ingredients = [];
    private $steps = [];
    private $tags = [];
    private $likeCount = 0;

    public function __construct(
        $cocktail_id = null,
        $title = '',
        $description = '',
        $image = '',
        $is_sticky = false,
        $category_id = null,
        $user_id = null,
        array $ingredients = [], 
        array $steps = [], 
        array $tags = [], 
        $likeCount = 0
    ) {
        $this->cocktail_id = $cocktail_id;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->is_sticky = $is_sticky; // Initialize sticky status
        $this->category_id = $category_id;
        $this->user_id = $user_id;
        $this->ingredients = $ingredients;
        $this->steps = $steps; // Initialize steps
        $this->tags = $tags; // Optional
        $this->likeCount = $likeCount; // Initialize like count
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

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getImage()
    {
        return $this->image;
    }
    public function setImage($image)
    {
        $this->image = $image;
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
    public function addIngredient(Ingredient $ingredient)
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
}
