<?php
/**
 * Two-Factor Authentication API
 * Handles TOTP-based 2FA setup, enable/disable, and verification
 */

use OTPHP\TOTP;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

header('Content-Type: application/json');
require_once '../config/middleware.php';
require_once '../../vendor/autoload.php';
applyAuthMiddleware();

global $pdo;

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
            if ($action === 'status') {
                get2FAStatus($userId);
            } elseif ($action === 'setup') {
                setup2FA($userId);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);

            if ($action === 'enable') {
                enable2FA($userId, $input);
            } elseif ($action === 'disable') {
                disable2FA($userId, $input);
            } elseif ($action === 'verify') {
                verify2FACode($userId, $input);
            } elseif ($action === 'regenerate_backup') {
                regenerateBackupCodes($userId);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

/**
 * Get current 2FA status for the user
 */
function get2FAStatus($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT enabled, created_at
        FROM user_2fa
        WHERE user_id = ?
    ");
    $stmt->execute([$userId]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo json_encode([
            'success' => true,
            'data' => [
                'enabled' => false,
                'has_secret' => false
            ]
        ]);
        return;
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'enabled' => (bool) $data['enabled'],
            'has_secret' => true,
            'created_at' => $data['created_at']
        ]
    ]);
}

/**
 * Setup 2FA - generate secret and QR code data
 */
function setup2FA($userId) {
    global $pdo;
    // Check if already has a secret
    $stmt = $pdo->prepare("SELECT secret FROM user_2fa WHERE user_id = ?");
    $stmt->execute([$userId]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing && $existing['enabled']) {
        echo json_encode(['success' => false, 'message' => '2FA is already enabled']);
        return;
    }

    // Get user email for QR code label
    $stmt = $pdo->prepare("SELECT email FROM user_accounts WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $user['email'] ?? '';

    // Create TOTP instance
    $appName = 'M&M Paraiso Resort';

    // Reuse existing secret if available, otherwise generate new one
    if ($existing && $existing['secret']) {
        $secret = $existing['secret'];
    } else {
        $totp = TOTP::create();
        $totp->setLabel($email);
        $totp->setIssuer($appName);
        $secret = $totp->getSecret();
    }

    // Create TOTP for QR code generation
    $totp = TOTP::create($secret);
    $totp->setLabel($email);
    $totp->setIssuer($appName);

    // Generate QR code as SVG (220px to fit nicely in modal)
    $renderer = new ImageRenderer(
        new RendererStyle(220),
        new SvgImageBackEnd()
    );
    $writer = new Writer($renderer);
    $qrCodeSvg = $writer->writeString($totp->getProvisioningUri());

    // Store the secret (not enabled yet)
    if ($existing) {
        $stmt = $pdo->prepare("
            UPDATE user_2fa
            SET secret = ?, updated_at = CURRENT_TIMESTAMP
            WHERE user_id = ?
        ");
        $stmt->execute([$secret, $userId]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO user_2fa (user_id, secret, enabled)
            VALUES (?, ?, 0)
        ");
        $stmt->execute([$userId, $secret]);
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'secret' => $secret,
            'qr_code_svg' => $qrCodeSvg,
            'manual_entry_key' => $secret
        ]
    ]);
}

/**
 * Enable 2FA - verify the code and enable
 */
function enable2FA($userId, $input) {
    global $pdo;
    $code = $input['code'] ?? '';

    if (empty($code)) {
        echo json_encode(['success' => false, 'message' => 'Verification code is required']);
        return;
    }

    // Get the secret
    $stmt = $pdo->prepare("SELECT secret FROM user_2fa WHERE user_id = ?");
    $stmt->execute([$userId]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => '2FA not set up. Please complete setup first.']);
        return;
    }

    // Verify the code using TOTP library (allow 1 window for time drift)
    $totp = TOTP::create($data['secret']);

    if (!$totp->verify($code, null, 1)) {
        echo json_encode(['success' => false, 'message' => 'Invalid verification code']);
        return;
    }

    // Generate backup codes
    $backupCodes = generateBackupCodes();

    // Enable 2FA
    $stmt = $pdo->prepare("
        UPDATE user_2fa
        SET enabled = 1, backup_codes = ?, last_used_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP
        WHERE user_id = ?
    ");
    $stmt->execute([json_encode($backupCodes), $userId]);

    echo json_encode([
        'success' => true,
        'message' => '2FA enabled successfully',
        'data' => [
            'backup_codes' => $backupCodes
        ]
    ]);
}

/**
 * Disable 2FA - requires password verification
 */
function disable2FA($userId, $input) {
    global $pdo;
    $password = $input['password'] ?? '';

    if (empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Password is required']);
        return;
    }

    // Verify password
    $stmt = $pdo->prepare("SELECT password_hash FROM user_accounts WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid password']);
        return;
    }

    // Disable 2FA
    $stmt = $pdo->prepare("
        UPDATE user_2fa
        SET enabled = 0, secret = NULL, backup_codes = NULL, updated_at = CURRENT_TIMESTAMP
        WHERE user_id = ?
    ");
    $stmt->execute([$userId]);

    echo json_encode([
        'success' => true,
        'message' => '2FA disabled successfully'
    ]);
}

/**
 * Verify 2FA code during login
 */
function verify2FACode($userId, $input) {
    global $pdo;
    $code = $input['code'] ?? '';

    if (empty($code)) {
        echo json_encode(['success' => false, 'message' => 'Code is required']);
        return;
    }

    // Get 2FA data
    $stmt = $pdo->prepare("SELECT secret, backup_codes FROM user_2fa WHERE user_id = ? AND enabled = 1");
    $stmt->execute([$userId]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => '2FA not enabled']);
        return;
    }

    // Check if it's a backup code
    $backupCodes = json_decode($data['backup_codes'] ?? '[]', true);
    if (in_array($code, $backupCodes)) {
        // Remove used backup code
        $backupCodes = array_diff($backupCodes, [$code]);
        $stmt = $pdo->prepare("UPDATE user_2fa SET backup_codes = ? WHERE user_id = ?");
        $stmt->execute([json_encode(array_values($backupCodes)), $userId]);

        echo json_encode(['success' => true, 'message' => 'Backup code verified']);
        return;
    }

    // Verify TOTP code using proper library (allow 1 window for time drift)
    $totp = TOTP::create($data['secret']);
    if ($totp->verify($code, null, 1)) {
        // Update last used
        $stmt = $pdo->prepare("UPDATE user_2fa SET last_used_at = CURRENT_TIMESTAMP WHERE user_id = ?");
        $stmt->execute([$userId]);

        echo json_encode(['success' => true, 'message' => 'Code verified']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid code']);
    }
}

/**
 * Regenerate backup codes
 */
function regenerateBackupCodes($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT enabled FROM user_2fa WHERE user_id = ?");
    $stmt->execute([$userId]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data || !$data['enabled']) {
        echo json_encode(['success' => false, 'message' => '2FA not enabled']);
        return;
    }

    $backupCodes = generateBackupCodes();
    
    $stmt = $pdo->prepare("UPDATE user_2fa SET backup_codes = ? WHERE user_id = ?");
    $stmt->execute([json_encode($backupCodes), $userId]);

    echo json_encode([
        'success' => true,
        'message' => 'Backup codes regenerated',
        'data' => ['backup_codes' => $backupCodes]
    ]);
}

/**
 * Generate backup codes
 */
function generateBackupCodes() {
    $codes = [];
    for ($i = 0; $i < 10; $i++) {
        $codes[] = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    }
    return $codes;
}
