<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent browser caching so hitting back after logout forces fresh login check
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Clear all session variables
$_SESSION = array();

// Destroy the session completely
session_destroy();

// Redirect back to login page
header("Location: ../login.php");
exit();
?>