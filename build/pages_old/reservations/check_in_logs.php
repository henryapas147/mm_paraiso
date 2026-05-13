<?php
require_once '../../config/db.php';

// Fetch all check-in logs with related data
$stmt = $pdo->query("
    SELECT
        cil.*,
        r.reservation_code,
        r.check_in_date,
        r.check_out_date,
        rm.room_number,
        rm.room_name,
        p.property_name
    FROM check_in_logs cil
    INNER JOIN reservations r ON cil.reservation_id = r.reservation_id
    LEFT JOIN reservation_rooms rr ON cil.res_room_id = rr.res_room_id
    LEFT JOIN rooms rm ON rr.room_id = rm.room_id
    LEFT JOIN properties p ON r.property_id = p.property_id
    ORDER BY cil.event_at DESC
");
$checkInLogs = $stmt->fetchAll();
?>

<?php $assets_path = '../assets'; $page_title = 'Check-in Logs'; ?>
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
              <h6 class="dark:text-white">Check-in/Check-out Logs</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="checkInLogsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Event Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Event At</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Processed By</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Notes</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($checkInLogs)): ?>
                      <tr>
                        <td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No check-in logs found
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($checkInLogs as $log): ?>
                        <tr>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($log['reservation_code']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($log['property_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <?php if ($log['room_number']): ?>
                              <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($log['room_number'] . ' - ' . $log['room_name']); ?></p>
                            <?php else: ?>
                              <p class="mb-0 text-xs leading-tight text-slate-400">-</p>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <?php
                            $eventColors = [
                              'check_in' => 'bg-green-100 text-green-600',
                              'check_out' => 'bg-blue-100 text-blue-600',
                              'early_check_in' => 'bg-purple-100 text-purple-600',
                              'late_check_out' => 'bg-orange-100 text-orange-600',
                              'room_transfer' => 'bg-cyan-100 text-cyan-600',
                              'extension' => 'bg-pink-100 text-pink-600'
                            ];
                            $eventClass = $eventColors[$log['event_type']] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="text-xs font-semibold <?php echo $eventClass; ?> px-2 py-1 rounded"><?php echo ucfirst(str_replace('_', ' ', $log['event_type'])); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo date('M d, Y h:i A', strtotime($log['event_at'])); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo $log['processed_by'] ? 'Staff ID: ' . substr($log['processed_by'], 0, 8) : '-'; ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo htmlspecialchars($log['notes'] ?? '-'); ?></p>
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
    table = document.getElementById("checkInLogsTable");
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