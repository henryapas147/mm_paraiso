<?php
require_once '../../config/db.php';

// Handle Delete Payment Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_payment') {
  try {
    error_log("Starting payment deletion process...");

    $payment_id = $_POST['payment_id'] ?? '';
    error_log("Deleting payment_id: " . $payment_id);

    if (empty($payment_id)) {
      throw new Exception("Payment ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Delete payment from database
    $stmt = $pdo->prepare("DELETE FROM payments WHERE payment_id = ?");
    $stmt->execute([$payment_id]);
    error_log("Deleted payment from database");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: payments.php?success=payment_deleted');
    exit;
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("Error deleting payment: " . $e->getMessage());
    $error_message = "Failed to delete payment. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Edit Payment Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_payment') {
  try {
    error_log("Starting payment edit process...");

    $payment_id = $_POST['payment_id'] ?? '';
    error_log("Editing payment_id: " . $payment_id);

    if (empty($payment_id)) {
      throw new Exception("Payment ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Update payment data
    $stmt = $pdo->prepare("
            UPDATE payments SET
                reservation_id = ?,
                method_id = ?,
                transaction_type = ?,
                amount = ?,
                reference_number = ?,
                proof_image_url = ?,
                payment_status = ?,
                payment_date = ?,
                payment_time = ?,
                notes = ?
            WHERE payment_id = ?
        ");

    $stmt->execute([
      $_POST['reservation_id'],
      $_POST['method_id'],
      $_POST['transaction_type'],
      $_POST['amount'],
      $_POST['reference_number'] ?? null,
      $_POST['proof_image_url'] ?? null,
      $_POST['payment_status'],
      $_POST['payment_date'],
      $_POST['payment_time'] ?? null,
      $_POST['notes'] ?? null,
      $payment_id
    ]);

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: payments.php?success=payment_updated');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error editing payment: " . $e->getMessage());
    $error_message = "Failed to update payment. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error editing payment: " . $e->getMessage());
    $error_message = "Failed to update payment. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Add Payment Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_payment') {
  try {
    error_log("Starting payment addition process...");

    // Generate UUID for payment_id
    $payment_id = sprintf(
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

    // Start transaction
    $pdo->beginTransaction();

    // Insert payment data
    $stmt = $pdo->prepare("
            INSERT INTO payments (
                payment_id, reservation_id, method_id, transaction_type, amount, 
                reference_number, proof_image_url, payment_status, payment_date, 
                payment_time, notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

    $params = [
      $payment_id,
      $_POST['reservation_id'],
      $_POST['method_id'],
      $_POST['transaction_type'],
      $_POST['amount'],
      $_POST['reference_number'] ?? null,
      $_POST['proof_image_url'] ?? null,
      $_POST['payment_status'],
      $_POST['payment_date'],
      $_POST['payment_time'] ?? null,
      $_POST['notes'] ?? null
    ];

    error_log("Executing payment insert with params: " . json_encode($params));
    $stmt->execute($params);
    error_log("Payment inserted successfully");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: payments.php?success=payment_added');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error adding payment: " . $e->getMessage());
    error_log("Error code: " . $e->getCode());
    error_log("Error info: " . print_r($e->errorInfo, true));
    $error_message = "Failed to add payment. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error adding payment: " . $e->getMessage());
    $error_message = "Failed to add payment. Please try again. Error: " . $e->getMessage();
  }
}

// Fetch all payments with related data
$stmt = $pdo->query("
    SELECT 
        p.*,
        pm.method_label,
        pm.method_type
    FROM payments p
    LEFT JOIN payment_methods pm ON p.method_id = pm.method_id
    ORDER BY p.payment_date DESC, p.created_at DESC
");
$payments = $stmt->fetchAll();

// Fetch payment methods for dropdown
$paymentMethods = $pdo->query("SELECT method_id, method_label FROM payment_methods WHERE is_active = 1 ORDER BY method_label")->fetchAll();
?>

<script>
  // Store payments data for JavaScript access
  const paymentsData = <?php echo json_encode($payments); ?>;
  const paymentMethodsData = <?php echo json_encode($paymentMethods); ?>;
</script>

<?php $assets_path = '../assets';
$page_title = 'Payments'; ?>
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
              <h6 class="mb-0 font-bold dark:text-white">Total Payments</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($payments); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Confirmed</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $confirmedPayments = array_filter($payments, function ($p) {
                return $p['payment_status'] === 'confirmed';
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($confirmedPayments); ?></h5>
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
              $pendingPayments = array_filter($payments, function ($p) {
                return $p['payment_status'] === 'pending';
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($pendingPayments); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Amount</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $totalAmount = array_sum(array_column($payments, 'amount'));
              ?>
              <h5 class="font-bold text-3xl dark:text-white">₱<?php echo number_format($totalAmount, 2); ?></h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Payments Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Payments</h6>
              <button onclick="openAddPaymentModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Payment
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="paymentsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Payment ID</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Method</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Amount</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($payments)): ?>
                      <tr>
                        <td colspan="8" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No payments found. <a href="javascript:;" onclick="openAddPaymentModal()" class="text-blue-500 font-semibold">Add your first payment</a>.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($payments as $index => $payment): ?>
                        <tr class="payment-row" data-payment-id="<?php echo $payment['payment_id']; ?>" data-reservation-id="<?php echo htmlspecialchars($payment['reservation_id']); ?>" data-method-id="<?php echo $payment['method_id']; ?>" data-transaction-type="<?php echo htmlspecialchars($payment['transaction_type']); ?>" data-amount="<?php echo $payment['amount']; ?>" data-reference-number="<?php echo htmlspecialchars($payment['reference_number'] ?? ''); ?>" data-proof-image="<?php echo htmlspecialchars($payment['proof_image_url'] ?? ''); ?>" data-payment-status="<?php echo htmlspecialchars($payment['payment_status']); ?>" data-payment-date="<?php echo $payment['payment_date']; ?>" data-payment-time="<?php echo $payment['payment_time']; ?>" data-notes="<?php echo htmlspecialchars($payment['notes'] ?? ''); ?>">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white"><?php echo substr($payment['payment_id'], 0, 8) . '...'; ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo substr($payment['reservation_id'], 0, 8) . '...'; ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($payment['method_label'] ?? 'N/A'); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo ucfirst(str_replace('_', ' ', $payment['transaction_type'])); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">₱<?php echo number_format($payment['amount'], 2); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></p>
                          </td>
                          <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <?php
                            $statusClass = '';
                            switch ($payment['payment_status']) {
                              case 'confirmed':
                                $statusClass = 'from-emerald-500 to-teal-400';
                                break;
                              case 'pending':
                                $statusClass = 'from-amber-500 to-orange-400';
                                break;
                              case 'rejected':
                                $statusClass = 'from-red-500 to-rose-400';
                                break;
                              case 'refunded':
                                $statusClass = 'from-slate-500 to-slate-400';
                                break;
                              case 'voided':
                                $statusClass = 'from-gray-600 to-gray-400';
                                break;
                              default:
                                $statusClass = 'from-blue-500 to-indigo-400';
                            }
                            ?>
                            <span class="bg-gradient-to-tl <?php echo $statusClass; ?> px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white"><?php echo ucfirst($payment['payment_status']); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="openViewPaymentModal('<?php echo $payment['payment_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditPaymentModal('<?php echo $payment['payment_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Payment">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="confirmDeletePayment('<?php echo $payment['payment_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Payment">
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

      <!-- Add Payment Modal -->
      <div id="addPaymentModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddPaymentModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Add New Payment</h3>
            <button onclick="closeAddPaymentModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form method="POST" action="payments.php">
              <input type="hidden" name="action" value="add_payment">
              <div class="space-y-4">
                <div>
                  <label for="reservation_id" class="block text-sm font-medium text-gray-700">Reservation ID</label>
                  <input type="text" name="reservation_id" id="reservation_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="UUID">
                </div>
                <div>
                  <label for="method_id" class="block text-sm font-medium text-gray-700">Payment Method</label>
                  <select name="method_id" id="method_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <?php foreach ($paymentMethods as $method): ?>
                      <option value="<?php echo $method['method_id']; ?>"><?php echo htmlspecialchars($method['method_label']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label for="transaction_type" class="block text-sm font-medium text-gray-700">Transaction Type</label>
                  <select name="transaction_type" id="transaction_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="payment">Payment</option>
                    <option value="partial_payment">Partial Payment</option>
                    <option value="refund">Refund</option>
                    <option value="adjustment">Adjustment</option>
                    <option value="void">Void</option>
                  </select>
                </div>
                <div>
                  <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                  <input type="number" name="amount" id="amount" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
                </div>
                <div>
                  <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                  <input type="text" name="reference_number" id="reference_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="GCash ref #, bank ref #, etc.">
                </div>
                <div>
                  <label for="proof_image_url" class="block text-sm font-medium text-gray-700">Proof Image URL</label>
                  <input type="text" name="proof_image_url" id="proof_image_url" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="https://...">
                </div>
                <div>
                  <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                  <select name="payment_status" id="payment_status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="rejected">Rejected</option>
                    <option value="refunded">Refunded</option>
                    <option value="voided">Voided</option>
                  </select>
                </div>
                <div>
                  <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                  <input type="date" name="payment_date" id="payment_date" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="payment_time" class="block text-sm font-medium text-gray-700">Payment Time</label>
                  <input type="time" name="payment_time" id="payment_time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                  <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeAddPaymentModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Add Payment
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Payment Modal -->
      <div id="editPaymentModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditPaymentModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Edit Payment</h3>
            <button onclick="closeEditPaymentModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form method="POST" action="payments.php">
              <input type="hidden" name="action" value="edit_payment">
              <input type="hidden" name="payment_id" id="edit_payment_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_reservation_id" class="block text-sm font-medium text-gray-700">Reservation ID</label>
                  <input type="text" name="reservation_id" id="edit_reservation_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_method_id" class="block text-sm font-medium text-gray-700">Payment Method</label>
                  <select name="method_id" id="edit_method_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <?php foreach ($paymentMethods as $method): ?>
                      <option value="<?php echo $method['method_id']; ?>"><?php echo htmlspecialchars($method['method_label']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label for="edit_transaction_type" class="block text-sm font-medium text-gray-700">Transaction Type</label>
                  <select name="transaction_type" id="edit_transaction_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="payment">Payment</option>
                    <option value="partial_payment">Partial Payment</option>
                    <option value="refund">Refund</option>
                    <option value="adjustment">Adjustment</option>
                    <option value="void">Void</option>
                  </select>
                </div>
                <div>
                  <label for="edit_amount" class="block text-sm font-medium text-gray-700">Amount</label>
                  <input type="number" name="amount" id="edit_amount" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_reference_number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                  <input type="text" name="reference_number" id="edit_reference_number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_proof_image_url" class="block text-sm font-medium text-gray-700">Proof Image URL</label>
                  <input type="text" name="proof_image_url" id="edit_proof_image_url" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                  <select name="payment_status" id="edit_payment_status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="rejected">Rejected</option>
                    <option value="refunded">Refunded</option>
                    <option value="voided">Voided</option>
                  </select>
                </div>
                <div>
                  <label for="edit_payment_date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                  <input type="date" name="payment_date" id="edit_payment_date" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_payment_time" class="block text-sm font-medium text-gray-700">Payment Time</label>
                  <input type="time" name="payment_time" id="edit_payment_time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_notes" class="block text-sm font-medium text-gray-700">Notes</label>
                  <textarea name="notes" id="edit_notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeEditPaymentModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Update Payment
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- View Payment Modal -->
      <div id="viewPaymentModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewPaymentModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Payment Details</h3>
            <button onclick="closeViewPaymentModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Payment ID</label>
                <p id="view_payment_id" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Reservation</label>
                <p id="view_reservation_id" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Payment Method</label>
                <p id="view_method" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Transaction Type</label>
                <p id="view_transaction_type" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Amount</label>
                <p id="view_amount" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Reference Number</label>
                <p id="view_reference_number" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Payment Date</label>
                <p id="view_payment_date" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Payment Time</label>
                <p id="view_payment_time" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Status</label>
                <p id="view_payment_status" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Notes</label>
                <p id="view_notes" class="mt-1 text-sm text-gray-900"></p>
              </div>
            </div>
          </div>
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
  // Modal Functions
  function openAddPaymentModal() {
    document.getElementById('addPaymentModal').style.display = 'block';
    document.getElementById('addPaymentModal').classList.remove('hidden');
  }

  function closeAddPaymentModal() {
    document.getElementById('addPaymentModal').style.display = 'none';
    document.getElementById('addPaymentModal').classList.add('hidden');
  }

  function openEditPaymentModal(paymentId) {
    const payment = paymentsData.find(p => p.payment_id === paymentId);
    if (payment) {
      document.getElementById('edit_payment_id').value = payment.payment_id;
      document.getElementById('edit_reservation_id').value = payment.reservation_id;
      document.getElementById('edit_method_id').value = payment.method_id;
      document.getElementById('edit_transaction_type').value = payment.transaction_type;
      document.getElementById('edit_amount').value = payment.amount;
      document.getElementById('edit_reference_number').value = payment.reference_number || '';
      document.getElementById('edit_proof_image_url').value = payment.proof_image_url || '';
      document.getElementById('edit_payment_status').value = payment.payment_status;
      document.getElementById('edit_payment_date').value = payment.payment_date;
      document.getElementById('edit_payment_time').value = payment.payment_time || '';
      document.getElementById('edit_notes').value = payment.notes || '';

      document.getElementById('editPaymentModal').style.display = 'block';
      document.getElementById('editPaymentModal').classList.remove('hidden');
    }
  }

  function closeEditPaymentModal() {
    document.getElementById('editPaymentModal').style.display = 'none';
    document.getElementById('editPaymentModal').classList.add('hidden');
  }

  function openViewPaymentModal(paymentId) {
    const payment = paymentsData.find(p => p.payment_id === paymentId);
    if (payment) {
      document.getElementById('view_payment_id').textContent = payment.payment_id;
      document.getElementById('view_reservation_id').textContent = payment.reservation_number || payment.reservation_id;
      document.getElementById('view_method').textContent = payment.method_label || 'N/A';
      document.getElementById('view_transaction_type').textContent = payment.transaction_type;
      document.getElementById('view_amount').textContent = '₱' + parseFloat(payment.amount).toFixed(2);
      document.getElementById('view_reference_number').textContent = payment.reference_number || 'N/A';
      document.getElementById('view_payment_date').textContent = new Date(payment.payment_date).toLocaleDateString();
      document.getElementById('view_payment_time').textContent = payment.payment_time || 'N/A';
      document.getElementById('view_payment_status').textContent = payment.payment_status;
      document.getElementById('view_notes').textContent = payment.notes || 'N/A';

      document.getElementById('viewPaymentModal').style.display = 'block';
      document.getElementById('viewPaymentModal').classList.remove('hidden');
    }
  }

  function closeViewPaymentModal() {
    document.getElementById('viewPaymentModal').style.display = 'none';
    document.getElementById('viewPaymentModal').classList.add('hidden');
  }

  function confirmDeletePayment(paymentId) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'payments.php';
        form.innerHTML = '<input type="hidden" name="action" value="delete_payment"><input type="hidden" name="payment_id" value="' + paymentId + '">';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('paymentsTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
      const td = tr[i].getElementsByTagName('td');
      let visible = false;
      for (let j = 0; j < td.length - 1; j++) {
        if (td[j]) {
          const txtValue = td[j].textContent || td[j].innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            visible = true;
            break;
          }
        }
      }
      tr[i].style.display = visible ? '' : 'none';
    }
  }

  // Success/Error Messages
  <?php if (isset($_GET['success'])): ?>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '<?php echo htmlspecialchars($_GET['success']); ?>',
      timer: 2000,
      showConfirmButton: false
    });
  <?php endif; ?>

  <?php if (isset($error_message)): ?>
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: '<?php echo htmlspecialchars($error_message); ?>',
      confirmButtonColor: '#3085d6'
    });
  <?php endif; ?>
</script>

</html>