<?php
require_once 'BaseController.php';

class StepController {
    private $stepService;

    public function __construct(
        StepService $stepService
    ) {
        $this->stepService = $stepService;
    }

    public function addStep() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cocktail_id = intval($_POST['cocktail_id']);
            $step_number = intval($_POST['step_number']);
            $instruction = sanitize($_POST['step_instruction']); 

            $this->stepService->addStep($cocktail_id, $step_number, $instruction);

            // Redirect back to the cocktail view page
            header('Location: /cocktails/view?id=' . $cocktail_id);
            exit();
        }
    }
}