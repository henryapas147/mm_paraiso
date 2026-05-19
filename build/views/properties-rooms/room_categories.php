<?php
// Helper function to generate UUID
function generateUUID()
{
  return sprintf(
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
}
?>

<style>
  :root {
  --color-background-primary: #ffffff;
  --color-background-secondary: #f8fafc;
  --color-border-primary: #185fa5;
  --color-border-secondary: #e2e8f0;
  --color-border-tertiary: #cbd5e1;
  --color-text-primary: #1e293b;
  --color-text-secondary: #64748b;
  }
  .rc-page { padding: 24px; max-width: 100%; margin: 0 auto; overflow-x: hidden; }
  .rc-card {
  background: var(--color-background-primary);
  border: 0.5px solid var(--color-border-tertiary);
  border-radius: 12px;
  margin-bottom: 0;
  }
  .rc-card-header {
  padding: 16px 20px;
  border-bottom: 0.5px solid var(--color-border-tertiary);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
  }
  .rc-card-title { font-size: 16px; font-weight: 500; }
  .rc-toolbar { display: flex; align-items: center; gap: 8px; flex-wrap: nowrap; }
  .rc-search-wrap {
  display: flex;
  align-items: center;
  border: 0.5px solid var(--color-border-tertiary);
  border-radius: 6px;
  padding: 4px 10px 4px 30px;
  background: #f9fafb;
  position: relative;
  min-width: 200px;
  }
  .rc-search-wrap i {
  position: absolute;
  left: 8px;
  color: var(--color-text-secondary);
  font-size: 12px;
  }
  .rc-search-input {
  border: none;
  outline: none;
  background: transparent;
  font-size: 13px;
  width: 100%;
  color: var(--color-text-primary);
  }
  .rc-filter-select {
  border: 0.5px solid var(--color-border-tertiary);
  border-radius: 6px;
  padding: 6px 10px;
  font-size: 13px;
  color: var(--color-text-primary);
  outline: none;
  background: #f9fafb;
  }
  .rc-btn {
  border: 0.5px solid var(--color-border-secondary);
  border-radius: 6px;
  padding: 6px 12px;
  font-size: 13px;
  font-weight: 500;
  color: var(--color-text-primary);
  background: #ffffff;
  cursor: pointer;
  transition: all 0.18s ease;
  }
  .rc-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
  .rc-btn-primary {
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  color: white;
  border: none;
  }
  .rc-btn-primary:hover { opacity: 0.9; box-shadow: 0 2px 8px rgba(59,130,246,0.25); }
  .rc-table-wrap { overflow-x: auto; }
  .rc-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
  }
  .rc-table th {
  text-align: left;
  padding: 10px 12px;
  border-bottom: 0.5px solid var(--color-border-secondary);
  color: var(--color-text-secondary);
  font-size: 12px;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  background: #f9fafb;
  }
  .rc-table td {
  padding: 10px 12px;
  border-bottom: 0.5px solid var(--color-border-secondary);
  color: var(--color-text-primary);
  }
  .rc-table tr:hover td { background: #f8fafc; }
  .rc-name { font-size: 13px; font-weight: 500; }
  .rc-code { font-size: 12px; color: var(--color-text-secondary); }
  .rc-text { color: var(--color-text-primary); font-size: 13px; }
  .rc-status-badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  }
  .rc-status-active { background: #dcfce7; color: #166534; }
  .rc-status-inactive { background: #e5e7eb; color: #374151; }
  .rc-actions { display: flex; gap: 4px; justify-content: flex-end; }
  .rc-icon-btn {
  border: 0.5px solid var(--color-border-secondary);
  border-radius: 6px;
  width: 28px;
  height: 28px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  background: #ffffff;
  color: var(--color-text-secondary);
  font-size: 12px;
  transition: all 0.18s ease;
  }
  .rc-icon-btn.view:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }
  .rc-icon-btn.edit:hover { border-color: #f59e0b; color: #f59e0b; background: #fffbeb; }
  .rc-icon-btn.del:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
  .rc-empty-state { text-align: center; padding: 24px; color: var(--color-text-secondary); font-size: 13px; }
  .rc-modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.4);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.18s ease;
  }
  .rc-modal-backdrop.open { opacity: 1; pointer-events: auto; }
  .rc-modal {
  background: #ffffff;
  border-radius: 12px;
  width: 90%;
  max-width: 700px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 10px 40px rgba(0,0,0,0.15);
  transform: scale(0.96) translateY(10px);
  transition: transform 0.18s ease;
  }
  .rc-modal-backdrop.open .rc-modal { transform: scale(1) translateY(0); }
  .rc-modal-header {
  padding: 14px 18px;
  border-bottom: 0.5px solid var(--color-border-secondary);
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  background: #ffffff;
  z-index: 1;
  }
  .rc-modal-title { font-size: 15px; font-weight: 500; }
  .rc-modal-close {
  border: none;
  background: none;
  cursor: pointer;
  color: var(--color-text-secondary);
  font-size: 16px;
  padding: 4px;
  border-radius: 4px;
  }
  .rc-modal-close:hover { background: #f1f5f9; color: var(--color-text-primary); }
  .rc-modal-body { padding: 16px 18px; }
  .rc-modal-footer {
  padding: 12px 18px;
  border-top: 0.5px solid var(--color-border-secondary);
  display: flex;
  gap: 8px;
  justify-content: flex-end;
  position: sticky;
  bottom: 0;
  background: #ffffff;
  }
  .rc-section-label {
  font-size: 12px;
  font-weight: 500;
  color: var(--color-text-secondary);
  text-transform: uppercase;
  letter-spacing: 0.3px;
  margin: 0 0 10px;
  }
  .rc-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px; }
  .rc-form-group { display: flex; flex-direction: column; gap: 4px; }
  .rc-form-group.full { grid-column: 1 / -1; }
  .rc-form-group label { font-size: 12px; font-weight: 500; color: var(--color-text-secondary); }
  .rc-form-group input,
  .rc-form-group select,
  .rc-form-group textarea {
  border: 0.5px solid var(--color-border-tertiary);
  border-radius: 6px;
  padding: 7px 10px;
  font-size: 13px;
  color: var(--color-text-primary);
  outline: none;
  background: #ffffff;
  }
  .rc-form-group input:focus,
  .rc-form-group select:focus,
  .rc-form-group textarea:focus { border-color: var(--color-border-primary); }
  .rc-form-group textarea { resize: vertical; min-height: 60px; }
  .rc-checkbox-wrap { display: flex; align-items: center; gap: 6px; font-size: 13px; }
  .rc-checkbox-wrap input[type=checkbox] {
  width: 14px;
  height: 14px;
  accent-color: var(--color-border-primary);
  }
  .rc-toast {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #10b981;
  color: white;
  padding: 10px 16px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 500;
  box-shadow: 0 4px 12px rgba(0,0,0,0.12);
  opacity: 0;
  transform: translateY(10px);
  transition: all 0.25s ease;
  z-index: 2000;
  }
  .rc-toast.error { background: #ef4444; }
  .rc-toast.show { opacity: 1; transform: translateY(0); }
  @media (max-width: 1024px) {
  .rc-table th:nth-child(3),
  .rc-table td:nth-child(3) { display: none; }
  }
  @media (max-width: 768px) {
  .rc-card-header { flex-direction: column; align-items: stretch; }
  .rc-toolbar { flex-wrap: wrap; }
  .rc-search-wrap { min-width: 100%; }
  .rc-form-grid { grid-template-columns: 1fr; }
  .rc-modal { width: 95%; max-height: 95vh; }
  .rc-table th:nth-child(2),
  .rc-table td:nth-child(2) { display: none; }
  }
  @media (max-width: 480px) {
  .rc-page { padding: 12px; }
  .rc-card-header { padding: 12px; }
  .rc-toolbar { gap: 6px; }
  .rc-btn { font-size: 12px; padding: 5px 10px; }
  .rc-icon-btn { width: 26px; height: 26px; }
  .rc-table th:nth-child(4),
  .rc-table td:nth-child(4) { display: none; }
  }
  </style>

  <script>
    // Data will be fetched from API on page load
    let categoriesData = [];
    let propertiesData = [];

    // Fetch data from API on page load
    document.addEventListener('DOMContentLoaded', async function() {
      await fetchCategoriesData();
    });

    async function fetchCategoriesData() {
      try {
        // Fetch categories
        const response = await fetch('../api/room_categories.php?action=list');
        const result = await response.json();
        if (result.success) {
          categoriesData = result.data;
        }

        // Fetch properties for dropdowns
        const propsResponse = await fetch('../api/properties.php?action=list');
        const propsResult = await propsResponse.json();
        if (propsResult.success) {
          propertiesData = propsResult.data.data || propsResult.data;
        }

        renderCategoriesTable();
        populatePropertyDropdowns();
      } catch (error) {
        console.error('Error fetching categories:', error);
        showRcToast('Failed to load categories data', 'error');
      }
    }

    function showRcToast(message, type = 'success') {
      const toast = document.getElementById('rcToast');
      toast.textContent = message;
      toast.className = `rc-toast ${type}`;
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 3000);
    }

    function populatePropertyDropdowns() {
      const addPropertyDropdown = document.getElementById('addPropertyDropdown');
      const editPropertyDropdown = document.getElementById('editPropertyDropdown');

      if (addPropertyDropdown && propertiesData.length > 0) {
        addPropertyDropdown.innerHTML = '<option value="">Select Property</option>';
        propertiesData.forEach(property => {
          const option = document.createElement('option');
          option.value = property.property_id;
          option.textContent = `${property.property_name} (${property.property_code})`;
          addPropertyDropdown.appendChild(option);
        });
      }

      if (editPropertyDropdown && propertiesData.length > 0) {
        editPropertyDropdown.innerHTML = '<option value="">Select Property</option>';
        propertiesData.forEach(property => {
          const option = document.createElement('option');
          option.value = property.property_id;
          option.textContent = `${property.property_name} (${property.property_code})`;
          editPropertyDropdown.appendChild(option);
        });
      }
    }

    function renderCategoriesTable() {
      const tbody = document.querySelector('#categoriesTableBody');
      if (!tbody) return;
      if (categoriesData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5"><div class="rc-empty-state">No categories found. <a href="javascript:;" onclick="openAddCategoryModal()" style="color:#185fa5;font-weight:500;">Add your first category</a>.</div></td></tr>`;
        return;
      }
      tbody.innerHTML = categoriesData.map((category) => {
        const property = propertiesData.find(p => p.property_id === category.property_id);
        return `
        <tr class="category-row">
          <td>
            <div class="rc-name">${category.category_name}</div>
            <div class="rc-code">${category.category_code}</div>
          </td>
          <td><span class="rc-text">${property ? property.property_name : '—'}</span></td>
          <td><span class="rc-text">${category.base_price_per_night || '—'}</span></td>
          <td><span class="rc-text">${category.max_total_capacity || '—'}</span></td>
          <td style="text-align:center;"><span class="rc-status-badge rc-status-${category.is_active ? 'active' : 'inactive'}">${category.is_active ? 'Active' : 'Inactive'}</span></td>
          <td>
            <div class="rc-actions">
              <button class="rc-icon-btn edit" onclick="openEditCategoryModal('${category.category_id}')" title="Edit"><i class="fas fa-pen"></i></button>
              <button class="rc-icon-btn del" onclick="confirmDeleteCategory('${category.category_id}', '${(category.category_name).replace(/'/g, "\\'")}')" title="Delete"><i class="fas fa-trash"></i></button>
            </div>
          </td>
        </tr>
      `;
      }).join('');
    }

    // Delete Category Function (API-based)
    async function confirmDeleteCategory(categoryId, categoryName) {
      if (!confirm(`Delete "${categoryName}"? This action cannot be undone.`)) return;
      try {
        const response = await fetch('../api/room_categories.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'delete',
            category_id: categoryId
          })
        });
        const result = await response.json();
        if (result.success) {
          showRcToast('Category deleted successfully!');
          await fetchCategoriesData();
        } else {
          showRcToast(result.message || 'Failed to delete category', 'error');
        }
      } catch (error) {
        showRcToast('An error occurred while deleting the category', 'error');
      }
    }

    // Add Category Form Submission (API-based)
    async function handleAddCategorySubmit(event) {
      event.preventDefault();
      const form = event.target;
      const formData = new FormData(form);

      // Generate UUID for category_id
      const category_id = generateUUID();

      // Prepare data for API
      const data = {
        category_id: category_id,
        property_id: formData.get('property_id'),
        category_code: formData.get('category_code'),
        category_name: formData.get('category_name'),
        description: formData.get('description') || null,
        base_price_per_night: formData.get('base_price_per_night') || 0,
        base_price_per_hour: formData.get('base_price_per_hour') || 0,
        base_price_day_use: formData.get('base_price_day_use') || 0,
        minimum_hours: formData.get('minimum_hours') || 2,
        max_adult_capacity: formData.get('max_adult_capacity') || 2,
        max_child_capacity: formData.get('max_child_capacity') || 2,
        max_total_capacity: formData.get('max_total_capacity') || 4,
        extra_adult_fee: formData.get('extra_adult_fee') || 0,
        extra_child_fee: formData.get('extra_child_fee') || 0,
        extra_senior_fee: formData.get('extra_senior_fee') || 0,
        floor_area_sqm: formData.get('floor_area_sqm') || null,
        bed_configuration: formData.get('bed_configuration') || null,
        view_type: formData.get('view_type') || null,
        floor_level: formData.get('floor_level') || null,
        smoking_policy: formData.get('smoking_policy') || 'non_smoking',
        pet_friendly: formData.get('pet_friendly') || 0,
        allows_hourly: formData.get('allows_hourly') || 1,
        allows_overnight: formData.get('allows_overnight') || 1,
        allows_day_use: formData.get('allows_day_use') || 1,
        sort_order: formData.get('sort_order') || 0,
        is_active: 1
      };

      try {
        const response = await fetch('../api/room_categories.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'create',
            ...data
          })
        });
        const result = await response.json();

        if (result.success) {
          showRcToast('Category added successfully!');
          closeCategoryFormModal();
          await fetchCategoriesData();
        } else {
          showRcToast(result.message || 'Failed to add category', 'error');
        }
      } catch (error) {
        showRcToast('An error occurred while adding the category', 'error');
      }
    }

    // Edit Category Form Submission (API-based)
    async function handleEditCategorySubmit(event) {
      event.preventDefault();
      const form = event.target;
      const formData = new FormData(form);

      const data = {
        category_id: formData.get('category_id'),
        property_id: formData.get('property_id'),
        category_code: formData.get('category_code'),
        category_name: formData.get('category_name'),
        description: formData.get('description') || null,
        base_price_per_night: formData.get('base_price_per_night') || 0,
        base_price_per_hour: formData.get('base_price_per_hour') || 0,
        base_price_day_use: formData.get('base_price_day_use') || 0,
        minimum_hours: formData.get('minimum_hours') || 2,
        max_adult_capacity: formData.get('max_adult_capacity') || 2,
        max_child_capacity: formData.get('max_child_capacity') || 2,
        max_total_capacity: formData.get('max_total_capacity') || 4,
        extra_adult_fee: formData.get('extra_adult_fee') || 0,
        extra_child_fee: formData.get('extra_child_fee') || 0,
        extra_senior_fee: formData.get('extra_senior_fee') || 0,
        floor_area_sqm: formData.get('floor_area_sqm') || null,
        bed_configuration: formData.get('bed_configuration') || null,
        view_type: formData.get('view_type') || null,
        floor_level: formData.get('floor_level') || null,
        smoking_policy: formData.get('smoking_policy') || 'non_smoking',
        pet_friendly: formData.get('pet_friendly') || 0,
        allows_hourly: formData.get('allows_hourly') || 1,
        allows_overnight: formData.get('allows_overnight') || 1,
        allows_day_use: formData.get('allows_day_use') || 1,
        sort_order: formData.get('sort_order') || 0,
        is_active: formData.get('is_active') || 1
      };

      try {
        const response = await fetch('../api/room_categories.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'update',
            ...data
          })
        });
        const result = await response.json();

        if (result.success) {
          showRcToast('Category updated successfully!');
          closeCategoryFormModal();
          await fetchCategoriesData();
        } else {
          showRcToast(result.message || 'Failed to update category', 'error');
        }
      } catch (error) {
        showRcToast('An error occurred while updating the category', 'error');
      }
    }
  </script>

  <!-- PAGE CONTENT -->
  <div class="rc-page">
    <div class="rc-card">
      <div class="rc-card-header">
        <span class="rc-card-title">Room Categories</span>
        <div class="rc-toolbar">
          <div class="rc-search-wrap">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" id="searchInput" class="rc-search-input" placeholder="Search categories…" oninput="filterTable()">
          </div>
          <select class="rc-filter-select" id="statusFilter" onchange="filterTable()">
            <option value="">All statuses</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
          <button class="rc-btn rc-btn-primary" onclick="openAddCategoryModal()">
            <i class="fas fa-plus"></i> Add Category
          </button>
        </div>
      </div>
      <div class="rc-table-wrap">
        <table class="rc-table" id="categoriesTable">
          <thead>
            <tr>
              <th>Category</th>
              <th>Property</th>
              <th>Base Price</th>
              <th>Capacity</th>
              <th style="text-align:center;">Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody id="categoriesTableBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="rcToast" class="rc-toast"></div>

  <!-- Category Details Modal -->
  <div id="categoryDetailsModal" class="rc-modal-backdrop" onclick="if(event.target===this)closeCategoryDetailsModal()">
    <div class="rc-modal">
      <div class="rc-modal-header">
        <span class="rc-modal-title" id="modalCategoryName">Category Details</span>
        <button class="rc-modal-close" onclick="closeCategoryDetailsModal()"><i class="fas fa-times"></i></button>
      </div>
      <div class="rc-modal-body" id="modalCategoryContent"></div>
    </div>
  </div>

  <!-- Add/Edit Category Modal -->
  <div id="categoryFormModal" class="rc-modal-backdrop" onclick="if(event.target===this)closeCategoryFormModal()">
    <div class="rc-modal">
      <div class="rc-modal-header">
        <span class="rc-modal-title" id="formModalTitle">Add Category</span>
        <button class="rc-modal-close" onclick="closeCategoryFormModal()"><i class="fas fa-times"></i></button>
      </div>
      <form id="addCategoryForm" onsubmit="handleAddCategorySubmit(event)">
        <div class="rc-modal-body">
          <p class="rc-section-label">Basic Information</p>
          <div class="rc-form-grid">
            <div class="rc-form-group">
              <label>Property *</label>
              <select name="property_id" id="addPropertyDropdown" required>
                <option value="">Select Property</option>
              </select>
            </div>
            <div class="rc-form-group">
              <label>Category Code *</label>
              <input type="text" name="category_code" id="formCategoryCode" required maxlength="30" placeholder="e.g. STD, DLX, STE">
            </div>
            <div class="rc-form-group full">
              <label>Category Name *</label>
              <input type="text" name="category_name" id="formCategoryName" required maxlength="100" placeholder="e.g. Standard Room, Deluxe Suite">
            </div>
            <div class="rc-form-group full">
              <label>Description</label>
              <textarea name="description" id="formDescription" rows="3" placeholder="Category description..."></textarea>
            </div>
          </div>

          <p class="rc-section-label">Pricing</p>
          <div class="rc-form-grid">
            <div class="rc-form-group">
              <label>Base Price Per Night (PHP)</label>
              <input type="number" name="base_price_per_night" id="formBasePriceNight" step="0.01" min="0" value="0">
            </div>
            <div class="rc-form-group">
              <label>Base Price Per Hour (PHP)</label>
              <input type="number" name="base_price_per_hour" id="formBasePriceHour" step="0.01" min="0" value="0">
            </div>
            <div class="rc-form-group">
              <label>Base Price Day Use (PHP)</label>
              <input type="number" name="base_price_day_use" id="formBasePriceDayUse" step="0.01" min="0" value="0">
            </div>
            <div class="rc-form-group">
              <label>Minimum Hours</label>
              <input type="number" name="minimum_hours" id="formMinimumHours" min="1" max="24" value="2">
            </div>
          </div>

          <p class="rc-section-label">Capacity</p>
          <div class="rc-form-grid">
            <div class="rc-form-group">
              <label>Max Adult Capacity</label>
              <input type="number" name="max_adult_capacity" id="formMaxAdultCapacity" min="0" value="2">
            </div>
            <div class="rc-form-group">
              <label>Max Child Capacity</label>
              <input type="number" name="max_child_capacity" id="formMaxChildCapacity" min="0" value="2">
            </div>
            <div class="rc-form-group">
              <label>Max Total Capacity</label>
              <input type="number" name="max_total_capacity" id="formMaxTotalCapacity" min="0" value="4">
            </div>
            <div class="rc-form-group"></div>
          </div>

          <p class="rc-section-label">Extra Fees</p>
          <div class="rc-form-grid">
            <div class="rc-form-group">
              <label>Extra Adult Fee (PHP)</label>
              <input type="number" name="extra_adult_fee" id="formExtraAdultFee" step="0.01" min="0" value="0">
            </div>
            <div class="rc-form-group">
              <label>Extra Child Fee (PHP)</label>
              <input type="number" name="extra_child_fee" id="formExtraChildFee" step="0.01" min="0" value="0">
            </div>
            <div class="rc-form-group">
              <label>Extra Senior Fee (PHP)</label>
              <input type="number" name="extra_senior_fee" id="formExtraSeniorFee" step="0.01" min="0" value="0">
            </div>
            <div class="rc-form-group"></div>
          </div>

          <p class="rc-section-label">Physical Details</p>
          <div class="rc-form-grid">
            <div class="rc-form-group">
              <label>Floor Area (sqm)</label>
              <input type="number" name="floor_area_sqm" id="formFloorArea" step="0.01" min="0">
            </div>
            <div class="rc-form-group">
              <label>Bed Configuration</label>
              <input type="text" name="bed_configuration" id="formBedConfiguration" maxlength="150" placeholder="e.g. 1 King Bed + 1 Sofa Bed">
            </div>
            <div class="rc-form-group">
              <label>View Type</label>
              <input type="text" name="view_type" id="formViewType" maxlength="60" placeholder="e.g. ocean, mountain, garden">
            </div>
            <div class="rc-form-group">
              <label>Floor Level</label>
              <input type="text" name="floor_level" id="formFloorLevel" maxlength="30">
            </div>
          </div>

          <p class="rc-section-label">Policies</p>
          <div class="rc-form-grid">
            <div class="rc-form-group">
              <label>Smoking Policy</label>
              <select name="smoking_policy" id="formSmokingPolicy">
                <option value="non_smoking">Non Smoking</option>
                <option value="smoking">Smoking</option>
                <option value="designated_area">Designated Area</option>
              </select>
            </div>
            <div class="rc-form-group">
              <label>Pet Friendly</label>
              <select name="pet_friendly" id="formPetFriendly">
                <option value="0">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>

          <p class="rc-section-label">Booking Options</p>
          <div class="rc-form-grid">
            <div class="rc-form-group">
              <label class="rc-checkbox-wrap">
                <input type="checkbox" name="allows_hourly" id="formAllowsHourly" value="1" checked>
                Allows Hourly
              </label>
            </div>
            <div class="rc-form-group">
              <label class="rc-checkbox-wrap">
                <input type="checkbox" name="allows_overnight" id="formAllowsOvernight" value="1" checked>
                Allows Overnight
              </label>
            </div>
            <div class="rc-form-group">
              <label class="rc-checkbox-wrap">
                <input type="checkbox" name="allows_day_use" id="formAllowsDayUse" value="1" checked>
                Allows Day Use
              </label>
            </div>
            <div class="rc-form-group">
              <label>Sort Order</label>
              <input type="number" name="sort_order" id="formSortOrder" min="0" value="0">
            </div>
          </div>
        </div>
        <div class="rc-modal-footer">
          <button type="button" class="rc-btn" onclick="closeCategoryFormModal()">Cancel</button>
          <button type="submit" class="rc-btn rc-btn-primary"><i class="fas fa-save"></i> Save Category</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function viewCategoryDetails(categoryId) {
      const category = categoriesData.find(c => c.category_id === categoryId);
      if (!category) return;
      const modalName = document.getElementById('modalCategoryName');
      const modalContent = document.getElementById('modalCategoryContent');
      modalName.textContent = category.category_name;
      const property = propertiesData.find(p => p.property_id === category.property_id);
      modalContent.innerHTML = `
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div><p class="rc-section-label">Basic Information</p>
          <div style="display:flex;flex-direction:column;gap:8px;"><div><span style="font-size:12px;color:#64748b;">Category Code</span><div style="font-size:13px;font-weight:500;">${category.category_code}</div></div>
          <div><span style="font-size:12px;color:#64748b;">Category Name</span><div>${category.category_name}</div></div>
          <div><span style="font-size:12px;color:#64748b;">Description</span><div>${category.description || '—'}</div></div></div></div>
        <div><p class="rc-section-label">Property</p>
          <div style="display:flex;flex-direction:column;gap:8px;"><div><span style="font-size:12px;color:#64748b;">Property Name</span><div style="font-weight:500;">${property ? property.property_name : '—'}</div></div></div></div>
        <div><p class="rc-section-label">Pricing</p>
          <div style="display:flex;flex-direction:column;gap:8px;"><div><span style="font-size:12px;color:#64748b;">Base Price Per Night</span><div style="font-weight:500;">PHP ${parseFloat(category.base_price_per_night || 0).toFixed(2)}</div></div>
          <div><span style="font-size:12px;color:#64748b;">Base Price Per Hour</span><div style="font-weight:500;">PHP ${parseFloat(category.base_price_per_hour || 0).toFixed(2)}</div></div>
          <div><span style="font-size:12px;color:#64748b;">Base Price Day Use</span><div style="font-weight:500;">PHP ${parseFloat(category.base_price_day_use || 0).toFixed(2)}</div></div>
          <div><span style="font-size:12px;color:#64748b;">Minimum Hours</span><div>${category.minimum_hours || 2} hours</div></div></div></div>
        <div><p class="rc-section-label">Capacity</p>
          <div style="display:flex;flex-direction:column;gap:8px;"><div><span style="font-size:12px;color:#64748b;">Max Adult Capacity</span><div>${category.max_adult_capacity} adults</div></div>
          <div><span style="font-size:12px;color:#64748b;">Max Child Capacity</span><div>${category.max_child_capacity} children</div></div>
          <div><span style="font-size:12px;color:#64748b;">Max Total Capacity</span><div>${category.max_total_capacity} guests</div></div></div></div>
        <div><p class="rc-section-label">Extra Fees</p>
          <div style="display:flex;flex-direction:column;gap:8px;"><div><span style="font-size:12px;color:#64748b;">Extra Adult Fee</span><div style="font-weight:500;">PHP ${parseFloat(category.extra_adult_fee || 0).toFixed(2)}</div></div>
          <div><span style="font-size:12px;color:#64748b;">Extra Child Fee</span><div style="font-weight:500;">PHP ${parseFloat(category.extra_child_fee || 0).toFixed(2)}</div></div>
          <div><span style="font-size:12px;color:#64748b;">Extra Senior Fee</span><div style="font-weight:500;">PHP ${parseFloat(category.extra_senior_fee || 0).toFixed(2)}</div></div></div></div>
        <div><p class="rc-section-label">Policies</p>
          <div style="display:flex;flex-direction:column;gap:8px;"><div><span style="font-size:12px;color:#64748b;">Smoking Policy</span><div>${(category.smoking_policy || 'non_smoking').replace(/_/g, ' ').toUpperCase()}</div></div>
          <div><span style="font-size:12px;color:#64748b;">Pet Friendly</span><div>${category.pet_friendly ? 'Yes' : 'No'}</div></div></div></div>
        <div><p class="rc-section-label">Booking Options</p>
          <div style="display:flex;flex-direction:column;gap:8px;"><div><span style="font-size:12px;color:#64748b;">Allows Hourly</span><div>${category.allows_hourly ? 'Yes' : 'No'}</div></div>
          <div><span style="font-size:12px;color:#64748b;">Allows Overnight</span><div>${category.allows_overnight ? 'Yes' : 'No'}</div></div>
          <div><span style="font-size:12px;color:#64748b;">Allows Day Use</span><div>${category.allows_day_use ? 'Yes' : 'No'}</div></div></div></div>
        <div style="grid-column:1/-1;"><p class="rc-section-label">System Information</p>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;"><div><span style="font-size:12px;color:#64748b;">Status</span><div>${category.is_active ? 'Active' : 'Inactive'}</div></div>
          <div><span style="font-size:12px;color:#64748b;">Sort Order</span><div>${category.sort_order || 0}</div></div></div></div>
      </div>
    `;
      document.getElementById('categoryDetailsModal').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeCategoryDetailsModal() {
      document.getElementById('categoryDetailsModal').classList.remove('open');
      document.body.style.overflow = '';
    }

    function openAddCategoryModal() {
      document.getElementById('addCategoryForm').reset();
      document.getElementById('formModalTitle').textContent = 'Add Category';
      document.getElementById('formAllowsHourly').checked = true;
      document.getElementById('formAllowsOvernight').checked = true;
      document.getElementById('formAllowsDayUse').checked = true;
      document.getElementById('categoryFormModal').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function openEditCategoryModal(categoryId) {
      const category = categoriesData.find(c => c.category_id === categoryId);
      if (!category) return;
      document.getElementById('formModalTitle').textContent = 'Edit Category';
      document.getElementById('addPropertyDropdown').value = category.property_id;
      document.getElementById('formCategoryCode').value = category.category_code;
      document.getElementById('formCategoryName').value = category.category_name;
      document.getElementById('formDescription').value = category.description || '';
      document.getElementById('formBasePriceNight').value = category.base_price_per_night || 0;
      document.getElementById('formBasePriceHour').value = category.base_price_per_hour || 0;
      document.getElementById('formBasePriceDayUse').value = category.base_price_day_use || 0;
      document.getElementById('formMinimumHours').value = category.minimum_hours || 2;
      document.getElementById('formMaxAdultCapacity').value = category.max_adult_capacity || 2;
      document.getElementById('formMaxChildCapacity').value = category.max_child_capacity || 2;
      document.getElementById('formMaxTotalCapacity').value = category.max_total_capacity || 4;
      document.getElementById('formExtraAdultFee').value = category.extra_adult_fee || 0;
      document.getElementById('formExtraChildFee').value = category.extra_child_fee || 0;
      document.getElementById('formExtraSeniorFee').value = category.extra_senior_fee || 0;
      document.getElementById('formFloorArea').value = category.floor_area_sqm || '';
      document.getElementById('formBedConfiguration').value = category.bed_configuration || '';
      document.getElementById('formViewType').value = category.view_type || '';
      document.getElementById('formFloorLevel').value = category.floor_level || '';
      document.getElementById('formSmokingPolicy').value = category.smoking_policy || 'non_smoking';
      document.getElementById('formPetFriendly').value = category.pet_friendly || '0';
      document.getElementById('formAllowsHourly').checked = category.allows_hourly == 1;
      document.getElementById('formAllowsOvernight').checked = category.allows_overnight == 1;
      document.getElementById('formAllowsDayUse').checked = category.allows_day_use == 1;
      document.getElementById('formSortOrder').value = category.sort_order || 0;
      document.getElementById('categoryFormModal').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeCategoryFormModal() {
      document.getElementById('categoryFormModal').classList.remove('open');
      document.body.style.overflow = '';
      document.getElementById('addCategoryForm').reset();
    }

    function filterTable() {
      const filter = (document.getElementById('searchInput')?.value || '').toLowerCase();
      const statusFilter = (document.getElementById('statusFilter')?.value || '').toLowerCase();
      const rows = document.querySelectorAll('#categoriesTableBody .category-row');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const matchText = !filter || text.includes(filter);
        const matchStatus = !statusFilter || text.includes(statusFilter);
        row.style.display = (matchText && matchStatus) ? '' : 'none';
      });
    }

    window.addEventListener('load', function() {
      <?php if (isset($error_message)): ?>
        showRcToast(<?php echo json_encode($error_message); ?>, 'error');
      <?php endif; ?>
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('success') === 'category_added') {
        window.history.replaceState({}, document.title, window.location.pathname);
        showRcToast('Category added successfully!');
      }
      if (urlParams.get('success') === 'category_updated') {
        window.history.replaceState({}, document.title, window.location.pathname);
        showRcToast('Category updated successfully!');
      }
      if (urlParams.get('success') === 'category_deleted') {
        window.history.replaceState({}, document.title, window.location.pathname);
        showRcToast('Category deleted successfully!');
      }
    });
  </script>