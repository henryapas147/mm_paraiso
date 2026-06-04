<?php
/**
 * Notification Service
 * Handles creation and delivery of notifications to users
 */

namespace App\Services;

use PDO;

class NotificationService {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Create a notification and send it to specified users
     * 
     * @param array $data Notification data
     *   - type: notification type (reservation_confirmed, payment_received, etc.)
     *   - title: notification title
     *   - body: notification body/message
     *   - user_ids: array of user IDs to send to (or single user ID)
     *   - property_id: optional property ID
     *   - reference_type: optional reference type (reservation, payment, etc.)
     *   - reference_id: optional reference ID
     *   - action_url: optional deep link URL
     *   - scheduled_at: optional scheduled datetime (null = send immediately)
     * 
     * @return array Result with success status and notification ID
     */
    public function createNotification(array $data) {
        try {
            // Generate notification ID
            $notifId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

            // Validate required fields
            $required = ['type', 'title', 'body', 'user_ids'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    return ['success' => false, 'error' => "Missing required field: {$field}"];
                }
            }

            // Normalize user_ids to array
            $userIds = is_array($data['user_ids']) ? $data['user_ids'] : [$data['user_ids']];

            // Insert notification
            $stmt = $this->pdo->prepare("
                INSERT INTO notifications (
                    notif_id, property_id, notif_type, title, body, 
                    action_url, reference_type, reference_id, 
                    scheduled_at, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'sent', NOW())
            ");
            $stmt->execute([
                $notifId,
                $data['property_id'] ?? null,
                $data['type'],
                $data['title'],
                $data['body'],
                $data['action_url'] ?? null,
                $data['reference_type'] ?? null,
                $data['reference_id'] ?? null,
                $data['scheduled_at'] ?? null
            ]);

            // Insert recipients
            foreach ($userIds as $userId) {
                $recipientId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
                    mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
                    mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));

                $recipientStmt = $this->pdo->prepare("
                    INSERT INTO notification_recipients (
                        recipient_id, notif_id, user_id, is_read, 
                        is_sent, sent_at, delivery_status, created_at
                    ) VALUES (?, ?, ?, 0, 1, NOW(), 'delivered', NOW())
                ");
                $recipientStmt->execute([$recipientId, $notifId, $userId]);
            }

            return ['success' => true, 'notif_id' => $notifId];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Create reservation-related notification
     */
    public function createReservationNotification($type, $userId, $reservationId, $title, $body, $propertyId = null) {
        return $this->createNotification([
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'user_ids' => $userId,
            'property_id' => $propertyId,
            'reference_type' => 'reservation',
            'reference_id' => $reservationId,
            'action_url' => "../customers/reservations.php?reservation_id={$reservationId}"
        ]);
    }

    /**
     * Create payment-related notification
     */
    public function createPaymentNotification($userId, $paymentId, $title, $body, $propertyId = null) {
        return $this->createNotification([
            'type' => 'payment_received',
            'title' => $title,
            'body' => $body,
            'user_ids' => $userId,
            'property_id' => $propertyId,
            'reference_type' => 'payment',
            'reference_id' => $paymentId,
            'action_url' => "../customers/payments.php?payment_id={$paymentId}"
        ]);
    }

    /**
     * Create system alert notification
     */
    public function createSystemAlert($userId, $title, $body, $propertyId = null) {
        return $this->createNotification([
            'type' => 'system_alert',
            'title' => $title,
            'body' => $body,
            'user_ids' => $userId,
            'property_id' => $propertyId,
            'reference_type' => null,
            'reference_id' => null
        ]);
    }

    /**
     * Create promotional notification
     */
    public function createPromotionalNotification($userIds, $title, $body, $propertyId = null) {
        return $this->createNotification([
            'type' => 'promo_announcement',
            'title' => $title,
            'body' => $body,
            'user_ids' => $userIds,
            'property_id' => $propertyId,
            'reference_type' => 'promo',
            'reference_id' => null
        ]);
    }

    /**
     * Create support notification
     */
    public function createSupportNotification($userId, $title, $body, $propertyId = null) {
        return $this->createNotification([
            'type' => 'staff_alert',
            'title' => $title,
            'body' => $body,
            'user_ids' => $userId,
            'property_id' => $propertyId,
            'reference_type' => 'support',
            'reference_id' => null
        ]);
    }
}
