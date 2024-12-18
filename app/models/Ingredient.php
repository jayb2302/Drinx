<?php
class Ingredient {
    private $ingredient_id;
    private $name;
    private $cocktail_id;
    private $quantity;
    private $unit_id;
    private $unit_name;

    public function __construct($ingredient_id = null, $name = '', $cocktail_id = null, $quantity = null, $unit_id = null, $unit_name = null) {
        $this->ingredient_id = $ingredient_id;
        $this->name = $name;
        $this->cocktail_id = $cocktail_id;
        $this->quantity = $quantity;
        $this->unit_id = $unit_id;
        $this->unit_name = $unit_name;
    }

    // Getters and setters
    public function getIngredientId() {
        return $this->ingredient_id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCocktailId() {
        return $this->cocktail_id;
    }

    public function getQuantity() {
        return $this->quantity;
    }
    
    public function getFormattedQuantity($ingredientService)
    {
        return $ingredientService->convertDecimalToFraction($this->quantity);
    }

    public function getUnitName() {
        return $this->unit_name;
    }

    public function getUnitId() { 
        return $this->unit_id;
    }

    public function setIngredientId($ingredient_id) {
        $this->ingredient_id = $ingredient_id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCocktailId($cocktail_id) {
        $this->cocktail_id = $cocktail_id;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function setUnitId($unit_id) {
        $this->unit_id = $unit_id;
    }
    
    public function setUnitName($unit_name) {
        $this->unit_name = $unit_name;
    }
    

}