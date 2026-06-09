<?php
/**
 * Carousel Slides API
 * Handles CRUD operations for carousel slides
 */

require_once '../../config/db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            handleGet($pdo);
            break;

        case 'POST':
            // Check if it's a file upload or JSON request
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            if (strpos($contentType, 'multipart/form-data') !== false) {
                handleFormPost($pdo);
            } else {
                handleJsonPost($pdo);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    error_log("Carousel API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error']);
} catch (Exception $e) {
    error_log("Carousel API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

/**
 * GET handler - fetch slides
 */
function handleGet($pdo) {
    $adminMode = isset($_GET['admin']) && $_GET['admin'] == '1';

    if ($adminMode) {
        // Admin view: get all slides
        $stmt = $pdo->query("
            SELECT 
                slide_id,
                image_url,
                mobile_image_url,
                headline,
                subheadline,
                category_label,
                cta_text,
                cta_link,
                sort_order,
                is_active,
                valid_from,
                valid_until,
                view_count,
                click_count,
                created_at,
                updated_at
            FROM carousel_slides
            ORDER BY sort_order ASC, created_at DESC
        ");
    } else {
        // Public view: get only active, valid slides
        $stmt = $pdo->query("
            SELECT 
                slide_id,
                image_url,
                mobile_image_url,
                headline,
                subheadline,
                category_label,
                cta_text,
                cta_link,
                sort_order
            FROM carousel_slides
            WHERE is_active = 1
            AND (valid_from IS NULL OR valid_from <= CURDATE())
            AND (valid_until IS NULL OR valid_until >= CURDATE())
            ORDER BY sort_order ASC
        ");
    }

    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert is_active to proper boolean/integer for frontend
    foreach ($slides as &$slide) {
        $slide['is_active'] = (int) $slide['is_active'];
        $slide['sort_order'] = (int) $slide['sort_order'];
        $slide['view_count'] = (int) ($slide['view_count'] ?? 0);
        $slide['click_count'] = (int) ($slide['click_count'] ?? 0);
    }

    echo json_encode([
        'success' => true,
        'slides' => $slides,
        'count' => count($slides)
    ]);
}

/**
 * POST handler for form data (create/update with file upload)
 */
function handleFormPost($pdo) {
    $slideId = $_POST['slide_id'] ?? '';
    $isUpdate = !empty($slideId);

    // Handle file upload
    $imageUrl = $_POST['image_url'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageUrl = uploadImage($_FILES['image']);
    }

    if (!$imageUrl && !$isUpdate) {
        echo json_encode(['success' => false, 'error' => 'Image is required']);
        return;
    }

    $data = [
        'headline' => $_POST['headline'] ?? '',
        'subheadline' => $_POST['subheadline'] ?? null,
        'category_label' => $_POST['category_label'] ?? null,
        'cta_text' => $_POST['cta_text'] ?? null,
        'cta_link' => $_POST['cta_link'] ?? null,
        'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        'is_active' => (int) ($_POST['is_active'] ?? 1)
    ];

    if ($imageUrl) {
        $data['image_url'] = $imageUrl;
    }

    if ($isUpdate) {
        // Update existing slide
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $slideId;

        $sql = "UPDATE carousel_slides SET " . implode(', ', $fields) . " WHERE slide_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        echo json_encode(['success' => true, 'message' => 'Slide updated', 'slide_id' => $slideId]);
    } else {
        // Create new slide
        $slideId = generateUUID();
        $data['slide_id'] = $slideId;

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO carousel_slides ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));

        echo json_encode(['success' => true, 'message' => 'Slide created', 'slide_id' => $slideId]);
    }
}

/**
 * POST handler for JSON actions (toggle, delete, reorder)
 */
function handleJsonPost($pdo) {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';

    switch ($action) {
        case 'toggle':
            toggleSlide($pdo, $input);
            break;

        case 'delete':
            deleteSlide($pdo, $input);
            break;

        case 'reorder':
            reorderSlides($pdo, $input);
            break;

        case 'track_view':
            trackView($pdo, $input);
            break;

        case 'track_click':
            trackClick($pdo, $input);
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Unknown action']);
    }
}

function toggleSlide($pdo, $input) {
    $slideId = $input['slide_id'] ?? '';
    $isActive = $input['is_active'] ?? 1;

    if (!$slideId) {
        echo json_encode(['success' => false, 'error' => 'Slide ID required']);
        return;
    }

    $stmt = $pdo->prepare("UPDATE carousel_slides SET is_active = ? WHERE slide_id = ?");
    $stmt->execute([$isActive, $slideId]);

    echo json_encode(['success' => true, 'message' => 'Slide status updated']);
}

function deleteSlide($pdo, $input) {
    $slideId = $input['slide_id'] ?? '';

    if (!$slideId) {
        echo json_encode(['success' => false, 'error' => 'Slide ID required']);
        return;
    }

    // Get the image URL before deleting
    $stmt = $pdo->prepare("SELECT image_url FROM carousel_slides WHERE slide_id = ?");
    $stmt->execute([$slideId]);
    $slide = $stmt->fetch(PDO::FETCH_ASSOC);

    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM carousel_slides WHERE slide_id = ?");
    $stmt->execute([$slideId]);

    // Delete the image file if it exists
    if ($slide && !empty($slide['image_url'])) {
        $imagePath = __DIR__ . '/../../' . $slide['image_url'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Slide deleted']);
}

function reorderSlides($pdo, $input) {
    $slideIds = $input['slide_ids'] ?? [];

    if (empty($slideIds)) {
        echo json_encode(['success' => false, 'error' => 'Slide IDs array required']);
        return;
    }

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("UPDATE carousel_slides SET sort_order = ? WHERE slide_id = ?");
        foreach ($slideIds as $index => $slideId) {
            $stmt->execute([$index, $slideId]);
        }
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Order updated']);
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function trackView($pdo, $input) {
    $slideId = $input['slide_id'] ?? '';
    if (!$slideId) {
        echo json_encode(['success' => false, 'error' => 'Slide ID required']);
        return;
    }

    $stmt = $pdo->prepare("UPDATE carousel_slides SET view_count = view_count + 1 WHERE slide_id = ?");
    $stmt->execute([$slideId]);
    echo json_encode(['success' => true]);
}

function trackClick($pdo, $input) {
    $slideId = $input['slide_id'] ?? '';
    if (!$slideId) {
        echo json_encode(['success' => false, 'error' => 'Slide ID required']);
        return;
    }

    $stmt = $pdo->prepare("UPDATE carousel_slides SET click_count = click_count + 1 WHERE slide_id = ?");
    $stmt->execute([$slideId]);
    echo json_encode(['success' => true]);
}

/**
 * Upload image to server
 */
function uploadImage($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Invalid file type. Only JPG, PNG, GIF, WebP allowed.');
    }

    if ($file['size'] > $maxSize) {
        throw new Exception('File too large. Max 5MB allowed.');
    }

    // Create upload directory if not exists
    $uploadDir = '../../uploads/carousel/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'carousel_' . uniqid() . '.' . $ext;
    $filepath = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception('Failed to upload file');
    }

    // Return relative path from pages directory
    return 'uploads/carousel/' . $filename;
}

/**
 * Generate UUID v4
 */
function generateUUID() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}
