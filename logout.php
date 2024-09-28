<?php
session_start(); // Start the session

// Unset all of the session variables
$_SESSION = [];

// If you want to destroy the session entirely
session_destroy();

// Redirect to the main page
header("Location: login.php"); // Change 'main_page.php' to your actual main page file
exit; // Ensure no further code is executed
?>
