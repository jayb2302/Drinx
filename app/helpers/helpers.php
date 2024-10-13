<?php
// Example: Function to generate a URL
function url($path = '') {
    return 'http://drinx.local/' . ltrim($path, '/');
}
function base_url() {
    return 'http://' . $_SERVER['HTTP_HOST']; // No need to include '/Drinx' if that's not the path to the application
}

function asset($path) {
    return base_url() . '/' . ltrim($path, '/public');
}
// Example: Function to sanitize input
function sanitize($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

// Example: Function to check if a user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Example: Function to redirect to a given path
function redirect($url) {
    header("Location: $url");
    exit(); // Make sure to exit after redirecting
}