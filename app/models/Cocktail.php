<?php
class Cocktail {
    private $cocktail_id;
    private $title;
    private $description;
    private $image;
    private $category_id;
    private $steps; // Add this property to hold the steps

    public function __construct($cocktail_id, $title, $description, $image, $category_id, $steps = []) {
        $this->cocktail_id = $cocktail_id;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->category_id = $category_id;
        $this->steps = $steps; // Initialize the steps property
    }

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

    public function getSteps() { // Define this method to return steps
        return $this->steps;
    }
}