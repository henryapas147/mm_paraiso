<?php
/**
 * User Settings API
 * Handles saving and loading user preferences and settings
 */

require_once '../config/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_settings':
            getSettings($pdo, $userId);
            break;
        case 'save_settings':
            saveSettings($pdo, $userId);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

function getSettings($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT setting_key, setting_value, setting_type, category
        FROM user_settings
        WHERE user_id = ?
    ");
    $stmt->execute([$userId]);
    $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Convert to key-value array
    $settingsArray = [];
    foreach ($settings as $setting) {
        $value = $setting['setting_value'];
        
        // Convert based on type
        switch ($setting['setting_type']) {
            case 'boolean':
                $value = (bool) $value;
                break;
            case 'integer':
                $value = (int) $value;
                break;
            case 'json':
                $value = json_decode($value, true);
                break;
        }
        
        $settingsArray[$setting['setting_key']] = $value;
    }
    
    echo json_encode(['success' => true, 'data' => $settingsArray]);
}

function saveSettings($pdo, $userId) {
    // Only accept POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['settings'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }
    
    $settings = $input['settings'];
    
    // Begin transaction
    $pdo->beginTransaction();
    
    try {
        foreach ($settings as $key => $value) {
            // Determine setting type
            $type = 'string';
            if (is_bool($value)) {
                $type = 'boolean';
                $value = $value ? '1' : '0';
            } elseif (is_int($value)) {
                $type = 'integer';
                $value = (string) $value;
            } elseif (is_array($value)) {
                $type = 'json';
                $value = json_encode($value);
            } else {
                $value = (string) $value;
            }
            
            // Determine category based on key
            $category = 'general';
            if (strpos($key, 'email_') === 0) {
                $category = 'email';
            } elseif (strpos($key, 'push_') === 0 || strpos($key, 'sms_') === 0 || strpos($key, 'in_app_') === 0) {
                $category = 'notification';
            } elseif (in_array($key, ['profile_visibility', 'share_data_partners', 'allow_analytics', 'personalized_recommendations'])) {
                $category = 'privacy';
            } elseif (in_array($key, ['language', 'timezone', 'currency'])) {
                $category = 'preferences';
            }
            
            // Upsert setting
            $stmt = $pdo->prepare("
                INSERT INTO user_settings (user_id, setting_key, setting_value, setting_type, category)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                setting_value = VALUES(setting_value),
                setting_type = VALUES(setting_type),
                category = VALUES(category),
                updated_at = CURRENT_TIMESTAMP
            ");
            $stmt->execute([$userId, $key, $value, $type, $category]);
        }
        
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Settings saved successfully']);
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
