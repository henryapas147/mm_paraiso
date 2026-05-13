<?php
require_once '../../config/db.php';

// Fetch all food orders with property and reservation details
$stmt = $pdo->query("
    SELECT
        fo.*,
        p.property_name,
        r.reservation_id,
        r.reservation_code,
        CONCAT(r.walkin_first_name, ' ', r.walkin_last_name) as guest_name
    FROM food_orders fo
    LEFT JOIN properties p ON fo.property_id = p.property_id
    LEFT JOIN reservations r ON fo.reservation_id = r.reservation_id
    ORDER BY fo.created_at DESC, fo.order_number DESC
");
$foodOrders = $stmt->fetchAll();

// Fetch all properties
$stmt = $pdo->query("SELECT property_id, property_name FROM properties ORDER BY property_name ASC");
$properties = $stmt->fetchAll();

// Fetch all active reservations
$stmt = $pdo->query("SELECT reservation_id, reservation_code, CONCAT(walkin_first_name, ' ', walkin_last_name) as guest_name FROM reservations WHERE reservation_status IN ('confirmed', 'checked_in') ORDER BY reservation_code ASC");
$reservations = $stmt->fetchAll();
?>

<script>
  // Store food orders data for JavaScript access
  const foodOrdersData = <?php echo json_encode($foodOrders); ?>;
  const propertiesData = <?php echo json_encode($properties); ?>;
  const reservationsData = <?php echo json_encode($reservations); ?>;
</script>

<?php $assets_path = '../assets';
$page_title = 'Food Orders'; ?>
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
              <h6 class="mb-0 font-bold dark:text-white">Total Orders</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($foodOrders); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Pending</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $pendingOrders = array_filter($foodOrders, function ($o) {
                return in_array($o['order_status'], ['pending', 'confirmed', 'preparing']);
              });
              ?>
              <h5 class="font-bold text-3xl text-orange-500"><?php echo count($pendingOrders); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Ready/Delivered</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $readyOrders = array_filter($foodOrders, function ($o) {
                return in_array($o['order_status'], ['ready', 'delivered']);
              });
              ?>
              <h5 class="font-bold text-3xl text-emerald-500"><?php echo count($readyOrders); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Unpaid</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $unpaidOrders = array_filter($foodOrders, function ($o) {
                return $o['payment_status'] === 'unpaid';
              });
              ?>
              <h5 class="font-bold text-3xl text-red-500"><?php echo count($unpaidOrders); ?></h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Food Orders Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Food Orders</h6>
              <button onclick="openAddOrderModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> New Order
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="orderTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Order</th>
                      <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Customer</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($foodOrders)): ?>
                      <tr>
                        <td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No food orders found. <a href="javascript:;" onclick="openAddOrderModal()" class="text-blue-500 font-semibold">Create your first order</a>.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($foodOrders as $index => $order): ?>
                        <tr class="order-row">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex px-2 py-1">
                              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl bg-gradient-to-tl from-orange-500 to-red-500">
                                <i class="fas fa-receipt"></i>
                              </div>
                              <div class="flex flex-col justify-center">
                                <h6 class="mb-0 text-sm leading-normal dark:text-white"><?php echo htmlspecialchars($order['order_number']); ?></h6>
                                <span class="text-xs text-slate-400 dark:text-white/60"><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></span>
                              </div>
                            </div>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <?php
                            $typeIcons = [
                              'dine_in' => '<i class="fas fa-utensils mr-1"></i>',
                              'room_service' => '<i class="fas fa-bed mr-1"></i>',
                              'takeout' => '<i class="fas fa-shopping-bag mr-1"></i>'
                            ];
                            ?>
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo $typeIcons[$order['order_type']] ?? ''; ?><?php echo ucfirst(str_replace('_', ' ', $order['order_type'])); ?></p>
                            <?php if (!empty($order['table_number'])): ?>
                              <span class="text-xs text-slate-400 dark:text-white/60">Table: <?php echo htmlspecialchars($order['table_number']); ?></span>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($order['customer_name'] ?? ($order['guest_name'] ?? 'Walk-in')); ?></p>
                            <?php if (!empty($order['reservation_id'])): ?>
                              <span class="text-xs text-blue-500">Linked to reservation</span>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($order['property_name']); ?></p>
                          </td>
                          <td class="p-2 text-right align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-sm font-bold leading-tight dark:text-white"><?php echo number_format($order['total_amount'], 2); ?></p>
                          </td>
                          <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <?php
                            $statusColors = [
                              'pending' => 'from-slate-500 to-slate-400',
                              'confirmed' => 'from-blue-500 to-blue-400',
                              'preparing' => 'from-amber-500 to-orange-400',
                              'ready' => 'from-emerald-500 to-teal-400',
                              'delivered' => 'from-cyan-500 to-blue-400',
                              'cancelled' => 'from-red-500 to-rose-400'
                            ];
                            $color = $statusColors[$order['order_status']] ?? 'from-slate-500 to-slate-400';
                            ?>
                            <span class="bg-gradient-to-tl <?php echo $color; ?> px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white"><?php echo str_replace('_', ' ', $order['order_status']); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2 flex-wrap">
                              <?php if ($order['order_status'] === 'pending'): ?>
                                <button onclick="changeOrderStatus('<?php echo $order['order_id']; ?>', 'confirmed', '<?php echo htmlspecialchars($order['order_number']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Confirm Order">
                                  <i class="fas fa-check mr-1.5 text-xs"></i>
                                  <span>Confirm</span>
                                </button>
                              <?php endif; ?>
                              <?php if ($order['order_status'] === 'confirmed'): ?>
                                <button onclick="changeOrderStatus('<?php echo $order['order_id']; ?>', 'preparing', '<?php echo htmlspecialchars($order['order_number']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Start Preparing">
                                  <i class="fas fa-fire mr-1.5 text-xs"></i>
                                  <span>Prepare</span>
                                </button>
                              <?php endif; ?>
                              <?php if ($order['order_status'] === 'preparing'): ?>
                                <button onclick="changeOrderStatus('<?php echo $order['order_id']; ?>', 'ready', '<?php echo htmlspecialchars($order['order_number']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-500/20 hover:text-emerald-600 dark:hover:text-emerald-400 hover:border-emerald-300 dark:hover:border-emerald-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Mark Ready">
                                  <i class="fas fa-bell mr-1.5 text-xs"></i>
                                  <span>Ready</span>
                                </button>
                              <?php endif; ?>
                              <?php if ($order['order_status'] === 'ready'): ?>
                                <button onclick="changeOrderStatus('<?php echo $order['order_id']; ?>', 'delivered', '<?php echo htmlspecialchars($order['order_number']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-cyan-50 dark:hover:bg-cyan-500/20 hover:text-cyan-600 dark:hover:text-cyan-400 hover:border-cyan-300 dark:hover:border-cyan-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Mark Delivered">
                                  <i class="fas fa-check-circle mr-1.5 text-xs"></i>
                                  <span>Deliver</span>
                                </button>
                              <?php endif; ?>
                              <?php if (!in_array($order['order_status'], ['cancelled', 'delivered'])): ?>
                                <button onclick="changeOrderStatus('<?php echo $order['order_id']; ?>', 'cancelled', '<?php echo htmlspecialchars($order['order_number']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Cancel Order">
                                  <i class="fas fa-times mr-1.5 text-xs"></i>
                                  <span>Cancel</span>
                                </button>
                              <?php endif; ?>
                              <button onclick="changePaymentStatus('<?php echo $order['order_id']; ?>', '<?php echo htmlspecialchars($order['order_number']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-500/20 hover:text-purple-600 dark:hover:text-purple-400 hover:border-purple-300 dark:hover:border-purple-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Change Payment Status">
                                <i class="fas fa-credit-card mr-1.5 text-xs"></i>
                                <span>Payment</span>
                              </button>
                              <button onclick="addOrderItems('<?php echo $order['order_id']; ?>', '<?php echo htmlspecialchars($order['order_number']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-green-50 dark:hover:bg-green-500/20 hover:text-green-600 dark:hover:text-green-400 hover:border-green-300 dark:hover:border-green-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Add Items">
                                <i class="fas fa-plus mr-1.5 text-xs"></i>
                                <span>Add Items</span>
                              </button>
                              <button onclick="printOrder('<?php echo $order['order_id']; ?>', '<?php echo htmlspecialchars($order['order_number']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-500/20 hover:text-slate-600 dark:hover:text-slate-400 hover:border-slate-300 dark:hover:border-slate-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Print Order">
                                <i class="fas fa-print mr-1.5 text-xs"></i>
                                <span>Print</span>
                              </button>
                              <button onclick="viewOrderDetails('<?php echo $order['order_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditOrderModal('<?php echo $order['order_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Order">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="deleteOrder('<?php echo $order['order_id']; ?>', '<?php echo htmlspecialchars($order['order_number']); ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Order">
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

      <!-- View Order Details Modal -->
      <div id="orderDetailsModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeOrderDetailsModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white" id="orderModalTitle">Order Details</h3>
            <button onclick="closeOrderDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6" id="orderModalContent">
            <!-- Content will be populated by JavaScript -->
          </div>
        </div>
      </div>

      <!-- Add Order Modal -->
      <div id="addOrderModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddOrderModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Create New Food Order</h3>
            <button onclick="closeAddOrderModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="addOrderForm" onsubmit="submitAddOrder(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property <span class="text-red-500">*</span></label>
                  <select name="property_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Property</option>
                    <?php foreach ($properties as $prop): ?>
                      <option value="<?php echo $prop['property_id']; ?>"><?php echo htmlspecialchars($prop['property_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Order Type <span class="text-red-500">*</span></label>
                  <select name="order_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="dine_in">Dine In</option>
                    <option value="room_service">Room Service</option>
                    <option value="takeout">Takeout</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Reservation (Optional)</label>
                  <select name="reservation_id" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Walk-in Customer</option>
                    <?php foreach ($reservations as $res): ?>
                      <option value="<?php echo $res['reservation_id']; ?>"><?php echo htmlspecialchars($res['guest_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Customer Name</label>
                  <input type="text" name="customer_name" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Customer name">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Table Number</label>
                  <input type="text" name="table_number" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., T-01">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Order Status</label>
                  <select name="order_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                    <option value="delivered">Delivered</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Payment Status</label>
                  <select name="payment_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="unpaid">Unpaid</option>
                    <option value="charged_to_room">Charged to Room</option>
                    <option value="paid">Paid</option>
                  </select>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeAddOrderModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Order
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Order Modal -->
      <div id="editOrderModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditOrderModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Edit Food Order</h3>
            <button onclick="closeEditOrderModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <form id="editOrderForm" onsubmit="submitEditOrder(event)">
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="order_id" id="editOrderId">
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Property <span class="text-red-500">*</span></label>
                  <select name="property_id" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Property</option>
                    <?php foreach ($properties as $prop): ?>
                      <option value="<?php echo $prop['property_id']; ?>"><?php echo htmlspecialchars($prop['property_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Order Type <span class="text-red-500">*</span></label>
                  <select name="order_type" required class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="dine_in">Dine In</option>
                    <option value="room_service">Room Service</option>
                    <option value="takeout">Takeout</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Reservation (Optional)</label>
                  <select name="reservation_id" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Walk-in Customer</option>
                    <?php foreach ($reservations as $res): ?>
                      <option value="<?php echo $res['reservation_id']; ?>"><?php echo htmlspecialchars($res['guest_name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Customer Name</label>
                  <input type="text" name="customer_name" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Customer name">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Table Number</label>
                  <input type="text" name="table_number" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., T-01">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Order Status</label>
                  <select name="order_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Payment Status</label>
                  <select name="payment_status" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="unpaid">Unpaid</option>
                    <option value="charged_to_room">Charged to Room</option>
                    <option value="paid">Paid</option>
                  </select>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-600 dark:text-white mb-2">Notes</label>
                  <textarea name="notes" rows="2" class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-white/20 dark:bg-slate-700/50 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes..."></textarea>
                </div>
              </div>
            </div>
            <div class="p-6 border-t border-gray-200 dark:border-white/10 flex justify-end gap-3">
              <button type="button" onclick="closeEditOrderModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white bg-gray-100 dark:bg-slate-700 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                Cancel
              </button>
              <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Order
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
  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('orderTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName('td');
      let found = false;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j]) {
          const textValue = cells[j].textContent || cells[j].innerText;
          if (textValue.toLowerCase().indexOf(filter) > -1) {
            found = true;
            break;
          }
        }
      }

      row.style.display = found ? '' : 'none';
    }
  }

  // View Order Details
  function viewOrderDetails(orderId) {
    const order = foodOrdersData.find(o => o.order_id == orderId);
    if (!order) return;

    document.getElementById('orderModalTitle').textContent = `Order Details - ${order.order_number}`;
    document.getElementById('orderModalContent').innerHTML = `
          <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Order Number</p>
              <p class="font-semibold dark:text-white">${order.order_number}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Order Type</p>
              <p class="font-semibold dark:text-white">${order.order_type}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Customer</p>
              <p class="font-semibold dark:text-white">${order.customer_name || 'Walk-in'}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Table Number</p>
              <p class="font-semibold dark:text-white">${order.table_number || '-'}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Order Status</p>
              <p class="font-semibold dark:text-white">${order.order_status}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Payment Status</p>
              <p class="font-semibold dark:text-white">${order.payment_status}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Subtotal</p>
              <p class="font-semibold dark:text-white">$${parseFloat(order.subtotal).toFixed(2)}</p>
            </div>
            <div>
              <p class="text-sm text-slate-500 dark:text-white/60">Total</p>
              <p class="font-semibold dark:text-white">$${parseFloat(order.total_amount).toFixed(2)}</p>
            </div>
          </div>
          ${order.notes ? `
          <div class="mb-4">
            <p class="text-sm text-slate-500 dark:text-white/60">Notes</p>
            <p class="font-semibold dark:text-white">${order.notes}</p>
          </div>
          ` : ''}
          <div class="p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg">
            <p class="text-sm text-slate-500 dark:text-white/60 mb-2">Order Items</p>
            <p class="text-sm dark:text-white/80 italic">Order items will be displayed here when the feature is fully implemented.</p>
          </div>
        `;
    document.getElementById('orderDetailsModal').style.display = 'block';
  }

  function closeOrderDetailsModal() {
    document.getElementById('orderDetailsModal').style.display = 'none';
  }

  // Add Order Modal
  function openAddOrderModal() {
    document.getElementById('addOrderModal').style.display = 'block';
    document.getElementById('addOrderForm').reset();
  }

  function closeAddOrderModal() {
    document.getElementById('addOrderModal').style.display = 'none';
  }

  function submitAddOrder(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Show loading
    Swal.fire({
      title: 'Creating...',
      text: 'Please wait while we create the order.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    // TODO: Implement AJAX call to create order
    // For now, just show success message
    setTimeout(() => {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Food order created successfully.',
        confirmButtonColor: '#3085d6'
      }).then(() => {
        closeAddOrderModal();
        location.reload();
      });
    }, 1000);
  }

  // Edit Order Modal
  function openEditOrderModal(orderId) {
    const order = foodOrdersData.find(o => o.order_id == orderId);
    if (!order) return;

    document.getElementById('editOrderId').value = order.order_id;
    document.getElementById('editOrderForm').elements['property_id'].value = order.property_id;
    document.getElementById('editOrderForm').elements['order_type'].value = order.order_type;
    document.getElementById('editOrderForm').elements['reservation_id'].value = order.reservation_id || '';
    document.getElementById('editOrderForm').elements['customer_name'].value = order.customer_name || '';
    document.getElementById('editOrderForm').elements['table_number'].value = order.table_number || '';
    document.getElementById('editOrderForm').elements['order_status'].value = order.order_status;
    document.getElementById('editOrderForm').elements['payment_status'].value = order.payment_status;
    document.getElementById('editOrderForm').elements['notes'].value = order.notes || '';

    document.getElementById('editOrderModal').style.display = 'block';
  }

  function closeEditOrderModal() {
    document.getElementById('editOrderModal').style.display = 'none';
  }

  function submitEditOrder(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Show loading
    Swal.fire({
      title: 'Updating...',
      text: 'Please wait while we update the order.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    // TODO: Implement AJAX call to update order
    // For now, just show success message
    setTimeout(() => {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Food order updated successfully.',
        confirmButtonColor: '#3085d6'
      }).then(() => {
        closeEditOrderModal();
        location.reload();
      });
    }, 1000);
  }

  // Delete Order
  function deleteOrder(orderId, orderNumber) {
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to delete "${orderNumber}". This action cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // TODO: Implement AJAX call to delete order
        // For now, just show success message
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'Food order has been deleted.',
          confirmButtonColor: '#3085d6'
        }).then(() => {
          location.reload();
        });
      }
    });
  }

  // Change Order Status
  function changeOrderStatus(orderId, newStatus, orderNumber) {
    const statusText = newStatus.replace('_', ' ');
    Swal.fire({
      title: 'Change Order Status?',
      text: `Change order "${orderNumber}" status to "${statusText}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, change it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // TODO: Implement AJAX call to change order status
        Swal.fire({
          icon: 'success',
          title: 'Status Changed!',
          text: `Order status changed to ${statusText}.`,
          confirmButtonColor: '#3085d6'
        }).then(() => {
          location.reload();
        });
      }
    });
  }

  // Change Payment Status
  function changePaymentStatus(orderId, orderNumber) {
    Swal.fire({
      title: 'Change Payment Status',
      text: `Change payment status for order "${orderNumber}"`,
      icon: 'question',
      input: 'select',
      inputOptions: {
        'unpaid': 'Unpaid',
        'charged_to_room': 'Charged to Room',
        'paid': 'Paid'
      },
      inputPlaceholder: 'Select payment status',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Change',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed && result.value) {
        // TODO: Implement AJAX call to change payment status
        Swal.fire({
          icon: 'success',
          title: 'Payment Status Changed!',
          text: 'Payment status has been updated.',
          confirmButtonColor: '#3085d6'
        }).then(() => {
          location.reload();
        });
      }
    });
  }

  // Add Order Items
  function addOrderItems(orderId, orderNumber) {
    Swal.fire({
      title: 'Add Items to Order',
      text: `Add items to order "${orderNumber}"`,
      icon: 'info',
      confirmButtonColor: '#3085d6'
    });
    // TODO: Implement modal to add items to order
  }

  // Print Order
  function printOrder(orderId, orderNumber) {
    Swal.fire({
      title: 'Print Order',
      text: `Print order "${orderNumber}"`,
      icon: 'info',
      confirmButtonColor: '#3085d6'
    });
    // TODO: Implement print functionality
  }
</script>

</html>