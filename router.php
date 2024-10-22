<?php
namespace App\Routing;

class Router {
    protected $routes = []; // Ensure this is defined as a property of the class

    public function add($method, $path, $action) {
        $this->routes[] = compact('method', 'path', 'action'); // Adding route
    }

    public function resolve($uri) {
        foreach ($this->routes as $route) {
            if ($_SERVER['REQUEST_METHOD'] === $route['method'] && preg_match($route['path'], $uri, $matches)) {
                // Remove the first element as it is the full match
                array_shift($matches);
                return [$route['action'], $matches]; // Return the action and the extracted parameters
            }
        }
        return null; // No matching route found
    }
    public function get($path, $action) {
        $this->add('GET', $path, $action);
    }
}