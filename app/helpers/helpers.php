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
function sanitizeTrim($input) {
    return is_string($input) ? trim($input) : $input;
}
// Function to validate and sanitize numeric input
function sanitizeNumber($input) {
    if (is_numeric($input)) {
        // Convert to float for fractional values
        return floatval($input); 
    }
    return null; 
}

function sanitizeQuery($query, $maxLength = 100) {
    $sanitized = trim($query);

    // Strip HTML tags
    $sanitized = strip_tags($sanitized);

    // Escape special characters for safe use in SQL
    $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');

    // Limit the length to prevent abuse
    if (strlen($sanitized) > $maxLength) {
        $sanitized = substr($sanitized, 0, $maxLength);
    }

    return $sanitized;
}

// Function to redirect to a given path
function redirect($path) {
    header("Location: " . url($path));
    exit();
}

//If you want to retain error handling but manage it from AuthController, you can remove this
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

function validateUsername($username, &$errors)
{
   
    if (strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters long.";
    }
    if (strlen($username) > 30) { 
        $errors[] = "Username cannot be more than 30 characters long.";
    }

    if (!preg_match('/^[\p{L}0-9\-_]+$/u', $username)) {
        $errors[] = "Username can only contain letters, numbers, hyphens (-), and underscores (_).";
    }
    return empty($errors); 
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

function convertPrepTimeToMinutes($prepTime)
{
    switch ($prepTime) {
        case '<15':
            return 10; // "Less than 15 minutes"
        case '15–30':
            return 20; // "15–30 minutes"
        case '30–60':
            return 45; // "30–60 minutes"
        case '>60':
            return 90; //  "More than 60 minutes"
        default:
            return null; 
    }
}

function formatPrepTime($prepTime)
{
    switch ($prepTime) {
        case 10:
            return "Less than 15 minutes";
        case 20:
            return "15–30 minutes";
        case 45:
            return "30–60 minutes";
        case 90:
            return "More than 60 minutes";
        default:
            return "Unknown preparation time"; 
    }
}

// Helper function to get the first sentence of a description
function getFirstSentence($description) {
    if (empty(trim($description))) {
        return ''; // Return an empty string if the description is empty or whitespace
    }

    // Split the description into sentences using punctuation as delimiters
    $sentences = preg_split('/(?<=[.?!])\s+/', $description, -1, PREG_SPLIT_NO_EMPTY);

    // Return the first sentence or fallback to the trimmed description
    return $sentences[0] ?? trim($description);
}

function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));  
        error_log("Generated CSRF Token: " . $_SESSION['csrf_token']);
    }
    return $_SESSION['csrf_token']; 
}

function validateCsrfToken($token)
{
    $isValid = isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    if ($isValid) {
        unset($_SESSION['csrf_token']); 
    }
    return $isValid;
}