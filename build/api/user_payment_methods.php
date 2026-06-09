<?php
/**
 * User Payment Methods API
 * Handles CRUD operations for user's saved payment methods
 */

header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    switch ($method) {
        case 'GET':
            if ($action === 'list') {
                listPaymentMethods($pdo, $userId);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if ($action === 'add') {
                addPaymentMethod($pdo, $userId, $input);
            } elseif ($action === 'remove') {
                removePaymentMethod($pdo, $userId, $input);
            } elseif ($action === 'set_default') {
                setDefaultPaymentMethod($pdo, $userId, $input);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

/**
 * List user's payment methods
 */
function listPaymentMethods($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT payment_method_id, method_type, provider, display_name, 
               last_4, expiry_month, expiry_year, is_default, is_active, created_at
        FROM user_payment_methods
        WHERE user_id = ? AND is_active = 1
        ORDER BY is_default DESC, created_at DESC
    ");
    $stmt->execute([$userId]);
    $methods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $methods
    ]);
}

/**
 * Add a new payment method
 */
function addPaymentMethod($pdo, $userId, $input) {
    $methodType = $input['method_type'] ?? '';
    $provider = $input['provider'] ?? null;
    $displayName = $input['display_name'] ?? null;
    $tokenizedData = $input['tokenized_data'] ?? null;
    $last4 = $input['last_4'] ?? null;
    $expiryMonth = $input['expiry_month'] ?? null;
    $expiryYear = $input['expiry_year'] ?? null;
    $metadata = $input['metadata'] ?? null;

    // Validate required fields
    if (empty($methodType)) {
        echo json_encode(['success' => false, 'message' => 'Payment method type is required']);
        return;
    }

    // For cards, require last 4 and expiry
    if (in_array($methodType, ['credit_card', 'debit_card'])) {
        if (empty($last4) || empty($expiryMonth) || empty($expiryYear)) {
            echo json_encode(['success' => false, 'message' => 'Card details are incomplete']);
            return;
        }
    }

    // Generate UUID
    $paymentMethodId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
        mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

    // Check if this will be the first method (make it default)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_payment_methods WHERE user_id = ?");
    $stmt->execute([$userId]);
    $count = $stmt->fetchColumn();
    $isDefault = $count == 0 ? 1 : 0;

    // Insert payment method
    $stmt = $pdo->prepare("
        INSERT INTO user_payment_methods 
        (payment_method_id, user_id, method_type, provider, display_name, 
         tokenized_data, last_4, expiry_month, expiry_year, is_default, metadata)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $paymentMethodId,
        $userId,
        $methodType,
        $provider,
        $displayName,
        $tokenizedData,
        $last4,
        $expiryMonth,
        $expiryYear,
        $isDefault,
        $metadata ? json_encode($metadata) : null
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Payment method added successfully',
        'data' => [
            'payment_method_id' => $paymentMethodId,
            'is_default' => $isDefault
        ]
    ]);
}

/**
 * Remove a payment method
 */
function removePaymentMethod($pdo, $userId, $input) {
    $paymentMethodId = $input['payment_method_id'] ?? '';

    if (empty($paymentMethodId)) {
        echo json_encode(['success' => false, 'message' => 'Payment method ID is required']);
        return;
    }

    // Verify ownership
    $stmt = $pdo->prepare("
        SELECT is_default 
        FROM user_payment_methods 
        WHERE payment_method_id = ? AND user_id = ?
    ");
    $stmt->execute([$paymentMethodId, $userId]);
    $method = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$method) {
        echo json_encode(['success' => false, 'message' => 'Payment method not found']);
        return;
    }

    // If removing default, set another as default
    if ($method['is_default']) {
        $stmt = $pdo->prepare("
            UPDATE user_payment_methods 
            SET is_default = 1 
            WHERE user_id = ? AND payment_method_id != ? AND is_active = 1
            LIMIT 1
        ");
        $stmt->execute([$userId, $paymentMethodId]);
    }

    // Soft delete (set is_active = 0)
    $stmt = $pdo->prepare("
        UPDATE user_payment_methods 
        SET is_active = 0, updated_at = CURRENT_TIMESTAMP 
        WHERE payment_method_id = ? AND user_id = ?
    ");
    $stmt->execute([$paymentMethodId, $userId]);

    echo json_encode([
        'success' => true,
        'message' => 'Payment method removed successfully'
    ]);
}

/**
 * Set a payment method as default
 */
function setDefaultPaymentMethod($pdo, $userId, $input) {
    $paymentMethodId = $input['payment_method_id'] ?? '';

    if (empty($paymentMethodId)) {
        echo json_encode(['success' => false, 'message' => 'Payment method ID is required']);
        return;
    }

    // Verify ownership
    $stmt = $pdo->prepare("
        SELECT payment_method_id 
        FROM user_payment_methods 
        WHERE payment_method_id = ? AND user_id = ? AND is_active = 1
    ");
    $stmt->execute([$paymentMethodId, $userId]);
    $method = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$method) {
        echo json_encode(['success' => false, 'message' => 'Payment method not found']);
        return;
    }

    // Start transaction
    $pdo->beginTransaction();

    try {
        // Remove default from all methods
        $stmt = $pdo->prepare("
            UPDATE user_payment_methods 
            SET is_default = 0 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);

        // Set new default
        $stmt = $pdo->prepare("
            UPDATE user_payment_methods 
            SET is_default = 1, updated_at = CURRENT_TIMESTAMP 
            WHERE payment_method_id = ? AND user_id = ?
        ");
        $stmt->execute([$paymentMethodId, $userId]);

        $pdo->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Default payment method updated successfully'
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
