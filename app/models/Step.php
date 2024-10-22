<?php
class Step {
    private $step_id;
    private $cocktail_id;
    private $instruction;
    private $step_number;

    public function __construct($step_id = null, $cocktail_id = null, $step_number = null, $instruction = '') {
        $this->step_id = $step_id;
        $this->cocktail_id = $cocktail_id;
        $this->step_number = $step_number;
        $this->instruction = $instruction;
    }

    // Getters
    public function getStepId() {
        return $this->step_id;
    }

    public function getCocktailId() {
        return $this->cocktail_id;
    }

    public function getStepNumber() {
        return $this->step_number;
    }

    public function getInstruction() {
        return $this->instruction;
    }

    // This method allows you to retrieve the step ID
    public function getId() {
        return $this->getStepId();
    }

    // Setters
    public function setStepId($step_id) {
        $this->step_id = $step_id;
    }

    public function setCocktailId($cocktail_id) {
        $this->cocktail_id = $cocktail_id;
    }

    public function setStepNumber($step_number) {
        $this->step_number = $step_number;
    }

    public function setInstruction($instruction) {
        $this->instruction = $instruction;
    }
}