<?php
require_once '../../config/db.php';

// Fetch all reservation guests with related data
$stmt = $pdo->query("
    SELECT
        rg.*,
        r.reservation_code,
        r.check_in_date,
        r.check_out_date,
        CONCAT(COALESCE(r.walkin_first_name, up.first_name), ' ', COALESCE(r.walkin_last_name, up.last_name)) as primary_guest_name,
        p.property_name
    FROM reservation_guests rg
    INNER JOIN reservations r ON rg.reservation_id = r.reservation_id
    LEFT JOIN user_accounts ua ON r.customer_user_id = ua.user_id
    LEFT JOIN user_profiles up ON r.customer_user_id = up.user_id
    LEFT JOIN properties p ON r.property_id = p.property_id
    ORDER BY rg.created_at DESC
");
$reservationGuests = $stmt->fetchAll();
?>

<?php $assets_path = '../assets';
$page_title = 'Reservation Guests'; ?>
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
              <h6 class="dark:text-white">Reservation Guests List</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="reservationGuestsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Guest Name</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Age</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Gender</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nationality</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">ID Type</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">ID Number</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($reservationGuests)): ?>
                      <tr>
                        <td colspan="9" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No reservation guests found
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($reservationGuests as $guest): ?>
                        <tr>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($guest['reservation_code']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($guest['property_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($guest['first_name'] . ' ' . $guest['last_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <?php
                            $typeColors = [
                              'primary' => 'bg-purple-100 text-purple-600',
                              'companion' => 'bg-blue-100 text-blue-600',
                              'child' => 'bg-green-100 text-green-600',
                              'senior' => 'bg-orange-100 text-orange-600',
                              'infant' => 'bg-pink-100 text-pink-600'
                            ];
                            $typeClass = $typeColors[$guest['guest_type']] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="text-xs font-semibold <?php echo $typeClass; ?> px-2 py-1 rounded"><?php echo ucfirst($guest['guest_type']); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo $guest['age'] ?? '-'; ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo ucfirst($guest['gender'] ?? '-'); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo htmlspecialchars($guest['nationality'] ?? '-'); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo htmlspecialchars($guest['id_type'] ?? '-'); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo htmlspecialchars($guest['id_number'] ?? '-'); ?></p>
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
    table = document.getElementById("reservationGuestsTable");
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