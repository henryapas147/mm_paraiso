<?php
require_once 'config/config.php';

// Fetch company information
$companyInfo = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM company_information WHERE is_active = 1 LIMIT 1");
    $stmt->execute();
    $companyInfo = $stmt->fetch();
} catch (PDOException $e) {
    error_log("Failed to fetch company info: " . $e->getMessage());
}

// Use database values or fallback to defaults
$companyName = $companyInfo['company_name'] ?? 'M&M Paraiso';
$description = $companyInfo['description'] ?? 'Your perfect getaway destination. Experience luxury and comfort in our beautifully designed properties.';

$address = $companyInfo['address_line1'] ?? '';
$city = $companyInfo['city'] ?? '';
$province = $companyInfo['province'] ?? '';
$country = $companyInfo['country'] ?? '';
$fullAddress = trim("$address, $city, $province, $country", ', ');
if (empty($fullAddress)) $fullAddress = 'Paradise City, Philippines';

$phone = $companyInfo['phone_number'] ?? '+63 XXX XXX XXXX';
$email = $companyInfo['email'] ?? 'info@mparaiso.com';

$facebookUrl = $companyInfo['facebook_url'] ?? 'https://www.facebook.com/anthonzcatzz';
$instagramUrl = $companyInfo['instagram_url'] ?? '#';
$twitterUrl = $companyInfo['twitter_url'] ?? '#';
$linkedinUrl = $companyInfo['linkedin_url'] ?? '#';

// Parse business hours JSON
$businessHours = [];
if ($companyInfo && !empty($companyInfo['business_hours'])) {
    $businessHours = json_decode($companyInfo['business_hours'], true) ?? [];
}

// Format hours display
$hoursDisplay = [];
$daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
foreach ($daysOrder as $day) {
    if (isset($businessHours[$day]) && $businessHours[$day]['is_open']) {
        $hoursDisplay[] = ucfirst(substr($day, 0, 3)) . ': ' . $businessHours[$day]['open'] . ' - ' . $businessHours[$day]['close'];
    }
}

// Fallback hours if no data
if (empty($hoursDisplay)) {
    $hoursDisplay = [
        'Mon - Fri: 9:00 AM - 6:00 PM',
        'Saturday: 10:00 AM - 4:00 PM',
        'Sunday: Closed'
    ];
}
?>
<footer class="footer bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase mb-3 text-white">About <?php echo htmlspecialchars($companyName); ?></h5>
                <p class="text-white-50"><?php echo htmlspecialchars($description); ?></p>
                <div class="social-links mt-3">
                    <a href="<?php echo htmlspecialchars($facebookUrl); ?>" target="_blank" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="<?php echo htmlspecialchars($instagramUrl); ?>" target="_blank" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="<?php echo htmlspecialchars($twitterUrl); ?>" target="_blank" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="<?php echo htmlspecialchars($linkedinUrl); ?>" target="_blank" class="text-white"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="text-uppercase mb-3 text-white">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="properties.php" class="text-white-50 text-decoration-none">Properties</a></li>
                    <li class="mb-2"><a href="contact.php" class="text-white-50 text-decoration-none">Contact Us</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">About Us</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-3 text-white">Contact Info</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> <span class="text-white-50"><?php echo htmlspecialchars($fullAddress); ?></span></li>
                    <li class="mb-2"><i class="fas fa-phone me-2"></i> <span class="text-white-50"><?php echo htmlspecialchars($phone); ?></span></li>
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i> <span class="text-white-50"><?php echo htmlspecialchars($email); ?></span></li>
                </ul>
            </div>

            <!-- Opening Hours -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-3 text-white">Opening Hours</h5>
                <ul class="list-unstyled text-white-50">
                    <?php foreach (array_slice($hoursDisplay, 0, 5) as $hours): ?>
                        <li class="mb-2"><?php echo htmlspecialchars($hours); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <hr class="my-4 border-secondary">

        <!-- Copyright -->
        <div class="row">
            <div class="col-md-8">
                <p class="text-white-50 mb-0">Copyright © <?php echo date('Y'); ?> <?php echo htmlspecialchars($companyName); ?>. All rights reserved.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <p class="text-white-50 mb-0">Designed and Developed by: <a rel="nofollow" href="https://www.facebook.com/anthonzcatzz" target="_blank" class="text-white">@nthonz</a> & <a href="https://www.facebook.com/henry.apas.2024" target="_blank" class="text-white">Hoza</a></p>
            </div>
        </div>
    </div>
</footer>