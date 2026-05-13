<?php
require_once '../../config/db.php';

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

// Handle Add Room Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_room') {
  try {
    $pdo->beginTransaction();

    $property_id = $_POST['property_id'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $room_number = $_POST['room_number'] ?? '';

    // Validate required fields
    if (empty($property_id) || empty($category_id) || empty($room_number)) {
      throw new Exception("Property, Category, and Room Number are required fields.");
    }

    // Check if room number already exists for this property
    $stmt = $pdo->prepare("SELECT room_id FROM rooms WHERE property_id = ? AND room_number = ? AND is_active = 1");
    $stmt->execute([$property_id, $room_number]);
    if ($stmt->fetch()) {
      throw new Exception("Room number '$room_number' already exists for this property.");
    }

    // Generate room ID
    $room_id = generateUUID();
    $room_name = $_POST['room_name'] ?? '';
    $floor_number = $_POST['floor_number'] ?? '';
    $building_block = $_POST['building_block'] ?? '';
    $room_status = $_POST['room_status'] ?? 'available';
    $override_price_per_night = !empty($_POST['override_price_per_night']) ? $_POST['override_price_per_night'] : null;
    $override_price_per_hour = !empty($_POST['override_price_per_hour']) ? $_POST['override_price_per_hour'] : null;
    $override_price_day_use = !empty($_POST['override_price_day_use']) ? $_POST['override_price_day_use'] : null;
    $internal_notes = $_POST['internal_notes'] ?? '';

    // Insert room
    $stmt = $pdo->prepare("
            INSERT INTO rooms (
                room_id, property_id, category_id, room_number, room_name,
                floor_number, building_block, room_status,
                override_price_per_night, override_price_per_hour, override_price_day_use,
                internal_notes, is_active, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())
        ");
    $stmt->execute([
      $room_id,
      $property_id,
      $category_id,
      $room_number,
      $room_name,
      $floor_number,
      $building_block,
      $room_status,
      $override_price_per_night,
      $override_price_per_hour,
      $override_price_day_use,
      $internal_notes
    ]);

    // Handle image uploads
    if (isset($_FILES['room_images']) && !empty($_FILES['room_images']['name'][0])) {
      $upload_dir = __DIR__ . '/../../uploads/rooms/';
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      foreach ($_FILES['room_images']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['room_images']['error'][$key] === UPLOAD_ERR_OK) {
          $file_name = $_FILES['room_images']['name'][$key];
          $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
          $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

          if (in_array($file_ext, $allowed_exts)) {
            $new_filename = $room_id . '_' . uniqid() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($tmp_name, $upload_path)) {
              $image_url = 'uploads/rooms/' . $new_filename;
              $image_type = $_POST['image_type'][$key] ?? 'interior';
              $is_cover = isset($_POST['is_cover']) && $_POST['is_cover'] == $key ? 1 : 0;
              $caption = $_POST['image_caption'][$key] ?? '';

              // Insert room image
              $image_id = generateUUID();
              $stmt = $pdo->prepare("
                                INSERT INTO room_images (
                                    image_id, room_id, image_url, image_type, is_cover,
                                    caption, sort_order, is_active, created_at
                                ) VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())
                            ");
              $stmt->execute([
                $image_id,
                $room_id,
                $image_url,
                $image_type,
                $is_cover,
                $caption,
                $key
              ]);
            }
          }
        }
      }
    }

    $pdo->commit();
    header('Location: rooms.php?success=room_added');
    exit;
  } catch (Exception $e) {
    $pdo->rollBack();
    $error_message = $e->getMessage();
  }
}

// Handle Delete Room Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_room') {
  try {
    $room_id = $_POST['room_id'] ?? '';

    if (empty($room_id)) {
      throw new Exception("Room ID is required");
    }

    $pdo->beginTransaction();

    // Fetch room images to delete files
    $stmt = $pdo->prepare("SELECT image_url FROM room_images WHERE room_id = ?");
    $stmt->execute([$room_id]);
    $images = $stmt->fetchAll();

    // Delete image files from filesystem
    foreach ($images as $img) {
      $image_path = __DIR__ . '/../../' . $img['image_url'];
      if (file_exists($image_path)) {
        unlink($image_path);
      }
    }

    // Delete room images from database
    $stmt = $pdo->prepare("DELETE FROM room_images WHERE room_id = ?");
    $stmt->execute([$room_id]);

    // Delete room from database (soft delete - set is_active = 0)
    $stmt = $pdo->prepare("UPDATE rooms SET is_active = 0 WHERE room_id = ?");
    $stmt->execute([$room_id]);

    $pdo->commit();

    header('Location: rooms.php?success=room_deleted');
    exit;
  } catch (Exception $e) {
    $pdo->rollBack();
    $error_message = $e->getMessage();
  }
}

// Handle Edit Room Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_room') {
  try {
    $pdo->beginTransaction();

    $room_id = $_POST['room_id'] ?? '';
    $property_id = $_POST['property_id'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $room_number = $_POST['room_number'] ?? '';

    // Validate required fields
    if (empty($room_id) || empty($property_id) || empty($category_id) || empty($room_number)) {
      throw new Exception("Room ID, Property, Category, and Room Number are required fields.");
    }

    // Check if room number already exists for this property (excluding current room)
    $stmt = $pdo->prepare("SELECT room_id FROM rooms WHERE property_id = ? AND room_number = ? AND room_id != ? AND is_active = 1");
    $stmt->execute([$property_id, $room_number, $room_id]);
    if ($stmt->fetch()) {
      throw new Exception("Room number '$room_number' already exists for this property.");
    }

    $room_name = $_POST['room_name'] ?? '';
    $floor_number = $_POST['floor_number'] ?? '';
    $building_block = $_POST['building_block'] ?? '';
    $room_status = $_POST['room_status'] ?? 'available';
    $override_price_per_night = !empty($_POST['override_price_per_night']) ? $_POST['override_price_per_night'] : null;
    $override_price_per_hour = !empty($_POST['override_price_per_hour']) ? $_POST['override_price_per_hour'] : null;
    $override_price_day_use = !empty($_POST['override_price_day_use']) ? $_POST['override_price_day_use'] : null;
    $internal_notes = $_POST['internal_notes'] ?? '';

    // Update room
    $stmt = $pdo->prepare("
            UPDATE rooms SET
                property_id = ?,
                category_id = ?,
                room_number = ?,
                room_name = ?,
                floor_number = ?,
                building_block = ?,
                room_status = ?,
                override_price_per_night = ?,
                override_price_per_hour = ?,
                override_price_day_use = ?,
                internal_notes = ?,
                updated_at = NOW()
            WHERE room_id = ?
        ");
    $stmt->execute([
      $property_id,
      $category_id,
      $room_number,
      $room_name,
      $floor_number,
      $building_block,
      $room_status,
      $override_price_per_night,
      $override_price_per_hour,
      $override_price_day_use,
      $internal_notes,
      $room_id
    ]);

    // Handle image uploads (new images)
    if (isset($_FILES['room_images']) && !empty($_FILES['room_images']['name'][0])) {
      $upload_dir = __DIR__ . '/../../uploads/rooms/';
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      foreach ($_FILES['room_images']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['room_images']['error'][$key] === UPLOAD_ERR_OK) {
          $file_name = $_FILES['room_images']['name'][$key];
          $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
          $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

          if (in_array($file_ext, $allowed_exts)) {
            $new_filename = $room_id . '_' . uniqid() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($tmp_name, $upload_path)) {
              $image_url = 'uploads/rooms/' . $new_filename;
              $image_type = $_POST['image_type'][$key] ?? 'interior';
              $caption = $_POST['image_caption'][$key] ?? '';

              // Insert room image
              $image_id = generateUUID();
              $stmt = $pdo->prepare("
                                INSERT INTO room_images (
                                    image_id, room_id, image_url, image_type, caption,
                                    sort_order, is_active, created_at
                                ) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())
                            ");
              $stmt->execute([
                $image_id,
                $room_id,
                $image_url,
                $image_type,
                $caption,
                $key
              ]);
            }
          }
        }
      }
    }

    // Handle image deletions
    if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
      foreach ($_POST['delete_images'] as $image_id) {
        // Get image URL before deletion
        $stmt = $pdo->prepare("SELECT image_url FROM room_images WHERE image_id = ?");
        $stmt->execute([$image_id]);
        $img = $stmt->fetch();

        if ($img) {
          // Delete file from filesystem
          $image_path = __DIR__ . '/../../' . $img['image_url'];
          if (file_exists($image_path)) {
            unlink($image_path);
          }

          // Delete from database
          $stmt = $pdo->prepare("DELETE FROM room_images WHERE image_id = ?");
          $stmt->execute([$image_id]);
        }
      }
    }

    $pdo->commit();

    header('Location: rooms.php?success=room_updated');
    exit;
  } catch (Exception $e) {
    $pdo->rollBack();
    $error_message = $e->getMessage();
  }
}

// Fetch properties for dropdown
$stmt = $pdo->query("SELECT property_id, property_name, property_code FROM properties WHERE is_active = 1 ORDER BY property_name ASC");
$properties = $stmt->fetchAll();

// Fetch room categories for dropdown
$stmt = $pdo->query("SELECT category_id, category_name, category_code, property_id FROM room_categories WHERE is_active = 1 ORDER BY category_name ASC");
$categories = $stmt->fetchAll();

// Fetch all rooms with related data
$stmt = $pdo->query("
    SELECT 
        r.*,
        p.property_name,
        p.property_code,
        rc.category_name,
        rc.category_code,
        rc.base_price_per_night,
        rc.base_price_per_hour,
        rc.base_price_day_use
    FROM rooms r
    INNER JOIN properties p ON r.property_id = p.property_id
    INNER JOIN room_categories rc ON r.category_id = rc.category_id
    WHERE r.is_active = 1
    ORDER BY p.property_name ASC, r.room_number ASC
");
$rooms = $stmt->fetchAll();

// Fetch room images for each room
$roomImages = [];
foreach ($rooms as $room) {
  $stmt = $pdo->prepare("
        SELECT image_id, image_url, thumbnail_url, caption, image_type, sort_order
        FROM room_images
        WHERE room_id = ? AND is_active = 1
        ORDER BY sort_order ASC
    ");
  $stmt->execute([$room['room_id']]);
  $roomImages[$room['room_id']] = $stmt->fetchAll();
}
?>

<script>
  // Store rooms data for JavaScript access
  const roomsData = <?php echo json_encode($rooms); ?>;
  const roomImagesData = <?php echo json_encode($roomImages); ?>;
</script>


<?php $assets_path = '../assets';
$page_title = 'Rooms'; ?>
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
      <!-- table 1 -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Rooms</h6>
              <button onclick="openAddRoomModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Room
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="roomsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($rooms)): ?>
                      <tr>
                        <td colspan="5" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No rooms found. Add a room to get started.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($rooms as $index => $room): ?>
                        <tr class="room-row">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
                                <i class="fas fa-door-open"></i>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo htmlspecialchars($room['room_name'] ?: $room['room_number']); ?></h6>
                                <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400"><?php echo htmlspecialchars($room['room_number']); ?></p>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($room['property_name']); ?></p>
                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars($room['property_code']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($room['category_name']); ?></p>
                            <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars($room['category_code']); ?></p>
                          </td>
                          <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
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
                            <span class="px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none" style="background-color: <?php echo $statusColor; ?>; color: white !important;"><?php echo $statusLabel; ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="viewRoomDetails('<?php echo $room['room_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditRoomModal('<?php echo $room['room_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Room">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="deleteRoom('<?php echo $room['room_id']; ?>', '<?php echo htmlspecialchars($room['room_name'] ?: $room['room_number']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Room">
                                <i class="fas fa-trash-alt mr-1.5 text-xs"></i>
                                <span>Delete</span>
                              </button>
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

      <!-- Room Details Modal -->
      <div id="roomDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeRoomDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white" id="modalRoomName" style="font-size: 1.25rem; font-weight: bold; margin: 0;">Room Details</h3>
            <button onclick="closeRoomDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="modalRoomContent" style="padding: 1.5rem;">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Room Modal -->
      <div id="addRoomModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddRoomModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Add New Room</h3>
            <button onclick="closeAddRoomModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addRoomForm" method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="add_room">

            <?php if (isset($error_message)): ?>
              <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <?php echo htmlspecialchars($error_message); ?>
              </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Property Selection -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Property *</label>
                <select name="property_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="">Select Property</option>
                  <?php foreach ($properties as $property): ?>
                    <option value="<?php echo htmlspecialchars($property['property_id']); ?>">
                      <?php echo htmlspecialchars($property['property_name'] . ' (' . $property['property_code'] . ')'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Category Selection -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Category *</label>
                <select name="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="">Select Category</option>
                  <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['category_id']); ?>" data-property-id="<?php echo htmlspecialchars($category['property_id']); ?>">
                      <?php echo htmlspecialchars($category['category_name'] . ' (' . $category['category_code'] . ')'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Room Number -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Number *</label>
                <input type="text" name="room_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 101, A-02, Cottage-3">
              </div>

              <!-- Room Name -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Name</label>
                <input type="text" name="room_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Sunset Villa, Lagoon Cottage">
              </div>

              <!-- Floor Number -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Floor Number</label>
                <input type="number" name="floor_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 1, 2, 3">
              </div>

              <!-- Building Block -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Building Block</label>
                <input type="text" name="building_block" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Building A, East Wing">
              </div>

              <!-- Room Status -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Status *</label>
                <select name="room_status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="available">Available</option>
                  <option value="occupied">Occupied</option>
                  <option value="reserved">Reserved</option>
                  <option value="cleaning">Cleaning</option>
                  <option value="maintenance">Maintenance</option>
                  <option value="out_of_order">Out of Order</option>
                  <option value="blocked">Blocked</option>
                </select>
              </div>

              <!-- Price Override Per Night -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Per Night (PHP)</label>
                <input type="number" step="0.01" name="override_price_per_night" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
              </div>

              <!-- Price Override Per Hour -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Per Hour (PHP)</label>
                <input type="number" step="0.01" name="override_price_per_hour" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
              </div>

              <!-- Price Override Day Use -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Day Use (PHP)</label>
                <input type="number" step="0.01" name="override_price_day_use" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
              </div>

              <!-- Internal Notes -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Internal Notes</label>
                <textarea name="internal_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any additional notes about this room..."></textarea>
              </div>

              <!-- Image Upload Section -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Images</label>
                <div id="imageUploadContainer">
                  <div class="image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                      <div>
                        <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
                        <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
                      </div>
                      <div>
                        <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
                        <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                          <option value="interior">Interior</option>
                          <option value="exterior">Exterior</option>
                          <option value="bathroom">Bathroom</option>
                          <option value="view">View</option>
                          <option value="amenity">Amenity</option>
                          <option value="bed">Bed</option>
                          <option value="balcony">Balcony</option>
                          <option value="other">Other</option>
                        </select>
                      </div>
                      <div>
                        <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
                        <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
                      </div>
                    </div>
                  </div>
                </div>
                <button type="button" onclick="addImageField()" class="mt-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                  <i class="fas fa-plus mr-1"></i> Add Another Image
                </button>
              </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-white/10">
              <button type="button" onclick="closeAddRoomModal()" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">
                Cancel
              </button>
              <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:shadow-md transition-all">
                <i class="fas fa-save mr-1"></i> Save Room
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Room Modal -->
      <div id="editRoomModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditRoomModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Room</h3>
            <button onclick="closeEditRoomModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editRoomForm" method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="edit_room">
            <input type="hidden" name="room_id" id="edit_room_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Property Selection -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Property *</label>
                <select name="property_id" id="edit_property_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="">Select Property</option>
                  <?php foreach ($properties as $property): ?>
                    <option value="<?php echo htmlspecialchars($property['property_id']); ?>">
                      <?php echo htmlspecialchars($property['property_name'] . ' (' . $property['property_code'] . ')'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Category Selection -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Category *</label>
                <select name="category_id" id="edit_category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="">Select Category</option>
                  <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['category_id']); ?>" data-property-id="<?php echo htmlspecialchars($category['property_id']); ?>">
                      <?php echo htmlspecialchars($category['category_name'] . ' (' . $category['category_code'] . ')'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Room Number -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Number *</label>
                <input type="text" name="room_number" id="edit_room_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 101, A-02, Cottage-3">
              </div>

              <!-- Room Name -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Name</label>
                <input type="text" name="room_name" id="edit_room_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Sunset Villa, Lagoon Cottage">
              </div>

              <!-- Floor Number -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Floor Number</label>
                <input type="number" name="floor_number" id="edit_floor_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 1, 2, 3">
              </div>

              <!-- Building Block -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Building Block</label>
                <input type="text" name="building_block" id="edit_building_block" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Building A, East Wing">
              </div>

              <!-- Room Status -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Room Status *</label>
                <select name="room_status" id="edit_room_status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="available">Available</option>
                  <option value="occupied">Occupied</option>
                  <option value="reserved">Reserved</option>
                  <option value="cleaning">Cleaning</option>
                  <option value="maintenance">Maintenance</option>
                  <option value="out_of_order">Out of Order</option>
                  <option value="blocked">Blocked</option>
                </select>
              </div>

              <!-- Price Override Per Night -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Per Night (PHP)</label>
                <input type="number" step="0.01" name="override_price_per_night" id="edit_override_price_per_night" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
              </div>

              <!-- Price Override Per Hour -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Per Hour (PHP)</label>
                <input type="number" step="0.01" name="override_price_per_hour" id="edit_override_price_per_hour" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
              </div>

              <!-- Price Override Day Use -->
              <div>
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Override Price Day Use (PHP)</label>
                <input type="number" step="0.01" name="override_price_day_use" id="edit_override_price_day_use" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to use category price">
              </div>

              <!-- Internal Notes -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Internal Notes</label>
                <textarea name="internal_notes" id="edit_internal_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any additional notes about this room..."></textarea>
              </div>

              <!-- Existing Images Section -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Existing Images</label>
                <div id="existingImagesContainer" class="mb-4">
                  <!-- Existing images will be loaded here -->
                </div>
              </div>

              <!-- Image Upload Section -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-500 dark:text-white/60 mb-2">Add New Images</label>
                <div id="editImageUploadContainer">
                  <div class="image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                      <div>
                        <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
                        <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
                      </div>
                      <div>
                        <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
                        <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                          <option value="interior">Interior</option>
                          <option value="exterior">Exterior</option>
                          <option value="bathroom">Bathroom</option>
                          <option value="view">View</option>
                          <option value="amenity">Amenity</option>
                          <option value="bed">Bed</option>
                          <option value="balcony">Balcony</option>
                          <option value="other">Other</option>
                        </select>
                      </div>
                      <div>
                        <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
                        <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
                      </div>
                    </div>
                  </div>
                </div>
                <button type="button" onclick="addEditImageField()" class="mt-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                  <i class="fas fa-plus mr-1"></i> Add Another Image
                </button>
              </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-white/10">
              <button type="button" onclick="closeEditRoomModal()" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">
                Cancel
              </button>
              <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:shadow-md transition-all">
                <i class="fas fa-save mr-1"></i> Update Room
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
  function viewRoomDetails(roomId) {
    const room = roomsData.find(r => r.room_id === roomId);
    if (!room) return;

    const modal = document.getElementById('roomDetailsModal');
    const modalName = document.getElementById('modalRoomName');
    const modalContent = document.getElementById('modalRoomContent');

    modalName.textContent = room.room_name || room.room_number;

    const statusColors = {
      'available': '#10b981',
      'occupied': '#ef4444',
      'reserved': '#3b82f6',
      'cleaning': '#eab308',
      'maintenance': '#6b7280',
      'out_of_order': '#dc2626',
      'blocked': '#a855f7'
    };
    const statusColor = statusColors[room.room_status] || '#6b7280';
    const statusLabel = room.room_status ? room.room_status.replace(/_/g, ' ').toUpperCase() : 'UNKNOWN';

    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Basic Information</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Room Number</p>
                <p class="text-sm font-semibold dark:text-white">${room.room_number}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Room Name</p>
                <p class="text-sm dark:text-white">${room.room_name || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Floor Number</p>
                <p class="text-sm dark:text-white">${room.floor_number || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Building Block</p>
                <p class="text-sm dark:text-white">${room.building_block || '-'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Status</p>
                <span class="px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none" style="background-color: ${statusColor}; color: white !important;">${statusLabel}</span>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Property & Category</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Property</p>
                <p class="text-sm font-semibold dark:text-white">${room.property_name}</p>
                <p class="text-xs text-slate-400 dark:text-white/60">${room.property_code}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Category</p>
                <p class="text-sm font-semibold dark:text-white">${room.category_name}</p>
                <p class="text-xs text-slate-400 dark:text-white/60">${room.category_code}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Pricing (Category Base)</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Per Night</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(room.base_price_per_night || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Per Hour</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(room.base_price_per_hour || 0).toFixed(2)}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Day Use</p>
                <p class="text-sm font-semibold dark:text-white">PHP ${parseFloat(room.base_price_day_use || 0).toFixed(2)}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Price Overrides</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Override Per Night</p>
                <p class="text-sm dark:text-white">${room.override_price_per_night ? 'PHP ' + parseFloat(room.override_price_per_night).toFixed(2) : 'Using category price'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Override Per Hour</p>
                <p class="text-sm dark:text-white">${room.override_price_per_hour ? 'PHP ' + parseFloat(room.override_price_per_hour).toFixed(2) : 'Using category price'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Override Day Use</p>
                <p class="text-sm dark:text-white">${room.override_price_day_use ? 'PHP ' + parseFloat(room.override_price_day_use).toFixed(2) : 'Using category price'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Housekeeping</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Last Cleaned At</p>
                <p class="text-sm dark:text-white">${room.last_cleaned_at ? new Date(room.last_cleaned_at).toLocaleString() : 'Never'}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Last Inspected At</p>
                <p class="text-sm dark:text-white">${room.last_inspected_at ? new Date(room.last_inspected_at).toLocaleString() : 'Never'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Notes</h4>
            <div class="space-y-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Internal Notes</p>
                <p class="text-sm dark:text-white">${room.internal_notes || '-'}</p>
              </div>
            </div>
          </div>

          <div class="md:col-span-2">
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">System Information</h4>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Created At</p>
                <p class="text-sm dark:text-white">${new Date(room.created_at).toLocaleString()}</p>
              </div>
              <div>
                <p class="text-xs text-slate-500 dark:text-white/60">Updated At</p>
                <p class="text-sm dark:text-white">${new Date(room.updated_at).toLocaleString()}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeRoomDetailsModal() {
    const modal = document.getElementById('roomDetailsModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
  }

  function deleteRoom(roomId, roomName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete the room "${roomName}"? This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href;

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_room';

        const roomIdInput = document.createElement('input');
        roomIdInput.type = 'hidden';
        roomIdInput.name = 'room_id';
        roomIdInput.value = roomId;

        form.appendChild(actionInput);
        form.appendChild(roomIdInput);
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  function openAddRoomModal() {
    const modal = document.getElementById('addRoomModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeAddRoomModal() {
    const modal = document.getElementById('addRoomModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('addRoomForm').reset();
  }

  function addImageField() {
    const container = document.getElementById('imageUploadContainer');
    const imageEntry = document.createElement('div');
    imageEntry.className = 'image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20 relative';
    imageEntry.innerHTML = `
        <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
          <i class="fas fa-times"></i>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
            <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
          </div>
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
            <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="interior">Interior</option>
              <option value="exterior">Exterior</option>
              <option value="bathroom">Bathroom</option>
              <option value="view">View</option>
              <option value="amenity">Amenity</option>
              <option value="bed">Bed</option>
              <option value="balcony">Balcony</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
            <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
          </div>
        </div>
      `;
    container.appendChild(imageEntry);
  }

  function openEditRoomModal(roomId) {
    const room = roomsData.find(r => r.room_id === roomId);
    if (!room) return;

    // Populate form fields with existing data
    document.getElementById('edit_room_id').value = room.room_id;
    document.getElementById('edit_property_id').value = room.property_id;
    document.getElementById('edit_category_id').value = room.category_id;
    document.getElementById('edit_room_number').value = room.room_number;
    document.getElementById('edit_room_name').value = room.room_name || '';
    document.getElementById('edit_floor_number').value = room.floor_number || '';
    document.getElementById('edit_building_block').value = room.building_block || '';
    document.getElementById('edit_room_status').value = room.room_status;
    document.getElementById('edit_override_price_per_night').value = room.override_price_per_night || '';
    document.getElementById('edit_override_price_per_hour').value = room.override_price_per_hour || '';
    document.getElementById('edit_override_price_day_use').value = room.override_price_day_use || '';
    document.getElementById('edit_internal_notes').value = room.internal_notes || '';

    // Load existing images
    loadExistingImages(roomId);

    const modal = document.getElementById('editRoomModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
  }

  function closeEditRoomModal() {
    const modal = document.getElementById('editRoomModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('editRoomForm').reset();
    document.getElementById('existingImagesContainer').innerHTML = '';
    document.getElementById('editImageUploadContainer').innerHTML = `
        <div class="image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
              <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
            </div>
            <div>
              <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
              <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="interior">Interior</option>
                <option value="exterior">Exterior</option>
                <option value="bathroom">Bathroom</option>
                <option value="view">View</option>
                <option value="amenity">Amenity</option>
                <option value="bed">Bed</option>
                <option value="balcony">Balcony</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
              <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
            </div>
          </div>
        </div>
      `;
  }

  function addEditImageField() {
    const container = document.getElementById('editImageUploadContainer');
    const imageEntry = document.createElement('div');
    imageEntry.className = 'image-entry mb-4 p-4 border border-gray-200 rounded-lg dark:border-white/20 relative';
    imageEntry.innerHTML = `
        <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
          <i class="fas fa-times"></i>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image File</label>
            <input type="file" name="room_images[]" accept="image/*" class="w-full text-sm text-slate-500 dark:text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white">
          </div>
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Image Type</label>
            <select name="image_type[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="interior">Interior</option>
              <option value="exterior">Exterior</option>
              <option value="bathroom">Bathroom</option>
              <option value="view">View</option>
              <option value="amenity">Amenity</option>
              <option value="bed">Bed</option>
              <option value="balcony">Balcony</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div>
            <label class="block text-xs text-slate-500 dark:text-white/60 mb-1">Caption</label>
            <input type="text" name="image_caption[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:bg-slate-800 dark:border-white/20 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Image caption">
          </div>
        </div>
      `;
    container.appendChild(imageEntry);
  }

  function loadExistingImages(roomId) {
    const container = document.getElementById('existingImagesContainer');
    const images = roomImagesData[roomId] || [];

    if (images.length === 0) {
      container.innerHTML = '<p class="text-sm text-slate-500">No images uploaded yet.</p>';
      return;
    }

    container.innerHTML = images.map(img => `
        <div class="relative inline-block mr-2 mb-2">
          <img src="../${img.image_url}" alt="${img.caption || ''}" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
          <button type="button" onclick="markImageForDeletion('${img.image_id}', this)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
            <i class="fas fa-times"></i>
          </button>
          <input type="hidden" name="delete_images[]" id="delete_img_${img.image_id}" value="">
          <p class="text-xs text-slate-500 mt-1 w-24 truncate">${img.caption || img.image_type}</p>
        </div>
      `).join('');
  }

  function markImageForDeletion(imageId, button) {
    const input = document.getElementById(`delete_img_${imageId}`);
    if (input.value === imageId) {
      input.value = '';
      button.classList.remove('bg-red-600');
      button.classList.add('bg-red-500');
      button.parentElement.classList.remove('opacity-50');
    } else {
      input.value = imageId;
      button.classList.remove('bg-red-500');
      button.classList.add('bg-red-600');
      button.parentElement.classList.add('opacity-50');
    }
  }

  // Filter categories based on selected property
  document.addEventListener('DOMContentLoaded', function() {
    const propertySelect = document.querySelector('select[name="property_id"]');
    const categorySelect = document.querySelector('select[name="category_id"]');

    if (propertySelect && categorySelect) {
      propertySelect.addEventListener('change', function() {
        const selectedPropertyId = this.value;
        const categoryOptions = categorySelect.querySelectorAll('option[data-property-id]');

        categoryOptions.forEach(option => {
          if (option.dataset.propertyId === selectedPropertyId || selectedPropertyId === '') {
            option.style.display = '';
          } else {
            option.style.display = 'none';
          }
        });

        // Reset category selection if current selection is not valid
        if (selectedPropertyId !== '' && categorySelect.value !== '') {
          const selectedOption = categorySelect.querySelector('option[value="' + categorySelect.value + '"]');
          if (selectedOption && selectedOption.dataset.propertyId !== selectedPropertyId) {
            categorySelect.value = '';
          }
        }
      });
    }
  });

  // Show success/error messages
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

    if (urlParams.get('success') === 'room_added') {
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Room added successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }

    if (urlParams.get('success') === 'room_deleted') {
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        text: 'Room deleted successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }

    if (urlParams.get('success') === 'room_updated') {
      const newUrl = window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);

      Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: 'Room updated successfully!',
        timer: 3000,
        showConfirmButton: false
      });
    }
  });

  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('roomsTable');
    const rows = table.getElementsByClassName('room-row');

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