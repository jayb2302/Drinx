<?php
session_start(); // Start the session

// Destroy the session
session_unset();    // Unset all session variables
session_destroy();  // Destroy the session

// Redirect to the homepage or login page
header('Location: index.php'); // Redirect to the homepage after logout
exit;