<?php
session_start();

// Redirect to pages/index.php as priority landing page
header('Location: pages/index.php');
exit;
?>