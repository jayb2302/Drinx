<?php
namespace App\Routing;

class Router {
    protected $routes = [];

    public function add($method, $path, $action) {
        $this->routes[] = compact('method', 'path', 'action'); // Adding route
    }

    public function resolve($uri) {
        foreach ($this->routes as $route) {
            if ($_SERVER['REQUEST_METHOD'] === $route['method'] && preg_match($route['path'], $uri, $matches)) {
                error_log("Matched route: " . $route['path']); // Logs the matched route
    
                // Remove the first element as it is the full match
                array_shift($matches);
                return [$route['action'], $matches]; // Return the action and the extracted parameters
            }
        }
    
        error_log("No route matched for URI: $uri"); // Log unmatched routes
        return null; // No matching route found
    }
}