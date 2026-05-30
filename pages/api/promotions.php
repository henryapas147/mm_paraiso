<?php
/**
 * Public API Endpoint: Fetch Active Promotions
 * Returns active promotions with images for the special offers section
 */

require_once '../../config/db.php';

header('Content-Type: application/json');

try {
    // Fetch active promotions with their discount details and images
    $stmt = $pdo->prepare("
        SELECT
            p.promo_id,
            p.promo_code,
            p.promo_title,
            p.short_desc,
            p.full_description,
            p.promo_type,
            p.valid_from,
            p.valid_until,
            p.property_id,
            prop.property_name,
            d.discount_id,
            d.discount_type,
            d.discount_value,
            d.usage_limit,
            d.usage_count,
            pi.image_url,
            pi.thumbnail_url,
            pi.image_type
        FROM promotions p
        LEFT JOIN discounts d ON p.discount_id = d.discount_id
        LEFT JOIN properties prop ON p.property_id = prop.property_id
        LEFT JOIN promotion_images pi ON p.promo_id = pi.promo_id AND pi.is_active = 1
        WHERE p.is_active = 1
        AND (p.valid_from IS NULL OR p.valid_from <= CURDATE())
        AND (p.valid_until IS NULL OR p.valid_until >= CURDATE())
        ORDER BY p.valid_until ASC, p.created_at DESC
    ");
    $stmt->execute();
    $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group promotions by promo_id to handle multiple images
    $groupedPromos = [];
    foreach ($promotions as $promo) {
        $promoId = $promo['promo_id'];
        if (!isset($groupedPromos[$promoId])) {
            $groupedPromos[$promoId] = [
                'promo_id' => $promo['promo_id'],
                'promo_code' => $promo['promo_code'],
                'promo_title' => $promo['promo_title'],
                'short_desc' => $promo['short_desc'],
                'full_description' => $promo['full_description'],
                'promo_type' => $promo['promo_type'],
                'valid_from' => $promo['valid_from'],
                'valid_until' => $promo['valid_until'],
                'property_id' => $promo['property_id'],
                'property_name' => $promo['property_name'],
                'discount_type' => $promo['discount_type'],
                'discount_value' => floatval($promo['discount_value'] ?? 0),
                'usage_limit' => $promo['usage_limit'],
                'usage_count' => $promo['usage_count'],
                'images' => []
            ];
        }
        if ($promo['image_url']) {
            $groupedPromos[$promoId]['images'][] = [
                'image_url' => $promo['image_url'],
                'thumbnail_url' => $promo['thumbnail_url'],
                'image_type' => $promo['image_type']
            ];
        }
    }

    // Format for frontend consumption
    $formattedPromos = array_map(function($promo) {
        // Determine discount display
        $discountDisplay = '';
        if ($promo['discount_type'] === 'percentage') {
            $discountDisplay = intval($promo['discount_value']) . '% OFF';
        } else if ($promo['discount_type'] === 'fixed') {
            $discountDisplay = '₱' . number_format($promo['discount_value'], 2) . ' OFF';
        } else {
            $discountDisplay = 'Special Offer';
        }

        // Map promo type to tab category
        $tabCategory = 'all-deals';
        $promoTypeLower = strtolower($promo['promo_type'] ?? '');
        if (strpos($promoTypeLower, 'seasonal') !== false || strpos($promoTypeLower, 'holiday') !== false) {
            $tabCategory = 'seasonal';
        } else if (strpos($promoTypeLower, 'package') !== false || strpos($promoTypeLower, 'bundle') !== false) {
            $tabCategory = 'packages';
        } else if (strpos($promoTypeLower, 'early') !== false || strpos($promoTypeLower, 'advance') !== false) {
            $tabCategory = 'early-bird';
        }

        // Get primary image or fallback
        $primaryImage = null;
        if (!empty($promo['images'])) {
            $bannerImage = null;
            foreach ($promo['images'] as $img) {
                if ($img['image_type'] === 'banner') {
                    $bannerImage = $img['image_url'];
                    break;
                }
            }
            $primaryImage = $bannerImage ?: $promo['images'][0]['image_url'];
        }
        // Fallback to default image if no images
        if (!$primaryImage) {
            $primaryImage = 'uploads/promotion_images/promo_default.png';
        }

        // Format dates
        $validFrom = $promo['valid_from'] ? date('F j, Y', strtotime($promo['valid_from'])) : null;
        $validUntil = $promo['valid_until'] ? date('F j, Y', strtotime($promo['valid_until'])) : 'Ongoing';

        return [
            'id' => $promo['promo_id'],
            'code' => $promo['promo_code'],
            'title' => $promo['promo_title'],
            'description' => $promo['short_desc'] ?? $promo['full_description'] ?? '',
            'discount' => $discountDisplay,
            'discount_type' => $promo['discount_type'],
            'discount_value' => $promo['discount_value'],
            'type' => $tabCategory,
            'promo_type' => $promo['promo_type'],
            'valid_from' => $validFrom,
            'valid_until' => $validUntil,
            'valid_until_raw' => $promo['valid_until'],
            'property_name' => $promo['property_name'],
            'image' => $primaryImage ? '../' . $primaryImage : null,
            'usage_remaining' => $promo['usage_limit'] ? ($promo['usage_limit'] - $promo['usage_count']) : null
        ];
    }, array_values($groupedPromos));

    echo json_encode([
        'success' => true,
        'promotions' => $formattedPromos,
        'count' => count($formattedPromos)
    ]);

} catch (PDOException $e) {
    error_log("Promotions API error: " . $e->getMessage());
    error_log("Promotions API error details: " . $e->getCode() . " - " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
