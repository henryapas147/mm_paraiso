<?php
require_once '../../config/db.php';

// Fetch all reservation rooms with related data
$stmt = $pdo->query("
    SELECT 
        rr.*,
        r.reservation_code,
        r.reservation_status,
        r.customer_user_id,
        r.walkin_first_name,
        r.walkin_last_name,
        rm.room_number,
        rm.room_name,
        rc.category_name,
        p.property_name
    FROM reservation_rooms rr
    INNER JOIN reservations r ON rr.reservation_id = r.reservation_id
    INNER JOIN rooms rm ON rr.room_id = rm.room_id
    INNER JOIN room_categories rc ON rr.category_id = rc.category_id
    INNER JOIN properties p ON r.property_id = p.property_id
    ORDER BY rr.created_at DESC
");
$reservationRooms = $stmt->fetchAll();
?>

<?php $assets_path = '../assets';
$page_title = 'Reservation Rooms'; ?>
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
              <h6 class="dark:text-white">Reservation Rooms List</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="reservationRoomsTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Reservation</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Price/Night</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nights/Hours</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room Total</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Room Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($reservationRooms)): ?>
                      <tr>
                        <td colspan="9" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No reservation rooms found
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($reservationRooms as $rr): ?>
                        <tr>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($rr['reservation_code']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($rr['property_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($rr['room_number'] . ' - ' . $rr['room_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo htmlspecialchars($rr['category_name']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <span class="text-xs font-semibold bg-blue-100 text-blue-600 px-2 py-1 rounded"><?php echo ucfirst($rr['booking_type']); ?></span>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo number_format($rr['price_per_night'], 2); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
                            <?php if ($rr['booking_type'] === 'hourly'): ?>
                              <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo $rr['num_hours'] ?? '-'; ?> hrs</p>
                            <?php else: ?>
                              <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo $rr['num_nights']; ?> nights</p>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo number_format($rr['room_total'], 2); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
                            <?php
                            $statusColors = [
                              'reserved' => 'bg-blue-100 text-blue-600',
                              'checked_in' => 'bg-green-100 text-green-600',
                              'checked_out' => 'bg-gray-100 text-gray-600',
                              'cancelled' => 'bg-red-100 text-red-600',
                              'no_show' => 'bg-orange-100 text-orange-600'
                            ];
                            $statusClass = $statusColors[$rr['room_status']] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="text-xs font-semibold <?php echo $statusClass; ?> px-2 py-1 rounded"><?php echo ucfirst(str_replace('_', ' ', $rr['room_status'])); ?></span>
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
    table = document.getElementById("reservationRoomsTable");
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