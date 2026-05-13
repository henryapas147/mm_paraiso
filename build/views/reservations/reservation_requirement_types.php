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
            <tbody id="requirementTypesTableBody">
              <tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let requirementTypesData = [];

  document.addEventListener('DOMContentLoaded', function() {
    fetchRequirementTypesData();
  });

  async function fetchRequirementTypesData() {
    try {
      const response = await fetch('../api/reservation_requirements.php?action=types');
      const result = await response.json();
      if (result.success) {
        requirementTypesData = result.data;
        renderRequirementTypesTable();
      } else {
        document.getElementById('requirementTypesTableBody').innerHTML = `<tr><td colspan="7" class="p-4 text-center text-red-400">${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching requirement types:', error);
      document.getElementById('requirementTypesTableBody').innerHTML = `<tr><td colspan="7" class="p-4 text-center text-red-400">Failed to load data</td></tr>`;
    }
  }

  function renderRequirementTypesTable() {
    const tbody = document.getElementById('requirementTypesTableBody');
    if (!tbody) return;

    if (requirementTypesData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-slate-400 dark:text-white/60">No requirement types found</td></tr>`;
      return;
    }

    tbody.innerHTML = requirementTypesData.map(rt => {
      const appliesTo = (rt.applies_to || '').split(',').map(t => t.trim()).filter(Boolean);
      const appliesHtml = appliesTo.map(t =>
        `<span class="text-xs font-semibold bg-blue-100 text-blue-600 px-2 py-1 rounded mr-1 capitalize">${t.replace(/_/g, ' ')}</span>`
      ).join('');

      const mandatoryBadge = rt.is_mandatory == 1
        ? `<span class="text-xs font-semibold bg-green-100 text-green-600 px-2 py-1 rounded">Yes</span>`
        : `<span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-1 rounded">No</span>`;

      const activeBadge = rt.is_active == 1
        ? `<span class="text-xs font-semibold bg-green-100 text-green-600 px-2 py-1 rounded">Active</span>`
        : `<span class="text-xs font-semibold bg-red-100 text-red-600 px-2 py-1 rounded">Inactive</span>`;

      const propertyCell = rt.property_name
        ? rt.property_name
        : `<span class="text-slate-400">All Properties</span>`;

      return `
        <tr class="reqtype-row">
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${rt.req_code || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 font-semibold leading-tight dark:text-white text-xs">${rt.req_label || '-'}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            <p class="mb-0 text-xs leading-tight dark:text-white">${propertyCell}</p>
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap">
            ${appliesHtml || '<span class="text-slate-400 text-xs">-</span>'}
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
            ${mandatoryBadge}
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-center">
            ${activeBadge}
          </td>
          <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap text-right">
            <p class="mb-0 text-xs leading-tight dark:text-white">${rt.sort_order || '-'}</p>
          </td>
        </tr>
      `;
    }).join('');
  }

  function filterTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    document.querySelectorAll('#requirementTypesTable .reqtype-row').forEach(row => {
      row.style.display = row.textContent.toUpperCase().includes(filter) ? '' : 'none';
    });
  }
</script>
