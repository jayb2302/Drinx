<?php
// Function to load environment variables from a .env file
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return;
    }
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        [$key, $value] = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
    
    return $env;
}
loadEnv(__DIR__ . '/../../.env');

// // Function to generate a URL
// function url($path = '') {
//     $baseUrl = (getenv('ENV') == 'live') ? 'http://drinx.local' : 'https://drinx.info';
//     return $baseUrl . '/' . ltrim($path, '/');
// }
// Function to generate URL based on environment
function url($path = '') {
    $environment = $_ENV['ENV'] ?? 'local'; // Default to 'local' if ENV is not set
    $baseUrl = ($environment === 'live') ? 'https://drinx.info' : 'http://drinx.local';
    return $baseUrl . '/' . ltrim($path, '/');
}

function base_url() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
}

function base_path($path = '') {
    return __DIR__ . '/../../' . ltrim($path, '/');
}

function asset($path) {
    return base_url() . '/' . ltrim($path, '/public');
}

// Function to sanitize input
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Function to validate and sanitize numeric input
function sanitizeNumber($input) {
    if (is_numeric($input)) {
        // Convert to float for fractional values
        return floatval($input); 
    }
    return null; 
}

// Function to redirect to a given path
function redirect($path) {
    header("Location: " . url($path));
    exit();
}

// Optional: If you want to retain error handling but manage it from AuthController, you can remove this
function setErrorMessage($message) {
    $_SESSION['error'] = $message;
}

function formatDate($datetime, $format = 'd.M Y H:i') {
    try {
        $date = new DateTime($datetime);
        return $date->format($format);
    } catch (Exception $e) {
        return 'Unknown date';
    }
}

function generateCocktailSlug($title) {
    // Replace spaces with hyphens, and convert the title to lowercase
    return strtolower(str_replace(' ', '-', $title));
}

function validatePassword($password, &$errors)
{
    $requirements = [];
    if (strlen($password) < 8) {
        $requirements[] = "at least 8 characters";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $requirements[] = "one uppercase letter";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $requirements[] = "one lowercase letter";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $requirements[] = "one number";
    }

    if (!empty($requirements)) {
        $errors[] = "Password must include: " . implode(', ', $requirements) . ".";
    }

    return empty($errors); // Return true if no errors
}