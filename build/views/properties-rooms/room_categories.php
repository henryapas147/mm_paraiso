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
  .rc-modal-wide {
  max-width: 900px !important;
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
    // Helper function to generate UUID in JavaScript
    function generateUUID() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
    }

    // Data will be fetched from API on page load
    let categoriesData = [];
    let propertiesData = [];
    let amenitiesData = [];
    let currentPage = 1;
    let itemsPerPage = 10;
    let totalItems = 0;

    // Fetch data from API on page load
    document.addEventListener('DOMContentLoaded', async function() {
      await fetchCategoriesData();
      await fetchAmenitiesData();
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

    async function fetchAmenitiesData() {
      try {
        // Request all amenities without pagination
        const response = await fetch('../api/amenities.php?action=list&per_page=100');
        const result = await response.json();
        if (result.success) {
          amenitiesData = result.data.data || result.data || [];
        }
      } catch (error) {
        console.error('Error fetching amenities:', error);
      }
    }

    function renderAmenitiesCheckboxes(selectedAmenities = []) {
      const container = document.getElementById('amenitiesContainer');
      if (!container) return;

      if (amenitiesData.length === 0) {
        container.innerHTML = '<div style="color:var(--color-text-secondary);font-size:13px;">No amenities available</div>';
        return;
      }

      container.innerHTML = amenitiesData.map(amenity => {
        const isChecked = selectedAmenities.includes(amenity.amenity_id) ? 'checked' : '';
        const iconHtml = amenity.icon_code ? `<i class="fa-solid ${amenity.icon_code}" style="margin-right:6px;"></i>` : '';
        return `
          <label class="rc-checkbox-wrap" style="display:flex;align-items:center;gap:6px;font-size:13px;">
            <input type="checkbox" name="amenities[]" value="${amenity.amenity_id}" ${isChecked}>
            ${iconHtml}${amenity.amenity_name}
          </label>
        `;
      }).join('');
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
        tbody.innerHTML = `<tr><td colspan="6"><div class="rc-empty-state">No categories found. <a href="javascript:;" onclick="openAddCategoryModal()" style="color:#185fa5;font-weight:500;">Add your first category</a>.</div></td></tr>`;
        return;
      }
      tbody.innerHTML = categoriesData.map((category) => {
        const property = propertiesData.find(p => p.property_id === category.property_id);
        const propertyName = category.property_name || (property ? property.property_name : '—');
        const roomCount = category.room_count || 0;
        const availableRooms = category.available_rooms || 0;
        return `
        <tr class="category-row">
          <td>
            <div class="rc-name">${category.category_name}</div>
            <div class="rc-code">${category.category_code}</div>
          </td>
          <td><span class="rc-text">${propertyName}</span></td>
          <td><span class="rc-text">${category.base_price_per_night ? '₱' + parseFloat(category.base_price_per_night).toLocaleString('en-PH', {minimumFractionDigits: 2}) : '—'}</span></td>
          <td><span class="rc-text">${category.max_total_capacity || '—'}</span></td>
          <td>
            <span class="rc-text">${roomCount} room${roomCount !== 1 ? 's' : ''}</span>
            ${availableRooms > 0 ? `<span style="font-size:11px;color:#16a34a;margin-left:4px;">(${availableRooms} available)</span>` : ''}
          </td>
          <td style="text-align:center;"><span class="rc-status-badge rc-status-${category.is_active ? 'active' : 'inactive'}">${category.is_active ? 'Active' : 'Inactive'}</span></td>
          <td>
            <div class="rc-actions">
              <button class="rc-icon-btn view" onclick="viewCategoryDetails('${category.category_id}')" title="View Details"><i class="fas fa-eye"></i></button>
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
      showDeleteCategoryConfirmation(categoryName, categoryId);
    }

    function showDeleteCategoryConfirmation(categoryName, categoryId) {
      const modal = document.createElement('div');
      modal.style.cssText = `
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5); z-index: 10000;
        display: flex; align-items: center; justify-content: center;
      `;

      const dialog = document.createElement('div');
      dialog.style.cssText = `
        background: white; border-radius: 12px; padding: 24px;
        max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        position: relative;
      `;

      dialog.innerHTML = `
        <div style="margin-bottom: 16px;">
          <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #1e293b;">Delete Category</h3>
          <p style="margin: 0; font-size: 13px; color: #64748b; line-height: 1.5;">
            Are you sure you want to delete category "<strong>${categoryName}</strong>"? This action cannot be undone.
          </p>
        </div>
        <div style="display: flex; gap: 8px; justify-content: flex-end;">
          <button id="cancelDeleteBtn" style="
            padding: 8px 16px; font-size: 13px; font-weight: 500;
            border: 0.5px solid #e2e8f0; border-radius: 6px;
            background: white; color: #64748b; cursor: pointer;
          ">Cancel</button>
          <button id="confirmDeleteBtn" style="
            padding: 8px 16px; font-size: 13px; font-weight: 500;
            border: none; border-radius: 6px;
            background: #ef4444; color: white; cursor: pointer;
          ">Delete</button>
        </div>
      `;

      modal.appendChild(dialog);
      document.body.appendChild(modal);

      const cancelBtn = dialog.querySelector('#cancelDeleteBtn');
      cancelBtn.onclick = () => {
        document.body.removeChild(modal);
      };

      const confirmBtn = dialog.querySelector('#confirmDeleteBtn');
      confirmBtn.onclick = async () => {
        document.body.removeChild(modal);
        await executeDeleteCategory(categoryId, categoryName);
      };

      modal.onclick = (e) => {
        if (e.target === modal) {
          document.body.removeChild(modal);
        }
      };
    }

    async function executeDeleteCategory(categoryId, categoryName) {
      try {
        // Get CSRF token from the form
        const form = document.getElementById('addCategoryForm');
        const csrfToken = form ? form.querySelector('[name="csrf_token"]')?.value : '';

        const response = await fetch('../api/room_categories.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'delete',
            category_id: categoryId,
            csrf_token: csrfToken
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

      // Get selected amenities
      const selectedAmenities = Array.from(document.querySelectorAll('input[name="amenities[]"]:checked')).map(cb => cb.value);

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
        allows_hourly: formData.get('allows_hourly') ? 1 : 0,
        allows_overnight: formData.get('allows_overnight') ? 1 : 0,
        allows_day_use: formData.get('allows_day_use') ? 1 : 0,
        sort_order: formData.get('sort_order') || 0,
        is_active: 1,
        amenities: selectedAmenities,
        csrf_token: formData.get('csrf_token')
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

      // Get selected amenities
      const selectedAmenities = Array.from(document.querySelectorAll('input[name="amenities[]"]:checked')).map(cb => cb.value);

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
        allows_hourly: formData.get('allows_hourly') ? 1 : 0,
        allows_overnight: formData.get('allows_overnight') ? 1 : 0,
        allows_day_use: formData.get('allows_day_use') ? 1 : 0,
        sort_order: formData.get('sort_order') || 0,
        is_active: formData.get('is_active') ? 1 : 0,
        amenities: selectedAmenities,
        csrf_token: formData.get('csrf_token')
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
              <th>Rooms</th>
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
    <div class="rc-modal rc-modal-wide">
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
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
        <input type="hidden" name="category_id" id="formCategoryId">
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
              <label>Status</label>
              <select name="is_active" id="formIsActive">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
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

          <p class="rc-section-label">Amenities</p>
          <div class="rc-form-group full">
            <div id="amenitiesContainer" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:8px;max-height:200px;overflow-y:auto;padding:8px;border:0.5px solid var(--color-border-tertiary);border-radius:6px;">
              <div style="color:var(--color-text-secondary);font-size:13px;">Loading amenities...</div>
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
    async function viewCategoryDetails(categoryId) {
      const category = categoriesData.find(c => c.category_id === categoryId);
      if (!category) return;

      const property = propertiesData.find(p => p.property_id === category.property_id);

      // Fetch amenities for this category
      let categoryAmenities = [];
      try {
        const response = await fetch(`../api/room_amenities.php?action=by_category&category_id=${categoryId}`);
        const result = await response.json();
        if (result.success && result.data) {
          categoryAmenities = result.data;
        }
      } catch (error) {
        console.error('Error fetching category amenities:', error);
      }

      // Generate amenities HTML
      let amenitiesHtml = '';
      if (categoryAmenities.length > 0) {
        amenitiesHtml = categoryAmenities.map(ca => {
          const amenity = amenitiesData.find(a => a.amenity_id === ca.amenity_id);
          if (!amenity) return '';
          const iconHtml = amenity.icon_code ? `<i class="fa-solid ${amenity.icon_code}" style="margin-right:8px;color:#185fa5;"></i>` : '<i class="fa-solid fa-check" style="margin-right:8px;color:#185fa5;"></i>';
          return `<span style="background:#f0f9ff;color:#0c4a6e;padding:6px 12px;border-radius:16px;font-size:12px;display:inline-flex;align-items:center;border:1px solid #bae6fd;">${iconHtml}${amenity.amenity_name}</span>`;
        }).join('');
      } else {
        amenitiesHtml = '<span style="color:#94a3b8;font-size:13px;font-style:italic;">No amenities assigned to this category</span>';
      }

      // Generate rooms list HTML
      let roomsHtml = '';
      if (category.rooms && category.rooms.length > 0) {
        roomsHtml = category.rooms.map(room => {
          const statusColors = {
            'available': '#e8f5e9',
            'occupied': '#fff3e0',
            'reserved': '#e3f2fd',
            'cleaning': '#fce4ec',
            'maintenance': '#ffebee',
            'out_of_order': '#37474f'
          };
          const statusTextColors = {
            'available': '#2e7d32',
            'occupied': '#e65100',
            'reserved': '#1565c0',
            'cleaning': '#c2185b',
            'maintenance': '#c62828',
            'out_of_order': '#fff'
          };
          return `
            <div style="padding:12px;border:0.5px solid #e2e8f0;border-radius:8px;display:flex;flex-direction:column;gap:6px;background:#fff;transition:all 0.2s;">
              <div style="display:flex;justify-content:space-between;align-items:center;">
                <div style="font-weight:600;font-size:14px;color:#1e293b;">${room.room_number}</div>
                <span style="background:${statusColors[room.room_status] || '#e0e0e0'};color:${statusTextColors[room.room_status] || '#424242'};padding:3px 8px;border-radius:10px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">${room.room_status.replace('_', ' ')}</span>
              </div>
              ${room.room_name ? `<div style="font-size:12px;color:#64748b;">${room.room_name}</div>` : ''}
              <div style="font-size:11px;color:#94a3b8;">${room.floor_number ? 'Floor ' + room.floor_number : ''}${room.building_block ? ' • ' + room.building_block : ''}</div>
            </div>
          `;
        }).join('');
      } else {
        roomsHtml = '<div style="color:#94a3b8;font-size:13px;padding:30px;text-align:center;background:#f8fafc;border-radius:8px;border:1px dashed #cbd5e1;">No rooms assigned to this category</div>';
      }

      const modalName = document.getElementById('modalCategoryName');
      const modalContent = document.getElementById('modalCategoryContent');
      modalName.textContent = category.category_name;
      modalContent.innerHTML = `
      <!-- Header with Status -->
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #e2e8f0;">
        <div>
          <div style="font-size:18px;font-weight:600;color:#1e293b;">${category.category_name}</div>
          <div style="font-size:13px;color:#64748b;margin-top:2px;">${category.category_code}</div>
        </div>
        <span style="background:${category.is_active ? '#dcfce7' : '#f1f5f9'};color:${category.is_active ? '#166534' : '#64748b'};padding:6px 12px;border-radius:20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">${category.is_active ? 'Active' : 'Inactive'}</span>
      </div>

      <!-- Main Content Grid -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <!-- Left Column -->
        <div style="display:flex;flex-direction:column;gap:20px;">
          <!-- Basic Info Card -->
          <div style="background:#f8fafc;padding:16px;border-radius:8px;border:1px solid #e2e8f0;">
            <p class="rc-section-label" style="margin-bottom:12px;color:#185fa5;">Basic Information</p>
            <div style="display:flex;flex-direction:column;gap:10px;">
              <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #e2e8f0;">
                <span style="font-size:12px;color:#64748b;">Property</span>
                <span style="font-size:13px;font-weight:500;">${property ? property.property_name : '—'}</span>
              </div>
              <div style="display:flex;justify-content:space-between;padding:8px 0;">
                <span style="font-size:12px;color:#64748b;">Description</span>
                <span style="font-size:13px;font-weight:400;text-align:right;max-width:60%;">${category.description || '—'}</span>
              </div>
            </div>
          </div>

          <!-- Pricing Card -->
          <div style="background:#f8fafc;padding:16px;border-radius:8px;border:1px solid #e2e8f0;">
            <p class="rc-section-label" style="margin-bottom:12px;color:#185fa5;">Pricing</p>
            <div style="display:flex;flex-direction:column;gap:8px;">
              <div style="display:flex;justify-content:space-between;padding:6px 0;">
                <span style="font-size:12px;color:#64748b;">Per Night</span>
                <span style="font-size:13px;font-weight:600;color:#185fa5;">₱${parseFloat(category.base_price_per_night || 0).toLocaleString('en-PH', {minimumFractionDigits: 2})}</span>
              </div>
              <div style="display:flex;justify-content:space-between;padding:6px 0;">
                <span style="font-size:12px;color:#64748b;">Per Hour</span>
                <span style="font-size:13px;font-weight:600;color:#185fa5;">₱${parseFloat(category.base_price_per_hour || 0).toLocaleString('en-PH', {minimumFractionDigits: 2})}</span>
              </div>
              <div style="display:flex;justify-content:space-between;padding:6px 0;">
                <span style="font-size:12px;color:#64748b;">Day Use</span>
                <span style="font-size:13px;font-weight:600;color:#185fa5;">₱${parseFloat(category.base_price_day_use || 0).toLocaleString('en-PH', {minimumFractionDigits: 2})}</span>
              </div>
              <div style="display:flex;justify-content:space-between;padding:6px 0;border-top:1px solid #e2e8f0;">
                <span style="font-size:12px;color:#64748b;">Minimum Hours</span>
                <span style="font-size:13px;font-weight:500;">${category.minimum_hours || 2} hrs</span>
              </div>
            </div>
          </div>

          <!-- Extra Fees Card -->
          <div style="background:#f8fafc;padding:16px;border-radius:8px;border:1px solid #e2e8f0;">
            <p class="rc-section-label" style="margin-bottom:12px;color:#185fa5;">Extra Fees</p>
            <div style="display:flex;flex-direction:column;gap:8px;">
              <div style="display:flex;justify-content:space-between;padding:6px 0;">
                <span style="font-size:12px;color:#64748b;">Adult</span>
                <span style="font-size:13px;font-weight:500;">₱${parseFloat(category.extra_adult_fee || 0).toLocaleString('en-PH', {minimumFractionDigits: 2})}</span>
              </div>
              <div style="display:flex;justify-content:space-between;padding:6px 0;">
                <span style="font-size:12px;color:#64748b;">Child</span>
                <span style="font-size:13px;font-weight:500;">₱${parseFloat(category.extra_child_fee || 0).toLocaleString('en-PH', {minimumFractionDigits: 2})}</span>
              </div>
              <div style="display:flex;justify-content:space-between;padding:6px 0;">
                <span style="font-size:12px;color:#64748b;">Senior</span>
                <span style="font-size:13px;font-weight:500;">₱${parseFloat(category.extra_senior_fee || 0).toLocaleString('en-PH', {minimumFractionDigits: 2})}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div style="display:flex;flex-direction:column;gap:20px;">
          <!-- Capacity Card -->
          <div style="background:#f8fafc;padding:16px;border-radius:8px;border:1px solid #e2e8f0;">
            <p class="rc-section-label" style="margin-bottom:12px;color:#185fa5;">Capacity</p>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
              <div style="text-align:center;padding:10px;background:#fff;border-radius:6px;border:1px solid #e2e8f0;">
                <div style="font-size:20px;font-weight:700;color:#185fa5;">${category.max_adult_capacity}</div>
                <div style="font-size:11px;color:#64748b;text-transform:uppercase;">Adults</div>
              </div>
              <div style="text-align:center;padding:10px;background:#fff;border-radius:6px;border:1px solid #e2e8f0;">
                <div style="font-size:20px;font-weight:700;color:#185fa5;">${category.max_child_capacity}</div>
                <div style="font-size:11px;color:#64748b;text-transform:uppercase;">Children</div>
              </div>
              <div style="text-align:center;padding:10px;background:#fff;border-radius:6px;border:1px solid #e2e8f0;">
                <div style="font-size:20px;font-weight:700;color:#185fa5;">${category.max_total_capacity}</div>
                <div style="font-size:11px;color:#64748b;text-transform:uppercase;">Total</div>
              </div>
            </div>
          </div>

          <!-- Policies Card -->
          <div style="background:#f8fafc;padding:16px;border-radius:8px;border:1px solid #e2e8f0;">
            <p class="rc-section-label" style="margin-bottom:12px;color:#185fa5;">Policies</p>
            <div style="display:flex;flex-direction:column;gap:10px;">
              <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #e2e8f0;">
                <span style="font-size:12px;color:#64748b;">Smoking</span>
                <span style="font-size:13px;font-weight:500;">${(category.smoking_policy || 'non_smoking').replace(/_/g, ' ').toUpperCase()}</span>
              </div>
              <div style="display:flex;justify-content:space-between;padding:8px 0;">
                <span style="font-size:12px;color:#64748b;">Pet Friendly</span>
                <span style="font-size:13px;font-weight:500;">${category.pet_friendly ? '<span style="color:#16a34a;">Yes</span>' : '<span style="color:#64748b;">No</span>'}</span>
              </div>
            </div>
          </div>

          <!-- Booking Options Card -->
          <div style="background:#f8fafc;padding:16px;border-radius:8px;border:1px solid #e2e8f0;">
            <p class="rc-section-label" style="margin-bottom:12px;color:#185fa5;">Booking Options</p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
              ${category.allows_hourly ? '<span style="background:#dbeafe;color:#1e40af;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:600;">Hourly</span>' : ''}
              ${category.allows_overnight ? '<span style="background:#dbeafe;color:#1e40af;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:600;">Overnight</span>' : ''}
              ${category.allows_day_use ? '<span style="background:#dbeafe;color:#1e40af;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:600;">Day Use</span>' : ''}
            </div>
          </div>
        </div>
      </div>

      <!-- Amenities Section -->
      <div style="margin-top:20px;">
        <p class="rc-section-label" style="margin-bottom:12px;color:#185fa5;">Amenities (${categoryAmenities.length})</p>
        <div style="display:flex;flex-wrap:wrap;gap:8px;background:#f8fafc;padding:16px;border-radius:8px;border:1px solid #e2e8f0;">
          ${amenitiesHtml}
        </div>
      </div>

      <!-- Rooms Section -->
      <div style="margin-top:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
          <p class="rc-section-label" style="margin:0;color:#185fa5;">Rooms (${category.room_count || 0})</p>
          <div style="font-size:12px;color:#64748b;">
            <span style="color:#16a34a;font-weight:500;">${category.available_rooms || 0} available</span>
            ${category.occupied_rooms > 0 ? `• <span style="color:#e65100;">${category.occupied_rooms} occupied</span>` : ''}
            ${category.reserved_rooms > 0 ? `• <span style="color:#1565c0;">${category.reserved_rooms} reserved</span>` : ''}
          </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;max-height:280px;overflow-y:auto;padding:4px;">${roomsHtml}</div>
      </div>

      <!-- System Info Footer -->
      <div style="margin-top:20px;padding-top:16px;border-top:1px solid #e2e8f0;display:flex;gap:20px;font-size:12px;color:#94a3b8;">
        <span>Sort Order: ${category.sort_order || 0}</span>
        <span>Created: ${new Date(category.created_at).toLocaleDateString()}</span>
        <span>Updated: ${new Date(category.updated_at).toLocaleDateString()}</span>
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
      const form = document.getElementById('addCategoryForm');
      form.reset();
      form.onsubmit = handleAddCategorySubmit;
      document.getElementById('formCategoryId').value = '';
      document.getElementById('formModalTitle').textContent = 'Add Category';
      document.getElementById('formAllowsHourly').checked = true;
      document.getElementById('formAllowsOvernight').checked = true;
      document.getElementById('formAllowsDayUse').checked = true;
      renderAmenitiesCheckboxes([]);
      document.getElementById('categoryFormModal').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function openEditCategoryModal(categoryId) {
      const category = categoriesData.find(c => c.category_id === categoryId);
      if (!category) return;
      const form = document.getElementById('addCategoryForm');
      form.onsubmit = handleEditCategorySubmit;
      document.getElementById('formCategoryId').value = categoryId;
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
      document.getElementById('formIsActive').value = category.is_active ?? 1;
      document.getElementById('formAllowsHourly').checked = category.allows_hourly == 1;
      document.getElementById('formAllowsOvernight').checked = category.allows_overnight == 1;
      document.getElementById('formAllowsDayUse').checked = category.allows_day_use == 1;
      document.getElementById('formSortOrder').value = category.sort_order || 0;

      // Fetch and render existing amenities for this category
      fetchCategoryAmenities(categoryId).then(selectedAmenities => {
        renderAmenitiesCheckboxes(selectedAmenities);
      });

      document.getElementById('categoryFormModal').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    async function fetchCategoryAmenities(categoryId) {
      try {
        const response = await fetch(`../api/room_amenities.php?action=by_category&category_id=${categoryId}`);
        const result = await response.json();
        if (result.success) {
          return result.data.map(ra => ra.amenity_id);
        }
        return [];
      } catch (error) {
        console.error('Error fetching category amenities:', error);
        return [];
      }
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