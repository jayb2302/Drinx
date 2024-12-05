<?php
require_once '/../app/config/dependencies.php';

class BaseController {
    protected $userService;
    protected $cocktailService;
    protected $stepService;
 
    public function __construct(UserService $userService, CocktailService $cocktailService, StepService $stepService,) {
        $this->userService = $userService;
        $this->cocktailService = $cocktailService;
        $this->stepService = $stepService;
    }
}