<?php
require_once '../../config/db.php';

// Fetch all requirement types
$stmt = $pdo->query("
    SELECT 
        rt.*,
        p.property_name
    FROM reservation_requirement_types rt
    LEFT JOIN properties p ON rt.property_id = p.property_id
    WHERE rt.is_active = 1
    ORDER BY rt.sort_order ASC, rt.req_label ASC
");
$requirementTypes = $stmt->fetchAll();
?>

<?php $assets_path = '../assets';
$page_title = 'Reservation Requirement Type'; ?>
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
              <h6 class="dark:text-white">Requirement Types List</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
              <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500" id="requirementTypesTable">
                  <thead class="align-bottom">
                    <tr>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Code</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Label</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Property</th>
                      <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Applies To</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Mandatory</th>
                      <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Active</th>
                      <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Sort Order</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($requirementTypes)): ?>
                      <tr>
                        <td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">
                          No requirement types found
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($requirementTypes as $rt): ?>
                        <tr>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($rt['req_code']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs"><?php echo htmlspecialchars($rt['req_label']); ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo $rt['property_name'] ? htmlspecialchars($rt['property_name']) : '<span class="text-slate-400">All Properties</span>'; ?></p>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
                            <?php
                            $appliesTo = explode(',', $rt['applies_to']);
                            foreach ($appliesTo as $type) {
                              echo '<span class="text-xs font-semibold bg-blue-100 text-blue-600 px-2 py-1 rounded mr-1">' . ucfirst(str_replace('_', ' ', $type)) . '</span>';
                            }
                            ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
                            <?php if ($rt['is_mandatory']): ?>
                              <span class="text-xs font-semibold bg-green-100 text-green-600 px-2 py-1 rounded">Yes</span>
                            <?php else: ?>
                              <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-1 rounded">No</span>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
                            <?php if ($rt['is_active']): ?>
                              <span class="text-xs font-semibold bg-green-100 text-green-600 px-2 py-1 rounded">Active</span>
                            <?php else: ?>
                              <span class="text-xs font-semibold bg-red-100 text-red-600 px-2 py-1 rounded">Inactive</span>
                            <?php endif; ?>
                          </td>
                          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
                            <p class="mb-0 text-xs leading-tight dark:text-white"><?php echo $rt['sort_order']; ?></p>
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
    table = document.getElementById("requirementTypesTable");
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