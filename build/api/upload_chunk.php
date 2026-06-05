<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

requirePermission('properties.edit');

// CSRF check
$csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? null;
if (!$csrfToken || $csrfToken !== ($_SESSION['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$uploadId    = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['upload_id'] ?? '');
$chunkIndex  = (int)($_POST['chunk_index'] ?? 0);
$totalChunks = (int)($_POST['total_chunks'] ?? 1);
$filename    = basename($_POST['filename'] ?? 'upload');
$ext         = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

// Allowed video types
$allowedTypes = ['mp4', 'webm', 'ogg'];
if (!in_array($ext, $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only MP4, WebM, and OGG are allowed.']);
    exit;
}

// MIME type mapping
$allowedMimeTypes = [
    'mp4' => ['video/mp4', 'video/quicktime'],
    'webm' => ['video/webm'],
    'ogg' => ['video/ogg']
];

// Check if chunk file exists and validate
if (!isset($_FILES['chunk']) || $_FILES['chunk']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error occurred.']);
    exit;
}

// Validate MIME type
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($_FILES['chunk']['tmp_name']);
if (!in_array($mimeType, $allowedMimeTypes[$ext] ?? [])) {
    echo json_encode(['success' => false, 'message' => 'Invalid file MIME type.']);
    exit;
}

// Validate file size (max 100MB per chunk, total will be validated on assembly)
$maxChunkSize = 100 * 1024 * 1024; // 100MB
if ($_FILES['chunk']['size'] > $maxChunkSize) {
    echo json_encode(['success' => false, 'message' => 'Chunk size exceeds maximum limit.']);
    exit;
}

$tmpDir = sys_get_temp_dir() . '/chunks_' . $uploadId . '/';
if (!is_dir($tmpDir)) mkdir($tmpDir, 0700, true);

// Save this chunk
$chunkFile = $tmpDir . 'chunk_' . $chunkIndex;
if (!move_uploaded_file($_FILES['chunk']['tmp_name'], $chunkFile)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save chunk']);
    exit;
}

// Check if all chunks are received
$receivedChunks = glob($tmpDir . 'chunk_*');
if (count($receivedChunks) < $totalChunks) {
    echo json_encode(['success' => true, 'done' => false]);
    exit;
}

// Assemble chunks
$uploadDir  = __DIR__ . '/../assets/videos/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

// Generate secure random filename
$finalName  = 'company_video_' . bin2hex(random_bytes(16)) . '_' . time() . '.' . $ext;
$finalPath  = $uploadDir . $finalName;
$out        = fopen($finalPath, 'wb');

$totalSize = 0;
$maxTotalSize = 100 * 1024 * 1024; // 100MB total

for ($i = 0; $i < $totalChunks; $i++) {
    $chunkData = file_get_contents($tmpDir . 'chunk_' . $i);
    $totalSize += strlen($chunkData);
    
    // Check total size during assembly
    if ($totalSize > $maxTotalSize) {
        fclose($out);
        unlink($finalPath);
        array_map('unlink', glob($tmpDir . '*'));
        rmdir($tmpDir);
        echo json_encode(['success' => false, 'message' => 'Total file size exceeds 100MB limit.']);
        exit;
    }
    
    fwrite($out, $chunkData);
}
fclose($out);

// Cleanup temp chunks
array_map('unlink', glob($tmpDir . '*'));
rmdir($tmpDir);

// Set secure file permissions
chmod($finalPath, 0644);

$relativePath = 'assets/videos/' . $finalName;
echo json_encode(['success' => true, 'done' => true, 'path' => $relativePath]);
