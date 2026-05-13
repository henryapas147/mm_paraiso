<?php
session_start();

// Check if user is already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: build/');
    exit;
}

// Redirect to sign-in page using relative path
header('Location: build/sign-in');
exit;
?>