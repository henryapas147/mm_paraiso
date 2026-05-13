<?php
require_once '../../config/db.php';

// Handle Delete Invoice Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_invoice') {
  try {
    error_log("Starting invoice deletion process...");

    $invoice_id = $_POST['invoice_id'] ?? '';
    error_log("Deleting invoice_id: " . $invoice_id);

    if (empty($invoice_id)) {
      throw new Exception("Invoice ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Delete invoice from database
    $stmt = $pdo->prepare("DELETE FROM invoices WHERE invoice_id = ?");
    $stmt->execute([$invoice_id]);
    error_log("Deleted invoice from database");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: invoices.php?success=invoice_deleted');
    exit;
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("Error deleting invoice: " . $e->getMessage());
    $error_message = "Failed to delete invoice. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Edit Invoice Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_invoice') {
  try {
    error_log("Starting invoice edit process...");

    $invoice_id = $_POST['invoice_id'] ?? '';
    error_log("Editing invoice_id: " . $invoice_id);

    if (empty($invoice_id)) {
      throw new Exception("Invoice ID is required");
    }

    // Start transaction
    $pdo->beginTransaction();

    // Update invoice data
    $stmt = $pdo->prepare("
            UPDATE invoices SET
                reservation_id = ?,
                invoice_number = ?,
                invoice_type = ?,
                invoice_date = ?,
                due_date = ?,
                subtotal = ?,
                discount_amount = ?,
                tax_amount = ?,
                total_amount = ?,
                amount_paid = ?,
                balance = ?,
                status = ?,
                notes = ?
            WHERE invoice_id = ?
        ");

    $stmt->execute([
      $_POST['reservation_id'],
      $_POST['invoice_number'],
      $_POST['invoice_type'],
      $_POST['invoice_date'],
      $_POST['due_date'] ?? null,
      $_POST['subtotal'],
      $_POST['discount_amount'],
      $_POST['tax_amount'],
      $_POST['total_amount'],
      $_POST['amount_paid'],
      $_POST['balance'],
      $_POST['status'],
      $_POST['notes'] ?? null,
      $invoice_id
    ]);

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: invoices.php?success=invoice_updated');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error editing invoice: " . $e->getMessage());
    $error_message = "Failed to update invoice. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error editing invoice: " . $e->getMessage());
    $error_message = "Failed to update invoice. Please try again. Error: " . $e->getMessage();
  }
}

// Handle Add Invoice Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_invoice') {
  try {
    error_log("Starting invoice addition process...");

    // Generate UUID for invoice_id
    $invoice_id = sprintf(
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

    // Generate invoice number
    $year = date('Y');
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM invoices WHERE invoice_number LIKE ?");
    $stmt->execute(["OR-$year-%"]);
    $result = $stmt->fetch();
    $next_num = str_pad(($result['count'] + 1), 6, '0', STR_PAD_LEFT);
    $invoice_number = "OR-$year-$next_num";

    // Start transaction
    $pdo->beginTransaction();

    // Insert invoice data
    $stmt = $pdo->prepare("
            INSERT INTO invoices (
                invoice_id, reservation_id, invoice_number, invoice_type, invoice_date, 
                due_date, subtotal, discount_amount, tax_amount, total_amount, 
                amount_paid, balance, status, notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

    $params = [
      $invoice_id,
      $_POST['reservation_id'],
      $invoice_number,
      $_POST['invoice_type'],
      $_POST['invoice_date'],
      $_POST['due_date'] ?? null,
      $_POST['subtotal'],
      $_POST['discount_amount'],
      $_POST['tax_amount'],
      $_POST['total_amount'],
      $_POST['amount_paid'],
      $_POST['balance'],
      $_POST['status'],
      $_POST['notes'] ?? null
    ];

    error_log("Executing invoice insert with params: " . json_encode($params));
    $stmt->execute($params);
    error_log("Invoice inserted successfully");

    $pdo->commit();

    // Redirect to avoid form resubmission
    header('Location: invoices.php?success=invoice_added');
    exit;
  } catch (PDOException $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("PDO Error adding invoice: " . $e->getMessage());
    error_log("Error code: " . $e->getCode());
    error_log("Error info: " . print_r($e->errorInfo, true));
    $error_message = "Failed to add invoice. Please try again. Error: " . $e->getMessage();
  } catch (Exception $e) {
    if ($pdo->inTransaction()) {
      $pdo->rollBack();
    }
    error_log("General Error adding invoice: " . $e->getMessage());
    $error_message = "Failed to add invoice. Please try again. Error: " . $e->getMessage();
  }
}

// Fetch all invoices with related data
$stmt = $pdo->query("
    SELECT 
        i.*
    FROM invoices i
    ORDER BY i.invoice_date DESC, i.created_at DESC
");
$invoices = $stmt->fetchAll();
?>

<script>
  // Store invoices data for JavaScript access
  const invoicesData = <?php echo json_encode($invoices); ?>;
</script>

<?php $assets_path = '../assets';
$page_title = 'Invoices'; ?>
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
              <h6 class="mb-0 font-bold dark:text-white">Total Invoices</h6>
            </div>
            <div class="p-4 flex-auto">
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($invoices); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Paid</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $paidInvoices = array_filter($invoices, function ($i) {
                return $i['status'] === 'paid';
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($paidInvoices); ?></h5>
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
              $pendingInvoices = array_filter($invoices, function ($i) {
                return $i['status'] === 'draft' || $i['status'] === 'sent' || $i['status'] === 'partial';
              });
              ?>
              <h5 class="font-bold text-3xl dark:text-white"><?php echo count($pendingInvoices); ?></h5>
            </div>
          </div>
        </div>
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
          <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
              <h6 class="mb-0 font-bold dark:text-white">Total Outstanding</h6>
            </div>
            <div class="p-4 flex-auto">
              <?php
              $totalOutstanding = array_sum(array_column($invoices, 'balance'));
              ?>
              <h5 class="font-bold text-3xl dark:text-white">₱<?php echo number_format($totalOutstanding, 2); ?></h5>
            </div>
          </div>
        </div>
      </div>

      <!-- Invoices Table -->
      <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
          <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-between items-center">
              <h6 class="dark:text-white">Invoices</h6>
              <button onclick="openAddInvoiceModal()" class="bg-gradient-to-tl from-blue-500 to-violet-500 text-white px-4 py-2 rounded-lg text-xs font-semibold uppercase shadow-md hover:shadow-xs transition-all">
                <i class="fas fa-plus mr-1"></i> Add Invoice
              </button>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="invoicesTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Invoice #</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Balance</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($invoices)): ?>
                      <tr>
                        <td colspan="8" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No invoices found. <a href="javascript:;" onclick="openAddInvoiceModal()" class="text-blue-500 font-semibold">Add your first invoice</a>.
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($invoices as $index => $invoice): ?>
                        <tr class="invoice-row" data-invoice-id="<?php echo $invoice['invoice_id']; ?>" data-reservation-id="<?php echo htmlspecialchars($invoice['reservation_id']); ?>" data-invoice-number="<?php echo htmlspecialchars($invoice['invoice_number']); ?>" data-invoice-type="<?php echo htmlspecialchars($invoice['invoice_type']); ?>" data-invoice-date="<?php echo $invoice['invoice_date']; ?>" data-due-date="<?php echo $invoice['due_date']; ?>" data-subtotal="<?php echo $invoice['subtotal']; ?>" data-discount-amount="<?php echo $invoice['discount_amount']; ?>" data-tax-amount="<?php echo $invoice['tax_amount']; ?>" data-total-amount="<?php echo $invoice['total_amount']; ?>" data-amount-paid="<?php echo $invoice['amount_paid']; ?>" data-balance="<?php echo $invoice['balance']; ?>" data-status="<?php echo htmlspecialchars($invoice['status']); ?>" data-notes="<?php echo htmlspecialchars($invoice['notes'] ?? ''); ?>">
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white"><?php echo htmlspecialchars($invoice['invoice_number']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo substr($invoice['reservation_id'], 0, 8) . '...'; ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo ucfirst(str_replace('_', ' ', $invoice['invoice_type'])); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo date('M d, Y', strtotime($invoice['invoice_date'])); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">₱<?php echo number_format($invoice['total_amount'], 2); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white">₱<?php echo number_format($invoice['balance'], 2); ?></p>
                          </td>
                          <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <?php
                            $statusClass = '';
                            switch ($invoice['status']) {
                              case 'paid':
                                $statusClass = 'from-emerald-500 to-teal-400';
                                break;
                              case 'partial':
                                $statusClass = 'from-blue-500 to-indigo-400';
                                break;
                              case 'draft':
                                $statusClass = 'from-slate-500 to-slate-400';
                                break;
                              case 'sent':
                                $statusClass = 'from-amber-500 to-orange-400';
                                break;
                              case 'overdue':
                                $statusClass = 'from-red-500 to-rose-400';
                                break;
                              case 'void':
                                $statusClass = 'from-gray-600 to-gray-400';
                                break;
                              default:
                                $statusClass = 'from-blue-500 to-indigo-400';
                            }
                            ?>
                            <span class="bg-gradient-to-tl <?php echo $statusClass; ?> px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white"><?php echo ucfirst($invoice['status']); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex items-center gap-2">
                              <button onclick="openViewInvoiceModal('<?php echo $invoice['invoice_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" title="View Details">
                                <i class="fas fa-info-circle mr-1.5 text-xs"></i>
                                <span>View</span>
                              </button>
                              <button onclick="openEditInvoiceModal('<?php echo $invoice['invoice_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-white/70 bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-white/10 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-500/20 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Invoice">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span>Edit</span>
                              </button>
                              <button onclick="confirmDeleteInvoice('<?php echo $invoice['invoice_id']; ?>')" class="group relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-white dark:bg-slate-700/50 border border-red-200 dark:border-white/10 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-300 dark:hover:border-red-500 transition-all duration-200 shadow-sm hover:shadow-md" title="Delete Invoice">
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

      <!-- Add Invoice Modal -->
      <div id="addInvoiceModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddInvoiceModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Add New Invoice</h3>
            <button onclick="closeAddInvoiceModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form method="POST" action="invoices.php">
              <input type="hidden" name="action" value="add_invoice">
              <div class="space-y-4">
                <div>
                  <label for="reservation_id" class="block text-sm font-medium text-gray-700">Reservation ID</label>
                  <input type="text" name="reservation_id" id="reservation_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="UUID">
                </div>
                <div>
                  <label for="invoice_type" class="block text-sm font-medium text-gray-700">Invoice Type</label>
                  <select name="invoice_type" id="invoice_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="official_receipt">Official Receipt</option>
                    <option value="proforma">Proforma</option>
                    <option value="billing_statement">Billing Statement</option>
                    <option value="refund_receipt">Refund Receipt</option>
                  </select>
                </div>
                <div>
                  <label for="invoice_date" class="block text-sm font-medium text-gray-700">Invoice Date</label>
                  <input type="date" name="invoice_date" id="invoice_date" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                  <input type="date" name="due_date" id="due_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="subtotal" class="block text-sm font-medium text-gray-700">Subtotal</label>
                    <input type="number" name="subtotal" id="subtotal" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0.00">
                  </div>
                  <div>
                    <label for="discount_amount" class="block text-sm font-medium text-gray-700">Discount</label>
                    <input type="number" name="discount_amount" id="discount_amount" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0.00">
                  </div>
                </div>
                <div>
                  <label for="tax_amount" class="block text-sm font-medium text-gray-700">Tax Amount</label>
                  <input type="number" name="tax_amount" id="tax_amount" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0.00">
                </div>
                <div>
                  <label for="total_amount" class="block text-sm font-medium text-gray-700">Total Amount</label>
                  <input type="number" name="total_amount" id="total_amount" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0.00">
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="amount_paid" class="block text-sm font-medium text-gray-700">Amount Paid</label>
                    <input type="number" name="amount_paid" id="amount_paid" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0.00">
                  </div>
                  <div>
                    <label for="balance" class="block text-sm font-medium text-gray-700">Balance</label>
                    <input type="number" name="balance" id="balance" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="0.00">
                  </div>
                </div>
                <div>
                  <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                  <select name="status" id="status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="draft">Draft</option>
                    <option value="sent">Sent</option>
                    <option value="paid">Paid</option>
                    <option value="partial">Partial</option>
                    <option value="overdue">Overdue</option>
                    <option value="void">Void</option>
                  </select>
                </div>
                <div>
                  <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                  <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeAddInvoiceModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Add Invoice
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Edit Invoice Modal -->
      <div id="editInvoiceModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditInvoiceModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Edit Invoice</h3>
            <button onclick="closeEditInvoiceModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <form method="POST" action="invoices.php">
              <input type="hidden" name="action" value="edit_invoice">
              <input type="hidden" name="invoice_id" id="edit_invoice_id">
              <div class="space-y-4">
                <div>
                  <label for="edit_reservation_id" class="block text-sm font-medium text-gray-700">Reservation ID</label>
                  <input type="text" name="reservation_id" id="edit_reservation_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                  <input type="text" name="invoice_number" id="edit_invoice_number" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_invoice_type" class="block text-sm font-medium text-gray-700">Invoice Type</label>
                  <select name="invoice_type" id="edit_invoice_type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="official_receipt">Official Receipt</option>
                    <option value="proforma">Proforma</option>
                    <option value="billing_statement">Billing Statement</option>
                    <option value="refund_receipt">Refund Receipt</option>
                  </select>
                </div>
                <div>
                  <label for="edit_invoice_date" class="block text-sm font-medium text-gray-700">Invoice Date</label>
                  <input type="date" name="invoice_date" id="edit_invoice_date" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                  <input type="date" name="due_date" id="edit_due_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="edit_subtotal" class="block text-sm font-medium text-gray-700">Subtotal</label>
                    <input type="number" name="subtotal" id="edit_subtotal" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                  <div>
                    <label for="edit_discount_amount" class="block text-sm font-medium text-gray-700">Discount</label>
                    <input type="number" name="discount_amount" id="edit_discount_amount" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                </div>
                <div>
                  <label for="edit_tax_amount" class="block text-sm font-medium text-gray-700">Tax Amount</label>
                  <input type="number" name="tax_amount" id="edit_tax_amount" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                  <label for="edit_total_amount" class="block text-sm font-medium text-gray-700">Total Amount</label>
                  <input type="number" name="total_amount" id="edit_total_amount" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="edit_amount_paid" class="block text-sm font-medium text-gray-700">Amount Paid</label>
                    <input type="number" name="amount_paid" id="edit_amount_paid" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                  <div>
                    <label for="edit_balance" class="block text-sm font-medium text-gray-700">Balance</label>
                    <input type="number" name="balance" id="edit_balance" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  </div>
                </div>
                <div>
                  <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                  <select name="status" id="edit_status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="draft">Draft</option>
                    <option value="sent">Sent</option>
                    <option value="paid">Paid</option>
                    <option value="partial">Partial</option>
                    <option value="overdue">Overdue</option>
                    <option value="void">Void</option>
                  </select>
                </div>
                <div>
                  <label for="edit_notes" class="block text-sm font-medium text-gray-700">Notes</label>
                  <textarea name="notes" id="edit_notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button type="button" onclick="closeEditInvoiceModal()" class="px-6 py-2 border border-gray-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                  Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-tl from-blue-500 to-violet-500 text-white rounded-lg font-semibold hover:shadow-md transition-all">
                  Update Invoice
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- View Invoice Modal -->
      <div id="viewInvoiceModal" class="fixed inset-0 z-50 hidden" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewInvoiceModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-850 rounded-2xl shadow-2xl w-full max-w-lg" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: 90%; max-width: 600px; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-height: 90vh; display: flex; flex-direction: column;">
          <div class="p-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 class="text-xl font-bold dark:text-white">Invoice Details</h3>
            <button onclick="closeViewInvoiceModal()" class="text-gray-500 hover:text-gray-700 dark:text-white/60 dark:hover:text-white" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">
              <i class="fas fa-times text-xl">×</i>
            </button>
          </div>
          <div class="p-6 overflow-y-auto" style="padding: 1.5rem; flex: 1;">
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500">Invoice Number</label>
                <p id="view_invoice_number" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Reservation</label>
                <p id="view_reservation_id" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Invoice Type</label>
                <p id="view_invoice_type" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Invoice Date</label>
                <p id="view_invoice_date" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Due Date</label>
                <p id="view_due_date" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-500">Subtotal</label>
                  <p id="view_subtotal" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Discount</label>
                  <p id="view_discount_amount" class="mt-1 text-sm text-gray-900"></p>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Tax Amount</label>
                <p id="view_tax_amount" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-500">Total Amount</label>
                  <p id="view_total_amount" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Amount Paid</label>
                  <p id="view_amount_paid" class="mt-1 text-sm text-gray-900"></p>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Balance</label>
                <p id="view_balance" class="mt-1 text-sm text-gray-900"></p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Status</label>
                <p id="view_status" class="mt-1 text-sm text-gray-900"></p>
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
  function openAddInvoiceModal() {
    document.getElementById('addInvoiceModal').style.display = 'block';
    document.getElementById('addInvoiceModal').classList.remove('hidden');
  }

  function closeAddInvoiceModal() {
    document.getElementById('addInvoiceModal').style.display = 'none';
    document.getElementById('addInvoiceModal').classList.add('hidden');
  }

  function openEditInvoiceModal(invoiceId) {
    const invoice = invoicesData.find(i => i.invoice_id === invoiceId);
    if (invoice) {
      document.getElementById('edit_invoice_id').value = invoice.invoice_id;
      document.getElementById('edit_reservation_id').value = invoice.reservation_id;
      document.getElementById('edit_invoice_number').value = invoice.invoice_number;
      document.getElementById('edit_invoice_type').value = invoice.invoice_type;
      document.getElementById('edit_invoice_date').value = invoice.invoice_date;
      document.getElementById('edit_due_date').value = invoice.due_date || '';
      document.getElementById('edit_subtotal').value = invoice.subtotal;
      document.getElementById('edit_discount_amount').value = invoice.discount_amount;
      document.getElementById('edit_tax_amount').value = invoice.tax_amount;
      document.getElementById('edit_total_amount').value = invoice.total_amount;
      document.getElementById('edit_amount_paid').value = invoice.amount_paid;
      document.getElementById('edit_balance').value = invoice.balance;
      document.getElementById('edit_status').value = invoice.status;
      document.getElementById('edit_notes').value = invoice.notes || '';

      document.getElementById('editInvoiceModal').style.display = 'block';
      document.getElementById('editInvoiceModal').classList.remove('hidden');
    }
  }

  function closeEditInvoiceModal() {
    document.getElementById('editInvoiceModal').style.display = 'none';
    document.getElementById('editInvoiceModal').classList.add('hidden');
  }

  function openViewInvoiceModal(invoiceId) {
    const invoice = invoicesData.find(i => i.invoice_id === invoiceId);
    if (invoice) {
      document.getElementById('view_invoice_number').textContent = invoice.invoice_number;
      document.getElementById('view_reservation_id').textContent = invoice.reservation_id;
      document.getElementById('view_invoice_type').textContent = invoice.invoice_type;
      document.getElementById('view_invoice_date').textContent = new Date(invoice.invoice_date).toLocaleDateString();
      document.getElementById('view_due_date').textContent = invoice.due_date ? new Date(invoice.due_date).toLocaleDateString() : 'N/A';
      document.getElementById('view_subtotal').textContent = '₱' + parseFloat(invoice.subtotal).toFixed(2);
      document.getElementById('view_discount_amount').textContent = '₱' + parseFloat(invoice.discount_amount).toFixed(2);
      document.getElementById('view_tax_amount').textContent = '₱' + parseFloat(invoice.tax_amount).toFixed(2);
      document.getElementById('view_total_amount').textContent = '₱' + parseFloat(invoice.total_amount).toFixed(2);
      document.getElementById('view_amount_paid').textContent = '₱' + parseFloat(invoice.amount_paid).toFixed(2);
      document.getElementById('view_balance').textContent = '₱' + parseFloat(invoice.balance).toFixed(2);
      document.getElementById('view_status').textContent = invoice.status;
      document.getElementById('view_notes').textContent = invoice.notes || 'N/A';

      document.getElementById('viewInvoiceModal').style.display = 'block';
      document.getElementById('viewInvoiceModal').classList.remove('hidden');
    }
  }

  function closeViewInvoiceModal() {
    document.getElementById('viewInvoiceModal').style.display = 'none';
    document.getElementById('viewInvoiceModal').classList.add('hidden');
  }

  function confirmDeleteInvoice(invoiceId) {
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
        form.action = 'invoices.php';
        form.innerHTML = '<input type="hidden" name="action" value="delete_invoice"><input type="hidden" name="invoice_id" value="' + invoiceId + '">';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('invoicesTable');
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