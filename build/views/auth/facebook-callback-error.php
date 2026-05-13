<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Authentication Error'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
        <div class="mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($title ?? 'Authentication Error'); ?></h1>
        <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($message ?? 'An error occurred during Facebook authentication.'); ?></p>
        <?php if (isset($message) && strlen($message) > 100): ?>
            <div class="bg-gray-100 p-4 rounded-lg mb-6 text-left text-sm">
                <p class="font-semibold mb-2">Error Details:</p>
                <p class="text-gray-700 break-all"><?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php endif; ?>
        <a href="<?php echo htmlspecialchars($redirectUrl ?? './sign-in'); ?>" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            Go Back
        </a>
    </div>
</body>
</html>
