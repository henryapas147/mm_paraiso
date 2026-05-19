<?php
session_start();
session_destroy();

// Redirect to the public index page
header('Location: ../pages/index.php');
exit;
