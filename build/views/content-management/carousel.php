<?php
/**
 * Carousel Slides Management
 * Admin interface for managing hero banner carousel slides
 */

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
  * { box-sizing: border-box; margin: 0; padding: 0; }

  .page {
    padding: 24px;
    max-width: 100%;
    margin: 0 auto;
  }

  .page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 24px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .page-title { font-size: 22px; font-weight: 500; color: #333; }
  .page-subtitle { font-size: 13px; color: #666; margin-top: 2px; }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
    margin-bottom: 24px;
  }

  .stat-card {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px 20px;
  }

  .stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 8px;
  }

  .stat-value { font-size: 28px; font-weight: 500; }
  .stat-value.blue { color: #185FA5; }
  .stat-value.green { color: #3B6D11; }
  .stat-value.purple { color: #534AB7; }

  .card {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 12px;
  }

  .card-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .card-title { font-size: 16px; font-weight: 500; }

  .toolbar { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    border: 0.5px solid #d1d5db;
    background: #fff;
    color: #333;
    cursor: pointer;
    transition: all 0.15s;
  }

  .btn:hover { background: #f3f4f6; }
  .btn-primary { background: #185FA5; color: #fff; border-color: #185FA5; }
  .btn-primary:hover { background: #0c447c; border-color: #0c447c; }
  .btn-success { background: #3B6D11; color: #fff; border-color: #3B6D11; }
  .btn-success:hover { background: #2d520d; border-color: #2d520d; }
  .btn-danger { background: #dc2626; color: #fff; border-color: #dc2626; }
  .btn-danger:hover { background: #b91c1c; border-color: #b91c1c; }
  .btn i { font-size: 14px; }

  .slides-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 16px;
    padding: 20px;
  }

  .slide-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
    position: relative;
  }

  .slide-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
  .slide-card.inactive { opacity: 0.6; }

  .slide-preview {
    height: 160px;
    background-size: cover;
    background-position: center;
    position: relative;
  }

  .slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.5) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 16px;
  }

  .slide-category {
    font-size: 11px;
    color: rgba(255,255,255,0.9);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 4px;
  }

  .slide-category em { color: #fbbf24; font-style: normal; }

  .slide-headline {
    font-size: 18px;
    font-weight: 600;
    color: #fff;
    line-height: 1.3;
  }

  .slide-content {
    padding: 16px;
  }

  .slide-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 12px;
  }

  .info-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #666;
  }

  .info-row i { width: 16px; color: #999; }

  .slide-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
  }

  .slide-status.active { background: #dcfce7; color: #166534; }
  .slide-status.inactive { background: #f3f4f6; color: #6b7280; }
  .slide-status::before {
    content: "";
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
  }

  .slide-order {
    position: absolute;
    top: 12px;
    left: 12px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(255,255,255,0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
    color: #333;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .slide-actions {
    display: flex;
    gap: 6px;
    padding: 12px 16px;
    border-top: 1px solid #f3f4f6;
    background: #fafafa;
  }

  .slide-actions .btn { padding: 6px 10px; font-size: 12px; }

  .empty-state {
    text-align: center;
    padding: 60px 24px;
    color: #6b7280;
  }

  .empty-state i {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 16px;
  }

  .empty-state h3 { font-size: 18px; font-weight: 500; color: #374151; margin-bottom: 8px; }
  .empty-state p { font-size: 14px; margin-bottom: 20px; }

  /* Modal Styles */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 24px;
  }

  .modal-overlay.open { display: flex; }

  .modal {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  .modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .modal-title { font-size: 16px; font-weight: 500; }

  .modal-close {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    background: transparent;
    color: #6b7280;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .modal-close:hover { background: #f3f4f6; color: #374151; }

  .modal-body {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
    min-height: 0;
  }

  .modal-body::-webkit-scrollbar {
    width: 8px;
  }

  .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  .modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
  }

  .modal-body::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
  }

  .form-group { margin-bottom: 16px; }

  .form-label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
  }

  .form-label .optional { font-weight: 400; color: #9ca3af; }

  .form-input,
  .form-textarea,
  .form-select {
    width: 100%;
    padding: 8px 12px;
    font-size: 13px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: #fff;
    color: #333;
    outline: none;
    transition: border-color 0.15s;
  }

  .form-input:focus,
  .form-textarea:focus,
  .form-select:focus { border-color: #185FA5; }

  .form-textarea { min-height: 80px; resize: vertical; }

  .form-hint {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
  }

  .image-upload {
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.15s;
  }

  .image-upload:hover { border-color: #185FA5; background: #f8fafc; }

  .image-upload i { font-size: 32px; color: #9ca3af; margin-bottom: 8px; }

  .image-upload p { font-size: 13px; color: #6b7280; }

  .image-preview {
    width: 100%;
    height: 150px;
    background-size: cover;
    background-position: center;
    border-radius: 8px;
    margin-top: 12px;
    display: none;
  }

  .image-preview.show { display: block; }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }

  .checkbox-group {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
  }

  .checkbox-group label {
    font-size: 13px;
    color: #374151;
    cursor: pointer;
  }

  .modal-footer {
    padding: 16px 20px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    flex-shrink: 0;
    background: #fff;
  }

  .sortable-ghost { opacity: 0.5; }
  .dragging { opacity: 0.4; cursor: move; }
  .slide-card { cursor: grab; }
  .slide-card:active { cursor: grabbing; }

  /* Cropper styles */
  .cropper-container {
    max-height: 500px;
  }

  .crop-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.8);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    padding: 24px;
  }

  .crop-modal-overlay.open { display: flex; }

  .crop-modal {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 900px;
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  .crop-modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .crop-modal-title { font-size: 16px; font-weight: 500; }

  .crop-modal-body {
    padding: 20px;
    overflow: auto;
    flex: 1;
    min-height: 0;
  }

  .crop-modal-footer {
    padding: 16px 20px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    flex-shrink: 0;
    background: #fff;
  }

  .crop-wrapper {
    max-height: 500px;
    overflow: hidden;
  }

  .crop-wrapper img {
    max-width: 100%;
  }

  /* Custom Confirm Dialog */
  .confirm-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 3000;
    padding: 24px;
  }

  .confirm-overlay.open { display: flex; }

  .confirm-dialog {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 400px;
    padding: 24px;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
  }

  .confirm-title {
    font-size: 18px;
    font-weight: 500;
    color: #111827;
    margin-bottom: 8px;
  }

  .confirm-message {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 20px;
    line-height: 1.5;
  }

  .confirm-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }

  @media (max-width: 768px) {
    .slides-grid { grid-template-columns: 1fr; }
    .form-row { grid-template-columns: 1fr; }
  }
</style>

<div class="page">
  <!-- Page Header Card -->
  <div class="card" style="margin-bottom: 24px;">
    <div class="page-header" style="margin-bottom: 0; padding: 16px 20px;">
      <div>
        <h1 class="page-title">Carousel Slides</h1>
        <p class="page-subtitle">Manage hero banner slides on the public website</p>
      </div>
      <button class="btn btn-primary" onclick="openModal()">
        <i class="fas fa-plus"></i>
        <span>Add Slide</span>
      </button>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Slides</div>
      <div class="stat-value blue" id="stat-total">0</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Active</div>
      <div class="stat-value green" id="stat-active">0</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Inactive</div>
      <div class="stat-value" id="stat-inactive" style="color: #6b7280;">0</div>
    </div>
  </div>

  <!-- Slides Grid -->
  <div class="card">
    <div class="card-header">
      <span class="card-title">All Slides</span>
      <div class="toolbar">
        <span class="form-hint" style="margin: 0;">Drag slides to reorder</span>
      </div>
    </div>
    <div id="slides-container" class="slides-grid">
      <!-- Slides loaded dynamically -->
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="slideModal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title" id="modalTitle">Add New Slide</h3>
      <button class="modal-close" onclick="closeModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form id="slideForm" onsubmit="saveSlide(event)" style="display:flex;flex-direction:column;flex:1;min-height:0;overflow:hidden;">
      <div class="modal-body">
        <input type="hidden" id="slide_id" name="slide_id">

        <!-- Image Upload -->
        <div class="form-group">
          <label class="form-label">Background Image *</label>
          <div class="image-upload" onclick="document.getElementById('slide_image').click()">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Click to upload image</p>
            <p class="form-hint">Required: 1600x800px, JPG or PNG. Image will be cropped to fit.</p>
          </div>
          <input type="file" id="slide_image" name="slide_image" accept="image/*" style="display:none" onchange="handleImagePreview(this)">
          <div class="image-preview" id="imagePreview"></div>
          <input type="hidden" id="image_url" name="image_url">
        </div>

        <!-- Content -->
        <div class="form-group">
          <label class="form-label">Category Label <span class="optional">(e.g., M&M PARAISO)</span></label>
          <input type="text" class="form-input" id="category_label" name="category_label" placeholder="M&M <em>PARAISO</em>">
          <p class="form-hint">Use &lt;em&gt; for emphasized text styling</p>
        </div>

        <div class="form-group">
          <label class="form-label">Headline *</label>
          <input type="text" class="form-input" id="headline" name="headline" placeholder="Experience Paradise" required>
          <p class="form-hint">Use &lt;br&gt; for line breaks</p>
        </div>

        <div class="form-group">
          <label class="form-label">Subheadline <span class="optional">(optional)</span></label>
          <input type="text" class="form-input" id="subheadline" name="subheadline" placeholder="Your perfect getaway awaits">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">CTA Button Text</label>
            <input type="text" class="form-input" id="cta_text" name="cta_text" placeholder="Book Now">
          </div>
          <div class="form-group">
            <label class="form-label">CTA Link</label>
            <input type="text" class="form-input" id="cta_link" name="cta_link" placeholder="properties.php">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Sort Order</label>
            <input type="number" class="form-input" id="sort_order" name="sort_order" value="0" min="0">
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <select class="form-select" id="is_active" name="is_active">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Slide</button>
      </div>
    </form>
  </div>
</div>

<!-- Crop Modal -->
<div class="crop-modal-overlay" id="cropModal">
  <div class="crop-modal">
    <div class="crop-modal-header">
      <h3 class="crop-modal-title">Crop Image (1600x800)</h3>
      <button class="modal-close" onclick="closeCropModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="crop-modal-body">
      <div class="crop-wrapper">
        <img id="cropImage" src="" alt="Crop this image">
      </div>
    </div>
    <div class="crop-modal-footer">
      <button type="button" class="btn" onclick="closeCropModal()">Cancel</button>
      <button type="button" class="btn btn-primary" onclick="applyCrop()">Apply Crop</button>
    </div>
  </div>
</div>

<!-- Custom Confirm Dialog -->
<div class="confirm-overlay" id="confirmDialog">
  <div class="confirm-dialog">
    <h3 class="confirm-title" id="confirmTitle">Confirm Action</h3>
    <p class="confirm-message" id="confirmMessage">Are you sure you want to proceed?</p>
    <div class="confirm-actions">
      <button class="btn" onclick="closeConfirmDialog()">Cancel</button>
      <button class="btn btn-danger" id="confirmBtn">Confirm</button>
    </div>
  </div>
</div>

<!-- Toast Notification -->
<div id="toast" style="
  position: fixed;
  bottom: 24px;
  right: 24px;
  padding: 12px 20px;
  background: #333;
  color: #fff;
  border-radius: 8px;
  font-size: 13px;
  display: none;
  z-index: 1001;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
"></div>

<script>
  let slides = [];
  let editingId = null;

  // Load slides on page load
  document.addEventListener('DOMContentLoaded', loadSlides);

  function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.style.background = type === 'error' ? '#dc2626' : '#333';
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
  }

  async function loadSlides() {
    try {
      const response = await fetch('../../pages/api/carousel-slides.php?admin=1');
      const data = await response.json();

      if (data.success) {
        slides = data.slides || [];
        renderSlides();
        updateStats();
      } else {
        showToast('Failed to load slides', 'error');
      }
    } catch (error) {
      console.error('Error loading slides:', error);
      showToast('Error loading slides', 'error');
    }
  }

  function updateStats() {
    const total = slides.length;
    const active = slides.filter(s => s.is_active == 1).length;
    const inactive = total - active;

    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-active').textContent = active;
    document.getElementById('stat-inactive').textContent = inactive;
  }

  function renderSlides() {
    const container = document.getElementById('slides-container');

    if (slides.length === 0) {
      container.innerHTML = `
        <div class="empty-state" style="grid-column: 1/-1;">
          <i class="fas fa-images"></i>
          <h3>No slides yet</h3>
          <p>Create your first carousel slide to display on the homepage</p>
          <button class="btn btn-primary" onclick="openModal()">
            <i class="fas fa-plus"></i> Add First Slide
          </button>
        </div>
      `;
      return;
    }

    // Sort by sort_order
    const sortedSlides = [...slides].sort((a, b) => a.sort_order - b.sort_order);

    container.innerHTML = sortedSlides.map((slide, index) => `
      <div class="slide-card ${slide.is_active == 1 ? '' : 'inactive'}" data-id="${slide.slide_id}">
        <div class="slide-order">${index + 1}</div>
        <div class="slide-preview" style="background-image: url('${slide.image_url.startsWith('http') ? slide.image_url : '../../' + slide.image_url}')">
          <div class="slide-overlay">
            <div class="slide-category">${slide.category_label || ''}</div>
            <div class="slide-headline">${slide.headline || ''}</div>
          </div>
        </div>
        <div class="slide-content">
          <div class="slide-info">
            <div class="info-row">
              <i class="fas fa-mouse-pointer"></i>
              <span>${slide.cta_text || 'No CTA'} &rarr; ${slide.cta_link || '#'}</span>
            </div>
            <div class="info-row">
              <i class="fas fa-eye"></i>
              <span>${slide.view_count || 0} views</span>
            </div>
            <div class="info-row">
              <span class="slide-status ${slide.is_active == 1 ? 'active' : 'inactive'}">
                ${slide.is_active == 1 ? 'Active' : 'Inactive'}
              </span>
            </div>
          </div>
        </div>
        <div class="slide-actions">
          <button class="btn" onclick="editSlide('${slide.slide_id}')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button class="btn" onclick="toggleStatus('${slide.slide_id}')">
            <i class="fas fa-${slide.is_active == 1 ? 'eye-slash' : 'eye'}"></i>
            ${slide.is_active == 1 ? 'Hide' : 'Show'}
          </button>
          <button class="btn btn-danger" onclick="deleteSlide('${slide.slide_id}')">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    `).join('');

    // Initialize drag and drop after rendering
    initDragAndDrop();
  }

  function openModal(slideId = null) {
    editingId = slideId;
    const modal = document.getElementById('slideModal');
    const form = document.getElementById('slideForm');
    const preview = document.getElementById('imagePreview');

    // Reset form
    form.reset();
    preview.classList.remove('show');
    preview.style.backgroundImage = '';

    if (slideId) {
      // Edit mode
      const slide = slides.find(s => s.slide_id === slideId);
      if (slide) {
        document.getElementById('modalTitle').textContent = 'Edit Slide';
        document.getElementById('slide_id').value = slide.slide_id;
        document.getElementById('image_url').value = slide.image_url || '';
        document.getElementById('category_label').value = slide.category_label || '';
        document.getElementById('headline').value = slide.headline || '';
        document.getElementById('subheadline').value = slide.subheadline || '';
        document.getElementById('cta_text').value = slide.cta_text || '';
        document.getElementById('cta_link').value = slide.cta_link || '';
        document.getElementById('sort_order').value = slide.sort_order || 0;
        document.getElementById('is_active').value = slide.is_active || 1;

        if (slide.image_url) {
          const imageUrl = slide.image_url.startsWith('http') ? slide.image_url : '../../' + slide.image_url;
          preview.style.backgroundImage = `url('${imageUrl}')`;
          preview.classList.add('show');
        }
      }
    } else {
      // Add mode
      document.getElementById('modalTitle').textContent = 'Add New Slide';
      document.getElementById('slide_id').value = '';
      document.getElementById('sort_order').value = slides.length;
    }

    modal.classList.add('open');
  }

  function closeModal() {
    document.getElementById('slideModal').classList.remove('open');
    editingId = null;
  }

  let cropper = null;
  let originalFile = null;

  function handleImagePreview(input) {
    if (input.files && input.files[0]) {
      originalFile = input.files[0];
      const reader = new FileReader();
      reader.onload = function(e) {
        // Open crop modal with the image
        const cropImage = document.getElementById('cropImage');
        cropImage.src = e.target.result;
        openCropModal();
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function openCropModal() {
    const modal = document.getElementById('cropModal');
    const cropImage = document.getElementById('cropImage');
    
    modal.classList.add('open');
    
    // Initialize cropper
    if (cropper) {
      cropper.destroy();
    }
    
    cropper = new Cropper(cropImage, {
      aspectRatio: 16 / 8, // 1600x800 = 2:1 ratio
      viewMode: 1,
      autoCropArea: 1,
      responsive: true,
      guides: true,
      center: true,
      highlight: true,
      cropBoxMovable: true,
      cropBoxResizable: true,
      toggleDragModeOnDblclick: false
    });
  }

  function closeCropModal() {
    const modal = document.getElementById('cropModal');
    modal.classList.remove('open');
    
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
    
    // Reset file input if cancelled
    if (!document.getElementById('imagePreview').classList.contains('show')) {
      document.getElementById('slide_image').value = '';
    }
  }

  function applyCrop() {
    if (!cropper) return;
    
    // Get cropped canvas
    const canvas = cropper.getCroppedCanvas({
      width: 1600,
      height: 800,
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high'
    });
    
    if (canvas) {
      // Convert canvas to blob
      canvas.toBlob(function(blob) {
        // Create a new File object from the blob
        const croppedFile = new File([blob], originalFile.name, {
          type: originalFile.type,
          lastModified: Date.now()
        });
        
        // Create a DataTransfer to set the file
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(croppedFile);
        
        // Update the file input
        const fileInput = document.getElementById('slide_image');
        fileInput.files = dataTransfer.files;
        
        // Update preview
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.getElementById('imagePreview');
          preview.style.backgroundImage = `url('${e.target.result}')`;
          preview.classList.add('show');
        };
        reader.readAsDataURL(croppedFile);
        
        // Close crop modal
        closeCropModal();
      }, originalFile.type || 'image/jpeg', 0.9);
    }
  }

  async function saveSlide(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    // Add file if selected
    const fileInput = document.getElementById('slide_image');
    if (fileInput.files[0]) {
      formData.append('image', fileInput.files[0]);
    }

    try {
      const response = await fetch('../../pages/api/carousel-slides.php', {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      if (data.success) {
        showToast(editingId ? 'Slide updated successfully' : 'Slide created successfully');
        closeModal();
        loadSlides();
      } else {
        showToast(data.error || 'Failed to save slide', 'error');
      }
    } catch (error) {
      console.error('Error saving slide:', error);
      showToast('Error saving slide', 'error');
    }
  }

  function editSlide(slideId) {
    openModal(slideId);
  }

  async function toggleStatus(slideId) {
    const slide = slides.find(s => s.slide_id === slideId);
    if (!slide) return;

    const newStatus = slide.is_active == 1 ? 0 : 1;

    try {
      const response = await fetch('../../pages/api/carousel-slides.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          action: 'toggle',
          slide_id: slideId,
          is_active: newStatus
        })
      });

      const data = await response.json();

      if (data.success) {
        showToast(newStatus == 1 ? 'Slide is now active' : 'Slide is now hidden');
        loadSlides();
      } else {
        showToast(data.error || 'Failed to update slide', 'error');
      }
    } catch (error) {
      console.error('Error toggling slide:', error);
      showToast('Error updating slide', 'error');
    }
  }

  let confirmCallback = null;

  function showConfirmDialog(title, message, callback) {
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    confirmCallback = callback;
    document.getElementById('confirmDialog').classList.add('open');
  }

  function closeConfirmDialog() {
    document.getElementById('confirmDialog').classList.remove('open');
    confirmCallback = null;
  }

  document.getElementById('confirmBtn').addEventListener('click', function() {
    if (confirmCallback) {
      confirmCallback();
    }
    closeConfirmDialog();
  });

  async function deleteSlide(slideId) {
    showConfirmDialog(
      'Delete Slide',
      'Are you sure you want to delete this slide?',
      async () => {
        try {
          const response = await fetch('../../pages/api/carousel-slides.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              action: 'delete',
              slide_id: slideId
            })
          });

          const data = await response.json();

          if (data.success) {
            showToast('Slide deleted successfully');
            loadSlides();
          } else {
            showToast(data.error || 'Failed to delete slide', 'error');
          }
        } catch (error) {
          console.error('Error deleting slide:', error);
          showToast('Error deleting slide', 'error');
        }
      }
    );
  }

  // Drag and drop reordering
  let draggedItem = null;

  function initDragAndDrop() {
    const container = document.getElementById('slides-container');
    const items = container.querySelectorAll('.slide-card');

    items.forEach(item => {
      item.setAttribute('draggable', 'true');
      item.addEventListener('dragstart', handleDragStart);
      item.addEventListener('dragend', handleDragEnd);
      item.addEventListener('dragover', handleDragOver);
      item.addEventListener('drop', handleDrop);
      item.addEventListener('dragenter', handleDragEnter);
      item.addEventListener('dragleave', handleDragLeave);
    });
  }

  function handleDragStart(e) {
    draggedItem = this;
    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
  }

  function handleDragEnd(e) {
    this.classList.remove('dragging');
    document.querySelectorAll('.slide-card').forEach(item => {
      item.classList.remove('sortable-ghost');
    });
  }

  function handleDragOver(e) {
    if (e.preventDefault) e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    return false;
  }

  function handleDragEnter(e) {
    if (this !== draggedItem) {
      this.classList.add('sortable-ghost');
    }
  }

  function handleDragLeave(e) {
    this.classList.remove('sortable-ghost');
  }

  async function handleDrop(e) {
    if (e.stopPropagation) e.stopPropagation();

    if (draggedItem !== this) {
      const container = document.getElementById('slides-container');
      const items = Array.from(container.querySelectorAll('.slide-card'));
      const draggedIndex = items.indexOf(draggedItem);
      const targetIndex = items.indexOf(this);

      // Reorder in DOM
      if (draggedIndex < targetIndex) {
        this.parentNode.insertBefore(draggedItem, this.nextSibling);
      } else {
        this.parentNode.insertBefore(draggedItem, this);
      }

      // Update sort_order in database
      await updateSortOrder();
    }

    return false;
  }

  async function updateSortOrder() {
    const container = document.getElementById('slides-container');
    const items = container.querySelectorAll('.slide-card');
    const slideIds = Array.from(items).map(item => item.dataset.id);

    try {
      const response = await fetch('../../pages/api/carousel-slides.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          action: 'reorder',
          slide_ids: slideIds
        })
      });

      const data = await response.json();

      if (data.success) {
        loadSlides(); // Reload to get updated sort_order values
      } else {
        showToast(data.error || 'Failed to reorder slides', 'error');
      }
    } catch (error) {
      console.error('Error reordering slides:', error);
      showToast('Error reordering slides', 'error');
    }
  }

  // Close modal on overlay click
  document.getElementById('slideModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });

  // Close modal on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
  });
</script>