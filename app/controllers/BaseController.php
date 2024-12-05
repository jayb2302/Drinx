<?php
require_once __DIR__ . '/../config/dependencies.php';
require_once __DIR__ . '/../helpers/helpers.php';


class BaseController
{
    protected $authService;
    protected $userService;
    protected $cocktailService;

    public function __construct(
        AuthService $authService,
        UserService $userService,
        CocktailService $cocktailService,
    ) {
        $this->authService = $authService;
        $this->userService = $userService;
        $this->cocktailService = $cocktailService;
    }
}