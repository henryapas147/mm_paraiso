<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$action = $_GET['action'] ?? '';

try {
  switch ($action) {
    case 'regions':
      // Fetch all regions
      $stmt = $pdo->query("SELECT region_code, region_name, short_name FROM psgc_regions ORDER BY region_name ASC");
      echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      break;

    case 'provinces':
      // Fetch provinces by region
      $regionCode = $_GET['region_code'] ?? '';
      if (empty($regionCode)) {
        echo json_encode(['success' => false, 'message' => 'Region code is required']);
        exit;
      }
      $stmt = $pdo->prepare("SELECT province_code, province_name FROM psgc_provinces WHERE region_code = ? ORDER BY province_name ASC");
      $stmt->execute([$regionCode]);
      echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      break;

    case 'cities':
      // Fetch cities/municipalities by province
      $provinceCode = $_GET['province_code'] ?? '';
      if (empty($provinceCode)) {
        echo json_encode(['success' => false, 'message' => 'Province code is required']);
        exit;
      }
      $stmt = $pdo->prepare("SELECT city_municipality_code, city_municipality_name FROM psgc_cities_municipalities WHERE province_code = ? ORDER BY city_municipality_name ASC");
      $stmt->execute([$provinceCode]);
      echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      break;

    case 'barangays':
      // Fetch barangays by city/municipality
      $cityCode = $_GET['city_municipality_code'] ?? '';
      if (empty($cityCode)) {
        echo json_encode(['success' => false, 'message' => 'City/Municipality code is required']);
        exit;
      }
      $stmt = $pdo->prepare("SELECT barangay_code, barangay_name FROM psgc_barangays WHERE city_municipality_code = ? ORDER BY barangay_name ASC");
      $stmt->execute([$cityCode]);
      echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
      break;

    case 'full_address':
      // Fetch full address details from codes
      $regionCode = $_GET['region_code'] ?? '';
      $provinceCode = $_GET['province_code'] ?? '';
      $cityCode = $_GET['city_municipality_code'] ?? '';
      $barangayCode = $_GET['barangay_code'] ?? '';

      $address = [];

      if (!empty($regionCode)) {
        $stmt = $pdo->prepare("SELECT region_name FROM psgc_regions WHERE region_code = ?");
        $stmt->execute([$regionCode]);
        $address['region'] = $stmt->fetchColumn();
      }

      if (!empty($provinceCode)) {
        $stmt = $pdo->prepare("SELECT province_name FROM psgc_provinces WHERE province_code = ?");
        $stmt->execute([$provinceCode]);
        $address['province'] = $stmt->fetchColumn();
      }

      if (!empty($cityCode)) {
        $stmt = $pdo->prepare("SELECT city_municipality_name FROM psgc_cities_municipalities WHERE city_municipality_code = ?");
        $stmt->execute([$cityCode]);
        $address['city'] = $stmt->fetchColumn();
      }

      if (!empty($barangayCode)) {
        $stmt = $pdo->prepare("SELECT barangay_name FROM psgc_barangays WHERE barangay_code = ?");
        $stmt->execute([$barangayCode]);
        $address['barangay'] = $stmt->fetchColumn();
      }

      echo json_encode(['success' => true, 'data' => $address]);
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Invalid action']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
