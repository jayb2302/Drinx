<?php
require_once __DIR__ . '/../repositories/StepRepository.php';



class StepController {
    private $stepRepository;

    public function __construct($dbConnection) {
        $this->stepRepository = new StepRepository($dbConnection);  
    }

    public function addStep() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cocktail_id = intval($_POST['cocktail_id']);
            $step_number = intval($_POST['step_number']);
            $instruction = trim($_POST['step_instruction']);

            $this->stepRepository->addStep($cocktail_id, $step_number, $instruction);

            // Redirect back to the cocktail view page
            header('Location: /cocktails/view?id=' . $cocktail_id);
            exit();
        }
    }
}