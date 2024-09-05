<?php
session_start(); // Start the session to access and destroy session data

// Destroy the session
session_destroy();

// Redirect to the homepage (or desired page after logout)
header("Location: index.php");  // Change "index.php" to your desired redirect page
exit();
?>