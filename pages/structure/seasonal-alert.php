<?php
require_once 'config/config.php';

// Fetch active seasonal rates for current date
$activeSeasonalRates = [];
try {
    $stmt = $pdo->prepare("
        SELECT sr.season_name, sr.rate_multiplier, sr.date_start, sr.date_end,
               p.property_name, rc.category_name
        FROM seasonal_rates sr
        INNER JOIN properties p ON sr.property_id = p.property_id
        LEFT JOIN room_categories rc ON sr.applies_to = rc.category_id
        WHERE sr.is_active = 1
        AND (sr.date_start <= CURDATE() AND sr.date_end >= CURDATE())
        ORDER BY sr.date_start ASC
    ");
    $stmt->execute();
    $activeSeasonalRates = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Failed to fetch seasonal rates: " . $e->getMessage());
}

// Only show alert if there are active seasonal rates
if (!empty($activeSeasonalRates)):
?>
<div class="seasonal-alert-banner">
    <div class="alert-content">
        <div class="alert-icon">
            <i class="fa fa-bullhorn"></i>
        </div>
        <div class="marquee-container">
            <div class="marquee-track">
                <?php
                // Build the message string
                $messages = [];
                foreach ($activeSeasonalRates as $rate) {
                    $multiplier = floatval($rate['rate_multiplier']);
                    $percentageText = '';
                    $alertClass = '';

                    if ($multiplier < 1) {
                        $discountPercent = ((1 - $multiplier) * 100);
                        $percentageText = number_format($discountPercent, 0) . '% OFF';
                        $alertClass = 'discount';
                    } elseif ($multiplier > 1) {
                        $increasePercent = (($multiplier - 1) * 100);
                        $percentageText = '+' . number_format($increasePercent, 0) . '% SURCHARGE';
                        $alertClass = 'surcharge';
                    } else {
                        $percentageText = 'SPECIAL RATE';
                        $alertClass = 'special';
                    }

                    $messages[] = [
                        'badge' => $percentageText,
                        'name' => $rate['season_name'],
                        'property' => $rate['property_name'],
                        'category' => $rate['category_name'] ?? 'All Rooms',
                        'class' => $alertClass,
                        'date_start' => date('M j', strtotime($rate['date_start'])),
                        'date_end' => date('M j', strtotime($rate['date_end']))
                    ];
                }

                // Duplicate messages for seamless loop
                $allMessages = array_merge($messages, $messages);

                foreach ($allMessages as $msg):
                ?>
                    <div class="alert-message <?php echo $msg['class']; ?>">
                        <span class="rate-badge"><?php echo htmlspecialchars($msg['badge']); ?></span>
                        <span class="rate-name"><?php echo htmlspecialchars($msg['name']); ?></span>
                        <span class="rate-property">at <?php echo htmlspecialchars($msg['property']); ?></span>
                        <span class="rate-category">- <?php echo htmlspecialchars($msg['category']); ?></span>
                        <span class="rate-dates">(<?php echo htmlspecialchars($msg['date_start']); ?> - <?php echo htmlspecialchars($msg['date_end']); ?>)</span>
                        <span class="separator"><i class="fa fa-circle"></i></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button class="alert-close" onclick="this.parentElement.parentElement.style.display='none'">
            <i class="fa fa-times"></i>
        </button>
    </div>
</div>

<style>
.seasonal-alert-banner {
    background: #f35525;
    color: white;
    padding: 10px 0;
    position: sticky;
    top: 80px;
    z-index: 999;
    font-family: 'Poppins', sans-serif;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.alert-content {
    display: flex;
    align-items: center;
    max-width: 100%;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
}

.alert-icon {
    font-size: 16px;
    margin-right: 15px;
    flex-shrink: 0;
    animation: pulse 2s infinite;
    background: rgba(255,255,255,0.2);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
}

/* Marquee Styles */
.marquee-container {
    flex: 1;
    overflow: hidden;
    position: relative;
    height: 32px;
    display: flex;
    align-items: center;
}

.marquee-track {
    display: flex;
    align-items: center;
    gap: 40px;
    white-space: nowrap;
    animation: marquee 10s linear infinite;
}

.marquee-track:hover {
    animation-play-state: paused;
}

@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

.alert-message {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    flex-shrink: 0;
}

.rate-badge {
    background: rgba(255, 255, 255, 0.25);
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.alert-message.discount .rate-badge {
    background: #4CAF50;
    color: white;
}

.alert-message.surcharge .rate-badge {
    background: #ff5722;
    color: white;
}

.alert-message.special .rate-badge {
    background: #ffc107;
    color: #333;
}

.rate-name {
    font-weight: 600;
    font-size: 14px;
}

.rate-property {
    opacity: 0.9;
    font-size: 12px;
    font-weight: 400;
}

.rate-dates {
    opacity: 0.7;
    font-size: 11px;
    font-weight: 400;
}

.rate-category {
    opacity: 0.85;
    font-size: 12px;
    font-weight: 400;
    margin-left: 5px;
}

.separator {
    opacity: 0.6;
    font-size: 6px;
    margin: 0 5px;
}

.separator i {
    font-size: 6px;
}

.alert-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
    margin-left: 15px;
}

.alert-close:hover {
    background: rgba(255, 255, 255, 0.35);
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .alert-content {
        padding: 0 15px;
    }

    .alert-icon {
        width: 28px;
        height: 28px;
        font-size: 14px;
        margin-right: 10px;
    }

    .marquee-container {
        height: 28px;
    }

    .alert-message {
        font-size: 12px;
        gap: 8px;
    }

    .rate-name {
        font-size: 13px;
    }

    .rate-property {
        font-size: 11px;
    }
}
</style>
<?php endif; ?>
