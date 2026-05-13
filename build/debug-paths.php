<?php
/**
 * DROP THIS FILE anywhere in your project (e.g. /admin_booking/build/debug-paths.php)
 * Visit it in the browser: http://localhost/admin_booking/build/debug-paths.php
 * It will tell you exactly where files are being saved and whether the folder is writable.
 * DELETE THIS FILE after debugging.
 */

echo "<pre>";

// 1. Where is this file located?
echo "=== DIRECTORY INFO ===\n";
echo "__DIR__                     : " . __DIR__ . "\n";
echo "__DIR__ / ..                : " . realpath(__DIR__ . '/..') . "\n";
echo "__DIR__ / ../..             : " . realpath(__DIR__ . '/../..') . "\n";
echo "__DIR__ / ../../..          : " . realpath(__DIR__ . '/../../..') . "\n";

// 2. Try all possible upload paths
echo "\n=== UPLOAD PATH CANDIDATES ===\n";
$candidates = [
    __DIR__ . '/uploads/profile_photos/',
    __DIR__ . '/../uploads/profile_photos/',
    __DIR__ . '/../../uploads/profile_photos/',
    __DIR__ . '/../../../uploads/profile_photos/',
];

foreach ($candidates as $path) {
    $real    = realpath(dirname($path)); // parent folder
    $exists  = is_dir($path) ? 'EXISTS' : 'NOT FOUND';
    $write   = is_writable(dirname($path)) ? 'WRITABLE' : 'NOT WRITABLE';
    echo "$path\n  => $exists | Parent dir: $write\n\n";
}

// 3. Actually try to create the folder and write a test file
echo "=== WRITE TEST ===\n";
$testDir = __DIR__ . '/../../uploads/profile_photos/';
if (!is_dir($testDir)) {
    $made = mkdir($testDir, 0755, true);
    echo "mkdir($testDir): " . ($made ? "SUCCESS" : "FAILED") . "\n";
} else {
    echo "Directory already exists: $testDir\n";
}

$testFile = $testDir . 'test_write_' . time() . '.txt';
$written  = file_put_contents($testFile, 'write test');
echo "Write test file: " . ($written !== false ? "SUCCESS → $testFile" : "FAILED") . "\n";

if ($written !== false) {
    unlink($testFile); // clean up
    echo "Test file cleaned up.\n";
}

// 4. Try downloading a real image (small public image)
echo "\n=== CURL DOWNLOAD TEST ===\n";
$testUrl  = 'https://www.google.com/favicon.ico';
$ch       = curl_init($testUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_SSL_VERIFYPEER => false,
]);
$data     = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err      = curl_error($ch);
curl_close($ch);

echo "Test URL   : $testUrl\n";
echo "HTTP Code  : $httpCode\n";
echo "Data size  : " . strlen($data) . " bytes\n";
echo "cURL error : " . ($err ?: 'none') . "\n";

if ($data && $httpCode === 200) {
    $testImg = $testDir . 'test_image_' . time() . '.ico';
    $saved   = file_put_contents($testImg, $data);
    echo "Save test  : " . ($saved !== false ? "SUCCESS → $testImg" : "FAILED") . "\n";
    if ($saved !== false) unlink($testImg);
}

echo "\n=== DONE ===\n";
echo "Copy the output above and use it to confirm your correct upload path.\n";
echo "</pre>";
