<?php
// Google Sign-In Error View
// Variables expected: $title, $message, $redirectUrl
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($title ?? 'Error - Resort Booking System'); ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .container {
      text-align: center;
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      max-width: 400px;
    }
    .error-icon {
      font-size: 80px;
      color: #f44336;
      margin-bottom: 20px;
    }
    h1 {
      color: #333;
      margin-bottom: 10px;
    }
    p {
      color: #666;
      margin-bottom: 30px;
    }
    .btn {
      display: inline-block;
      padding: 12px 30px;
      background-color: #f44336;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      transition: background-color 0.3s;
    }
    .btn:hover {
      background-color: #da190b;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="error-icon">✕</div>
    <h1><?php echo htmlspecialchars($title ?? 'Authentication Failed'); ?></h1>
    <p><?php echo htmlspecialchars($message ?? 'There was an error. Please try again.'); ?></p>
    <a href="<?php echo htmlspecialchars($redirectUrl ?? '/build/sign-up'); ?>" class="btn" target="_top">Back to Sign Up</a>
  </div>
</body>
</html>
