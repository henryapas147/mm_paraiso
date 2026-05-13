<?php
require_once '../../config/db.php';

// Handle Delete Property Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_property') {
  try {
    error_log("Starting property deletion process...");

    $property_id = $_POST['property_id'] ?? '';
    error_log("Deleting property_id: " . $property_id);

    if (empty($property_id)) {
      throw new Exception("Property ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Fetch property images to delete files
    $stmt = $pdo->prepare("SELECT image_url FROM property_images WHERE property_id = ?");
    $stmt->execute([$property_id]);
    $images = $stmt->fetchAll();

    // Delete image files from filesystem
    foreach ($images as $img) {
      $image_path = __DIR__ . '/../../' . $img['image_url'];
      if (file_exists($image_path)) {
        if (unlink($image_path)) {
          error_log("Deleted image file: " . $image_path);
        } else {
          error_log("Failed to delete image file: " . $image_path);
        }
      }
    }

    // Delete property images from database
    $stmt = $pdo->prepare("DELETE FROM property_images WHERE property_id = ?");
    $stmt->execute([$property_id]);
    error_log("Deleted property images from database");

    // Delete property from database (hard delete)
    $stmt = $pdo->prepare("DELETE FROM properties WHERE property_id = ?");
    $stmt->execute([$property_id]);
    error_log("Deleted property from database");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: properties.php?success=property_deleted');
    exit;
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("Error deleting property: " . $e->getMessage());
    $error_message = "Failed to delete property. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Edit Property Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_property') {
  try {
    error_log("Starting property edit process...");

    $property_id = $_POST['property_id'] ?? '';
    error_log("Editing property_id: " . $property_id);

    if (empty($property_id)) {
      throw new Exception("Property ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Handle image uploads (new images)
    $uploaded_images = [];
    if (!empty($_FILES['property_images']['name'][0])) {
      $upload_dir = '../../uploads/properties/';
      $absolute_upload_dir = __DIR__ . '/../../uploads/properties/';

      if (!is_dir($absolute_upload_dir)) {
        mkdir($absolute_upload_dir, 0777, true);
      }

      foreach ($_FILES['property_images']['name'] as $key => $name) {
        if ($_FILES['property_images']['error'][$key] === UPLOAD_ERR_OK) {
          $tmp_name = $_FILES['property_images']['tmp_name'][$key];
          $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
          $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

          if (in_array($file_ext, $allowed_exts)) {
            $new_filename = uniqid('prop_', true) . '.' . $file_ext;
            $upload_path = $absolute_upload_dir . $new_filename;
            $relative_path = 'uploads/properties/' . $new_filename;

            if (move_uploaded_file($tmp_name, $upload_path)) {
              $uploaded_images[] = [
                'url' => $relative_path,
                'caption' => $_POST['image_captions'][$key] ?? ''
              ];
            }
          }
        }
      }
    }

    // Handle image deletions
    if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
      foreach ($_POST['delete_images'] as $image_id) {
        // Get image URL before deletion
        $stmt = $pdo->prepare("SELECT image_url FROM property_images WHERE image_id = ?");
        $stmt->execute([$image_id]);
        $img = $stmt->fetch();

        if ($img) {
          // Delete file from filesystem
          $image_path = __DIR__ . '/../../' . $img['image_url'];
          if (file_exists($image_path)) {
            unlink($image_path);
          }

          // Delete from database
          $stmt = $pdo->prepare("DELETE FROM property_images WHERE image_id = ?");
          $stmt->execute([$image_id]);
        }
      }
    }

    // Insert new property images
    if (!empty($uploaded_images)) {
      $img_stmt = $pdo->prepare("
                INSERT INTO property_images (image_id, property_id, image_url, caption, image_type, sort_order, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

      // Get current max sort order
      $stmt = $pdo->prepare("SELECT MAX(sort_order) as max_sort FROM property_images WHERE property_id = ?");
      $stmt->execute([$property_id]);
      $result = $stmt->fetch();
      $current_sort = $result['max_sort'] ?? 0;

      foreach ($uploaded_images as $index => $img) {
        $image_id = generateUUID();
        $is_cover = ($current_sort === 0 && $index === 0) ? 'cover' : 'gallery';
        $img_stmt->execute([
          $image_id,
          $property_id,
          $img['url'],
          $img['caption'],
          $is_cover,
          $current_sort + $index,
          1
        ]);
      }
    }

    // Update property data
    $stmt = $pdo->prepare("
            UPDATE properties SET
                property_code = ?,
                property_name = ?,
                property_type = ?,
                tagline = ?,
                description = ?,
                address_line1 = ?,
                address_line2 = ?,
                city = ?,
                province = ?,
                postal_code = ?,
                country = ?,
                latitude = ?,
                longitude = ?,
                phone_primary = ?,
                phone_secondary = ?,
                email = ?,
                website_url = ?,
                facebook_url = ?,
                check_in_time = ?,
                check_out_time = ?,
                earliest_check_in = ?,
                latest_check_out = ?,
                late_check_out_fee = ?,
                timezone = ?,
                currency_code = ?,
                tax_rate_percent = ?,
                is_active = ?,
                sort_order = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE property_id = ?
        ");

    $stmt->execute([
      $_POST['property_code'],
      $_POST['property_name'],
      $_POST['property_type'],
      $_POST['tagline'] ?? null,
      $_POST['description'] ?? null,
      $_POST['address_line1'],
      $_POST['address_line2'] ?? null,
      $_POST['city'],
      $_POST['province'] ?? null,
      $_POST['postal_code'] ?? null,
      $_POST['country'] ?? 'Philippines',
      $_POST['latitude'] ?? null,
      $_POST['longitude'] ?? null,
      $_POST['phone_primary'] ?? null,
      $_POST['phone_secondary'] ?? null,
      $_POST['email'] ?? null,
      $_POST['website_url'] ?? null,
      $_POST['facebook_url'] ?? null,
      $_POST['check_in_time'] ?? '14:00:00',
      $_POST['check_out_time'] ?? '12:00:00',
      $_POST['earliest_check_in'] ?? '08:00:00',
      $_POST['latest_check_out'] ?? '22:00:00',
      $_POST['late_check_out_fee'] ?? 0.00,
      $_POST['timezone'] ?? 'Asia/Manila',
      $_POST['currency_code'] ?? 'PHP',
      $_POST['tax_rate_percent'] ?? 0.00,
      $_POST['is_active'] ?? 1,
      $_POST['sort_order'] ?? 0,
      $property_id
    ]);

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: properties.php?success=property_updated');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error editing property: " . $e->getMessage());
    $error_message = "Failed to update property. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error editing property: " . $e->getMessage());
    $error_message = "Failed to update property. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Add Property Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_property') {
  try {
    error_log("Starting property addition process...");

    // Generate UUID for property_id
    $property_id = generateUUID();
    error_log("Generated property_id: " . $property_id);

    // Handle image uploads
    $uploaded_images = [];
    if (!empty($_FILES['property_images']['name'][0])) {
      $upload_dir = '../../uploads/properties/';
      $absolute_upload_dir = __DIR__ . '/../../uploads/properties/';

      error_log("Upload directory (relative): " . $upload_dir);
      error_log("Upload directory (absolute): " . $absolute_upload_dir);

      if (!is_dir($absolute_upload_dir)) {
        mkdir($absolute_upload_dir, 0777, true);
        error_log("Created upload directory: " . $absolute_upload_dir);
      } else {
        error_log("Upload directory exists: " . $absolute_upload_dir);
        error_log("Directory is writable: " . (is_writable($absolute_upload_dir) ? 'Yes' : 'No'));
      }

      foreach ($_FILES['property_images']['name'] as $key => $name) {
        if ($_FILES['property_images']['error'][$key] === UPLOAD_ERR_OK) {
          $tmp_name = $_FILES['property_images']['tmp_name'][$key];
          $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
          $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

          error_log("Processing file: " . $name . " (extension: " . $file_ext . ")");

          if (in_array($file_ext, $allowed_exts)) {
            $new_filename = uniqid('prop_', true) . '.' . $file_ext;
            $upload_path = $absolute_upload_dir . $new_filename;
            $relative_path = 'uploads/properties/' . $new_filename;

            error_log("Target path: " . $upload_path);
            error_log("Relative path to store: " . $relative_path);

            if (move_uploaded_file($tmp_name, $upload_path)) {
              $uploaded_images[] = [
                'url' => $relative_path,
                'caption' => $_POST['image_captions'][$key] ?? ''
              ];
              error_log("Successfully uploaded image: " . $new_filename);
              error_log("File exists after upload: " . (file_exists($upload_path) ? 'Yes' : 'No'));
            } else {
              error_log("Failed to move uploaded file from: " . $tmp_name . " to: " . $upload_path);
            }
          } else {
            error_log("Invalid file extension: " . $file_ext);
          }
        } else {
          error_log("Upload error code: " . $_FILES['property_images']['error'][$key] . " for file: " . $name);
        }
      }
    } else {
      error_log("No images uploaded in the request");
    }

    // Insert property data
    $stmt = $pdo->prepare("
            INSERT INTO properties (
                property_id, property_code, property_name, property_type, tagline, description,
                address_line1, address_line2, city, province, postal_code, country,
                latitude, longitude, phone_primary, phone_secondary, email, website_url, facebook_url,
                check_in_time, check_out_time, earliest_check_in, latest_check_out, late_check_out_fee,
                timezone, currency_code, tax_rate_percent, is_active, sort_order, created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

    $params = [
      $property_id,
      $_POST['property_code'],
      $_POST['property_name'],
      $_POST['property_type'],
      $_POST['tagline'] ?? null,
      $_POST['description'] ?? null,
      $_POST['address_line1'],
      $_POST['address_line2'] ?? null,
      $_POST['city'],
      $_POST['province'] ?? null,
      $_POST['postal_code'] ?? null,
      $_POST['country'] ?? 'Philippines',
      $_POST['latitude'] ?? null,
      $_POST['longitude'] ?? null,
      $_POST['phone_primary'] ?? null,
      $_POST['phone_secondary'] ?? null,
      $_POST['email'] ?? null,
      $_POST['website_url'] ?? null,
      $_POST['facebook_url'] ?? null,
      $_POST['check_in_time'] ?? '14:00:00',
      $_POST['check_out_time'] ?? '12:00:00',
      $_POST['earliest_check_in'] ?? '08:00:00',
      $_POST['latest_check_out'] ?? '22:00:00',
      $_POST['late_check_out_fee'] ?? 0.00,
      $_POST['timezone'] ?? 'Asia/Manila',
      $_POST['currency_code'] ?? 'PHP',
      $_POST['tax_rate_percent'] ?? 0.00,
      $_POST['is_active'] ?? 1,
      $_POST['sort_order'] ?? 0,
      null // created_by - will be set when auth is implemented
    ];

    error_log("Executing property insert with params: " . json_encode($params));
    $stmt->execute($params);
    error_log("Property inserted successfully");

    // Insert property images
    if (!empty($uploaded_images)) {
      $img_stmt = $pdo->prepare("
                INSERT INTO property_images (image_id, property_id, image_url, caption, image_type, sort_order, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

      foreach ($uploaded_images as $index => $img) {
        $image_id = generateUUID();
        $is_cover = ($index === 0) ? 'cover' : 'gallery';
        $img_stmt->execute([
          $image_id,
          $property_id,
          $img['url'],
          $img['caption'],
          $is_cover,
          $index,
          1
        ]);
        error_log("Image inserted: " . $image_id);
      }
    }

    // Redirect to avoid form resubmission
    header('Location: properties.php?success=property_added');
    exit;
  } catch (PDOException $e) {
    error_log("PDO Error adding property: " . $e->getMessage());
    error_log("Error code: " . $e->getCode());
    error_log("Error info: " . print_r($e->errorInfo, true));
    $error_message = "Failed to add property. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    error_log("General Error adding property: " . $e->getMessage());
    $error_message = "Failed to add property. Please try again. Error: " . $e->getMessage();
  }
}

// Helper function to generate UUID
function generateUUID()
{
  return sprintf(
    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff)
  );
}

// Fetch all properties
$stmt = $pdo->query("
    SELECT 
        p.*
    FROM properties p
    WHERE p.is_active = 1
    ORDER BY p.sort_order ASC, p.property_name ASC
");
$properties = $stmt->fetchAll();

// Fetch property images for each property
$propertyImages = [];
foreach ($properties as $property) {
  $stmt = $pdo->prepare("
        SELECT image_id, image_url, thumbnail_url, caption, image_type, sort_order
        FROM property_images
        WHERE property_id = ? AND is_active = 1
        ORDER BY image_type = 'cover' DESC, sort_order ASC
    ");
  $stmt->execute([$property['property_id']]);
  $propertyImages[$property['property_id']] = $stmt->fetchAll();
}

// Fetch rooms for each property
$propertyRooms = [];
foreach ($properties as $property) {
  $stmt = $pdo->prepare("
        SELECT 
            r.*,
            rc.category_name,
            rc.category_code,
            rc.base_price_per_night
        FROM rooms r
        INNER JOIN room_categories rc ON r.category_id = rc.category_id
        WHERE r.property_id = ? AND r.is_active = 1
        ORDER BY r.room_number ASC
    ");
  $stmt->execute([$property['property_id']]);
  $propertyRooms[$property['property_id']] = $stmt->fetchAll();
}
?>

<script>
  // Store properties data for JavaScript access
  const propertiesData = <?php echo json_encode($properties); ?>;
  const propertyImagesData = <?php echo json_encode($propertyImages); ?>;
</script>

<?php $assets_path = '../assets';
$page_title = 'Properties'; ?>
<!DOCTYPE html>
<html>

<head>
  <?php include('../../structure/head.php'); ?>
  <style>
    .swal2-popup {
      border-radius: 1rem !important;
    }

    .swal2-confirm {
      background-color: #3085d6 !important;
      color: white !important;
      border: none !important;
      padding: 0.75rem 1.5rem !important;
      border-radius: 0.5rem !important;
      font-weight: 600 !important;
    }

    .swal2-cancel {
      background-color: #d33 !important;
      color: white !important;
      border: none !important;
      padding: 0.75rem 1.5rem !important;
      border-radius: 0.5rem !important;
      font-weight: 600 !important;
    }
  </style>
</head>

<body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
  <div class="absolute w-full bg-blue-500 dark:hidden min-h-75"></div>
  <!-- sidenav  -->
  <?php include('../../structure/sidebar.php'); ?>

  <!-- end sidenav -->

  <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
    <!-- Navbar -->
    <?php include('../../structure/navbar.php'); ?>

    <!-- end Navbar -->

    <!-- cards -->
    <div class="w-full px-6 py-6 mx-auto">
      <!-- PAGE CONTENT -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Properties</h6>
              <button onclick="openAddPropertyModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Property
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="propertiesTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Location</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($properties)): ?>
                      <tr>
                        <td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No properties found. <a href="javascript:;" class="text-blue-500 font-semibold">Add your first property</a>.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($properties as $index => $property): ?>
                        <tr class="property-row">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div>
                                <?php
                                $property_images = $propertyImages[$property['property_id']] ?? [];
                                $cover_image = null;
                                foreach ($property_images as $img) {
                                  if ($img['image_type'] === 'cover') {
                                    $cover_image = $img;
                                    break;
                                  }
                                }
                                // If no cover image, use the first image
                                if (!$cover_image && !empty($property_images)) {
                                  $cover_image = $property_images[0];
                                }
                                ?>
                                <?php if ($cover_image): ?>
                                  <img src="../../<?php echo htmlspecialchars($cover_image['image_url']); ?>" alt="<?php echo htmlspecialchars($property['property_name']); ?>" class="mr-4 h-9 w-9 object-cover rounded-xl" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                  <div class="hidden inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-building"></i>
                                  </div>
                                <?php else: ?>
                                  <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-building"></i>
                                  </div>
                                <?php endif; ?>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo htmlspecialchars($property['property_name']); ?></h6>
                                <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400"><?php echo htmlspecialchars($property['property_code']); ?></p>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo ucfirst(str_replace('_', ' ', $property['property_type'])); ?></p>
                            <?php if ($property['tagline']): ?>
                              <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars(substr($property['tagline'], 0, 30)); ?>...</p>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($property['city']); ?></p>
                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars($property['country']); ?></p>
                          </td>
                          <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Active</span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="viewPropertyDetails('<?php echo $property['property_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="toggleRoomsDropdown('<?php echo $property['property_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-500/20 hover:text-emerald-600 dark:hover:text-emerald-400 hover:border-emerald-300 dark:hover:border-emerald-500 transition-all duration-200 shadow-sm hover:shadow-md" id="roomsBtn-<?php echo $property['property_id']; ?>" title="View Rooms">
                                <i class="fas fa-door-open mr-1.5 text-xs"></i>
                                <span>Rooms</span>
                              </button>
                              <button onclick="openEditPropertyModal('<?php echo $property['property_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Property">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="confirmDeleteProperty('<?php echo $property['property_id']; ?>', '<?php echo htmlspecialchars($property['property_name']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Property">
                                <i class="fas fa-trash-alt mr-1.5 text-xs"></i>
                                <span>Delete</span>
                              </button>
                            </div>
                          </td>
                        </tr>
                        <!-- Rooms Dropdown -->
                        <tr id="roomsDropdown-<?php echo $property['property_id']; ?>" class="hidden">
                          <td colspan="5" class="p-0 align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                            <div class="p-4 bg-gray-50 dark:bg-slate-800/50">
                              <div class="mb-3 flex items-center justify-between">
                                <h6 class="text-sm font-bold dark:text-white">Rooms for <?php echo htmlspecialchars($property['property_name']); ?></h6>
                                <span class="text-xs text-slate-500 dark:text-white/60"><?php echo count($propertyRooms[$property['property_id']] ?? []); ?> room(s)</span>
                              </div>
                              <?php
                              $rooms = $propertyRooms[$property['property_id']] ?? [];
                              if (empty($rooms)):
                              ?>
                                <p class="text-xs text-slate-400 dark:text-white/60 text-center py-4">No rooms found for this property.</p>
                              <?php else: ?>
                                <div class="overflow-x-auto">
                                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                    <thead class="align-bottom">
                                      <tr>
                                        <th class="px-4 py-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room</th>
                                        <th class="px-4 py-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                                        <th class="px-4 py-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                                        <th class="px-4 py-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Price/Night</th>
                                        <th class="px-4 py-2 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php foreach ($rooms as $room): ?>
                                        <tr>
                                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="flex items-center">
                                              <div class="inline-flex items-center justify-center mr-3 text-sm text-white transition-all duration-200 ease-in-out h-8 w-8 rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                                                <i class="fas fa-door-open text-xs"></i>
                                              </div>
                                              <div>
                                                <h6 class="mb-0 text-xs leading-normal dark:text-white"><?php echo htmlspecialchars($room['room_name'] ?: $room['room_number']); ?></h6>
                                                <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars($room['room_number']); ?></p>
                                              </div>
                                            </div>
                                          </td>
                                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($room['category_name']); ?></p>
                                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars($room['category_code']); ?></p>
                                          </td>
                                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <?php
                                            $statusColors = [
                                              'available' => '#10b981',
                                              'occupied' => '#ef4444',
                                              'reserved' => '#3b82f6',
                                              'cleaning' => '#eab308',
                                              'maintenance' => '#6b7280',
                                              'out_of_order' => '#dc2626',
                                              'blocked' => '#a855f7'
                                            ];
                                            $statusColor = $statusColors[$room['room_status']] ?? '#6b7280';
                                            $statusLabel = ucfirst(str_replace('_', ' ', $room['room_status']));
                                            ?>
                                            <span class="px-2 text-xs rounded py-1 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none" style="background-color: <?php echo $statusColor; ?>; color: white !important;"><?php echo $statusLabel; ?></span>
                                          </td>
                                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80">PHP <?php echo number_format($room['base_price_per_night'] ?? $room['override_price_per_night'] ?? 0, 2); ?></p>
                                          </td>
                                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <a href="rooms.php" class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 hover:text-blue-500 cursor-pointer">
                                              <i class="fas fa-external-link-alt mr-1"></i>Manage
                                            </a>
                                          </td>
                                        </tr>
                                      <?php endforeach; ?>
                                    </tbody>
                                  </table>
                                </div>
                              <?php endif; ?>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Property Details Modal -->
      <div id="propertyDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePropertyDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white" id="modalPropertyName" style="font-size: 1.25rem; font-weight: bold; margin: 0;">Property Details</h3>
            <button onclick="closePropertyDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="modalPropertyContent" style="padding: 1.5rem;">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Property Modal -->
      <div id="addPropertyModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddPropertyModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Add New Property</h3>
            <button onclick="closeAddPropertyModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="add_property">

            <!-- Basic Information -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Basic Information</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Code *</label>
                  <input type="text" name="property_code" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. RST-001">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Name *</label>
                  <input type="text" name="property_name" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. Sunset Beach Resort">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Type *</label>
                  <select name="property_type" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="resort">Resort</option>
                    <option value="hotel">Hotel</option>
                    <option value="pension_house">Pension House</option>
                    <option value="hostel">Hostel</option>
                    <option value="villa">Villa</option>
                    <option value="beach_resort">Beach Resort</option>
                    <option value="mountain_resort">Mountain Resort</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Tagline</label>
                  <input type="text" name="tagline" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. Your Paradise Awaits">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Description</label>
                  <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Property description..."></textarea>
                </div>
              </div>
            </div>

            <!-- Location -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Location</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Address Line 1 *</label>
                  <input type="text" name="address_line1" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Street address">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Address Line 2</label>
                  <input type="text" name="address_line2" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="Apartment, suite, etc.">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">City *</label>
                  <input type="text" name="city" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. Manila">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Province</label>
                  <input type="text" name="province" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. Metro Manila">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Postal Code</label>
                  <input type="text" name="postal_code" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. 1000">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Country</label>
                  <input type="text" name="country" value="Philippines" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Latitude</label>
                  <input type="text" name="latitude" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. 14.5995">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Longitude</label>
                  <input type="text" name="longitude" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. 120.9842">
                </div>
              </div>
            </div>

            <!-- Contact Information -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Contact Information</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Primary Phone</label>
                  <input type="text" name="phone_primary" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. +63 912 345 6789">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Secondary Phone</label>
                  <input type="text" name="phone_secondary" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. +63 912 345 6790">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Email</label>
                  <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. info@resort.com">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Website URL</label>
                  <input type="url" name="website_url" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. https://www.resort.com">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Facebook URL</label>
                  <input type="url" name="facebook_url" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" placeholder="e.g. https://www.facebook.com/resort">
                </div>
              </div>
            </div>

            <!-- Check-in/Check-out Settings -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Check-in/Check-out Settings</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Check-in Time</label>
                  <input type="time" name="check_in_time" value="14:00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Check-out Time</label>
                  <input type="time" name="check_out_time" value="12:00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Earliest Check-in</label>
                  <input type="time" name="earliest_check_in" value="08:00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Latest Check-out</label>
                  <input type="time" name="latest_check_out" value="22:00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Late Check-out Fee</label>
                  <input type="number" step="0.01" name="late_check_out_fee" value="0.00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
              </div>
            </div>

            <!-- Settings -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Settings</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Timezone</label>
                  <select name="timezone" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="Asia/Manila" selected>Asia/Manila</option>
                    <option value="Asia/Tokyo">Asia/Tokyo</option>
                    <option value="Asia/Singapore">Asia/Singapore</option>
                    <option value="Asia/Hong_Kong">Asia/Hong Kong</option>
                    <option value="UTC">UTC</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Currency Code</label>
                  <select name="currency_code" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="PHP" selected>PHP (Philippine Peso)</option>
                    <option value="USD">USD (US Dollar)</option>
                    <option value="EUR">EUR (Euro)</option>
                    <option value="SGD">SGD (Singapore Dollar)</option>
                    <option value="JPY">JPY (Japanese Yen)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Tax Rate (%)</label>
                  <input type="number" step="0.01" name="tax_rate_percent" value="0.00" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Sort Order</label>
                  <input type="number" name="sort_order" value="0" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Status</label>
                  <select name="is_active" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Image Upload -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Property Images</h4>
              <div class="border-2 border-dashed border-gray-300 dark:border-white/20 rounded-lg p-6">
                <div class="text-center mb-4">
                  <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-2"></i>
                  <p class="text-sm text-slate-600 dark:text-white/60">Click to upload or drag and drop</p>
                  <p class="text-xs text-slate-400 dark:text-white/40">JPG, JPEG, PNG, GIF, WEBP (Max 5MB each)</p>
                </div>
                <input type="file" name="property_images[]" multiple accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" id="propertyImagesInput" onchange="handleImagePreview(this)">
                <div id="imagePreviewContainer" class="mt-4 grid grid-cols-3 gap-4"></div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
              <button type="button" onclick="closeAddPropertyModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                <i class="fas fa-plus mr-2"></i>Add Property
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Property Modal -->
      <div id="editPropertyModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditPropertyModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Property</h3>
            <button onclick="closeEditPropertyModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="edit_property">
            <input type="hidden" name="property_id" id="edit_property_id">

            <!-- Basic Information -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Basic Information</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Code *</label>
                  <input type="text" name="property_code" id="edit_property_code" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Name *</label>
                  <input type="text" name="property_name" id="edit_property_name" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Property Type *</label>
                  <select name="property_type" id="edit_property_type" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="hotel">Hotel</option>
                    <option value="resort">Resort</option>
                    <option value="apartment">Apartment</option>
                    <option value="house">House</option>
                    <option value="villa">Villa</option>
                    <option value="hostel">Hostel</option>
                    <option value="motel">Motel</option>
                    <option value="guest_house">Guest House</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Tagline</label>
                  <input type="text" name="tagline" id="edit_tagline" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Description</label>
                  <textarea name="description" id="edit_description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white"></textarea>
                </div>
              </div>
            </div>

            <!-- Location -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Location</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Address Line 1 *</label>
                  <input type="text" name="address_line1" id="edit_address_line1" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Address Line 2</label>
                  <input type="text" name="address_line2" id="edit_address_line2" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">City *</label>
                  <input type="text" name="city" id="edit_city" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Province</label>
                  <input type="text" name="province" id="edit_province" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Postal Code</label>
                  <input type="text" name="postal_code" id="edit_postal_code" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Country *</label>
                  <input type="text" name="country" id="edit_country" value="Philippines" required class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Latitude</label>
                  <input type="number" step="any" name="latitude" id="edit_latitude" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Longitude</label>
                  <input type="number" step="any" name="longitude" id="edit_longitude" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
              </div>
            </div>

            <!-- Contact Information -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Contact Information</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Primary Phone</label>
                  <input type="tel" name="phone_primary" id="edit_phone_primary" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Secondary Phone</label>
                  <input type="tel" name="phone_secondary" id="edit_phone_secondary" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Email</label>
                  <input type="email" name="email" id="edit_email" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Website URL</label>
                  <input type="url" name="website_url" id="edit_website_url" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Facebook URL</label>
                  <input type="url" name="facebook_url" id="edit_facebook_url" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
              </div>
            </div>

            <!-- Check-in/Check-out Settings -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Check-in/Check-out Settings</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Check-in Time</label>
                  <input type="time" name="check_in_time" id="edit_check_in_time" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Check-out Time</label>
                  <input type="time" name="check_out_time" id="edit_check_out_time" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Earliest Check-in</label>
                  <input type="time" name="earliest_check_in" id="edit_earliest_check_in" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Latest Check-out</label>
                  <input type="time" name="latest_check_out" id="edit_latest_check_out" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Late Check-out Fee</label>
                  <input type="number" step="0.01" name="late_check_out_fee" id="edit_late_check_out_fee" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
              </div>
            </div>

            <!-- Settings -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Settings</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Timezone</label>
                  <select name="timezone" id="edit_timezone" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="Asia/Manila">Asia/Manila</option>
                    <option value="Asia/Tokyo">Asia/Tokyo</option>
                    <option value="Asia/Singapore">Asia/Singapore</option>
                    <option value="Asia/Hong_Kong">Asia/Hong Kong</option>
                    <option value="UTC">UTC</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Currency Code</label>
                  <select name="currency_code" id="edit_currency_code" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="PHP">PHP (Philippine Peso)</option>
                    <option value="USD">USD (US Dollar)</option>
                    <option value="EUR">EUR (Euro)</option>
                    <option value="SGD">SGD (Singapore Dollar)</option>
                    <option value="JPY">JPY (Japanese Yen)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Tax Rate (%)</label>
                  <input type="number" step="0.01" name="tax_rate_percent" id="edit_tax_rate_percent" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Sort Order</label>
                  <input type="number" name="sort_order" id="edit_sort_order" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-white mb-1">Status</label>
                  <select name="is_active" id="edit_is_active" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Existing Images -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Existing Images</h4>
              <div id="editExistingImagesContainer" class="grid grid-cols-3 gap-4"></div>
            </div>

            <!-- Image Upload -->
            <div class="mb-6">
              <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-4">Add New Images</h4>
              <div class="border-2 border-dashed border-gray-300 dark:border-white/20 rounded-lg p-6">
                <div class="text-center mb-4">
                  <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-2"></i>
                  <p class="text-sm text-slate-600 dark:text-white/60">Click to upload or drag and drop</p>
                  <p class="text-xs text-slate-400 dark:text-white/40">JPG, JPEG, PNG, GIF, WEBP (Max 5MB each)</p>
                </div>
                <input type="file" name="property_images[]" multiple accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="w-full px-3 py-2 border border-gray-300 dark:border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white" id="editPropertyImagesInput" onchange="handleEditImagePreview(this)">
                <div id="editImagePreviewContainer" class="mt-4 grid grid-cols-3 gap-4"></div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
              <button type="button" onclick="closeEditPropertyModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                <i class="fas fa-save mr-2"></i>Update Property
              </button>
            </div>
          </form>
        </div>
      </div>

      <?php include('../../structure/footer.php'); ?>

    </div>
    <!-- end cards -->
  </main>

  <?php include('../../structure/customizer.php'); ?>
</body>

<?php include('../../structure/script.php'); ?>
<script>
  function viewPropertyDetails(propertyId) {
    const property = propertiesData.find(p => p.property_id === propertyId);
    if (!property) return;

    const modal = document.getElementById('propertyDetailsModal');
    const modalName = document.getElementById('modalPropertyName');
    const modalContent = document.getElementById('modalPropertyContent');

    modalName.textContent = property.property_name;

    // Get property images
    const images = propertyImagesData[propertyId] || [];

    // Build images HTML
    let imagesHtml = '';
    if (images.length > 0) {
      imagesHtml = `
          <div class="mb-6">
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Property Images</h4>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
              ${images.map(img => {
                // The image_url is stored as 'uploads/properties/filename.png'
                // From build/pages/properties.php, we need to go up 2 levels to reach root
                const imagePath = `../../${img.image_url}`;
                console.log('Image URL from DB:', img.image_url);
                console.log('Full image path:', imagePath);
                return `
                <div class="relative group">
                  <img src="${imagePath}" alt="${img.caption || 'Property image'}" class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity bg-gray-200" onerror="console.log('Failed to load:', '${imagePath}'); this.style.display='none'; this.parentElement.innerHTML='<div class=\\'w-full h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 text-xs\\'>Image not found: ${img.image_url}</div>'" onclick="window.open('${imagePath}', '_blank')">
                  ${img.caption ? `<p class="text-xs text-slate-600 dark:text-white/80 mt-1 truncate">${img.caption}</p>` : ''}
                  <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                    ${img.image_type}
                  </span>
                </div>
              `}).join('')}
            </div>
          </div>
        `;
    } else {
      imagesHtml = `
          <div class="mb-6">
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Property Images</h4>
            <p class="text-sm text-slate-500 dark:text-white/60">No images uploaded for this property.</p>
          </div>
        `;
    }

    modalContent.innerHTML = `
        ${imagesHtml}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Basic Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property Code</p>
                <p class="text-sm font-semibold dark:text-white">${property.property_code}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property Type</p>
                <p class="text-sm font-semibold dark:text-white">${property.property_type.replace(/_/g, ' ').toUpperCase()}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Tagline</p>
                <p class="text-sm dark:text-white">${property.tagline || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Description</p>
                <p class="text-sm dark:text-white">${property.description || '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Location</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Address Line 1</p>
                <p class="text-sm dark:text-white">${property.address_line1}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Address Line 2</p>
                <p class="text-sm dark:text-white">${property.address_line2 || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">City</p>
                <p class="text-sm dark:text-white">${property.city}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Province</p>
                <p class="text-sm dark:text-white">${property.province || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Postal Code</p>
                <p class="text-sm dark:text-white">${property.postal_code || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Country</p>
                <p class="text-sm dark:text-white">${property.country}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Coordinates</p>
                <p class="text-sm dark:text-white">${property.latitude && property.longitude ? `${property.latitude}, ${property.longitude}` : '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Contact Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Primary Phone</p>
                <p class="text-sm dark:text-white">${property.phone_primary || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Secondary Phone</p>
                <p class="text-sm dark:text-white">${property.phone_secondary || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Email</p>
                <p class="text-sm dark:text-white">${property.email || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Website</p>
                <p class="text-sm dark:text-white">${property.website_url ? `<a href="${property.website_url}" target="_blank" class="text-blue-500 hover:underline">${property.website_url}</a>` : '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Facebook</p>
                <p class="text-sm dark:text-white">${property.facebook_url ? `<a href="${property.facebook_url}" target="_blank" class="text-blue-500 hover:underline">${property.facebook_url}</a>` : '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Check-in/Check-out</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Check-in Time</p>
                <p class="text-sm dark:text-white">${property.check_in_time}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Check-out Time</p>
                <p class="text-sm dark:text-white">${property.check_out_time}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Earliest Check-in</p>
                <p class="text-sm dark:text-white">${property.earliest_check_in}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Latest Check-out</p>
                <p class="text-sm dark:text-white">${property.latest_check_out}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Late Check-out Fee</p>
                <p class="text-sm dark:text-white">${property.currency_code || 'PHP'} ${parseFloat(property.late_check_out_fee).toFixed(2)}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Settings</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Timezone</p>
                <p class="text-sm dark:text-white">${property.timezone}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Currency</p>
                <p class="text-sm dark:text-white">${property.currency_code}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Tax Rate</p>
                <p class="text-sm dark:text-white">${property.tax_rate_percent}%</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <p class="text-sm dark:text-white">${property.is_active ? 'Active' : 'Inactive'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Created At</p>
                <p class="text-sm dark:text-white">${new Date(property.created_at).toLocaleString()}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Updated At</p>
                <p class="text-sm dark:text-white">${new Date(property.updated_at).toLocaleString()}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closePropertyDetailsModal() {
    const modal = document.getElementById('propertyDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  // Toggle Rooms Dropdown Function
  function toggleRoomsDropdown(propertyId) {
    const dropdown = document.getElementById('roomsDropdown-' + propertyId);
    const btn = document.getElementById('roomsBtn-' + propertyId);

    if (dropdown.classList.contains('hidden')) {
      dropdown.classList.remove('hidden');
      btn.innerHTML = '<i class="fas fa-chevron-up mr-1"></i>Hide Rooms';
    } else {
      dropdown.classList.add('hidden');
      btn.innerHTML = '<i class="fas fa-door-open mr-1"></i>View Rooms';
    }
  }

  // Add Property Modal Functions
  function openAddPropertyModal() {
    const modal = document.getElementById('addPropertyModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeAddPropertyModal() {
    const modal = document.getElementById('addPropertyModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
    // Clear image preview
    const previewContainer = document.getElementById('imagePreviewContainer');
    if (previewContainer) {
      previewContainer.innerHTML = '';
    }
  }

  // Image Preview Function
  function handleImagePreview(input) {
    const previewContainer = document.getElementById('imagePreviewContainer');
    previewContainer.innerHTML = '';

    if (input.files && input.files.length > 0) {
      Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function(e) {
          const previewDiv = document.createElement('div');
          previewDiv.className = 'relative';
          previewDiv.innerHTML = `
              <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-32 object-cover rounded-lg">
              <button type="button" onclick="removeImage(${index})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors">
                <i class="fas fa-times text-xs"></i>
              </button>
              <input type="text" name="image_captions[]" placeholder="Caption (optional)" class="mt-2 w-full px-2 py-1 text-xs border border-gray-300 dark:border-white/20 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
            `;
          previewContainer.appendChild(previewDiv);
        };

        reader.readAsDataURL(file);
      });
    }
  }

  function removeImage(index) {
    const input = document.getElementById('propertyImagesInput');
    const previewContainer = document.getElementById('imagePreviewContainer');

    // Remove file from input
    const filesArray = Array.from(input.files);
    filesArray.splice(index, 1);

    // Create new FileList
    const dataTransfer = new DataTransfer();
    filesArray.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;

    // Refresh preview
    handleImagePreview(input);
  }

  // Show success message if property was added
  window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);

    // Show error message if present
    <?php if (isset($error_message)): ?>
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: <?php echo json_encode($error_message); ?>,
        confirmButtonColor: '#3085d6'
      });
    <?php endif; ?>

    if (urlParams.get('success') === 'property_added') {
      // Remove the parameter from URL
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      // Show success alert
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Property added successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }

    if (urlParams.get('success') === 'property_deleted') {
      // Remove the parameter from URL
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      // Show success alert
      Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        text: 'Property deleted successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }

    if (urlParams.get('success') === 'property_updated') {
      // Remove the parameter from URL
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      // Show success alert
      Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: 'Property updated successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }
  });

  // Delete Property Function
  function confirmDeleteProperty(propertyId, propertyName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete "${propertyName}"? This action will also delete all associated images and cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'swal-confirm-button',
        cancelButton: 'swal-cancel-button'
      },
      buttonsStyling: true
    }).then((result) => {
      if (result.isConfirmed) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_property';

        const propertyIdInput = document.createElement('input');
        propertyIdInput.type = 'hidden';
        propertyIdInput.name = 'property_id';
        propertyIdInput.value = propertyId;

        form.appendChild(actionInput);
        form.appendChild(propertyIdInput);
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  // Edit Property Modal Functions
  function openEditPropertyModal(propertyId) {
    const property = propertiesData.find(p => p.property_id === propertyId);
    if (!property) return;

    // Populate form fields with existing data
    document.getElementById('edit_property_id').value = property.property_id;
    document.getElementById('edit_property_code').value = property.property_code;
    document.getElementById('edit_property_name').value = property.property_name;
    document.getElementById('edit_property_type').value = property.property_type;
    document.getElementById('edit_tagline').value = property.tagline || '';
    document.getElementById('edit_description').value = property.description || '';
    document.getElementById('edit_address_line1').value = property.address_line1;
    document.getElementById('edit_address_line2').value = property.address_line2 || '';
    document.getElementById('edit_city').value = property.city;
    document.getElementById('edit_province').value = property.province || '';
    document.getElementById('edit_postal_code').value = property.postal_code || '';
    document.getElementById('edit_country').value = property.country;
    document.getElementById('edit_latitude').value = property.latitude || '';
    document.getElementById('edit_longitude').value = property.longitude || '';
    document.getElementById('edit_phone_primary').value = property.phone_primary || '';
    document.getElementById('edit_phone_secondary').value = property.phone_secondary || '';
    document.getElementById('edit_email').value = property.email || '';
    document.getElementById('edit_website_url').value = property.website_url || '';
    document.getElementById('edit_facebook_url').value = property.facebook_url || '';
    document.getElementById('edit_check_in_time').value = property.check_in_time || '14:00';
    document.getElementById('edit_check_out_time').value = property.check_out_time || '12:00';
    document.getElementById('edit_earliest_check_in').value = property.earliest_check_in || '08:00';
    document.getElementById('edit_latest_check_out').value = property.latest_check_out || '22:00';
    document.getElementById('edit_late_check_out_fee').value = property.late_check_out_fee || 0.00;
    document.getElementById('edit_timezone').value = property.timezone || 'Asia/Manila';
    document.getElementById('edit_currency_code').value = property.currency_code || 'PHP';
    document.getElementById('edit_tax_rate_percent').value = property.tax_rate_percent || 0.00;
    document.getElementById('edit_sort_order').value = property.sort_order || 0;
    document.getElementById('edit_is_active').value = property.is_active ? '1' : '0';

    // Display existing images
    const images = propertyImagesData[propertyId] || [];
    const existingImagesContainer = document.getElementById('editExistingImagesContainer');
    existingImagesContainer.innerHTML = '';

    if (images.length > 0) {
      images.forEach(img => {
        const imgDiv = document.createElement('div');
        imgDiv.className = 'relative group';
        imgDiv.innerHTML = `
            <img src="../../${img.image_url}" alt="${img.caption || 'Property image'}" class="w-full h-32 object-cover rounded-lg">
            <div class="absolute top-2 right-2 flex gap-1">
              <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">${img.image_type}</span>
              <button type="button" onclick="markImageForDeletion('${img.image_id}', this)" class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors" title="Delete image">
                <i class="fas fa-trash text-xs"></i>
              </button>
            </div>
            <input type="hidden" name="delete_images[]" value="" data-image-id="${img.image_id}" class="delete-image-input">
            ${img.caption ? `<p class="text-xs text-slate-600 dark:text-white/80 mt-1 truncate">${img.caption}</p>` : ''}
          `;
        existingImagesContainer.appendChild(imgDiv);
      });
    } else {
      existingImagesContainer.innerHTML = '<p class="text-sm text-slate-500 dark:text-white/60 col-span-3">No images uploaded for this property.</p>';
    }

    // Open modal
    const modal = document.getElementById('editPropertyModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeEditPropertyModal() {
    const modal = document.getElementById('editPropertyModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';

    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }

    // Clear image preview
    const previewContainer = document.getElementById('editImagePreviewContainer');
    if (previewContainer) {
      previewContainer.innerHTML = '';
    }
  }

  function markImageForDeletion(imageId, button) {
    const input = document.querySelector(`.delete-image-input[data-image-id="${imageId}"]`);
    if (input) {
      if (input.value === imageId) {
        // Unmark for deletion
        input.value = '';
        button.classList.remove('bg-red-600');
        button.classList.add('bg-red-500');
        button.parentElement.parentElement.querySelector('img').style.opacity = '1';
      } else {
        // Mark for deletion
        input.value = imageId;
        button.classList.remove('bg-red-500');
        button.classList.add('bg-red-600');
        button.parentElement.parentElement.querySelector('img').style.opacity = '0.5';
      }
    }
  }

  function handleEditImagePreview(input) {
    const previewContainer = document.getElementById('editImagePreviewContainer');
    previewContainer.innerHTML = '';

    if (input.files && input.files.length > 0) {
      Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function(e) {
          const previewDiv = document.createElement('div');
          previewDiv.className = 'relative';
          previewDiv.innerHTML = `
              <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-32 object-cover rounded-lg">
              <button type="button" onclick="removeEditImage(${index})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors">
                <i class="fas fa-times text-xs"></i>
              </button>
              <input type="text" name="image_captions[]" placeholder="Caption (optional)" class="mt-2 w-full px-2 py-1 text-xs border border-gray-300 dark:border-white/20 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
            `;
          previewContainer.appendChild(previewDiv);
        };

        reader.readAsDataURL(file);
      });
    }
  }

  function removeEditImage(index) {
    const input = document.getElementById('editPropertyImagesInput');
    const previewContainer = document.getElementById('editImagePreviewContainer');

    // Remove file from input
    const filesArray = Array.from(input.files);
    filesArray.splice(index, 1);

    // Create new FileList
    const dataTransfer = new DataTransfer();
    filesArray.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;

    // Refresh preview
    handleEditImagePreview(input);
  }

  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('propertiesTable');
    const rows = table.getElementsByClassName('property-row');

    for (let i = 0; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName('td');
      let found = false;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
          found = true;
          break;
        }
      }

      row.style.display = found ? '' : 'none';
    }
  }
</script>

</html>