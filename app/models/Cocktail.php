<?php
class Cocktail {
    private $cocktail_id;
    private $title;
    private $description;
    private $image;
    private $category_id;
    private $steps;
    private $tags; 
    private $user_id;

    public function __construct($cocktail_id, $title, $description, $image, $category_id, $user_id, $steps = [], $tags = []) {
        $this->cocktail_id = $cocktail_id;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->category_id = $category_id;
        $this->user_id = $user_id;
        $this->steps = $steps; 
        $this->tags = $tags;
    }

    // Getter and setter for user_id
    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    // Other getters
    public function getCocktailId() {
        return $this->cocktail_id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImage() {
        return $this->image;
    }

    public function getCategoryId() {
        return $this->category_id; 
    }

    public function getSteps() { 
        return $this->steps;
    }
    
    public function getTags() {
        return $this->tags;
    }
}