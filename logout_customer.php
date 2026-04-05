<?php
session_start();

// Remove all session variables
session_unset();

// Destroy the session completely
session_destroy();

// Redirect back to home page
header("Location: index.php");
exit();
?>