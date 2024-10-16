<?php
// Function to generate a URL
function url($path = '') {
    return 'http://drinx.local/' . ltrim($path, '/');
}

function base_url() {
    return 'http://' . $_SERVER['HTTP_HOST']; // No need to include '/Drinx' if that's not the path to the application
}

function asset($path) {
    return base_url() . '/' . ltrim($path, '/public');
}

// Function to sanitize input
function sanitize($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
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
