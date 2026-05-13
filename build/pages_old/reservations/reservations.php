<?php
require_once '../../config/db.php';

// Fetch all reservations with related data
$stmt = $pdo->query("
    SELECT
        r.*,
        p.property_name,
        p.property_code,
        CONCAT(COALESCE(r.walkin_first_name, up.first_name), ' ', COALESCE(r.walkin_last_name, up.last_name)) as guest_name,
        COALESCE(r.walkin_email, ua.email) as guest_email,
        COALESCE(r.walkin_phone, ua.phone_number) as guest_phone
    FROM reservations r
    LEFT JOIN properties p ON r.property_id = p.property_id
    LEFT JOIN user_accounts ua ON r.customer_user_id = ua.user_id
    LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
    ORDER BY r.created_at DESC
");
$reservations = $stmt->fetchAll();
?>

<?php $assets_path = '../assets';
$page_title = 'Reservations'; ?>
<!DOCTYPE html>
<html>

<head>
  <?php include('../../structure/head.php'); ?>
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
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
              <h6 class="dark:text-white">Reservations List</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="reservationsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Code</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Guest</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Check-in</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Check-out</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Payment</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($reservations)): ?>
                      <tr>
                        <td colspan="9" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No reservations found
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($reservations as $reservation): ?>
                        <tr>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($reservation['reservation_code']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($reservation['guest_name']); ?></p>
                            <p class="mb-0 text-xs leading-tight text-slate-400"><?php echo htmlspecialchars($reservation['guest_email'] ?? '-'); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($reservation['property_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <span class="text-xs font-semibold bg-blue-100 text-blue-600 px-2 py-1 rounded"><?php echo ucfirst($reservation['booking_type']); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo date('M d, Y', strtotime($reservation['check_in_date'])); ?></p>
                            <p class="mb-0 text-xs leading-tight text-slate-400"><?php echo date('h:i A', strtotime($reservation['check_in_time'])); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <?php if ($reservation['check_out_date']): ?>
                              <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo date('M d, Y', strtotime($reservation['check_out_date'])); ?></p>
                              <p class="mb-0 text-xs leading-tight text-slate-400"><?php echo date('h:i A', strtotime($reservation['check_out_time'] ?? '00:00:00')); ?></p>
                            <?php else: ?>
                              <p class="mb-0 text-xs leading-tight text-slate-400">-</p>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
                            <?php
                            $statusColors = [
                              'pending' => 'bg-yellow-100 text-yellow-600',
                              'confirmed' => 'bg-green-100 text-green-600',
                              'checked_in' => 'bg-blue-100 text-blue-600',
                              'checked_out' => 'bg-gray-100 text-gray-600',
                              'cancelled' => 'bg-red-100 text-red-600',
                              'no_show' => 'bg-orange-100 text-orange-600',
                              'waitlisted' => 'bg-purple-100 text-purple-600'
                            ];
                            $statusClass = $statusColors[$reservation['reservation_status']] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="text-xs font-semibold <?php echo $statusClass; ?> px-2 py-1 rounded"><?php echo ucfirst(str_replace('_', ' ', $reservation['reservation_status'])); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
                            <?php
                            $paymentColors = [
                              'unpaid' => 'bg-red-100 text-red-600',
                              'partial' => 'bg-yellow-100 text-yellow-600',
                              'paid' => 'bg-green-100 text-green-600',
                              'refunded' => 'bg-blue-100 text-blue-600',
                              'void' => 'bg-gray-100 text-gray-600'
                            ];
                            $paymentClass = $paymentColors[$reservation['payment_status']] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="text-xs font-semibold <?php echo $paymentClass; ?> px-2 py-1 rounded"><?php echo ucfirst($reservation['payment_status']); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo number_format($reservation['total_amount'], 2); ?></p>
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

      <?php include('../../structure/footer.php'); ?>

    </div>
    <!-- end cards -->
  </main>

  <?php include('../../structure/customizer.php'); ?>
</body>

<?php include('../../structure/script.php'); ?>
<script>
  function filterTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("reservationsTable");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
      var visible = false;
      var tds = tr[i].getElementsByTagName("td");
      for (var j = 0; j < tds.length; j++) {
        if (tds[j]) {
          txtValue = tds[j].textContent || tds[j].innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            visible = true;
            break;
          }
        }
      }
      tr[i].style.display = visible ? "" : "none";
    }
  }
</script>

</html>