<?php
/**
 * Scheduled Notification System
 * This script should be run via cron job (e.g., daily) to check for expiring
 * promotions, seasonal rates, and discounts and notify customers.
 * 
 * Cron example: 0 9 * * * php /path/to/scheduled_notifications.php
 */

header('Content-Type: application/json');

// Use absolute path for cron job compatibility
$scriptDir = dirname(__FILE__);
require_once $scriptDir . '/../config/db.php';
require_once $scriptDir . '/../services/NotificationService.php';

use App\Services\NotificationService;

try {
    $notificationService = new NotificationService($pdo);
    
    // Get all active customer users
    $customerStmt = $pdo->prepare("
        SELECT ua.user_id 
        FROM user_accounts ua
        INNER JOIN user_roles ur ON ua.role_id = ur.role_id
        WHERE ur.role_code = 'customer'
        AND ua.account_status = 'active'
    ");
    $customerStmt->execute();
    $customerUsers = $customerStmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($customerUsers)) {
        echo json_encode(['success' => true, 'message' => 'No active customers found']);
        exit;
    }
    
    $notificationsCreated = 0;
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $threeDays = date('Y-m-d', strtotime('+3 days'));
    
    // 1. Check for promotions expiring soon
    $promoStmt = $pdo->prepare("
        SELECT promo_id, promo_title, short_desc, valid_until
        FROM promotions
        WHERE is_active = 1
        AND valid_until BETWEEN ? AND ?
        AND (valid_until > CURDATE() OR valid_until = CURDATE())
    ");
    $promoStmt->execute([$today, $threeDays]);
    $expiringPromos = $promoStmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($expiringPromos as $promo) {
        $daysLeft = (strtotime($promo['valid_until']) - strtotime($today)) / 86400;
        $timeText = $daysLeft <= 1 ? 'ends today' : ($daysLeft == 1 ? 'ends tomorrow' : "ends in {$daysLeft} days");
        
        $notificationService->createPromotionalNotification(
            $customerUsers,
            "Promotion Ending Soon: {$promo['promo_title']}",
            "This promotion {$timeText}. Don't miss out! " . ($promo['short_desc'] ?? ''),
            null
        );
        $notificationsCreated++;
    }
    
    // 2. Check for seasonal rates expiring soon
    $seasonStmt = $pdo->prepare("
        SELECT season_id, season_name, date_end
        FROM seasonal_rates
        WHERE is_active = 1
        AND date_end BETWEEN ? AND ?
        AND (date_end > CURDATE() OR date_end = CURDATE())
    ");
    $seasonStmt->execute([$today, $threeDays]);
    $expiringSeasons = $seasonStmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($expiringSeasons as $season) {
        $daysLeft = (strtotime($season['date_end']) - strtotime($today)) / 86400;
        $timeText = $daysLeft <= 1 ? 'ends today' : ($daysLeft == 1 ? 'ends tomorrow' : "ends in {$daysLeft} days");
        
        $notificationService->createPromotionalNotification(
            $customerUsers,
            "Seasonal Rate Ending: {$season['season_name']}",
            "This seasonal rate {$timeText}. Book now to take advantage!",
            null
        );
        $notificationsCreated++;
    }
    
    // 3. Check for discounts expiring soon
    $discountStmt = $pdo->prepare("
        SELECT discount_id, discount_name, discount_code, valid_until
        FROM discounts
        WHERE is_active = 1
        AND valid_until BETWEEN ? AND ?
        AND (valid_until > CURDATE() OR valid_until = CURDATE())
    ");
    $discountStmt->execute([$today, $threeDays]);
    $expiringDiscounts = $discountStmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($expiringDiscounts as $discount) {
        $daysLeft = (strtotime($discount['valid_until']) - strtotime($today)) / 86400;
        $timeText = $daysLeft <= 1 ? 'expires today' : ($daysLeft == 1 ? 'expires tomorrow' : "expires in {$daysLeft} days");
        
        $notificationService->createPromotionalNotification(
            $customerUsers,
            "Discount Code Expiring: {$discount['discount_name']}",
            "Use code {$discount['discount_code']} - it {$timeText}!",
            null
        );
        $notificationsCreated++;
    }
    
    // 4. Check for new promotions starting soon (within 3 days)
    $newPromoStmt = $pdo->prepare("
        SELECT promo_id, promo_title, short_desc, valid_from
        FROM promotions
        WHERE is_active = 1
        AND valid_from BETWEEN ? AND ?
    ");
    $newPromoStmt->execute([$today, $threeDays]);
    $newPromos = $newPromoStmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($newPromos as $promo) {
        $daysUntil = (strtotime($promo['valid_from']) - strtotime($today)) / 86400;
        $timeText = $daysUntil <= 0 ? 'starts today' : ($daysUntil == 1 ? 'starts tomorrow' : "starts in {$daysUntil} days");
        
        $notificationService->createPromotionalNotification(
            $customerUsers,
            "New Promotion Coming: {$promo['promo_title']}",
            "This promotion {$timeText}. " . ($promo['short_desc'] ?? 'Get ready!'),
            null
        );
        $notificationsCreated++;
    }
    
    echo json_encode([
        'success' => true,
        'message' => "Scheduled notifications completed. Created {$notificationsCreated} notifications.",
        'stats' => [
            'expiring_promotions' => count($expiringPromos),
            'expiring_seasonal_rates' => count($expiringSeasons),
            'expiring_discounts' => count($expiringDiscounts),
            'new_promotions' => count($newPromos),
            'total_notifications' => $notificationsCreated,
            'customers_notified' => count($customerUsers)
        ]
    ]);
    
} catch (Exception $e) {
    error_log("Scheduled notifications error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
