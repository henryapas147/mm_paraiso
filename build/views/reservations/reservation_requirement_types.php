<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">

<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0
  }

  body {
    font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
    background: #f5f5f5;
    color: #1a1a1a
  }

  .page {
    padding: 24px;
    max-width: 100%;
    margin: 0 auto
  }

  .card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px
  }

  .card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap
  }

  .card-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 16px;
    font-weight: 600;
    font-style: italic
  }

  .toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 6px;
    border: 1px solid #ccc;
    background: #fff;
    color: #1a1a1a;
    cursor: pointer;
    transition: background .15s
  }

  .btn:hover {
    background: #f5f5f5
  }

  .btn-primary {
    background: #185FA5;
    color: #fff;
    border-color: #185FA5
  }

  .btn-primary:hover {
    background: #0C447C;
    border-color: #0C447C
  }

  .btn-sm {
    padding: 4px 8px;
    font-size: 11px
  }

  .btn-danger {
    background: #DC2626;
    color: #fff;
    border-color: #DC2626
  }

  .btn-danger:hover {
    background: #B91C1C;
    border-color: #B91C1C
  }

  .search-wrap {
    position: relative
  }

  .search-wrap i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-size: 15px;
    pointer-events: none
  }

  .search-input {
    padding: 7px 12px 7px 34px;
    font-size: 13px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background: #fff;
    color: #1a1a1a;
    outline: none;
    width: 220px;
    min-width: 160px
  }

  .search-input:focus {
    border-color: #185FA5
  }

  .table-wrap {
    overflow-x: auto
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px
  }

  thead th {
    padding: 10px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 1px solid #e0e0e0;
    white-space: nowrap
  }

  thead th.center {
    text-align: center
  }

  thead th.right {
    text-align: right
  }

  .actions-cell {
    display: flex;
    align-items: center;
    gap: 6px
  }

  tbody tr {
    border-bottom: 1px solid #e0e0e0;
    transition: background .1s
  }

  tbody tr:last-child {
    border-bottom: none
  }

  tbody tr:hover {
    background: #f9f9f9
  }

  td {
    padding: 11px 16px;
    vertical-align: middle;
    white-space: nowrap
  }

  .badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize
  }

  .badge.yes {
    background: #E8F5E9;
    color: #2E7D32
  }

  .badge.no {
    background: #F5F5F5;
    color: #666
  }

  .badge.active {
    background: #E8F5E9;
    color: #2E7D32
  }

  .badge.inactive {
    background: #FFEBEE;
    color: #C62828
  }

  .badge.applies {
    background: #E3F2FD;
    color: #1565C0;
    margin-right: 4px
  }

  /* Modal */
  .overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .45);
    z-index: 9000;
    align-items: center;
    justify-content: center;
    padding: 20px
  }

  .overlay.open {
    display: flex
  }

  .modal {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e0e0e0;
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 24px 48px rgba(0, 0, 0, .18)
  }

  .modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between
  }

  .modal-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 16px;
    font-weight: 600;
    font-style: italic
  }

  .modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #666;
    font-size: 20px;
    line-height: 1;
    padding: 4px
  }

  .modal-close:hover {
    color: #1a1a1a
  }

  .modal-body {
    padding: 24px
  }

  .modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: flex-end;
    gap: 8px
  }

  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 6px
  }

  .form-group.full {
    grid-column: 1/-1
  }

  label {
    font-size: 12px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05em
  }

  label .req {
    color: #A32D2D;
    margin-left: 2px
  }

  input,
  select,
  textarea {
    font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
    font-size: 13px;
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    outline: none;
    transition: border-color .15s
  }

  input:focus,
  select:focus,
  textarea:focus {
    border-color: #185FA5
  }

  .checkbox-group {
    display: flex;
    flex-wrap: wrap;
    gap: 12px
  }

  .checkbox-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px
  }

  .checkbox-item input {
    width: auto;
    margin: 0
  }

  .empty-state {
    padding: 48px 24px;
    text-align: center;
    color: #666
  }

  .empty-state i {
    font-size: 32px;
    margin-bottom: 12px;
    display: block;
    color: #ccc
  }

  /* Custom Alert Modal */
  .alert-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .5);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px
  }

  .alert-overlay.open {
    display: flex
  }

  .alert-modal {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, .15)
  }

  .alert-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    gap: 12px
  }

  .alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px
  }

  .alert-icon.success {
    background: #E8F5E9;
    color: #2E7D32
  }

  .alert-icon.error {
    background: #FFEBEE;
    color: #C62828
  }

  .alert-icon.warning {
    background: #FFFBEB;
    color: #D97706
  }

  .alert-icon.info {
    background: #EFF6FF;
    color: #2563EB
  }

  .alert-title {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a
  }

  .alert-body {
    padding: 20px;
    font-size: 13px;
    color: #666;
    line-height: 1.5
  }

  .alert-footer {
    padding: 12px 20px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: flex-end;
    gap: 8px
  }

  .alert-btn {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 6px;
    border: 1px solid #ccc;
    background: #fff;
    color: #1a1a1a;
    cursor: pointer;
    transition: background .15s
  }

  .alert-btn:hover {
    background: #f5f5f5
  }

  .alert-btn.primary {
    background: #185FA5;
    color: #fff;
    border-color: #185FA5
  }

  .alert-btn.primary:hover {
    background: #0C447C;
    border-color: #0C447C
  }

  /* ── Responsive ──────────────────────────────────── */
  @media (max-width: 768px) {
    .card-header {
      flex-direction: column;
      align-items: stretch;
      gap: 16px;
      padding: 16px 18px;
    }
    .toolbar {
      flex-wrap: wrap;
      width: 100%;
    }
    .search-input {
      width: 100%;
      flex: 1;
    }
  }

  @media (max-width: 480px) {
    .toolbar {
      flex-direction: column;
      align-items: stretch;
    }
    .search-wrap {
      width: 100%;
    }
    .btn-primary {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 480px) {
    .page {
      padding: 16px;
    }
  }

  @media (max-width: 640px) {
    thead th {
      padding: 8px 12px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    td {
      padding: 8px 12px;
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .badge {
      font-size: 10px;
      padding: 2px 8px;
    }
  }

  @media (max-width: 640px) {
    .btn-sm {
      padding: 3px 6px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .empty-state {
      padding: 40px 20px;
    }
  }

  @media (max-width: 640px) {
    .empty-state i {
      font-size: 28px;
    }
  }

  @media (max-width: 768px) {
    .modal {
      max-width: 90vw;
    }
  }

  @media (max-width: 480px) {
    .modal {
      max-width: none;
      max-height: 95vh;
      border-radius: 12px;
    }
  }

  @media (max-width: 768px) {
    .modal-body {
      padding: 20px;
    }
  }

  @media (max-width: 480px) {
    .modal-body {
      padding: 16px;
      overflow-y: auto;
      overflow-x: hidden;
    }
  }

  @media (max-width: 480px) {
    .modal-footer {
      flex-direction: column;
    }
  }

  @media (max-width: 480px) {
    .modal-footer .btn,
    .modal-footer .btn-primary {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 640px) {
    .form-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .form-group.full {
      grid-column: auto;
    }
  }

  @media (max-width: 640px) {
    .modal-header {
      padding: 16px 18px;
    }
  }

  @media (max-width: 640px) {
    .modal-title {
      font-size: 14px;
    }
  }

  @media (max-width: 640px) {
    .modal-close {
      font-size: 18px;
      padding: 6px;
    }
  }

  @media (max-width: 640px) {
    label {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    input,
    select,
    textarea {
      font-size: 12px;
      padding: 7px 10px;
    }
  }

  @media (max-width: 640px) {
    .checkbox-group {
      gap: 8px;
    }
  }

  @media (max-width: 640px) {
    .checkbox-item {
      font-size: 12px;
    }
  }

  @media (max-width: 768px) {
    .alert-modal {
      max-width: 90vw;
    }
  }

  @media (max-width: 480px) {
    .alert-modal {
      max-width: none;
      padding: 20px;
    }
  }

  @media (max-width: 480px) {
    .alert-footer {
      flex-direction: column;
    }
  }

  @media (max-width: 480px) {
    .alert-btn {
      width: 100%;
      justify-content: center;
    }
  }
</style>

<!-- PAGE CONTENT -->

<div class="page">
  <div class="card">
    <div class="card-header">
      <div class="card-title">Requirement Types</div>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Search types..." onkeyup="filterTable()">
        </div>
        <button class="btn btn-primary" onclick="openModal()">
          <i class="fa-solid fa-plus"></i> Add New
        </button>
      </div>
    </div>
    <div class="table-wrap">
      <table id="requirementTypesTable">
        <thead>
          <tr>
            <th>Code</th>
            <th>Label</th>
            <th>Property</th>
            <th>Applies To</th>
            <th class="center">Mandatory</th>
            <th class="center">Active</th>
            <th class="right">Sort Order</th>
            <th class="center">Actions</th>
          </tr>
        </thead>
        <tbody id="requirementTypesTableBody">
          <tr>
            <td colspan="8" class="empty-state">
              <i class="fa-regular fa-clipboard"></i>
              Loading...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add/Edit Modal -->
<div class="overlay" id="requirementTypeModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modalTitle">Add Requirement Type</div>
      <button class="modal-close" onclick="closeModal()">&times;</button>
    </div>
    <div class="modal-body">
      <form id="requirementTypeForm" class="form-grid">
        <input type="hidden" id="reqTypeId">
        <div class="form-group full">
          <label>Code <span class="req">*</span></label>
          <input type="text" id="reqCode" required placeholder="e.g., REQ-VALID-ID">
        </div>
        <div class="form-group full">
          <label>Label <span class="req">*</span></label>
          <input type="text" id="reqLabel" required placeholder="e.g., Valid Government ID">
        </div>
        <div class="form-group">
          <label>Property</label>
          <select id="reqProperty">
            <option value="">All Properties</option>
          </select>
        </div>
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" id="reqSortOrder" value="0" min="0">
        </div>
        <div class="form-group">
          <label>Applies To</label>
          <div class="checkbox-group">
            <label class="checkbox-item">
              <input type="checkbox" id="appliesOvernight" value="overnight"> Overnight
            </label>
            <label class="checkbox-item">
              <input type="checkbox" id="appliesHourly" value="hourly"> Hourly
            </label>
            <label class="checkbox-item">
              <input type="checkbox" id="appliesWalkin" value="walk_in"> Walk-in
            </label>
            <label class="checkbox-item">
              <input type="checkbox" id="appliesOnline" value="online"> Online
            </label>
          </div>
        </div>
        <div class="form-group">
          <label>Options</label>
          <div class="checkbox-group">
            <label class="checkbox-item">
              <input type="checkbox" id="reqMandatory"> Mandatory
            </label>
            <label class="checkbox-item">
              <input type="checkbox" id="reqActive" checked> Active
            </label>
          </div>
        </div>
        <div class="form-group full">
          <label>Description</label>
          <textarea id="reqDescription" rows="3" placeholder="Optional description..."></textarea>
        </div>
        <div class="form-group full">
          <label>Template File (for guest reference)</label>
          <input type="file" id="reqTemplateFile" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="font-size: 0.8rem;">
          <div style="font-size: 0.7rem; color: #9CA3AF; margin-top: 0.25rem;">Upload a template form or document that guests can download as reference (PDF, JPG, PNG, Word - Max 10MB)</div>
          <div id="templatePreview" style="margin-top: 0.5rem; display: none;">
            <a href="#" id="templateLink" target="_blank" style="font-size: 0.8rem; color: #185FA5; text-decoration: none;">
              <i class="fa-solid fa-file"></i> <span id="templateFileName">View current template</span>
            </a>
            <button type="button" onclick="removeTemplate()" style="margin-left: 0.5rem; font-size: 0.7rem; padding: 2px 6px; border: 1px solid #DC2626; background: #FEF2F2; color: #DC2626; border-radius: 4px; cursor: pointer;">Remove</button>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal()">Cancel</button>
      <button class="btn btn-primary" onclick="saveRequirementType()">Save</button>
    </div>
  </div>
</div>

<!-- Custom Alert Modal -->
<div class="alert-overlay" id="alertModal">
  <div class="alert-modal">
    <div class="alert-header">
      <div class="alert-icon" id="alertIcon">✓</div>
      <div class="alert-title" id="alertTitle">Success</div>
    </div>
    <div class="alert-body" id="alertMessage">
      Operation completed successfully.
    </div>
    <div class="alert-footer">
      <button class="alert-btn primary" onclick="closeAlert()">OK</button>
    </div>
  </div>
</div>

<!-- View Modal -->
<div class="overlay" id="viewModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">View Requirement Type</div>
      <button class="modal-close" onclick="closeViewModal()">&times;</button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="form-group full">
          <label>Code</label>
          <div id="viewCode" style="padding: 8px 12px; background: #F9FAFB; border-radius: 6px; font-size: 13px;">—</div>
        </div>
        <div class="form-group full">
          <label>Label</label>
          <div id="viewLabel" style="padding: 8px 12px; background: #F9FAFB; border-radius: 6px; font-size: 13px;">—</div>
        </div>
        <div class="form-group">
          <label>Property</label>
          <div id="viewProperty" style="padding: 8px 12px; background: #F9FAFB; border-radius: 6px; font-size: 13px;">—</div>
        </div>
        <div class="form-group">
          <label>Sort Order</label>
          <div id="viewSortOrder" style="padding: 8px 12px; background: #F9FAFB; border-radius: 6px; font-size: 13px;">—</div>
        </div>
        <div class="form-group">
          <label>Applies To</label>
          <div id="viewAppliesTo" style="padding: 8px 12px; background: #F9FAFB; border-radius: 6px; font-size: 13px;">—</div>
        </div>
        <div class="form-group">
          <label>Options</label>
          <div style="display: flex; gap: 8px;">
            <span id="viewMandatory" class="badge">—</span>
            <span id="viewActive" class="badge">—</span>
          </div>
        </div>
        <div class="form-group full">
          <label>Description</label>
          <div id="viewDescription" style="padding: 8px 12px; background: #F9FAFB; border-radius: 6px; font-size: 13px; min-height: 60px;">—</div>
        </div>
        <div class="form-group full">
          <label>Template File</label>
          <div id="viewTemplate" style="padding: 8px 12px; background: #F9FAFB; border-radius: 6px; font-size: 13px;">—</div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary" onclick="closeViewModal()">Close</button>
    </div>
  </div>
</div>

<script>
  let requirementTypesData = [];
  let propertiesData = [];
  let csrfToken = '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>';

  document.addEventListener('DOMContentLoaded', function() {
    fetchRequirementTypesData();
    fetchPropertiesData();
  });

  // Custom Alert Functions
  function showAlert(type, title, message) {
    const modal = document.getElementById('alertModal');
    const icon = document.getElementById('alertIcon');
    const titleEl = document.getElementById('alertTitle');
    const messageEl = document.getElementById('alertMessage');

    // Reset classes
    icon.className = 'alert-icon';

    // Set icon and styling based on type
    switch(type) {
      case 'success':
        icon.classList.add('success');
        icon.textContent = '✓';
        break;
      case 'error':
        icon.classList.add('error');
        icon.textContent = '✕';
        break;
      case 'warning':
        icon.classList.add('warning');
        icon.textContent = '⚠';
        break;
      case 'info':
        icon.classList.add('info');
        icon.textContent = 'ℹ';
        break;
      default:
        icon.classList.add('success');
        icon.textContent = '✓';
    }

    titleEl.textContent = title;
    messageEl.textContent = message;
    modal.classList.add('open');
  }

  function closeAlert() {
    document.getElementById('alertModal').classList.remove('open');
  }

  // Confirm Dialog
  function showConfirm(message, onConfirm) {
    if (confirm(message)) {
      onConfirm();
    }
  }

  async function fetchPropertiesData() {
    try {
      const response = await fetch('../api/properties.php?action=list&limit=1000');
      const result = await response.json();
      if (result.success && result.data && Array.isArray(result.data.data)) {
        propertiesData = result.data.data;
        populatePropertyDropdown();
      } else {
        console.error('Invalid properties response:', result);
      }
    } catch (error) {
      console.error('Error fetching properties:', error);
    }
  }

  function populatePropertyDropdown() {
    const select = document.getElementById('reqProperty');
    select.innerHTML = '<option value="">All Properties</option>';
    if (Array.isArray(propertiesData)) {
      propertiesData.forEach(prop => {
        select.innerHTML += `<option value="${prop.property_id}">${prop.property_name}</option>`;
      });
    }
  }

  async function fetchRequirementTypesData() {
    try {
      const response = await fetch('../api/reservation_requirements.php?action=types');
      const result = await response.json();
      if (result.success) {
        requirementTypesData = result.data;
        renderRequirementTypesTable();
      } else {
        document.getElementById('requirementTypesTableBody').innerHTML = `<tr><td colspan="8" class="empty-state"><i>⚠</i>${result.message}</td></tr>`;
      }
    } catch (error) {
      console.error('Error fetching requirement types:', error);
      document.getElementById('requirementTypesTableBody').innerHTML = `<tr><td colspan="8" class="empty-state"><i>⚠</i>Failed to load data</td></tr>`;
    }
  }

  function renderRequirementTypesTable() {
    const tbody = document.getElementById('requirementTypesTableBody');
    if (!tbody) return;

    if (requirementTypesData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8" class="empty-state"><i class="fa-regular fa-clipboard"></i>No requirement types found</td></tr>`;
      return;
    }

    tbody.innerHTML = requirementTypesData.map(rt => {
      const appliesTo = (rt.applies_to || '').split(',').map(t => t.trim()).filter(Boolean);
      const appliesHtml = appliesTo.map(t =>
        `<span class="badge applies">${t.replace(/_/g, ' ')}</span>`
      ).join('');

      const mandatoryBadge = rt.is_mandatory == 1
        ? `<span class="badge yes">Yes</span>`
        : `<span class="badge no">No</span>`;

      const activeBadge = rt.is_active == 1
        ? `<span class="badge active">Active</span>`
        : `<span class="badge inactive">Inactive</span>`;

      const propertyCell = rt.property_name
        ? rt.property_name
        : `<span style="color:#999">All Properties</span>`;

      return `
        <tr class="reqtype-row">
          <td><strong>${rt.req_code || '-'}</strong></td>
          <td><strong>${rt.req_label || '-'}</strong></td>
          <td>${propertyCell}</td>
          <td>${appliesHtml || '<span style="color:#999">-</span>'}</td>
          <td style="text-align:center">${mandatoryBadge}</td>
          <td style="text-align:center">${activeBadge}</td>
          <td style="text-align:right">${rt.sort_order || '-'}</td>
          <td style="text-align:center">
            <div class="actions-cell" style="justify-content:center">
              <button class="btn btn-sm" onclick="viewRequirementType('${rt.req_type_id}')" title="View">
                <i class="fa-solid fa-eye"></i>
              </button>
              <button class="btn btn-sm" onclick="editRequirementType('${rt.req_type_id}')" title="Edit">
                <i class="fa-solid fa-pen"></i>
              </button>
              <button class="btn btn-sm btn-danger" onclick="deleteRequirementType('${rt.req_type_id}')" title="Delete">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
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

  function openModal(isEdit = false) {
    document.getElementById('modalTitle').textContent = isEdit ? 'Edit Requirement Type' : 'Add Requirement Type';
    document.getElementById('requirementTypeModal').classList.add('open');
    if (!isEdit) {
      document.getElementById('requirementTypeForm').reset();
      document.getElementById('reqTypeId').value = '';
      document.getElementById('reqActive').checked = true;
      document.getElementById('templatePreview').style.display = 'none';
    }
  }

  function closeModal() {
    document.getElementById('requirementTypeModal').classList.remove('open');
  }

  function removeTemplate() {
    document.getElementById('reqTemplateFile').value = '';
    document.getElementById('templatePreview').style.display = 'none';
  }

  function viewRequirementType(reqTypeId) {
    const rt = requirementTypesData.find(r => r.req_type_id == reqTypeId);
    if (!rt) {
      console.error('Requirement type not found:', reqTypeId);
      return;
    }

    document.getElementById('viewCode').textContent = rt.req_code || '—';
    document.getElementById('viewLabel').textContent = rt.req_label || '—';
    document.getElementById('viewProperty').textContent = rt.property_name || 'All Properties';
    document.getElementById('viewSortOrder').textContent = rt.sort_order || '0';

    // Applies to
    const appliesTo = (rt.applies_to || '').split(',').map(t => t.trim()).filter(Boolean);
    document.getElementById('viewAppliesTo').textContent = appliesTo.length > 0 ? appliesTo.join(', ') : '—';

    // Mandatory badge
    const mandatoryBadge = document.getElementById('viewMandatory');
    mandatoryBadge.textContent = rt.is_mandatory == 1 ? 'Mandatory' : 'Optional';
    mandatoryBadge.className = 'badge ' + (rt.is_mandatory == 1 ? 'yes' : 'no');

    // Active badge
    const activeBadge = document.getElementById('viewActive');
    activeBadge.textContent = rt.is_active == 1 ? 'Active' : 'Inactive';
    activeBadge.className = 'badge ' + (rt.is_active == 1 ? 'active' : 'inactive');

    // Description
    document.getElementById('viewDescription').textContent = rt.description || '—';

    // Template
    const templateDiv = document.getElementById('viewTemplate');
    if (rt.template_url) {
      templateDiv.innerHTML = `<a href="${rt.template_url}" target="_blank" style="color: #185FA5; text-decoration: none;"><i class="fa-solid fa-file"></i> View Template</a>`;
    } else {
      templateDiv.textContent = '—';
    }

    document.getElementById('viewModal').classList.add('open');
  }

  function closeViewModal() {
    document.getElementById('viewModal').classList.remove('open');
  }

  function editRequirementType(reqTypeId) {
    const rt = requirementTypesData.find(r => r.req_type_id == reqTypeId);
    if (!rt) {
      console.error('Requirement type not found:', reqTypeId);
      return;
    }

    document.getElementById('reqTypeId').value = rt.req_type_id;
    document.getElementById('reqCode').value = rt.req_code || '';
    document.getElementById('reqLabel').value = rt.req_label || '';
    document.getElementById('reqProperty').value = rt.property_id || '';
    document.getElementById('reqSortOrder').value = rt.sort_order || 0;
    document.getElementById('reqMandatory').checked = rt.is_mandatory == 1;
    document.getElementById('reqActive').checked = rt.is_active == 1;
    document.getElementById('reqDescription').value = rt.description || '';

    // Show template preview if exists
    if (rt.template_url) {
      document.getElementById('templatePreview').style.display = 'block';
      document.getElementById('templateLink').href = rt.template_url;
      document.getElementById('templateFileName').textContent = 'View current template';
    } else {
      document.getElementById('templatePreview').style.display = 'none';
    }

    // Set applies to checkboxes
    const appliesTo = (rt.applies_to || '').split(',').map(t => t.trim()).filter(Boolean);
    document.getElementById('appliesOvernight').checked = appliesTo.includes('overnight');
    document.getElementById('appliesHourly').checked = appliesTo.includes('hourly');
    document.getElementById('appliesWalkin').checked = appliesTo.includes('walk_in');
    document.getElementById('appliesOnline').checked = appliesTo.includes('online');

    openModal(true);
  }

  async function deleteRequirementType(reqTypeId) {
    showConfirm('Are you sure you want to delete this requirement type?', async () => {
      try {
        const response = await fetch('../api/reservation_requirement_types.php?action=delete', {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
          },
          body: JSON.stringify({ req_type_id: parseInt(reqTypeId) })
        });
        const result = await response.json();

        if (result.success) {
          showAlert('success', 'Success', 'Requirement type deleted successfully');
          fetchRequirementTypesData();
        } else {
          showAlert('error', 'Error', 'Failed to delete: ' + (result.message || 'Unknown error'));
        }
      } catch (error) {
        console.error('Error deleting requirement type:', error);
        showAlert('error', 'Error', 'Failed to delete requirement type');
      }
    });
  }

  async function saveRequirementType() {
    const reqTypeId = document.getElementById('reqTypeId').value;
    const reqCode = document.getElementById('reqCode').value.trim();
    const reqLabel = document.getElementById('reqLabel').value.trim();
    const reqProperty = document.getElementById('reqProperty').value;
    const reqSortOrder = document.getElementById('reqSortOrder').value;
    const reqMandatory = document.getElementById('reqMandatory').checked ? 1 : 0;
    const reqActive = document.getElementById('reqActive').checked ? 1 : 0;
    const reqDescription = document.getElementById('reqDescription').value.trim();
    const templateFile = document.getElementById('reqTemplateFile').files[0];

    // Get applies to values
    const appliesTo = [];
    if (document.getElementById('appliesOvernight').checked) appliesTo.push('overnight');
    if (document.getElementById('appliesHourly').checked) appliesTo.push('hourly');
    if (document.getElementById('appliesWalkin').checked) appliesTo.push('walk_in');
    if (document.getElementById('appliesOnline').checked) appliesTo.push('online');

    if (!reqCode || !reqLabel) {
      showAlert('warning', 'Validation Error', 'Code and Label are required');
      return;
    }

    try {
      // First, save the requirement type to get the ID (if creating)
      const data = {
        req_type_id: reqTypeId ? parseInt(reqTypeId) : null,
        req_code: reqCode,
        req_label: reqLabel,
        property_id: reqProperty || null,
        sort_order: parseInt(reqSortOrder) || 0,
        is_mandatory: reqMandatory,
        is_active: reqActive,
        applies_to: appliesTo.join(','),
        description: reqDescription,
        template_url: null
      };

      const action = reqTypeId ? 'update' : 'create';
      const response = await fetch(`../api/reservation_requirement_types.php?action=${action}`, {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify(data)
      });
      const result = await response.json();

      if (!result.success) {
        showAlert('error', 'Error', 'Failed to save: ' + (result.message || 'Unknown error'));
        return;
      }

      const savedReqTypeId = reqTypeId ? parseInt(reqTypeId) : result.req_type_id;

      // If a template file was selected, upload it
      if (templateFile) {
        const formData = new FormData();
        formData.append('req_type_id', savedReqTypeId);
        formData.append('file', templateFile);
        formData.append('csrf_token', csrfToken);

        const uploadResponse = await fetch('../api/reservation_requirement_types.php?action=upload', {
          method: 'POST',
          body: formData
        });
        const uploadResult = await uploadResponse.json();

        if (!uploadResult.success) {
          showAlert('warning', 'Warning', 'Requirement type saved but template upload failed: ' + (uploadResult.message || 'Unknown error'));
        }
      }

      showAlert('success', 'Success', reqTypeId ? 'Requirement type updated successfully' : 'Requirement type created successfully');
      closeModal();
      fetchRequirementTypesData();
    } catch (error) {
      console.error('Error saving requirement type:', error);
      showAlert('error', 'Error', 'Failed to save requirement type');
    }
  }
</script>
