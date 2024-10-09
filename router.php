<?php
namespace App\Routing;
class Router {
    protected $routes = []; // Ensure this is defined as a property of the class

    public function add($method, $path, $action) {
        $this->routes[] = compact('method', 'path', 'action'); // Adding route
    }

    public function resolve($uri) {
        foreach ($this->routes as $route) {
            if ($_SERVER['REQUEST_METHOD'] === $route['method'] && preg_match($route['path'], $uri)) {
                return $route['action']; // Return matched action
            }
        }
        return null; // No matching route found
    }
}
// function get($route, $path_to_include)
// {
//     if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//         route($route, $path_to_include);
//     }
// }

// function post($route, $path_to_include)
// {
//     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//         route($route, $path_to_include);
//     }
// }

// function route($route, $path_to_include)
// {
//     // Sanitize and trim the request URL
//     $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
//     $request_url = rtrim($request_url, '/');
//     $request_url = strtok($request_url, '?'); // Remove query string

//     // Check if the route matches the request URL
//     if ($route === $request_url) {
//         // Check if path_to_include is a callable (closure)
//         if (is_callable($path_to_include)) {
//             call_user_func($path_to_include);
//         } else {
//             // Include the specified PHP file
//             include_once __DIR__ . "/$path_to_include";
//         }
//         exit();
//     }
// }

// // Example usage of the router
// require_once __DIR__ . '/app/controllers/UserController.php';
// $userController = new UserController();

// // Define routes
// get('/login', function() use ($userController) {
//     $userController->showLoginForm();
// });

// post('/login', function() use ($userController) {
//     $userController->login();
// });

// get('/register', function() use ($userController) {
//     $userController->showRegisterForm();
// });

// post('/register', function() use ($userController) {
//     $userController->register();
// });

// // Define a route for logging out
// get('/logout', function() use ($userController) {
//     $userController->logout();
// });

// // Handle 404 errors
// http_response_code(404);
// echo "404 - Page Not Found";