<?php
require_once '../../config/db.php';

// Fetch all entrance logs with related data
$stmt = $pdo->query("
    SELECT 
        el.*,
        p.property_name,
        r.reservation_code,
        rg.first_name,
        rg.last_name,
        rg.guest_type as res_guest_type
    FROM entrance_logs el
    INNER JOIN properties p ON el.property_id = p.property_id
    LEFT JOIN reservations r ON el.reservation_id = r.reservation_id
    LEFT JOIN reservation_guests rg ON el.res_guest_id = rg.res_guest_id
    ORDER BY el.event_at DESC
");
$entranceLogs = $stmt->fetchAll();
?>

<?php $assets_path = '../assets';
$page_title = 'Entrance Logs'; ?>
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
              <h6 class="dark:text-white">Entrance/Exit Logs</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="entranceLogsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Visitor Name</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Visitor Type</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Entry Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Event At</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">ID Presented</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Logged By</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Notes</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($entranceLogs)): ?>
                      <tr>
                        <td colspan="9" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No entrance logs found
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($entranceLogs as $log): ?>
                        <tr>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($log['property_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <?php if ($log['reservation_code']): ?>
                              <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($log['reservation_code']); ?></p>
                            <?php else: ?>
                              <p class="mb-0 text-xs leading-tight text-slate-400">Walk-in</p>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <?php if ($log['visitor_name']): ?>
                              <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($log['visitor_name']); ?></p>
                            <?php elseif ($log['first_name']): ?>
                              <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($log['first_name'] . ' ' . $log['last_name']); ?></p>
                            <?php else: ?>
                              <p class="mb-0 text-xs leading-tight text-slate-400">-</p>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <?php
                            $typeColors = [
                              'adult' => 'bg-blue-100 text-blue-600',
                              'child' => 'bg-green-100 text-green-600',
                              'senior' => 'bg-orange-100 text-orange-600',
                              'infant' => 'bg-pink-100 text-pink-600',
                              'visitor' => 'bg-purple-100 text-purple-600',
                              'staff' => 'bg-gray-100 text-gray-600',
                              'delivery' => 'bg-cyan-100 text-cyan-600',
                              'vendor' => 'bg-indigo-100 text-indigo-600'
                            ];
                            $typeClass = $typeColors[$log['visitor_type']] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="text-xs font-semibold <?php echo $typeClass; ?> px-2 py-1 rounded"><?php echo ucfirst($log['visitor_type']); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
                            <?php if ($log['entry_type'] === 'entry'): ?>
                              <span class="text-xs font-semibold bg-green-100 text-green-600 px-2 py-1 rounded">Entry</span>
                            <?php else: ?>
                              <span class="text-xs font-semibold bg-red-100 text-red-600 px-2 py-1 rounded">Exit</span>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo date('M d, Y h:i A', strtotime($log['event_at'])); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo htmlspecialchars($log['id_presented'] ?? '-'); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo $log['logged_by'] ? 'Staff ID: ' . substr($log['logged_by'], 0, 8) : '-'; ?></p>
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
    table = document.getElementById("entranceLogsTable");
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