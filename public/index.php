<?php
ob_start();
require_once '../app/helpers/helpers.php';
require_once '../router.php';
require_once '../routes.php';

// Resolve the current request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$action = $router->resolve($requestUri);

echo "Requested URI: " . $requestUri; // Output the requested URI

// Call the appropriate controller action
if ($action) {
    [$controllerClass, $method] = $action;
    $controller = new $controllerClass();
    call_user_func([$controller, $method]);
} else {
    http_response_code(404);
    include '../app/views/404.php'; // Load 404 error page
}
ob_end_flush();