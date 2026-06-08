<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();
requirePermission('inventory.edit');

try {
  // Check if file was uploaded
  if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
    $error = $_FILES['product_image']['error'] ?? 'No file uploaded';
    echo json_encode(['success' => false, 'message' => 'Upload failed: ' . $error]);
    exit;
  }

  $file = $_FILES['product_image'];
  
  // Validate file type
  $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mimeType = finfo_file($finfo, $file['tmp_name']);
  finfo_close($finfo);
  
  if (!in_array($mimeType, $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.']);
    exit;
  }
  
  // Validate file size (max 5MB)
  $maxSize = 5 * 1024 * 1024; // 5MB
  if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 5MB.']);
    exit;
  }
  
  // Create upload directory if it doesn't exist
  $uploadDir = __DIR__ . '/../../uploads/products/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }
  
  // Generate unique filename
  $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
  $filename = sprintf('prod_%s_%s.%s', 
    date('Ymd'), 
    bin2hex(random_bytes(8)), 
    strtolower($extension)
  );
  
  $uploadPath = $uploadDir . $filename;
  
  // Move uploaded file
  if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file.']);
    exit;
  }
  
  // Return the URL path (relative to build directory where the view files are)
  $imageUrl = '../../uploads/products/' . $filename;
  
  echo json_encode([
    'success' => true, 
    'message' => 'Image uploaded successfully',
    'image_url' => $imageUrl,
    'filename' => $filename
  ]);
  
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
