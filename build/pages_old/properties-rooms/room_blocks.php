<?php
require_once '../../config/db.php';

// Handle Delete Block Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_block') {
  try {
    error_log("Starting block deletion process...");

    $block_id = $_POST['block_id'] ?? '';
    error_log("Deleting block_id: " . $block_id);

    if (empty($block_id)) {
      throw new Exception("Block ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Delete block from database (hard delete)
    $stmt = $pdo->prepare("DELETE FROM room_availability_blocks WHERE block_id = ?");
    $stmt->execute([$block_id]);
    error_log("Deleted block from database");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: room_blocks.php?success=block_deleted');
    exit;
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("Error deleting block: " . $e->getMessage());
    $error_message = "Failed to delete block. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Edit Block Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_block') {
  try {
    error_log("Starting block edit process...");

    $block_id = $_POST['block_id'] ?? '';
    error_log("Editing block_id: " . $block_id);

    if (empty($block_id)) {
      throw new Exception("Block ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Update block data
    $stmt = $pdo->prepare("
            UPDATE room_availability_blocks SET
                room_id = ?,
                block_type = ?,
                date_start = ?,
                date_end = ?,
                time_start = ?,
                time_end = ?,
                reason = ?,
                is_active = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE block_id = ?
        ");

    $stmt->execute([
      $_POST['room_id'],
      $_POST['block_type'] ?? 'maintenance',
      $_POST['date_start'],
      $_POST['date_end'],
      $_POST['time_start'] ?? null,
      $_POST['time_end'] ?? null,
      $_POST['reason'] ?? null,
      $_POST['is_active'] ?? 1,
      $block_id
    ]);

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: room_blocks.php?success=block_updated');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error editing block: " . $e->getMessage());
    $error_message = "Failed to update block. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error editing block: " . $e->getMessage());
    $error_message = "Failed to update block. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Add Block Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_block') {
  try {
    error_log("Starting block addition process...");

    // Generate UUID for block_id
    $block_id = generateUUID();
    error_log("Generated block_id: " . $block_id);

    // Start transaction
    $pdo->beginTransaction();

    // Insert block data
    $stmt = $pdo->prepare("
            INSERT INTO room_availability_blocks (
                block_id, room_id, block_type, date_start, date_end, time_start, time_end, reason, is_active, created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

    $params = [
      $block_id,
      $_POST['room_id'],
      $_POST['block_type'] ?? 'maintenance',
      $_POST['date_start'],
      $_POST['date_end'],
      $_POST['time_start'] ?? null,
      $_POST['time_end'] ?? null,
      $_POST['reason'] ?? null,
      $_POST['is_active'] ?? 1,
      null // created_by - will be set when auth is implemented
    ];

    error_log("Executing block insert with params: " . json_encode($params));
    $stmt->execute($params);
    error_log("Block inserted successfully");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: room_blocks.php?success=block_added');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error adding block: " . $e->getMessage());
    error_log("Error code: " . $e->getCode());
    error_log("Error info: " . print_r($e->errorInfo, true));
    $error_message = "Failed to add block. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error adding block: " . $e->getMessage());
    $error_message = "Failed to add block. Please try again. Error: " . $e->getMessage();
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

// Fetch all room availability blocks with room details
$stmt = $pdo->query("
    SELECT 
        rab.*,
        r.room_number,
        r.room_name,
        rc.category_name,
        p.property_name
    FROM room_availability_blocks rab
    INNER JOIN rooms r ON rab.room_id = r.room_id
    INNER JOIN room_categories rc ON r.category_id = rc.category_id
    INNER JOIN properties p ON r.property_id = p.property_id
    WHERE rab.is_active = 1
    ORDER BY rab.date_start DESC, r.room_number ASC
");
$blocks = $stmt->fetchAll();

// Fetch all rooms for dropdown
$stmt = $pdo->query("
    SELECT 
        r.room_id,
        r.room_number,
        r.room_name,
        rc.category_name,
        p.property_name
    FROM rooms r
    INNER JOIN room_categories rc ON r.category_id = rc.category_id
    INNER JOIN properties p ON r.property_id = p.property_id
    WHERE r.is_active = 1
    ORDER BY p.property_name ASC, r.room_number ASC
");
$rooms = $stmt->fetchAll();
?>

<script>
  // Store blocks data for JavaScript access
  const blocksData = <?php echo json_encode($blocks); ?>;
</script>

<?php $assets_path = '../assets';
$page_title = 'Room Blocks'; ?>
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
      <!-- Stats Cards -->
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Blocks</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($blocks); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Maintenance</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $maintenanceBlocks = array_filter($blocks, function ($b) {
                return $b['block_type'] === 'maintenance';
              });
              ?>
              <h5 class="font-bold text-3xl text-orange-500"><?php echo count($maintenanceBlocks); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Owner Use</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $ownerBlocks = array_filter($blocks, function ($b) {
                return $b['block_type'] === 'owner_use';
              });
              ?>
              <h5 class="font-bold text-3xl text-blue-500"><?php echo count($ownerBlocks); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Active Now</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $today = date('Y-m-d');
              $activeBlocks = array_filter($blocks, function ($b) use ($today) {
                return $b['date_start'] <= $today && $b['date_end'] >= $today;
              });
              ?>
              <h5 class="font-bold text-3xl text-red-500"><?php echo count($activeBlocks); ?></h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Room Blocks Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Room Availability Blocks</h6>
              <button onclick="openAddBlockModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Block
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="blocksTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Block Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date Range</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reason</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($blocks)): ?>
                      <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No room blocks found. <a href="javascript:;" class="text-blue-500 font-semibold">Add your first block</a>.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($blocks as $index => $block): ?>
                        <tr class="block-row" data-block-id="<?php echo $block['block_id']; ?>" data-room-id="<?php echo $block['room_id']; ?>" data-room-number="<?php echo htmlspecialchars($block['room_number']); ?>" data-room-name="<?php echo htmlspecialchars($block['room_name'] ?? ''); ?>" data-property-name="<?php echo htmlspecialchars($block['property_name']); ?>" data-block-type="<?php echo $block['block_type']; ?>" data-date-start="<?php echo $block['date_start']; ?>" data-date-end="<?php echo $block['date_end']; ?>" data-time-start="<?php echo $block['time_start'] ?? ''; ?>" data-time-end="<?php echo $block['time_end'] ?? ''; ?>" data-reason="<?php echo htmlspecialchars($block['reason'] ?? ''); ?>" data-is-active="<?php echo $block['is_active']; ?>">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-blue-500 to-violet-500">
                                <i class="fas fa-door-closed"></i>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo htmlspecialchars($block['room_number']); ?></h6>
                                <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo htmlspecialchars($block['property_name']); ?></p>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo ucfirst(str_replace('_', ' ', $block['block_type'])); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo date('M d, Y', strtotime($block['date_start'])); ?> - <?php echo date('M d, Y', strtotime($block['date_end'])); ?></p>
                            <?php if ($block['time_start']): ?>
                              <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-60 text-slate-400"><?php echo date('h:i A', strtotime($block['time_start'])); ?> - <?php echo date('h:i A', strtotime($block['time_end'])); ?></p>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars(substr($block['reason'] ?? '', 0, 30)); ?>...</p>
                          </td>
                          <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <?php
                            $today = date('Y-m-d');
                            $isActive = $block['date_start'] <= $today && $block['date_end'] >= $today;
                            $isFuture = $block['date_start'] > $today;
                            $isPast = $block['date_end'] < $today;
                            ?>
                            <?php if ($isActive): ?>
                              <span class="bg-gradient-to-tl from-red-500 to-rose-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Active</span>
                            <?php elseif ($isFuture): ?>
                              <span class="bg-gradient-to-tl from-blue-500 to-cyan-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Upcoming</span>
                            <?php else: ?>
                              <span class="bg-gradient-to-tl from-slate-500 to-slate-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Past</span>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="openViewBlockModal('<?php echo $block['block_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditBlockModal('<?php echo $block['block_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Block">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="confirmDeleteBlock('<?php echo $block['block_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Block">
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

      <!-- Add Block Modal -->
      <div id="addBlockModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddBlockModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Add New Block</h3>
            <button onclick="closeAddBlockModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form method="POST" action="room_blocks.php">
              <input type="hidden" name="action" value="add_block">
              <div class="space-y-4">
                <div>
                  <label for="room_id" class="block text-sm font-medium text-gray-700">Room</label>
                  <select name="room_id" id="room_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Select a room</option>
                    <?php foreach ($rooms as $room): ?>
                      <option value="<?php echo $room['room_id']; ?>"><?php echo htmlspecialchars($room['property_name']); ?> - <?php echo htmlspecialchars($room['room_number']); ?> (<?php echo htmlspecialchars($room['room_name'] ?? $room['category_name']); ?>)</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label for="block_type" class="block text-sm font-medium text-gray-700">Block Type</label>
                  <select name="block_type" id="block_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="maintenance">Maintenance</option>
                    <option value="owner_use">Owner Use</option>
                    <option value="event_hold">Event Hold</option>
                    <option value="deep_cleaning">Deep Cleaning</option>
                    <option value="renovation">Renovation</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="date_start" class="block text-sm font-medium text-gray-700">Start Date</label>
                  <input type="date" name="date_start" id="date_start" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="date_end" class="block text-sm font-medium text-gray-700">End Date</label>
                  <input type="date" name="date_end" id="date_end" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="time_start" class="block text-sm font-medium text-gray-700">Start Time (Optional)</label>
                  <input type="time" name="time_start" id="time_start" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="time_end" class="block text-sm font-medium text-gray-700">End Time (Optional)</label>
                  <input type="time" name="time_end" id="time_end" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                  <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Reason for blocking..."></textarea>
                </div>
                <div>
                  <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                  </label>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeAddBlockModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Add Block
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Block Modal -->
      <div id="editBlockModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditBlockModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Edit Block</h3>
            <button onclick="closeEditBlockModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form method="POST" action="room_blocks.php">
              <input type="hidden" name="action" value="edit_block">
              <input type="hidden" name="block_id" id="edit_block_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_room_id" class="block text-sm font-medium text-gray-700">Room</label>
                  <select name="room_id" id="edit_room_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Select a room</option>
                    <?php foreach ($rooms as $room): ?>
                      <option value="<?php echo $room['room_id']; ?>"><?php echo htmlspecialchars($room['property_name']); ?> - <?php echo htmlspecialchars($room['room_number']); ?> (<?php echo htmlspecialchars($room['room_name'] ?? $room['category_name']); ?>)</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label for="edit_block_type" class="block text-sm font-medium text-gray-700">Block Type</label>
                  <select name="block_type" id="edit_block_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="maintenance">Maintenance</option>
                    <option value="owner_use">Owner Use</option>
                    <option value="event_hold">Event Hold</option>
                    <option value="deep_cleaning">Deep Cleaning</option>
                    <option value="renovation">Renovation</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="edit_date_start" class="block text-sm font-medium text-gray-700">Start Date</label>
                  <input type="date" name="date_start" id="edit_date_start" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_date_end" class="block text-sm font-medium text-gray-700">End Date</label>
                  <input type="date" name="date_end" id="edit_date_end" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_time_start" class="block text-sm font-medium text-gray-700">Start Time (Optional)</label>
                  <input type="time" name="time_start" id="edit_time_start" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_time_end" class="block text-sm font-medium text-gray-700">End Time (Optional)</label>
                  <input type="time" name="time_end" id="edit_time_end" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_reason" class="block text-sm font-medium text-gray-700">Reason</label>
                  <textarea name="reason" id="edit_reason" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Reason for blocking..."></textarea>
                </div>
                <div>
                  <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                  </label>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeEditBlockModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Update Block
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- View Block Modal -->
      <div id="viewBlockModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewBlockModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 500px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Block Details</h3>
            <button onclick="closeViewBlockModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Room</label>
                <p id="view_room" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Property</label>
                <p id="view_property" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Block Type</label>
                <p id="view_block_type" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Date Range</label>
                <p id="view_date_range" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Time Range</label>
                <p id="view_time_range" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Reason</label>
                <p id="view_reason" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Status</label>
                <p id="view_status" class="mt-1 text-sm text-gray-900"></p>
              </div>
            </div>
          </div>
          <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end flex-shrink-0" style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
            <button type="button" onclick="closeViewBlockModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Form -->
    <form id="deleteBlockForm" method="POST" action="room_blocks.php" style="display: none;">
      <input type="hidden" name="action" value="delete_block">
      <input type="hidden" name="block_id" id="delete_block_id">
    </form>

    <?php include('../../structure/footer.php'); ?>

    </div>
    <!-- end cards -->
  </main>

  <?php include('../../structure/customizer.php'); ?>
</body>

<?php include('../../structure/script.php'); ?>
<script>
  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('blocksTable');
    const rows = table.getElementsByClassName('block-row');

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

  // Modal functions
  function openAddBlockModal() {
    const modal = document.getElementById('addBlockModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeAddBlockModal() {
    const modal = document.getElementById('addBlockModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
  }

  function openEditBlockModal(blockId) {
    const row = document.querySelector(`tr[data-block-id="${blockId}"]`);
    if (row) {
      document.getElementById('edit_block_id').value = blockId;
      document.getElementById('edit_room_id').value = row.dataset.roomId;
      document.getElementById('edit_block_type').value = row.dataset.blockType;
      document.getElementById('edit_date_start').value = row.dataset.dateStart;
      document.getElementById('edit_date_end').value = row.dataset.dateEnd;
      document.getElementById('edit_time_start').value = row.dataset.timeStart;
      document.getElementById('edit_time_end').value = row.dataset.timeEnd;
      document.getElementById('edit_reason').value = row.dataset.reason;
      document.getElementById('edit_is_active').checked = row.dataset.isActive === '1';

      const modal = document.getElementById('editBlockModal');
      modal.classList.remove('hidden');
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
  }

  function closeEditBlockModal() {
    const modal = document.getElementById('editBlockModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';

    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
  }

  function openViewBlockModal(blockId) {
    const row = document.querySelector(`tr[data-block-id="${blockId}"]`);
    if (row) {
      document.getElementById('view_room').textContent = row.dataset.roomNumber + (row.dataset.roomName ? ' - ' + row.dataset.roomName : '');
      document.getElementById('view_property').textContent = row.dataset.propertyName;
      document.getElementById('view_block_type').textContent = row.dataset.blockType.replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase());

      const dateRange = row.dataset.dateStart + ' to ' + row.dataset.dateEnd;
      document.getElementById('view_date_range').textContent = dateRange;

      const timeRange = row.dataset.timeStart && row.dataset.timeEnd ?
        row.dataset.timeStart + ' - ' + row.dataset.timeEnd :
        'All day';
      document.getElementById('view_time_range').textContent = timeRange;

      document.getElementById('view_reason').textContent = row.dataset.reason || 'No reason provided';
      document.getElementById('view_status').textContent = row.dataset.isActive === '1' ? 'Active' : 'Inactive';

      const modal = document.getElementById('viewBlockModal');
      modal.classList.remove('hidden');
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
  }

  function closeViewBlockModal() {
    const modal = document.getElementById('viewBlockModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  function confirmDeleteBlock(blockId) {
    const row = document.querySelector(`tr[data-block-id="${blockId}"]`);
    const roomInfo = row ? row.dataset.roomNumber + ' (' + row.dataset.propertyName + ')' : 'this block';

    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete the block for "${roomInfo}". This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('delete_block_id').value = blockId;
        document.getElementById('deleteBlockForm').submit();
      }
    });
  }

  // Show success/error messages
  document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_GET['success'])): ?>
      <?php if ($_GET['success'] === 'block_added'): ?>
        Swal.fire({
          title: 'Success!',
          text: 'Block has been added successfully.',
          icon: 'success',
          confirmButtonColor: '#3085d6'
        });
      <?php elseif ($_GET['success'] === 'block_updated'): ?>
        Swal.fire({
          title: 'Success!',
          text: 'Block has been updated successfully.',
          icon: 'success',
          confirmButtonColor: '#3085d6'
        });
      <?php elseif ($_GET['success'] === 'block_deleted'): ?>
        Swal.fire({
          title: 'Deleted!',
          text: 'Block has been deleted successfully.',
          icon: 'success',
          confirmButtonColor: '#3085d6'
        });
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
      Swal.fire({
        title: 'Error!',
        text: '<?php echo addslashes($error_message); ?>',
        icon: 'error',
        confirmButtonColor: '#3085d6'
      });
    <?php endif; ?>
  });
</script>

</html>