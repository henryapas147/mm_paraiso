<?php
require_once '../../config/db.php';

// Fetch all reservation requirements with related data
$stmt = $pdo->query("
    SELECT 
        rr.*,
        r.reservation_code,
        rt.req_code,
        rt.req_label,
        rt.req_type_id,
        rg.first_name,
        rg.last_name,
        rg.guest_type
    FROM reservation_requirements rr
    INNER JOIN reservations r ON rr.reservation_id = r.reservation_id
    INNER JOIN reservation_requirement_types rt ON rr.req_type_id = rt.req_type_id
    LEFT JOIN reservation_guests rg ON rr.res_guest_id = rg.res_guest_id
    ORDER BY rr.created_at DESC
");
$reservationRequirements = $stmt->fetchAll();
?>

<?php $assets_path = '../assets'; $page_title = 'Reservation Requirements'; ?>
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
                <h6 class="dark:text-white">Reservation Requirements List</h6>
              </div>
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="reservationRequirementsTable">
                    <thead class="align-bottom">
                      <tr>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Requirement</th>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Guest</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">File</th>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Notes</th>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reviewed By</th>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reviewed At</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($reservationRequirements)): ?>
                      <tr>
                        <td colspan="8" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No reservation requirements found
                        </td>
                      </tr>
                      <?php else: ?>
                        <?php foreach ($reservationRequirements as $req): ?>
                        <tr>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($req['reservation_code']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($req['req_label']); ?></p>
                            <p class="mb-0 text-xs leading-tight text-slate-400"><?php echo htmlspecialchars($req['req_code']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <?php if ($req['first_name']): ?>
                              <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo htmlspecialchars($req['first_name'] . ' ' . $req['last_name']); ?></p>
                              <p class="mb-0 text-xs leading-tight text-slate-400"><?php echo ucfirst($req['guest_type']); ?></p>
                            <?php else: ?>
                              <p class="mb-0 text-xs leading-tight text-slate-400">-</p>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
                            <?php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-600',
                                'submitted' => 'bg-blue-100 text-blue-600',
                                'approved' => 'bg-green-100 text-green-600',
                                'rejected' => 'bg-red-100 text-red-600'
                            ];
                            $statusClass = $statusColors[$req['status']] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="text-xs font-semibold <?php echo $statusClass; ?> px-2 py-1 rounded"><?php echo ucfirst($req['status']); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <?php if ($req['file_url']): ?>
                              <a href="<?php echo htmlspecialchars($req['file_url']); ?>" target="_blank" class="text-xs text-blue-500 hover:underline">View File</a>
                            <?php else: ?>
                              <p class="mb-0 text-xs leading-tight text-slate-400">No file</p>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo htmlspecialchars($req['notes'] ?? '-'); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo $req['reviewed_by'] ? 'Staff ID: ' . substr($req['reviewed_by'], 0, 8) : '-'; ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo $req['reviewed_at'] ? date('M d, Y h:i A', strtotime($req['reviewed_at'])) : '-'; ?></p>
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
      table = document.getElementById("reservationRequirementsTable");
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