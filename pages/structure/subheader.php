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
$email = $companyInfo['email'] ?? 'info@company.com';
$address = $companyInfo['address_line1'] ?? '';
$city = $companyInfo['city'] ?? '';
$province = $companyInfo['province'] ?? '';
$country = $companyInfo['country'] ?? '';
$fullAddress = trim("$address, $city, $province, $country", ', ');
if (empty($fullAddress)) $fullAddress = 'Sunny Isles Beach, FL 33160';

$facebookUrl = $companyInfo['facebook_url'] ?? '#';
$twitterUrl = $companyInfo['twitter_url'] ?? '#';
$linkedinUrl = $companyInfo['linkedin_url'] ?? '#';
$instagramUrl = $companyInfo['instagram_url'] ?? '#';
?>
<div class="sub-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <ul class="info">
                    <li><i class="fa fa-envelope"></i> <?php echo htmlspecialchars($email); ?></li>
                    <li><i class="fa fa-map"></i> <?php echo htmlspecialchars($fullAddress); ?></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-4">
                <ul class="social-links">
                    <li><a href="<?php echo htmlspecialchars($facebookUrl); ?>" target="_blank"><i class="fab fa-facebook"></i></a></li>
                    <li><a href="<?php echo htmlspecialchars($twitterUrl); ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="<?php echo htmlspecialchars($linkedinUrl); ?>" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                    <li><a href="<?php echo htmlspecialchars($instagramUrl); ?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>