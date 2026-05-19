<?php
header('Content-Type: application/json');
require_once '../config/middleware.php';
applyAuthMiddleware();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
  switch ($method) {
    case 'GET':
      if ($action === 'list') {
        $stmt = $pdo->query("SELECT * FROM company_information WHERE is_active = 1 LIMIT 1");
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $data]);

      } elseif ($action === 'related') {
        $regions = $pdo->query("SELECT region_code, region_name FROM psgc_regions ORDER BY region_name")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => ['regions' => $regions]]);
      }
      break;

    case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);

      if ($action === 'create') {
        $companyId = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("INSERT INTO company_information (
          company_id, company_name, legal_name, business_reg_number, tax_id,
          address_line1, address_line2, city, province, postal_code, country,
          region_code, province_code, city_municipality_code, barangay_code, barangay,
          phone_number, phone_number_2, email, email_2, website, google_maps_embed_url, description,
          facebook_url, instagram_url, twitter_url, linkedin_url, youtube_url,
          business_hours, is_active, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
          $companyId, $data['company_name'], $data['legal_name'] ?? null, $data['business_reg_number'] ?? null,
          $data['tax_id'] ?? null, $data['address_line1'], $data['address_line2'] ?? null, $data['city'],
          $data['province'] ?? null, $data['postal_code'] ?? null, $data['country'] ?? 'Philippines',
          $data['region_code'] ?? null, $data['province_code'] ?? null, $data['city_municipality_code'] ?? null, $data['barangay_code'] ?? null, $data['barangay'] ?? null,
          $data['phone_number'], $data['phone_number_2'] ?? null, $data['email'], $data['email_2'] ?? null,
          $data['website'] ?? null, $data['google_maps_embed_url'] ?? null, $data['description'] ?? null,
          $data['facebook_url'] ?? null, $data['instagram_url'] ?? null, $data['twitter_url'] ?? null,
          $data['linkedin_url'] ?? null, $data['youtube_url'] ?? null, json_encode($data['business_hours'] ?? []),
          1, $data['user_id'] ?? null
        ]);
        echo json_encode(['success' => true, 'message' => 'Company created', 'company_id' => $companyId]);

      } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE company_information SET
          company_name=?, legal_name=?, business_reg_number=?, tax_id=?,
          address_line1=?, address_line2=?, city=?, province=?, postal_code=?, country=?,
          region_code=?, province_code=?, city_municipality_code=?, barangay_code=?, barangay=?,
          phone_number=?, phone_number_2=?, email=?, email_2=?, website=?, google_maps_embed_url=?, description=?,
          facebook_url=?, instagram_url=?, twitter_url=?, linkedin_url=?, youtube_url=?,
          business_hours=?, updated_by=?
          WHERE company_id=?");
        $stmt->execute([
          $data['company_name'], $data['legal_name'] ?? null, $data['business_reg_number'] ?? null,
          $data['tax_id'] ?? null, $data['address_line1'], $data['address_line2'] ?? null, $data['city'],
          $data['province'] ?? null, $data['postal_code'] ?? null, $data['country'] ?? 'Philippines',
          $data['region_code'] ?? null, $data['province_code'] ?? null, $data['city_municipality_code'] ?? null, $data['barangay_code'] ?? null, $data['barangay'] ?? null,
          $data['phone_number'], $data['phone_number_2'] ?? null, $data['email'], $data['email_2'] ?? null,
          $data['website'] ?? null, $data['google_maps_embed_url'] ?? null, $data['description'] ?? null,
          $data['facebook_url'] ?? null, $data['instagram_url'] ?? null, $data['twitter_url'] ?? null,
          $data['linkedin_url'] ?? null, $data['youtube_url'] ?? null, json_encode($data['business_hours'] ?? []),
          $data['user_id'] ?? null, $data['company_id']
        ]);
        echo json_encode(['success' => true, 'message' => 'Company updated']);
      }
      break;

    default:
      http_response_code(405);
      echo json_encode(['success' => false, 'message' => 'Method not allowed']);
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
