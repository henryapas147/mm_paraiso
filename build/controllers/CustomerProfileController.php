<?php

namespace App\Controllers;

require_once __DIR__ . '/../config/db.php';

class CustomerProfileController extends Controller
{
    protected $pdo;

    public function __construct($params = [])
    {
        parent::__construct($params);
        global $pdo;
        $this->pdo = $pdo;
    }

    public function index()
    {
        // Get base path for redirects
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

        // Check if user is logged in
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: {$basePath}/sign-in");
            exit;
        }

        // Check if user is a customer
        if (($_SESSION['role_code'] ?? '') !== 'customer') {
            header("Location: {$basePath}/");
            exit;
        }

        $message = '';
        $messageType = '';

        // Handle profile update submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
            $user_id = $_SESSION['user_id'];
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $addressLine1 = trim($_POST['address_line1'] ?? '');
            $addressLine2 = trim($_POST['address_line2'] ?? '');
            $regionCode = trim($_POST['region_code'] ?? '');
            $provinceCode = trim($_POST['province_code'] ?? '');
            $cityMunicipalityCode = trim($_POST['city_municipality_code'] ?? '');
            $barangayCode = trim($_POST['barangay_code'] ?? '');
            $country = trim($_POST['country'] ?? '');
            $postalCode = trim($_POST['postal_code'] ?? '');

            try {
                // Update user profile with geography codes
                $stmt = $this->pdo->prepare("
                    UPDATE user_profiles
                    SET first_name = ?, last_name = ?, address_line1 = ?, address_line2 = ?,
                        region_code = ?, province_code = ?, city_municipality_code = ?, barangay_code = ?,
                        country = ?, postal_code = ?, updated_at = NOW()
                    WHERE user_id = ?
                ");
                $stmt->execute([$firstName, $lastName, $addressLine1, $addressLine2,
                    $regionCode, $provinceCode, $cityMunicipalityCode, $barangayCode,
                    $country, $postalCode, $user_id]);

                $_SESSION['profile_message'] = 'Profile updated successfully!';
                $_SESSION['profile_message_type'] = 'success';
                header("Location: {$basePath}/customer/profile");
                exit;
            } catch (\PDOException $e) {
                error_log("Profile update error: " . $e->getMessage());
                $_SESSION['profile_message'] = 'Error updating profile. Please try again.';
                $_SESSION['profile_message_type'] = 'error';
                header("Location: {$basePath}/customer/profile");
                exit;
            }
        }

        // Get message from session and clear it
        $message = $_SESSION['profile_message'] ?? '';
        $messageType = $_SESSION['profile_message_type'] ?? '';
        unset($_SESSION['profile_message'], $_SESSION['profile_message_type']);

        // Fetch user profile data
        $user_id = $_SESSION['user_id'];
        $user = null;
        $profile = null;

        try {
            // Get user account data
            $stmt = $this->pdo->prepare("
                SELECT ua.user_id, ua.email, ua.phone_number, ua.account_status,
                       ur.role_code, ur.role_label
                FROM user_accounts ua
                LEFT JOIN user_roles ur ON ua.role_id = ur.role_id
                WHERE ua.user_id = ? AND ua.deleted_at IS NULL
            ");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();

            // Get user profile data
            if ($user) {
                $stmt = $this->pdo->prepare("
                    SELECT first_name, last_name, middle_name, display_name,
                           profile_photo_url, cover_photo_url, address_line1, address_line2,
                           region_code, province_code, city_municipality_code, barangay_code,
                           r.region_name, p.province_name, c.city_municipality_name, b.barangay_name,
                           postal_code, country, date_of_birth
                    FROM user_profiles up
                    LEFT JOIN psgc_regions r ON up.region_code COLLATE utf8mb4_unicode_ci = r.region_code COLLATE utf8mb4_unicode_ci
                    LEFT JOIN psgc_provinces p ON up.province_code COLLATE utf8mb4_unicode_ci = p.province_code COLLATE utf8mb4_unicode_ci
                    LEFT JOIN psgc_cities_municipalities c ON up.city_municipality_code COLLATE utf8mb4_unicode_ci = c.city_municipality_code COLLATE utf8mb4_unicode_ci
                    LEFT JOIN psgc_barangays b ON up.barangay_code COLLATE utf8mb4_unicode_ci = b.barangay_code COLLATE utf8mb4_unicode_ci
                    WHERE up.user_id = ?
                ");
                $stmt->execute([$user_id]);
                $profile = $stmt->fetch();
            }
        } catch (\PDOException $e) {
            error_log("Profile fetch error: " . $e->getMessage());
        }

        // Use customer layout
        $this->viewCustomer('auth/customer_profile', [
            'message' => $message,
            'messageType' => $messageType,
            'user' => $user,
            'profile' => $profile
        ]);
    }
}
