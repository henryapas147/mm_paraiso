<?php
/**
 * API Endpoint: Get Payment Accounts for a Property
 * Returns payment methods and account details for a specific property
 */

require_once '../../config/db.php';


header('Content-Type: application/json');

// Get property_id from query parameter
$propertyId = $_GET['property_id'] ?? null;

if (!$propertyId) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Property ID is required'
    ]);
    exit;
}

try {
    // Fetch payment accounts with method details for this property
    $stmt = $pdo->prepare("
        SELECT 
            pa.account_id,
            pa.property_id,
            pa.account_name,
            pa.account_number,
            pa.bank_name,
            pa.branch_name,
            pa.qr_code_image,
            pa.instructions,
            pa.is_default,
            pm.method_id,
            pm.method_code,
            pm.method_label,
            pm.method_type
        FROM payment_accounts pa
        INNER JOIN payment_methods pm ON pa.method_id = pm.method_id
        WHERE pa.property_id = :property_id
        AND pa.is_active = 1
        AND pm.is_active = 1
        ORDER BY pm.sort_order, pa.is_default DESC
    ");

    $stmt->execute(['property_id' => $propertyId]);
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the response
    // Determine if proof is required based on method_type (cash doesn't need proof)
    $formattedAccounts = array_map(function ($account) {
        $requiresProof = $account['method_type'] !== 'cash';
        return [
            'account_id' => $account['account_id'],
            'method_id' => $account['method_id'],
            'method_code' => $account['method_code'],
            'method_name' => $account['method_label'],
            'requires_proof' => $requiresProof,
            'account_name' => $account['account_name'],
            'account_number' => $account['account_number'],
            'bank_name' => $account['bank_name'],
            'branch_name' => $account['branch_name'],
            'qr_code_image' => $account['qr_code_image'] ? '../uploads/payments/payment_accounts/' . basename($account['qr_code_image']) : null,
            'instructions' => $account['instructions'],
            'is_default' => (bool)$account['is_default']
        ];
    }, $accounts);

    echo json_encode([
        'success' => true,
        'property_id' => $propertyId,
        'accounts' => $formattedAccounts,
        'count' => count($formattedAccounts)
    ]);

} catch (PDOException $e) {
    error_log("Payment accounts API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch payment accounts'
    ]);
} catch (Exception $e) {
    error_log("Payment accounts API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'An unexpected error occurred'
    ]);
}
