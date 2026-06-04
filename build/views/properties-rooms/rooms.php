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
    --color-background-tertiary: #f1f5f9;
    --color-border-primary: #185fa5;
    --color-border-secondary: #e2e8f0;
    --color-border-tertiary: #cbd5e1;
    --color-text-primary: #1e293b;
    --color-text-secondary: #64748b;
    --color-text-tertiary: #94a3b8;
    --border-radius-md: 8px;
    --border-radius-lg: 12px;
  }
  .rm-page { padding: 24px; max-width: 100%; margin: 0 auto; overflow-x: hidden; }
  .rm-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: var(--border-radius-lg);
    margin-bottom: 0;
  }
  .rm-card-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid var(--color-border-tertiary);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }
  .rm-card-title { font-size: 16px; font-weight: 500; }
  .rm-toolbar { display: flex; align-items: center; gap: 8px; flex-wrap: nowrap; }
  .rm-search-wrap { position: relative; }
  .rm-search-wrap i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--color-text-secondary); font-size: 13px; pointer-events: none; }
  .rm-search-input {
    padding: 7px 12px 7px 32px;
    font-size: 13px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: var(--color-background-secondary);
    color: var(--color-text-primary);
    outline: none;
    width: 180px;
  }
  .rm-search-input:focus { border-color: var(--color-border-primary); background: var(--color-background-primary); }
  .rm-filter-select {
    padding: 7px 12px;
    font-size: 13px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: var(--color-background-secondary);
    color: var(--color-text-primary);
    outline: none; cursor: pointer;
  }
  .rm-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; font-size: 13px; font-weight: 500;
    border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-secondary);
    background: var(--color-background-primary);
    color: var(--color-text-primary);
    cursor: pointer; transition: background 0.15s; white-space: nowrap;
  }
  .rm-btn:hover { background: var(--color-background-secondary); }
  .rm-btn-primary { background: #185fa5; color: #fff; border-color: #185fa5; }
  .rm-btn-primary:hover { background: #0c447c; border-color: #0c447c; }
  .rm-btn i { font-size: 13px; }
  .rm-table-wrap { overflow-x: auto; }
  .rm-table { width: 100%; border-collapse: collapse; font-size: 13px; table-layout: fixed; }
  .rm-table thead th {
    padding: 10px 16px; text-align: left;
    font-size: 11px; font-weight: 500; color: var(--color-text-secondary);
    text-transform: uppercase; letter-spacing: 0.06em;
    border-bottom: 0.5px solid var(--color-border-tertiary); white-space: nowrap;
  }
  .rm-table thead th:nth-child(1) { width: 28%; }
  .rm-table thead th:nth-child(2) { width: 20%; }
  .rm-table thead th:nth-child(3) { width: 18%; }
  .rm-table thead th:nth-child(4) { width: 10%; }
  .rm-table thead th:nth-child(5) { width: 11%; text-align: center; }
  .rm-table thead th:nth-child(6) { width: 13%; text-align: right; }
  .rm-table tbody tr { border-bottom: 0.5px solid var(--color-border-tertiary); transition: background 0.1s; }
  .rm-table tbody tr:last-child { border-bottom: none; }
  .rm-table tbody tr:hover { background: var(--color-background-secondary); }
  .rm-table td { padding: 12px 16px; vertical-align: middle; }
  .rm-avatar {
    width: 40px; height: 40px;
    border-radius: var(--border-radius-md);
    background: #e6f1fb;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; overflow: hidden;
  }
  .rm-avatar img { width: 100%; height: 100%; object-fit: cover; }
  .rm-avatar i { font-size: 16px; color: #185fa5; }
  .rm-name { font-size: 13px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
  .rm-code { font-size: 11px; color: var(--color-text-secondary); margin-top: 1px; }
  .rm-text { font-size: 13px; color: var(--color-text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .rm-text-secondary { font-size: 12px; color: var(--color-text-secondary); }
  .rm-status-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 9999px; font-size: 11px; font-weight: 500; text-transform: capitalize;
  }
  .rm-status-badge::before { content: ""; width: 5px; height: 5px; border-radius: 50%; background: currentColor; display: inline-block; }
  .rm-status-available  { background: #eaf3de; color: #27500a; }
  .rm-status-occupied   { background: #fee2e2; color: #991b1b; }
  .rm-status-reserved   { background: #dbeafe; color: #1e40af; }
  .rm-status-cleaning   { background: #fef9c3; color: #854d0e; }
  .rm-status-maintenance{ background: #f1f5f9; color: #475569; }
  .rm-status-out_of_order{ background: #fecdd3; color: #9f1239; }
  .rm-status-blocked    { background: #ede9fe; color: #5b21b6; }
  .rm-actions { display: flex; align-items: center; gap: 5px; justify-content: flex-end; }
  .rm-icon-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px;
    border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-tertiary);
    background: transparent; color: var(--color-text-secondary);
    cursor: pointer; transition: all 0.15s; font-size: 13px;
  }
  .rm-icon-btn:hover { background: var(--color-background-secondary); color: var(--color-text-primary); }
  .rm-icon-btn.view:hover  { background: #e6f1fb; color: #0c447c; border-color: #85b7eb; }
  .rm-icon-btn.edit:hover  { background: #faeeda; color: #633806; border-color: #ef9f27; }
  .rm-icon-btn.del:hover   { background: #fcebeb; color: #791f1f; border-color: #f09595; }
  .rm-empty-state { padding: 48px 24px; text-align: center; color: var(--color-text-secondary); font-size: 13px; }
  /* Pagination */
  .rm-pagination {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px; border-top: 0.5px solid var(--color-border-tertiary);
    flex-wrap: wrap; gap: 10px;
  }
  .rm-pag-info { font-size: 12px; color: var(--color-text-secondary); white-space: nowrap; }
  .rm-per-page-wrap { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--color-text-secondary); }
  .rm-per-page-select {
    padding: 4px 8px; font-size: 12px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: var(--color-background-secondary);
    color: var(--color-text-primary); outline: none; cursor: pointer;
  }
  .rm-pag-controls { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
  .rm-pag-btn {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 30px; height: 30px; padding: 0 6px;
    font-size: 12px; border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-secondary);
    background: var(--color-background-primary); color: var(--color-text-secondary);
    cursor: pointer; transition: all 0.15s;
  }
  .rm-pag-btn:hover:not(:disabled) { background: var(--color-background-secondary); color: var(--color-text-primary); }
  .rm-pag-btn.active { background: #185fa5; color: #fff; border-color: #185fa5; }
  .rm-pag-btn:disabled { opacity: 0.4; cursor: not-allowed; }
  /* Modal */
  .rm-modal-backdrop {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.45); z-index: 9000;
    align-items: flex-start; justify-content: center;
    padding: 20px; overflow-y: auto;
  }
  .rm-modal-backdrop.open { display: flex; }
  .rm-modal {
    background: var(--color-background-primary);
    border-radius: 16px;
    border: 0.5px solid var(--color-border-tertiary);
    width: 100%; max-width: 860px; margin: auto;
  }
  .rm-modal-header {
    padding: 18px 24px; border-bottom: 0.5px solid var(--color-border-tertiary);
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; background: var(--color-background-primary);
    z-index: 1; border-radius: 16px 16px 0 0;
  }
  .rm-modal-title { font-size: 15px; font-weight: 600; }
  .rm-modal-close {
    width: 30px; height: 30px; border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-tertiary);
    background: transparent; color: var(--color-text-secondary);
    cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px;
  }
  .rm-modal-close:hover { background: var(--color-background-secondary); }
  .rm-modal-body { padding: 20px 24px; }
  .rm-modal-footer {
    padding: 14px 24px; border-top: 0.5px solid var(--color-border-tertiary);
    display: flex; justify-content: flex-end; gap: 8px;
    position: sticky; bottom: 0; background: var(--color-background-primary);
    border-radius: 0 0 16px 16px;
  }
  /* Form inside modal */
  .rm-section-label { font-size: 11px; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase; color: var(--color-text-secondary); margin: 0 0 12px; padding-bottom: 6px; border-bottom: 0.5px solid var(--color-border-tertiary); }
  .rm-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px; }
  .rm-form-group { display: flex; flex-direction: column; gap: 5px; }
  .rm-form-group.full { grid-column: 1 / -1; }
  .rm-form-group label { font-size: 12px; font-weight: 500; color: var(--color-text-secondary); }
  .rm-form-group input,
  .rm-form-group select,
  .rm-form-group textarea {
    padding: 8px 12px; font-size: 13px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: var(--color-background-secondary);
    color: var(--color-text-primary); outline: none;
    transition: border-color 0.15s;
  }
  .rm-form-group input:focus,
  .rm-form-group select:focus,
  .rm-form-group textarea:focus { border-color: var(--color-border-primary); background: var(--color-background-primary); }
  .rm-form-group textarea { resize: vertical; }
  /* Image entry */
  .rm-image-entry {
    display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;
    padding: 12px; border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md); margin-bottom: 10px; position: relative;
  }
  .rm-image-entry-remove {
    position: absolute; top: 8px; right: 8px;
    width: 22px; height: 22px; border-radius: 50%;
    background: #fee2e2; color: #991b1b; border: none;
    cursor: pointer; font-size: 11px; display: flex; align-items: center; justify-content: center;
  }
  /* Image Cropper Modal */
  .crop-modal {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.7); z-index: 10000;
    align-items: center; justify-content: center;
    padding: 20px;
  }
  .crop-modal.open { display: flex; }
  .crop-modal-content {
    background: white; border-radius: 12px;
    max-width: 900px; width: 100%; max-height: 90vh;
    display: flex; flex-direction: column;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
  }
  .crop-modal-header {
    padding: 16px 20px; border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between;
  }
  .crop-modal-title { font-size: 16px; font-weight: 600; }
  .crop-modal-close {
    background: none; border: none; font-size: 20px;
    color: #64748b; cursor: pointer; padding: 4px;
  }
  .crop-modal-body {
    padding: 20px; flex: 1; overflow: auto;
    display: flex; gap: 20px; flex-wrap: wrap;
    justify-content: center;
  }
  .crop-container {
    flex: 1; min-width: 300px; max-width: 500px;
  }
  .crop-container img { max-width: 100%; display: block; }
  .crop-preview-box {
    width: 350px; padding: 20px;
    background: #f8fafc; border-radius: 8px;
    border: 1px solid #e2e8f0;
  }
  .crop-preview-title {
    font-size: 12px; color: #64748b;
    margin-bottom: 10px; text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .crop-preview {
    width: 350px; height: 260px; overflow: hidden;
    border-radius: 8px; border: 2px solid #e2e8f0;
    background: #fff;
  }
  .crop-preview img { width: 100%; height: 100%; object-fit: cover; }
  .crop-dimensions {
    text-align: center; margin-top: 10px;
    font-size: 12px; color: #64748b;
  }
  .crop-modal-footer {
    padding: 16px 20px; border-top: 1px solid #e2e8f0;
    display: flex; justify-content: flex-end; gap: 10px;
  }
  .crop-btn {
    padding: 10px 20px; border-radius: 8px;
    font-size: 14px; font-weight: 500; cursor: pointer;
    border: 1px solid #e2e8f0; background: #fff;
    transition: all 0.2s;
  }
  .crop-btn:hover { background: #f8fafc; }
  .crop-btn-primary {
    background: #185fa5; color: white; border-color: #185fa5;
  }
  .crop-btn-primary:hover { background: #0c447c; }
  .crop-btn:disabled { opacity: 0.5; cursor: not-allowed; }
  /* Image preview in form */
  .rm-image-preview {
    width: 100%; max-width: 200px; height: 150px;
    object-fit: cover; border-radius: 8px;
    border: 1px solid #e2e8f0; margin-top: 8px;
    display: none;
  }
  .rm-image-preview.show { display: block; }
  .rm-crop-btn {
    font-size: 11px; color: #185fa5; background: #e6f1fb;
    border: 1px solid #185fa5; border-radius: 4px;
    padding: 4px 10px; cursor: pointer; margin-top: 5px;
  }
  .rm-crop-btn:hover { background: #d1e5f7; }
  .rm-add-image-btn { font-size: 12px; color: #185fa5; background: none; border: none; cursor: pointer; padding: 4px 0; display: inline-flex; align-items: center; gap: 4px; }
  .rm-existing-images { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
  .rm-existing-img { position: relative; }
  .rm-existing-img img { width: 80px; height: 80px; object-fit: cover; border-radius: var(--border-radius-md); border: 0.5px solid var(--color-border-secondary); display: block; }
  .rm-existing-img-del {
    position: absolute; top: 4px; right: 4px;
    width: 20px; height: 20px; border-radius: 50%;
    background: rgba(220,38,38,0.85); color: #fff; border: none;
    cursor: pointer; font-size: 10px; display: flex; align-items: center; justify-content: center;
  }
  .rm-existing-img p { font-size: 10px; color: var(--color-text-secondary); margin-top: 3px; width: 80px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: center; }
  /* Toast */
  .rm-toast {
    position: fixed; bottom: 24px; right: 24px;
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    padding: 11px 16px; font-size: 13px;
    display: flex; align-items: center; gap: 8px;
    z-index: 9999; transform: translateY(80px); opacity: 0; transition: all 0.25s;
  }
  .rm-toast.show { transform: none; opacity: 1; }
  .rm-toast.success { border-color: #9fe1cb; color: #085041; }
  .rm-toast.error   { border-color: #f7c1c1; color: #791f1f; }

  /* ── Mobile actions dropdown ── */
  .rm-actions-dropdown {
    position: relative;
    display: none;
  }
  .rm-actions-dropdown-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px;
    border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-tertiary);
    background: transparent; color: var(--color-text-secondary);
    cursor: pointer; font-size: 14px;
  }
  .rm-actions-dropdown-btn:hover { background: var(--color-background-secondary); color: var(--color-text-primary); }
  .rm-actions-dropdown-menu {
    position: absolute; right: 0; top: calc(100% + 4px);
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: var(--border-radius-md);
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    min-width: 150px; z-index: 100; overflow: hidden; display: none;
  }
  .rm-actions-dropdown-menu.open { display: block; }
  .rm-actions-dropdown-menu button {
    display: flex; align-items: center; gap: 8px;
    width: 100%; padding: 8px 12px; font-size: 12px;
    background: transparent; border: none;
    color: var(--color-text-primary); cursor: pointer; text-align: left;
  }
  .rm-actions-dropdown-menu button:hover { background: var(--color-background-secondary); }
  .rm-actions-dropdown-menu button.del-action:hover { background: #fcebeb; color: #791f1f; }
  .rm-actions-dropdown-menu button i { width: 16px; text-align: center; font-size: 13px; color: var(--color-text-secondary); }
  .rm-actions-dropdown-menu button.del-action:hover i { color: #791f1f; }

  /* ── Responsive ── */
  @media (max-width: 1400px) {
    .rm-page { padding: 20px; }
    .rm-search-input { width: 160px; }
  }

  @media (max-width: 1200px) {
    .rm-page { padding: 18px; }
    .rm-card-header { flex-direction: column; align-items: flex-start; }
    .rm-toolbar { width: 100%; flex-wrap: wrap; }
    .rm-search-input { width: 140px; }
    .rm-btn { padding: 6px 12px; font-size: 12px; }
  }

  @media (max-width: 1024px) {
    .rm-table thead th:nth-child(4), .rm-table tbody td:nth-child(4) { display: none; }
    .rm-table thead th:nth-child(1) { width: 32%; }
    .rm-table thead th:nth-child(2) { width: 24%; }
    .rm-table thead th:nth-child(3) { width: 22%; }
    .rm-card-title { font-size: 14px; }
    .rm-table td { padding: 10px 14px; }
    .rm-table thead th { padding: 8px 14px; }
  }

  @media (max-width: 992px) {
    .rm-page { padding: 16px; }
    .rm-card-header { padding: 14px 16px; flex-direction: column; align-items: flex-start; gap: 10px; }
    .rm-toolbar { width: 100%; flex-wrap: wrap; gap: 6px; }
    .rm-search-wrap { flex: 1 1 auto; }
    .rm-search-input { width: 100%; font-size: 12px; }
    .rm-filter-select { flex: 1 1 auto; font-size: 12px; padding: 6px 10px; }
    .rm-btn { padding: 6px 10px; font-size: 12px; }
    .rm-table td { padding: 10px 12px; }
    .rm-table thead th { padding: 8px 12px; font-size: 10px; }
    .rm-avatar { width: 36px; height: 36px; }
    .rm-avatar i { font-size: 14px; }
    .rm-name { font-size: 12px; max-width: 130px; }
    .rm-code { font-size: 10px; }
    .rm-text { font-size: 12px; }
    .rm-status-badge { font-size: 10px; padding: 2px 8px; }
    .rm-icon-btn { width: 28px; height: 28px; font-size: 12px; }
    .rm-pag-info { font-size: 11px; }
    .rm-pag-btn { min-width: 28px; height: 28px; font-size: 11px; }
    /* Modal */
    .rm-modal { max-width: 96vw; }
    .rm-modal-header { padding: 14px 20px; }
    .rm-modal-body { padding: 16px 20px; }
    .rm-modal-footer { padding: 12px 20px; }
  }

  @media (max-width: 768px) {
    .rm-page { padding: 14px; }
    .rm-card-header { padding: 12px; }
    .rm-card-title { font-size: 13px; }
    .rm-toolbar { gap: 6px; }
    .rm-table thead th:nth-child(3), .rm-table tbody td:nth-child(3) { display: none; }
    .rm-table thead th:nth-child(1) { width: 40%; }
    .rm-table thead th:nth-child(2) { width: 24%; }
    .rm-table thead th:nth-child(5) { width: 18%; }
    .rm-table thead th:nth-child(6) { width: 18%; }
    .rm-table td { padding: 8px 10px; }
    .rm-table thead th { padding: 8px 10px; font-size: 9px; }
    .rm-avatar { width: 34px; height: 34px; }
    .rm-name { font-size: 11px; max-width: 110px; }
    .rm-code { font-size: 9px; }
    .rm-status-badge { font-size: 9px; padding: 2px 6px; }
    .rm-icon-btn { width: 26px; height: 26px; font-size: 12px; }
    .rm-actions { gap: 3px; }
    /* Slide-up modal */
    .rm-modal-backdrop { padding: 0; align-items: flex-end; }
    .rm-modal { border-radius: 20px 20px 0 0; max-width: 100%; max-height: 95vh; overflow-y: auto; margin: 0; }
    .rm-modal-header { border-radius: 20px 20px 0 0; padding: 14px 18px; }
    .rm-modal-footer { border-radius: 0; padding: 12px 18px; flex-wrap: wrap; }
    .rm-modal-body { padding: 14px 18px; }
    .rm-modal-title { font-size: 14px; }
    .rm-form-grid { grid-template-columns: 1fr; }
    .rm-form-group.full { grid-column: 1; }
    .rm-image-entry { grid-template-columns: 1fr; }
    .rm-pagination { flex-direction: column; align-items: flex-start; gap: 8px; }
    .rm-per-page-wrap { display: none; }
    /* Crop modal */
    .crop-modal-body { flex-direction: column; }
    .crop-preview-box { width: 100%; }
    .crop-preview { width: 100%; height: auto; aspect-ratio: 4/3; }
  }

  @media (max-width: 640px) {
    .rm-card-header { flex-direction: column; align-items: stretch; }
    .rm-toolbar { flex-direction: column; align-items: stretch; }
    .rm-search-wrap, .rm-filter-select, .rm-btn { width: 100%; }
    .rm-search-input { width: 100%; }
    /* Dropdown actions on small screens */
    .rm-actions .rm-icon-btn { display: none; }
    .rm-actions-dropdown { display: block; }
  }

  @media (max-width: 480px) {
    .rm-page { padding: 12px; }
    .rm-table thead th:nth-child(2), .rm-table tbody td:nth-child(2) { display: none; }
    .rm-table thead th:nth-child(1) { width: 50%; }
    .rm-table thead th:nth-child(5) { width: 20%; }
    .rm-table thead th:nth-child(6) { width: 30%; }
    .rm-name { max-width: 90px; font-size: 11px; }
    .rm-code { display: none; }
    .rm-avatar { width: 32px; height: 32px; }
    .rm-avatar i { font-size: 12px; }
    .rm-actions .rm-icon-btn { display: none; }
    .rm-actions-dropdown { display: block; }
    .rm-modal-footer { flex-direction: column; align-items: stretch; }
    .rm-modal-footer .rm-btn { width: 100%; justify-content: center; }
    .rm-modal-title { font-size: 13px; }
    .rm-form-grid { gap: 10px; }
    .rm-form-group input, .rm-form-group select, .rm-form-group textarea { font-size: 14px; padding: 8px 10px; }
    .rm-section-label { font-size: 10px; }
    .rm-pag-btn { min-width: 26px; height: 26px; font-size: 10px; }
  }

  @media (max-width: 360px) {
    .rm-page { padding: 10px; }
    .rm-card-title { font-size: 12px; }
    .rm-modal-header { padding: 12px 14px; }
    .rm-modal-body { padding: 12px 14px; }
    .rm-modal-footer { padding: 10px 14px; }
    .rm-modal-title { font-size: 12px; }
    .rm-form-group input, .rm-form-group select, .rm-form-group textarea { font-size: 13px; padding: 7px 9px; }
    .rm-pag-btn { min-width: 24px; height: 24px; font-size: 9px; }
    .rm-name { max-width: 70px; }
  }

  @media (max-width: 768px) and (orientation: landscape) {
    .rm-modal-backdrop { padding: 8px; align-items: center; }
    .rm-modal { border-radius: 16px; max-height: 90vh; margin: auto; }
    .rm-modal-header { border-radius: 16px 16px 0 0; }
    .rm-modal-body { padding: 10px 16px; }
    .rm-form-grid { grid-template-columns: 1fr 1fr; }
    .rm-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
  }

  @media (hover: none) and (pointer: coarse) {
    .rm-icon-btn { width: 36px; height: 36px; }
    .rm-pag-btn { min-width: 36px; height: 36px; }
    .rm-btn { padding: 9px 16px; }
    .rm-form-group input, .rm-form-group select, .rm-form-group textarea { font-size: 16px; }
  }
</style>

<script>
  // Helper function to generate UUID (JavaScript version)
  function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      const r = Math.random() * 16 | 0;
      const v = c === 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
  }

  // Helper function to capitalize first letter of each word (PHP ucwords equivalent)
  function ucwords(str) {
    return str.toLowerCase().replace(/\b\w/g, function(char) {
      return char.toUpperCase();
    });
  }

  // Helper function to get icon for image type
  function getImageTypeIcon(imageType) {
    const iconMap = {
      'interior': 'fa-couch',
      'exterior': 'fa-building',
      'bathroom': 'fa-bath',
      'view': 'fa-eye',
      'amenity': 'fa-star',
      'bed': 'fa-bed',
      'balcony': 'fa-sun',
      'other': 'fa-image'
    };
    return iconMap[imageType] || 'fa-image';
  }

  // Data will be fetched from API on page load
  let roomsData = [];
  let propertiesData = [];
  let categoriesData = [];
  let roomImagesData = {};
  let csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";

  // Pagination state
  let currentPage = 1;
  let itemsPerPage = 10;
  let totalItems = 0;
  let totalPages = 0;
  let amenitiesData = [];

  // Fetch data from API on page load
  document.addEventListener('DOMContentLoaded', async function() {
    await fetchRoomsData();
    await fetchAmenitiesData();
  });

  async function fetchRoomsData() {
    try {
      // Fetch rooms with pagination
      const response = await fetch(`../api/rooms.php?action=list&page=${currentPage}&per_page=${itemsPerPage}`);
      const result = await response.json();
      if (result.success) {
        roomsData = result.data.data || [];
        const pagination = result.data.pagination || {};
        totalItems = pagination.total || 0;
        totalPages = pagination.total_pages || 0;

        // Populate roomImagesData from the rooms data
        roomImagesData = {};
        roomsData.forEach(room => {
          if (room.images && room.images.length > 0) {
            roomImagesData[room.room_id] = room.images;
          }
        });
      }

      // Fetch properties for dropdowns
      const propsResponse = await fetch('../api/properties.php?action=list');
      const propsResult = await propsResponse.json();
      if (propsResult.success) {
        propertiesData = propsResult.data.data || propsResult.data || [];
      }

      // Fetch categories for dropdowns
      const catsResponse = await fetch('../api/room_categories.php?action=list');
      const catsResult = await catsResponse.json();
      if (catsResult.success) {
        categoriesData = catsResult.data.data || catsResult.data || [];
      }

      renderRoomsTable();
      populateDropdowns();
    } catch (error) {
      console.error('Error fetching rooms:', error);
      showRmToast('Failed to load rooms data', 'error');
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

  function renderAmenitiesCheckboxes(selectedAmenities = [], containerId = 'amenitiesContainer') {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (amenitiesData.length === 0) {
      container.innerHTML = '<p style="color:var(--color-text-secondary);font-size:13px;">No amenities available</p>';
      return;
    }

    container.innerHTML = `
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;">
        ${amenitiesData.map(amenity => `
          <label style="display:flex;align-items:center;gap:8px;padding:8px;background:var(--color-background-secondary);border-radius:6px;cursor:pointer;transition:background 0.15s;">
            <input type="checkbox" name="amenities[]" value="${amenity.amenity_id}" ${selectedAmenities.includes(amenity.amenity_id) ? 'checked' : ''} style="width:14px;height:14px;accent-color:#185fa5;">
            <div style="display:flex;align-items:center;gap:6px;flex:1;">
              ${amenity.icon_code ? `<i class="fa-solid ${amenity.icon_code}" style="color:#185fa5;font-size:14px;"></i>` : ''}
              <span style="font-size:13px;color:var(--color-text-primary);">${amenity.amenity_name}</span>
            </div>
          </label>
        `).join('')}
      </div>
    `;
  }

  async function fetchRoomAmenities(roomId) {
    try {
      const response = await fetch(`../api/room_amenities.php?action=by_room&room_id=${roomId}`);
      const result = await response.json();
      if (result.success) {
        return result.data.map(ra => ra.amenity_id);
      }
      return [];
    } catch (error) {
      console.error('Error fetching room amenities:', error);
      return [];
    }
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

  function populateDropdowns() {
    // Populate category dropdowns
    const addCategoryDropdown = document.getElementById('addCategoryDropdown');
    const editCategoryDropdown = document.getElementById('editCategoryDropdown');

    if (addCategoryDropdown && categoriesData.length > 0) {
      addCategoryDropdown.innerHTML = '<option value="">Select Category</option>';
      categoriesData.forEach(category => {
        const property = propertiesData.find(p => p.property_id === category.property_id);
        const option = document.createElement('option');
        option.value = category.category_id;
        option.textContent = `${category.category_name} ${property ? '(' + property.property_name + ')' : ''}`;
        option.dataset.propertyId = category.property_id;
        option.dataset.propertyName = property ? property.property_name : '';
        // Store category defaults for auto-population
        option.dataset.basePriceNight = category.base_price_per_night || '';
        option.dataset.basePriceHour = category.base_price_per_hour || '';
        option.dataset.basePriceDayUse = category.base_price_day_use || '';
        option.dataset.floorLevel = category.floor_level || '';
        option.dataset.bedConfiguration = category.bed_configuration || '';
        option.dataset.viewType = category.view_type || '';
        option.dataset.smokingPolicy = category.smoking_policy || 'non_smoking';
        option.dataset.petFriendly = category.pet_friendly || '0';
        option.dataset.allowsHourly = category.allows_hourly || '1';
        option.dataset.allowsOvernight = category.allows_overnight || '1';
        option.dataset.allowsDayUse = category.allows_day_use || '1';
        addCategoryDropdown.appendChild(option);
      });
    }

    if (editCategoryDropdown && categoriesData.length > 0) {
      editCategoryDropdown.innerHTML = '<option value="">Select Category</option>';
      categoriesData.forEach(category => {
        const property = propertiesData.find(p => p.property_id === category.property_id);
        const option = document.createElement('option');
        option.value = category.category_id;
        option.textContent = `${category.category_name} ${property ? '(' + property.property_name + ')' : ''}`;
        option.dataset.propertyId = category.property_id;
        option.dataset.propertyName = property ? property.property_name : '';
        // Store category defaults for auto-population
        option.dataset.basePriceNight = category.base_price_per_night || '';
        option.dataset.basePriceHour = category.base_price_per_hour || '';
        option.dataset.basePriceDayUse = category.base_price_day_use || '';
        option.dataset.floorLevel = category.floor_level || '';
        option.dataset.bedConfiguration = category.bed_configuration || '';
        option.dataset.viewType = category.view_type || '';
        option.dataset.smokingPolicy = category.smoking_policy || 'non_smoking';
        option.dataset.petFriendly = category.pet_friendly || '0';
        option.dataset.allowsHourly = category.allows_hourly || '1';
        option.dataset.allowsOvernight = category.allows_overnight || '1';
        option.dataset.allowsDayUse = category.allows_day_use || '1';
        editCategoryDropdown.appendChild(option);
      });
    }
  }

  async function onCategoryChange(mode) {
    const categoryDropdown = document.getElementById(mode + 'CategoryDropdown');
    const propertyDisplay = document.getElementById(mode + 'PropertyDisplay');
    const containerId = mode === 'add' ? 'amenitiesContainer' : 'editAmenitiesContainer';

    if (categoryDropdown && propertyDisplay) {
      const selectedOption = categoryDropdown.options[categoryDropdown.selectedIndex];
      if (selectedOption && selectedOption.value) {
        propertyDisplay.value = selectedOption.dataset.propertyName || '';

        // Fetch and render amenities for this category
        const categoryAmenities = await fetchCategoryAmenities(selectedOption.value);
        renderAmenitiesCheckboxes(categoryAmenities, containerId);

        // Populate category defaults (only for add mode - edit mode keeps room's own values)
        if (mode === 'add') {
          // Populate pricing defaults (can be overridden)
          const priceNight = document.querySelector('#addRoomForm [name="override_price_per_night"]');
          const priceHour = document.querySelector('#addRoomForm [name="override_price_per_hour"]');
          const priceDayUse = document.querySelector('#addRoomForm [name="override_price_day_use"]');
          if (priceNight && !priceNight.value) priceNight.value = selectedOption.dataset.basePriceNight || '';
          if (priceHour && !priceHour.value) priceHour.value = selectedOption.dataset.basePriceHour || '';
          if (priceDayUse && !priceDayUse.value) priceDayUse.value = selectedOption.dataset.basePriceDayUse || '';

          // Populate physical attributes
          const floorNumber = document.querySelector('#addRoomForm [name="floor_number"]');
          const buildingBlock = document.querySelector('#addRoomForm [name="building_block"]');
          // floor_level from category can suggest floor_number
          if (floorNumber && selectedOption.dataset.floorLevel) {
            const level = selectedOption.dataset.floorLevel.toLowerCase();
            if (level.includes('ground') || level.includes('1st') || level.includes('first')) floorNumber.value = '1';
            else if (level.includes('2nd') || level.includes('second')) floorNumber.value = '2';
            else if (level.includes('3rd') || level.includes('third')) floorNumber.value = '3';
          }
        }
      } else {
        propertyDisplay.value = '';
        renderAmenitiesCheckboxes([], containerId);
      }
    }
  }

  function showRmToast(message, type = 'success') {
    const toast = document.getElementById('rmToast');
    toast.textContent = message;
    toast.className = `rm-toast ${type}`;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  }

  function renderRoomsTable() {
    const tbody = document.querySelector('#roomsTableBody');
    if (!tbody) return;

    if (roomsData.length === 0) {
      tbody.innerHTML = `<tr><td colspan="6"><div class="rm-empty-state">No rooms found. <a href="javascript:;" onclick="openAddRoomModal()" style="color:#185fa5;font-weight:500;">Add your first room</a>.</div></td></tr>`;
      renderPaginationControls();
      return;
    }

    tbody.innerHTML = roomsData.map((room) => {
      const property = propertiesData.find(p => p.property_id === room.property_id);
      const category = categoriesData.find(c => c.category_id === room.category_id);
      const status = room.room_status || 'available';
      const statusLabel = status.replace(/_/g, ' ');
      const avatarHtml = (room.images && room.images.length > 0)
        ? `<div class="rm-avatar"><img src="../${room.images[0].image_url}" alt="${room.room_name || room.room_number}"></div>`
        : `<div class="rm-avatar"><i class="fas fa-door-open"></i></div>`;

      const amenityCount = (room.amenities && room.amenities.length) || 0;

      return `
        <tr class="room-row">
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              ${avatarHtml}
              <div>
                <div class="rm-name">${room.room_name || room.room_number}</div>
                <div class="rm-code">${room.room_number}</div>
              </div>
            </div>
          </td>
          <td><span class="rm-text">${property ? property.property_name : '—'}</span></td>
          <td>
            <span class="rm-text">${category ? category.category_name : '—'}</span>
            ${amenityCount > 0 ? `<span style="display:inline-flex;align-items:center;gap:3px;margin-left:6px;padding:2px 6px;background:#f1f5f9;border-radius:4px;font-size:11px;color:#64748b;"><i class="fas fa-concierge-bell" style="font-size:9px;"></i>${amenityCount}</span>` : ''}
          </td>
          <td><span class="rm-text">${room.floor_number || '—'}</span></td>
          <td style="text-align:center;"><span class="rm-status-badge rm-status-${status}">${statusLabel}</span></td>
          <td>
            <div class="rm-actions">
              <button class="rm-icon-btn view" onclick="viewRoomDetails('${room.room_id}')" title="View"><i class="fas fa-eye"></i></button>
              <button class="rm-icon-btn edit" onclick="openEditRoomModal('${room.room_id}')" title="Edit"><i class="fas fa-pen"></i></button>
              <button class="rm-icon-btn del" onclick="confirmDeleteRoom('${room.room_id}', '${(room.room_name || room.room_number).replace(/'/g, "\\'")}')" title="Delete"><i class="fas fa-trash"></i></button>
              <div class="rm-actions-dropdown" id="rmActDrop-${room.room_id}">
                <button class="rm-actions-dropdown-btn" onclick="rmToggleActionsMenu('${room.room_id}', event)" title="Actions">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="rm-actions-dropdown-menu" id="rmActMenu-${room.room_id}">
                  <button onclick="viewRoomDetails('${room.room_id}');rmCloseActionsMenu('${room.room_id}')">
                    <i class="fas fa-eye"></i> View Details
                  </button>
                  <button onclick="openEditRoomModal('${room.room_id}');rmCloseActionsMenu('${room.room_id}')">
                    <i class="fas fa-pen"></i> Edit Room
                  </button>
                  <button class="del-action" onclick="confirmDeleteRoom('${room.room_id}', '${(room.room_name || room.room_number).replace(/'/g, "\\'")}');rmCloseActionsMenu('${room.room_id}')">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </div>
              </div>
            </div>
          </td>
        </tr>
      `;
    }).join('');

    renderPaginationControls();
  }

  function renderPaginationControls() {
    const container = document.getElementById('paginationControls');
    if (!container) return;

    const startItem = totalItems === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, totalItems);

    let pageButtons = '';
    const maxVisible = 5;
    let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    let end = Math.min(totalPages, start + maxVisible - 1);
    if (end - start < maxVisible - 1) start = Math.max(1, end - maxVisible + 1);
    for (let i = start; i <= end; i++) {
      pageButtons += `<button class="rm-pag-btn${i === currentPage ? ' active' : ''}" onclick="changePage(${i})">${i}</button>`;
    }

    container.innerHTML = `
      <div class="rm-per-page-wrap">
        Show
        <select class="rm-per-page-select" onchange="changeItemsPerPage(this.value)">
          <option value="5"  ${itemsPerPage === 5  ? 'selected' : ''}>5</option>
          <option value="10" ${itemsPerPage === 10 ? 'selected' : ''}>10</option>
          <option value="25" ${itemsPerPage === 25 ? 'selected' : ''}>25</option>
          <option value="50" ${itemsPerPage === 50 ? 'selected' : ''}>50</option>
        </select>
        entries
      </div>
      <span class="rm-pag-info">Showing ${startItem}–${endItem} of ${totalItems}</span>
      <div class="rm-pag-controls">
        <button class="rm-pag-btn" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>
        ${pageButtons}
        <button class="rm-pag-btn" onclick="changePage(${currentPage + 1})" ${currentPage >= totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>
      </div>
    `;
  }

  async function changePage(newPage) {
    if (newPage < 1 || newPage > totalPages) return;
    currentPage = newPage;
    await fetchRoomsData();
  }

  async function changeItemsPerPage(newPerPage) {
    itemsPerPage = parseInt(newPerPage);
    currentPage = 1;
    await fetchRoomsData();
  }

  async function viewRoomDetails(roomId) {
    const room = roomsData.find(r => r.room_id === roomId);
    if (!room) {
      console.error('Room not found:', roomId);
      return;
    }
    console.log('View room:', room.room_id, 'Amenities:', room.amenities);

    const modal = document.getElementById('roomDetailsModal');
    const modalName = document.getElementById('modalRoomName');
    const modalContent = document.getElementById('modalRoomContent');

    modalName.textContent = room.room_name || room.room_number;

    const property = propertiesData.find(p => p.property_id === room.property_id);
    const category = categoriesData.find(c => c.category_id === room.category_id);

    const statusColors = {
      'available':    { bg: '#d1fae5', text: '#065f46', dot: '#10b981' },
      'occupied':     { bg: '#fee2e2', text: '#991b1b', dot: '#ef4444' },
      'reserved':     { bg: '#dbeafe', text: '#1e40af', dot: '#3b82f6' },
      'cleaning':     { bg: '#fef9c3', text: '#854d0e', dot: '#eab308' },
      'maintenance':  { bg: '#f1f5f9', text: '#475569', dot: '#94a3b8' },
      'out_of_order': { bg: '#fecdd3', text: '#9f1239', dot: '#f43f5e' },
      'blocked':      { bg: '#ede9fe', text: '#5b21b6', dot: '#a855f7' }
    };
    const sc = statusColors[room.room_status] || statusColors['maintenance'];
    const statusLabel = (room.room_status || 'unknown').replace(/_/g, ' ');

    const fmt = (val) => val ? `PHP ${parseFloat(val).toLocaleString('en-PH', { minimumFractionDigits: 2 })}` : '<span style="color:var(--color-text-tertiary,#94a3b8);font-style:italic;">Using category default</span>';
    const fmtDate = (d) => d ? new Date(d).toLocaleString('en-PH', { dateStyle: 'medium', timeStyle: 'short' }) : '<span style="color:var(--color-text-tertiary,#94a3b8);font-style:italic;">Never</span>';
    const val = (v) => v || '<span style="color:var(--color-text-tertiary,#94a3b8);">—</span>';

    modalContent.innerHTML = `
      <style>
        .rm-section { margin-bottom: 1.5rem; }
        .rm-section-title { font-size: 11px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #94a3b8; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 0.5px solid #e2e8f0; }
        .rm-grid { display: grid; gap: 1rem; }
        .rm-grid-2 { grid-template-columns: 1fr 1fr; }
        .rm-grid-3 { grid-template-columns: 1fr 1fr 1fr; }
        .rm-field label { font-size: 11px; color: #94a3b8; display: block; margin-bottom: 3px; }
        .rm-field p { font-size: 14px; color: #1e293b; margin: 0; font-weight: 500; }
        .rm-price-card { background: #f8fafc; border: 0.5px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem; }
        .rm-price-card .rm-price-label { font-size: 11px; color: #94a3b8; margin-bottom: 4px; }
        .rm-price-card .rm-price-base { font-size: 15px; font-weight: 600; color: #1e293b; }
        .rm-price-card .rm-price-override { font-size: 12px; margin-top: 4px; color: #64748b; }
        .rm-price-override-active { color: #0f766e !important; font-weight: 600; }
        .rm-images-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 0.75rem; }
        .rm-img-thumb { position: relative; }
        .rm-img-thumb img { width: 100%; height: 90px; object-fit: cover; border-radius: 6px; display: block; cursor: pointer; }
        .rm-img-icon { position: absolute; top: 8px; right: 8px; background: rgba(0,0,0,0.6); color: #fff; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; backdrop-filter: blur(4px); }
        .rm-img-caption { font-size: 13px; color: #94a3b8; margin-top: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-transform: uppercase; font-weight: 600; }
        .rm-status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
        .rm-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        @media (max-width: 600px) {
          .rm-grid-2, .rm-grid-3 { grid-template-columns: 1fr; }
        }
      </style>

      ${(room.images && room.images.length > 0) ? `
      <div class="rm-section">
        <div class="rm-section-title">Photos</div>
        <div class="rm-images-grid">
          ${room.images.map(img => `
            <div class="rm-img-thumb">
              <img src="../${img.image_url}" alt="${img.caption || img.image_type}" onclick="window.open('../${img.image_url}', '_blank')" title="Click to open full size">
              <div class="rm-img-icon"><i class="fas ${getImageTypeIcon(img.image_type)}"></i></div>
              ${img.caption ? `<div class="rm-img-caption">${img.caption}</div>` : ''}
            </div>
          `).join('')}
        </div>
      </div>` : ''}

      <div class="rm-section">
        <div class="rm-section-title">Room identity</div>
        <div class="rm-grid rm-grid-2">
          <div class="rm-field"><label>Room number</label><p>${val(room.room_number)}</p></div>
          <div class="rm-field"><label>Room name</label><p>${val(room.room_name)}</p></div>
          <div class="rm-field"><label>Property</label><p>${val(property ? property.property_name : room.property_name)} <span style="font-size:11px;color:#94a3b8;font-weight:400;">${property ? (property.property_code ? '· ' + property.property_code : '') : ''}</span></p></div>
          <div class="rm-field"><label>Category</label><p>${val(category ? category.category_name : room.category_name)} <span style="font-size:11px;color:#94a3b8;font-weight:400;">${category ? (category.category_code ? '· ' + category.category_code : '') : ''}</span></p></div>
          <div class="rm-field"><label>Floor</label><p>${val(room.floor_number)}</p></div>
          <div class="rm-field"><label>Building block</label><p>${val(room.building_block)}</p></div>
        </div>
      </div>

      <div class="rm-section">
        <div class="rm-section-title">Status</div>
        <span class="rm-status-badge" style="background:${sc.bg};color:${sc.text};">
          <span class="rm-dot" style="background:${sc.dot};"></span>
          ${statusLabel}
        </span>
      </div>

      <div class="rm-section">
        <div class="rm-section-title">Pricing</div>
        <div class="rm-grid rm-grid-3">
          <div class="rm-price-card">
            <div class="rm-price-label">Per night</div>
            <div class="rm-price-base">PHP ${parseFloat((category && category.base_price_per_night) || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
            <div class="rm-price-override ${room.override_price_per_night ? 'rm-price-override-active' : ''}">
              ${room.override_price_per_night ? '↑ Override: PHP ' + parseFloat(room.override_price_per_night).toLocaleString('en-PH', { minimumFractionDigits: 2 }) : 'No override'}
            </div>
          </div>
          <div class="rm-price-card">
            <div class="rm-price-label">Per hour</div>
            <div class="rm-price-base">PHP ${parseFloat((category && category.base_price_per_hour) || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
            <div class="rm-price-override ${room.override_price_per_hour ? 'rm-price-override-active' : ''}">
              ${room.override_price_per_hour ? '↑ Override: PHP ' + parseFloat(room.override_price_per_hour).toLocaleString('en-PH', { minimumFractionDigits: 2 }) : 'No override'}
            </div>
          </div>
          <div class="rm-price-card">
            <div class="rm-price-label">Day use</div>
            <div class="rm-price-base">PHP ${parseFloat((category && category.base_price_day_use) || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
            <div class="rm-price-override ${room.override_price_day_use ? 'rm-price-override-active' : ''}">
              ${room.override_price_day_use ? '↑ Override: PHP ' + parseFloat(room.override_price_day_use).toLocaleString('en-PH', { minimumFractionDigits: 2 }) : 'No override'}
            </div>
          </div>
        </div>
      </div>

      <div class="rm-section">
        <div class="rm-section-title">Housekeeping</div>
        <div class="rm-grid rm-grid-2">
          <div class="rm-field"><label>Last cleaned</label><p style="font-weight:400;">${fmtDate(room.last_cleaned_at)}</p></div>
          <div class="rm-field"><label>Last inspected</label><p style="font-weight:400;">${fmtDate(room.last_inspected_at)}</p></div>
        </div>
      </div>

      ${room.internal_notes ? `
      <div class="rm-section">
        <div class="rm-section-title">Internal notes</div>
        <p style="font-size:14px;color:#475569;margin:0;line-height:1.6;padding:0.75rem 1rem;background:#f8fafc;border-left:3px solid #cbd5e1;border-radius:0 6px 6px 0;">${room.internal_notes}</p>
      </div>` : ''}

      ${(room.amenities && room.amenities.length > 0) ? `
      <div class="rm-section">
        <div class="rm-section-title">Amenities (${room.amenities.length})</div>
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
          ${room.amenities.map(a => `
            <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:#f1f5f9;border:0.5px solid #e2e8f0;border-radius:6px;font-size:13px;color:#475569;">
              ${a.icon_code ? `<i class="fas ${a.icon_code}" style="color:#64748b;"></i>` : ''}
              ${a.amenity_name}
            </span>
          `).join('')}
        </div>
      </div>` : ''}

      <div class="rm-section" style="margin-bottom:0;">
        <div class="rm-section-title">Record info</div>
        <div class="rm-grid rm-grid-2">
          <div class="rm-field"><label>Created</label><p style="font-weight:400;">${fmtDate(room.created_at)}</p></div>
          <div class="rm-field"><label>Last updated</label><p style="font-weight:400;">${fmtDate(room.updated_at)}</p></div>
        </div>
      </div>
    `;

    document.getElementById('roomDetailsModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeRoomDetailsModal() {
    document.getElementById('roomDetailsModal').classList.remove('open');
    document.body.style.overflow = '';
  }

  // Add Room Form Submission (API-based)
  async function handleAddRoomSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Generate UUID for room_id
    const room_id = generateUUID();

    // Get selected amenities
    const selectedAmenities = Array.from(document.querySelectorAll('input[name="amenities[]"]:checked')).map(cb => cb.value);

    // Prepare data for API (property_id is derived from category_id on server)
    const data = {
      csrf_token: formData.get('csrf_token'),
      room_id: room_id,
      category_id: formData.get('category_id'),
      room_number: formData.get('room_number'),
      room_name: formData.get('room_name') || null,
      floor_number: formData.get('floor_number') || null,
      building_block: formData.get('building_block') || null,
      room_status: formData.get('room_status') || 'available',
      override_price_per_night: formData.get('override_price_per_night') || null,
      override_price_per_hour: formData.get('override_price_per_hour') || null,
      override_price_day_use: formData.get('override_price_day_use') || null,
      internal_notes: formData.get('internal_notes') || null,
      amenities: selectedAmenities
    };

    try {
      // First create the room
      const response = await fetch('../api/rooms.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });
      const result = await response.json();

      if (result.success) {
        // Upload cropped images from hidden inputs (base64 data URLs)
        const imageFormData = new FormData();
        const hiddenImageInputs = form.querySelectorAll('input[type="hidden"][name="room_images[]"]');

        let hasImages = false;
        hiddenImageInputs.forEach((input, index) => {
          console.log('Hidden image input:', input.id, 'value:', input.value ? 'has data' : 'empty');
          if (input.value && input.value.startsWith('data:image')) {
            hasImages = true;
            // Convert base64 to file
            const file = dataURLtoFile(input.value, 'cropped_' + Date.now() + '_' + index + '.jpg');
            imageFormData.append('room_images[]', file);

            // Find the corresponding type and caption inputs from the same image entry
            const imageEntry = input.closest('.rm-image-entry');
            const typeInput = imageEntry ? imageEntry.querySelector('select[name="image_type[]"]') : null;
            const captionInput = imageEntry ? imageEntry.querySelector('input[name="image_caption[]"]') : null;

            imageFormData.append('image_type[]', typeInput ? typeInput.value : 'interior');
            imageFormData.append('image_caption[]', captionInput ? captionInput.value : '');
          }
        });

        console.log('Has images:', hasImages);

        if (hasImages) {
          imageFormData.append('room_id', room_id);
          imageFormData.append('csrf_token', formData.get('csrf_token'));
          const imageResponse = await fetch('../api/rooms.php', {
            method: 'POST',
            body: imageFormData
          });
          const imageResult = await imageResponse.json();
          console.log('Image upload result:', imageResult);

          if (!imageResult.success) {
            console.error('Image upload failed:', imageResult.message);
          }
        }

        showRmToast('Room added successfully!');
        closeAddRoomModal();
        await fetchRoomsData();
      } else {
        showRmToast(result.message || 'Failed to add room', 'error');
      }
    } catch (error) {
      showRmToast('An error occurred while adding the room', 'error');
    }
  }

  // Edit Room Form Submission (API-based)
  async function handleEditRoomSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Prepare data for API (property_id is derived from category_id on server)
    const data = {
      csrf_token: formData.get('csrf_token'),
      room_id: formData.get('room_id'),
      category_id: formData.get('category_id'),
      room_number: formData.get('room_number'),
      room_name: formData.get('room_name') || null,
      floor_number: formData.get('floor_number') || null,
      building_block: formData.get('building_block') || null,
      room_status: formData.get('room_status') || 'available',
      override_price_per_night: formData.get('override_price_per_night') || null,
      override_price_per_hour: formData.get('override_price_per_hour') || null,
      override_price_day_use: formData.get('override_price_day_use') || null,
      internal_notes: formData.get('internal_notes') || null,
      amenities: Array.from(document.querySelectorAll('input[name="amenities[]"]:checked')).map(cb => cb.value)
    };

    try {
      // First update the room
      const response = await fetch('../api/rooms.php?action=update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });
      const result = await response.json();

      if (result.success) {
        // Prepare data for image operations
        const imageFormData = new FormData();
        let hasImageOperations = false;
        
        // 1. Process deleted images
        const deleteImageInputs = form.querySelectorAll('input[name="delete_images[]"]');
        deleteImageInputs.forEach(input => {
          if (input.value) {
            hasImageOperations = true;
            imageFormData.append('delete_images[]', input.value);
          }
        });
        
        // 2. Process replaced images
        const replaceInputs = form.querySelectorAll('input[name^="replace_image_"]');
        replaceInputs.forEach((input, index) => {
          if (input.value && input.value.startsWith('data:image')) {
            hasImageOperations = true;
            const imageId = input.name.replace('replace_image_', '');
            const file = dataURLtoFile(input.value, 'replaced_' + imageId + '_' + Date.now() + '.jpg');
            imageFormData.append('replace_images[' + imageId + ']', file);
          }
        });
        
        // 3. Process new cropped images from hidden inputs
        const hiddenImageInputs = form.querySelectorAll('input[type="hidden"][name="room_images[]"]');
        hiddenImageInputs.forEach((input, index) => {
          console.log('Hidden image input:', input.id, 'value:', input.value ? 'has data' : 'empty');
          if (input.value && input.value.startsWith('data:image')) {
            hasImageOperations = true;
            // Convert base64 to file
            const file = dataURLtoFile(input.value, 'cropped_' + Date.now() + '_' + index + '.jpg');
            imageFormData.append('room_images[]', file);

            // Find the corresponding type and caption inputs from the same image entry
            const imageEntry = input.closest('.rm-image-entry');
            const typeInput = imageEntry ? imageEntry.querySelector('select[name="image_type[]"]') : null;
            const captionInput = imageEntry ? imageEntry.querySelector('input[name="image_caption[]"]') : null;

            imageFormData.append('image_type[]', typeInput ? typeInput.value : 'interior');
            imageFormData.append('image_caption[]', captionInput ? captionInput.value : '');
          }
        });

        console.log('Has image operations:', hasImageOperations);

        if (hasImageOperations) {
          imageFormData.append('room_id', data.room_id);
          const csrfToken = document.querySelector('input[name="csrf_token"]').value;
          imageFormData.append('csrf_token', csrfToken);
          
          console.log('Submitting image operations to API...');
          console.log('delete_images:', imageFormData.getAll('delete_images[]'));
          console.log('replace_images:', imageFormData.getAll('replace_images[]'));
          console.log('new images:', imageFormData.getAll('room_images[]'));
          
          try {
            const imageResponse = await fetch('../api/rooms.php', {
              method: 'POST',
              body: imageFormData
            });
            
            if (!imageResponse.ok) {
              console.error('HTTP error:', imageResponse.status, imageResponse.statusText);
              showRmToast('Server error: ' + imageResponse.status, 'error');
              return;
            }
            
            const responseText = await imageResponse.text();
            console.log('Raw API response:', responseText);
            
            let imageResult;
            try {
              imageResult = JSON.parse(responseText);
            } catch (parseError) {
              console.error('Failed to parse JSON:', parseError);
              console.error('Response was:', responseText);
              showRmToast('Server returned invalid response', 'error');
              return;
            }
            
            console.log('Image operations result:', imageResult);

            if (!imageResult.success) {
              console.error('Image operations failed:', imageResult.message);
              showRmToast(imageResult.message || 'Image operations failed', 'error');
              return;
            }
          } catch (fetchError) {
            console.error('Fetch error:', fetchError);
            showRmToast('Network error: ' + fetchError.message, 'error');
            return;
          }
        }

        showRmToast('Room updated successfully!');
        closeEditRoomModal();
        await fetchRoomsData();
      } else {
        showRmToast(result.message || 'Failed to update room', 'error');
      }
    } catch (error) {
      showRmToast('An error occurred while updating the room', 'error');
    }
  }

  // Mobile actions dropdown helpers
  function rmToggleActionsMenu(roomId, event) {
    event.stopPropagation();
    const menu = document.getElementById('rmActMenu-' + roomId);
    document.querySelectorAll('.rm-actions-dropdown-menu.open').forEach(m => { if (m !== menu) m.classList.remove('open'); });
    menu.classList.toggle('open');
  }

  function rmCloseActionsMenu(roomId) {
    const menu = document.getElementById('rmActMenu-' + roomId);
    if (menu) menu.classList.remove('open');
  }

  document.addEventListener('click', function() {
    document.querySelectorAll('.rm-actions-dropdown-menu.open').forEach(m => m.classList.remove('open'));
  });

  // Delete Room Function (API-based)
  async function confirmDeleteRoom(roomId, roomNumber) {
    // Show custom confirmation dialog
    if (!showDeleteConfirmation(roomNumber, roomId)) return;
  }

  function showDeleteConfirmation(roomNumber, roomId) {
    // Create confirmation modal
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
        <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #1e293b;">Delete Room</h3>
        <p style="margin: 0; font-size: 13px; color: #64748b; line-height: 1.5;">
          Are you sure you want to delete room "<strong>${roomNumber}</strong>"? This action cannot be undone.
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

    // Handle cancel
    const cancelBtn = dialog.querySelector('#cancelDeleteBtn');
    cancelBtn.onclick = () => {
      document.body.removeChild(modal);
    };

    // Handle confirm
    const confirmBtn = dialog.querySelector('#confirmDeleteBtn');
    confirmBtn.onclick = async () => {
      document.body.removeChild(modal);
      await executeDeleteRoom(roomId, roomNumber);
    };

    // Close on backdrop click
    modal.onclick = (e) => {
      if (e.target === modal) {
        document.body.removeChild(modal);
      }
    };
  }

  async function executeDeleteRoom(roomId, roomNumber) {
    try {
      const response = await fetch('../api/rooms.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
        body: JSON.stringify({ action: 'delete', room_id: roomId, csrf_token: csrfToken })
      });
      const result = await response.json();
      if (result.success) {
        showRmToast('Room deleted successfully!');
        await fetchRoomsData();
      } else {
        showRmToast(result.message || 'Failed to delete room', 'error');
      }
    } catch (error) {
      showRmToast('An error occurred while deleting the room', 'error');
    }
  }
</script>

<!-- PAGE CONTENT -->
<div class="rm-page">
  <div class="rm-card">
    <div class="rm-card-header">
      <span class="rm-card-title">Rooms</span>
      <div class="rm-toolbar">
        <div class="rm-search-wrap">
          <i class="fas fa-magnifying-glass"></i>
          <input type="text" id="searchInput" class="rm-search-input" placeholder="Search rooms…" oninput="filterTable()">
        </div>
        <select class="rm-filter-select" id="statusFilter" onchange="filterTable()">
          <option value="">All statuses</option>
          <option value="available">Available</option>
          <option value="occupied">Occupied</option>
          <option value="reserved">Reserved</option>
          <option value="cleaning">Cleaning</option>
          <option value="maintenance">Maintenance</option>
          <option value="out_of_order">Out of Order</option>
          <option value="blocked">Blocked</option>
        </select>
        <button class="rm-btn rm-btn-primary" onclick="openAddRoomModal()">
          <i class="fas fa-plus"></i> Add Room
        </button>
      </div>
    </div>
    <div class="rm-table-wrap">
      <table class="rm-table" id="roomsTable">
        <thead>
          <tr>
            <th>Room</th>
            <th>Property</th>
            <th>Category</th>
            <th>Floor</th>
            <th style="text-align:center;">Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="roomsTableBody"></tbody>
      </table>
    </div>
    <div class="rm-pagination" id="paginationControls"></div>
  </div>
</div>

<div id="rmToast" class="rm-toast"></div>

<!-- Room Details Modal -->
<div id="roomDetailsModal" class="rm-modal-backdrop" onclick="if(event.target===this)closeRoomDetailsModal()">
  <div class="rm-modal">
    <div class="rm-modal-header">
      <span class="rm-modal-title" id="modalRoomName">Room Details</span>
      <button class="rm-modal-close" onclick="closeRoomDetailsModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="rm-modal-body" id="modalRoomContent"></div>
  </div>
</div>

<!-- Add Room Modal -->
<div id="addRoomModal" class="rm-modal-backdrop" onclick="if(event.target===this)closeAddRoomModal()">
  <div class="rm-modal">
    <div class="rm-modal-header">
      <span class="rm-modal-title">Add New Room</span>
      <button class="rm-modal-close" onclick="closeAddRoomModal()"><i class="fas fa-times"></i></button>
    </div>
    <form id="addRoomForm" onsubmit="handleAddRoomSubmit(event)">
      <div class="rm-modal-body">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
        <p class="rm-section-label">Basic Info</p>
        <div class="rm-form-grid">
          <div class="rm-form-group">
            <label>Room Category *</label>
            <select name="category_id" id="addCategoryDropdown" required onchange="onCategoryChange('add')">
              <option value="">Select Category</option>
            </select>
          </div>
          <div class="rm-form-group">
            <label>Property</label>
            <input type="text" id="addPropertyDisplay" readonly style="background:#f8fafc;cursor:not-allowed;" placeholder="Select a category">
          </div>
          <div class="rm-form-group">
            <label>Room Number *</label>
            <input type="text" name="room_number" required placeholder="e.g., 101, A-02">
          </div>
          <div class="rm-form-group">
            <label>Room Name</label>
            <input type="text" name="room_name" placeholder="e.g., Sunset Villa">
          </div>
          <div class="rm-form-group">
            <label>Floor Number</label>
            <input type="number" name="floor_number" placeholder="e.g., 1">
          </div>
          <div class="rm-form-group">
            <label>Building Block</label>
            <input type="text" name="building_block" placeholder="e.g., Building A">
          </div>
          <div class="rm-form-group">
            <label>Room Status *</label>
            <select name="room_status" required>
              <option value="available">Available</option>
              <option value="occupied">Occupied</option>
              <option value="reserved">Reserved</option>
              <option value="cleaning">Cleaning</option>
              <option value="maintenance">Maintenance</option>
              <option value="out_of_order">Out of Order</option>
              <option value="blocked">Blocked</option>
            </select>
          </div>
          <div class="rm-form-group full"></div>
        </div>
        <p class="rm-section-label">Pricing (Category defaults auto-populated)</p>
        <div class="rm-form-grid">
          <div class="rm-form-group">
            <label>Per Night (PHP)</label>
            <input type="number" step="0.01" name="override_price_per_night" placeholder="From category or enter custom">
          </div>
          <div class="rm-form-group">
            <label>Per Hour (PHP)</label>
            <input type="number" step="0.01" name="override_price_per_hour" placeholder="From category or enter custom">
          </div>
          <div class="rm-form-group">
            <label>Day Use (PHP)</label>
            <input type="number" step="0.01" name="override_price_day_use" placeholder="From category or enter custom">
          </div>
          <div class="rm-form-group full">
            <label>Internal Notes</label>
            <textarea name="internal_notes" rows="3" placeholder="Any notes about this room..."></textarea>
          </div>
        </div>
        <p class="rm-section-label">Room Images</p>
        <div id="imageUploadContainer">
          <div class="rm-image-entry">
            <div class="rm-form-group"><label>Image File</label><input type="file" name="room_images[]" accept="image/*"></div>
            <div class="rm-form-group"><label>Image Type</label>
              <select name="image_type[]">
                <option value="interior">Interior</option><option value="exterior">Exterior</option>
                <option value="bathroom">Bathroom</option><option value="view">View</option>
                <option value="amenity">Amenity</option><option value="bed">Bed</option>
                <option value="balcony">Balcony</option><option value="other">Other</option>
              </select>
            </div>
            <div class="rm-form-group"><label>Caption</label><input type="text" name="image_caption[]" placeholder="Image caption"></div>
          </div>
        </div>
        <button type="button" class="rm-add-image-btn" onclick="addImageField()"><i class="fas fa-plus"></i> Add another image</button>

        <p class="rm-section-label">Amenities</p>
        <div id="amenitiesContainer"></div>
      </div>
      <div class="rm-modal-footer">
        <button type="button" class="rm-btn" onclick="closeAddRoomModal()">Cancel</button>
        <button type="submit" class="rm-btn rm-btn-primary"><i class="fas fa-save"></i> Save Room</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Room Modal -->
<div id="editRoomModal" class="rm-modal-backdrop" onclick="if(event.target===this)closeEditRoomModal()">
  <div class="rm-modal">
    <div class="rm-modal-header">
      <span class="rm-modal-title">Edit Room</span>
      <button class="rm-modal-close" onclick="closeEditRoomModal()"><i class="fas fa-times"></i></button>
    </div>
    <form id="editRoomForm" onsubmit="handleEditRoomSubmit(event)">
      <div class="rm-modal-body">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
        <input type="hidden" name="room_id" id="edit_room_id">
        <p class="rm-section-label">Basic Info</p>
        <div class="rm-form-grid">
          <div class="rm-form-group">
            <label>Room Category *</label>
            <select name="category_id" id="editCategoryDropdown" required onchange="onCategoryChange('edit')">
              <option value="">Select Category</option>
            </select>
          </div>
          <div class="rm-form-group">
            <label>Property</label>
            <input type="text" id="editPropertyDisplay" readonly style="background:#f8fafc;cursor:not-allowed;" placeholder="Select a category">
          </div>
          <div class="rm-form-group">
            <label>Room Number *</label>
            <input type="text" name="room_number" id="edit_room_number" required placeholder="e.g., 101, A-02">
          </div>
          <div class="rm-form-group">
            <label>Room Name</label>
            <input type="text" name="room_name" id="edit_room_name" placeholder="e.g., Sunset Villa">
          </div>
          <div class="rm-form-group">
            <label>Floor Number</label>
            <input type="number" name="floor_number" id="edit_floor_number" placeholder="e.g., 1">
          </div>
          <div class="rm-form-group">
            <label>Building Block</label>
            <input type="text" name="building_block" id="edit_building_block" placeholder="e.g., Building A">
          </div>
          <div class="rm-form-group">
            <label>Room Status *</label>
            <select name="room_status" id="edit_room_status" required>
              <option value="available">Available</option>
              <option value="occupied">Occupied</option>
              <option value="reserved">Reserved</option>
              <option value="cleaning">Cleaning</option>
              <option value="maintenance">Maintenance</option>
              <option value="out_of_order">Out of Order</option>
              <option value="blocked">Blocked</option>
            </select>
          </div>
        </div>
        <p class="rm-section-label">Pricing (Category defaults auto-populated)</p>
        <div class="rm-form-grid">
          <div class="rm-form-group">
            <label>Per Night (PHP)</label>
            <input type="number" step="0.01" name="override_price_per_night" id="edit_override_price_per_night" placeholder="From category or enter custom">
          </div>
          <div class="rm-form-group">
            <label>Per Hour (PHP)</label>
            <input type="number" step="0.01" name="override_price_per_hour" id="edit_override_price_per_hour" placeholder="From category or enter custom">
          </div>
          <div class="rm-form-group">
            <label>Day Use (PHP)</label>
            <input type="number" step="0.01" name="override_price_day_use" id="edit_override_price_day_use" placeholder="From category or enter custom">
          </div>
          <div class="rm-form-group full">
            <label>Internal Notes</label>
            <textarea name="internal_notes" id="edit_internal_notes" rows="3" placeholder="Any notes about this room..."></textarea>
          </div>
        </div>
        <p class="rm-section-label">Images</p>
        <div class="rm-existing-images" id="existingImagesContainer"></div>
        <p class="rm-section-label" style="margin-top:12px;">Add New Images</p>
        <div id="editImageUploadContainer">
          <div class="rm-image-entry">
            <div class="rm-form-group"><label>Image File</label><input type="file" name="room_images[]" accept="image/*"></div>
            <div class="rm-form-group"><label>Image Type</label>
              <select name="image_type[]">
                <option value="interior">Interior</option><option value="exterior">Exterior</option>
                <option value="bathroom">Bathroom</option><option value="view">View</option>
                <option value="amenity">Amenity</option><option value="bed">Bed</option><option value="balcony">Balcony</option><option value="other">Other</option>
              </select>
            </div>
            <div class="rm-form-group"><label>Caption</label><input type="text" name="image_caption[]" placeholder="Image caption"></div>
          </div>
        </div>
        <button type="button" class="rm-add-image-btn" onclick="addEditImageField()"><i class="fas fa-plus"></i> Add another image</button>

        <p class="rm-section-label" style="margin-top:12px;">Amenities</p>
        <div id="editAmenitiesContainer"></div>
      </div>
      <div class="rm-modal-footer">
        <button type="button" class="rm-btn" onclick="closeEditRoomModal()">Cancel</button>
        <button type="submit" class="rm-btn rm-btn-primary"><i class="fas fa-save"></i> Update Room</button>
      </div>
    </form>
  </div>
</div>

<!-- Image Cropper Modal -->
<div id="imageCropperModal" class="crop-modal" onclick="if(event.target===this)closeCropperModal()">
  <div class="crop-modal-content">
    <div class="crop-modal-header">
      <span class="crop-modal-title"><i class="fas fa-crop"></i> Crop Image (350 × 260)</span>
      <button class="crop-modal-close" onclick="closeCropperModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="crop-modal-body">
      <div class="crop-container">
        <img id="cropperImage" src="" alt="Image to crop">
      </div>
      <div class="crop-preview-box">
        <div class="crop-preview-title">Preview (350 × 260)</div>
        <div class="crop-preview">
          <div id="cropPreview" style="width: 350px; height: 260px; overflow: hidden;"></div>
        </div>
        <div class="crop-dimensions">Drag to position • Scroll to zoom</div>
      </div>
    </div>
    <div class="crop-modal-footer">
      <button type="button" class="crop-btn" onclick="closeCropperModal()">Cancel</button>
      <button type="button" class="crop-btn crop-btn-primary" onclick="applyCrop()">
        <i class="fas fa-check"></i> Apply Crop
      </button>
    </div>
  </div>
</div>

<script>

  // Global cropper instance and current file input reference
  let cropper = null;
  let currentFileInput = null;
  let currentPreviewImg = null;
  let croppedFile = null;

  function openAddRoomModal() {
    document.getElementById('addRoomForm').reset();
    document.getElementById('addPropertyDisplay').value = '';
    // Clear pricing fields so category defaults populate fresh
    document.querySelector('#addRoomForm [name="override_price_per_night"]').value = '';
    document.querySelector('#addRoomForm [name="override_price_per_hour"]').value = '';
    document.querySelector('#addRoomForm [name="override_price_day_use"]').value = '';
    renderAmenitiesCheckboxes();
    // Clear and init first image field with cropper wiring
    document.getElementById('imageUploadContainer').innerHTML = '';
    addImageField();
    document.getElementById('addRoomModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeAddRoomModal() {
    document.getElementById('addRoomModal').classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('addRoomForm').reset();
    document.getElementById('addPropertyDisplay').value = '';
  }

  function addImageField() {
    const container = document.getElementById('imageUploadContainer');
    const imageEntry = document.createElement('div');
    imageEntry.className = 'rm-image-entry';
    const entryId = 'img_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    imageEntry.innerHTML = `
      <button type="button" class="rm-image-entry-remove" onclick="this.closest('.rm-image-entry').remove()"><i class="fas fa-times"></i></button>
      <div class="rm-form-group">
        <label>Image File</label>
        <input type="file" id="${entryId}_file" accept="image/*" onchange="handleImageSelect(this, '${entryId}')">
        <input type="hidden" name="room_images[]" id="${entryId}_input">
        <img id="${entryId}_preview" class="rm-image-preview" style="width: 100%; max-width: 150px; height: 112px; object-fit: cover; border-radius: 6px; margin-top: 8px; display: none;">
        <button type="button" id="${entryId}_cropbtn" class="rm-crop-btn" style="display: none;" onclick="reCropImage('${entryId}')"><i class="fas fa-crop"></i> Crop Again</button>
      </div>
      <div class="rm-form-group"><label>Image Type</label>
        <select name="image_type[]">
          <option value="interior">Interior</option><option value="exterior">Exterior</option>
          <option value="bathroom">Bathroom</option><option value="view">View</option>
          <option value="amenity">Amenity</option><option value="bed">Bed</option>
          <option value="balcony">Balcony</option><option value="other">Other</option>
        </select>
      </div>
      <div class="rm-form-group"><label>Caption</label><input type="text" name="image_caption[]" placeholder="Image caption"></div>
    `;
    container.appendChild(imageEntry);
  }

  // Handle image file selection - open cropper immediately
  function handleImageSelect(fileInput, entryId) {
    console.log('handleImageSelect called with entryId:', entryId);
    console.log('fileInput:', fileInput);
    console.log('files:', fileInput.files);
    
    if (fileInput.files && fileInput.files[0]) {
      const file = fileInput.files[0];
      console.log('Selected file:', file.name, file.size);
      
      // Store reference and open cropper
      currentFileInput = document.getElementById(entryId + '_input');
      currentPreviewImg = document.getElementById(entryId + '_preview');
      const cropBtn = document.getElementById(entryId + '_cropbtn');
      
      console.log('currentFileInput:', currentFileInput);
      console.log('currentPreviewImg:', currentPreviewImg);
      
      // Read file and open cropper modal
      const reader = new FileReader();
      reader.onload = function(e) {
        console.log('File read complete, opening cropper modal');
        // Store original image for re-cropping
        currentFileInput.dataset.originalImage = e.target.result;
        openCropperModal(e.target.result, entryId);
      };
      reader.onerror = function(e) {
        console.error('FileReader error:', e);
        alert('Failed to read image file. Please try again.');
      };
      reader.readAsDataURL(file);
    } else {
      console.log('No file selected');
    }
  }

  // Re-crop existing image
  function reCropImage(entryId) {
    const hiddenInput = document.getElementById(entryId + '_input');
    if (hiddenInput.dataset.originalImage) {
      currentFileInput = hiddenInput;
      currentPreviewImg = document.getElementById(entryId + '_preview');
      openCropperModal(hiddenInput.dataset.originalImage, entryId);
    }
  }

  // Open cropper modal with image
  function openCropperModal(imageSrc, entryId) {
    console.log('Opening cropper modal for entry:', entryId);
    const modal = document.getElementById('imageCropperModal');
    const image = document.getElementById('cropperImage');
    
    if (!modal) {
      console.error('Cropper modal not found!');
      return;
    }
    
    // Show modal first
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    
    // Set image source and init cropper when loaded
    image.src = imageSrc;
    
    // Handle both cached and new images
    if (image.complete) {
      console.log('Image already loaded (cached), initializing cropper');
      initCropper();
    } else {
      console.log('Waiting for image to load...');
      image.onload = function() {
        console.log('Image loaded, initializing cropper');
        initCropper();
      };
      image.onerror = function() {
        console.error('Failed to load image');
        alert('Failed to load image. Please try again.');
        closeCropperModal();
      };
    }
  }

  // Initialize Cropper.js with 350x260 aspect ratio
  function initCropper() {
    const image = document.getElementById('cropperImage');
    const preview = document.getElementById('cropPreview');
    
    // Destroy existing cropper if any
    if (cropper) {
      cropper.destroy();
    }
    
    cropper = new Cropper(image, {
      aspectRatio: 350 / 260, // Exact target ratio
      viewMode: 1,
      dragMode: 'move',
      autoCropArea: 0.8,
      restore: false,
      guides: true,
      center: true,
      highlight: false,
      cropBoxMovable: true,
      cropBoxResizable: true,
      toggleDragModeOnDblclick: false,
      minCropBoxWidth: 175,
      minCropBoxHeight: 130,
      preview: '#cropPreview',
      ready: function() {
        // Set crop box to exact 350x260 on canvas
        const canvasData = cropper.getCanvasData();
        const scale = Math.min(canvasData.width / 350, canvasData.height / 260);
        cropper.setCropBoxData({
          width: 350 * scale,
          height: 260 * scale
        });
      }
    });
  }

  // Close cropper modal
  function closeCropperModal() {
    const modal = document.getElementById('imageCropperModal');
    modal.classList.remove('open');
    document.body.style.overflow = '';
    
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
    
    // Clear file input if no crop was applied (cancel without saving)
    if (currentFileInput && !currentFileInput.value) {
      // Find the visible file input using ID pattern (entryId_file)
      const entryId = currentFileInput.id.replace('_input', '');
      const fileInput = document.getElementById(entryId + '_file');
      if (fileInput) fileInput.value = '';
    }
    
    currentFileInput = null;
    currentPreviewImg = null;
  }

  // Apply crop and save to hidden input
  function applyCrop() {
    if (!cropper) return;
    
    // Get cropped canvas at exact 350x260 size
    const canvas = cropper.getCroppedCanvas({
      width: 350,
      height: 260,
      fillColor: '#fff',
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high'
    });
    
    // Convert to blob and then to file
    canvas.toBlob(function(blob) {
      if (!blob) {
        console.error('Failed to create blob from canvas');
        return;
      }
      
      // Create file from blob
      const fileName = 'cropped_' + Date.now() + '.jpg';
      croppedFile = new File([blob], fileName, { type: 'image/jpeg', lastModified: Date.now() });
      
      // Convert to data URL for preview and storage
      const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
      
      if (currentFileInput) {
        // Check if this is a replacement of existing image
        const isReplacement = currentFileInput.id.startsWith('replace_img_');
        
        if (isReplacement) {
          // Handle existing image replacement
          const imageId = currentFileInput.id.replace('replace_img_', '');
          
          // Store cropped data in replace hidden input
          currentFileInput.value = dataUrl;
          currentFileInput.dataset.originalImage = document.getElementById('cropperImage').src;
          
          // Update preview image to show replacement
          const previewImg = document.getElementById('existing_img_src_' + imageId);
          if (previewImg) {
            previewImg.src = dataUrl;
            previewImg.style.border = '2px solid #185fa5';
          }
          
          // Update caption to indicate replacement
          const caption = document.getElementById('existing_img_caption_' + imageId);
          if (caption) {
            caption.innerHTML = '<span style="color: #185fa5; font-weight: 500;"><i class="fas fa-sync-alt"></i> Will be replaced</span>';
          }
          
          showRmToast('Image will be replaced when you save changes');
        } else {
          // Handle new image (original flow)
          currentFileInput.value = dataUrl;
          currentFileInput.dataset.originalImage = document.getElementById('cropperImage').src;
          
          // Update preview
          if (currentPreviewImg) {
            currentPreviewImg.src = dataUrl;
            currentPreviewImg.style.display = 'block';
          }
          
          // Show crop again button
          const cropBtn = document.getElementById(currentFileInput.id.replace('_input', '_cropbtn'));
          if (cropBtn) cropBtn.style.display = 'inline-block';
        }
      }
      
      closeCropperModal();
    }, 'image/jpeg', 0.9);
  }

  // Convert data URL to File object for form submission
  function dataURLtoFile(dataurl, filename) {
    const arr = dataurl.split(',');
    const mime = arr[0].match(/:(.*?);/)[1];
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);
    while (n--) {
      u8arr[n] = bstr.charCodeAt(n);
    }
    return new File([u8arr], filename, { type: mime });
  }

  function openEditRoomModal(roomId) {
    const room = roomsData.find(r => r.room_id === roomId);
    if (!room) {
      console.error('Room not found for edit:', roomId);
      return;
    }
    console.log('Edit room:', room.room_id, 'Amenities:', room.amenities);

    // Reset image upload container and add cropping-enabled field
    document.getElementById('editImageUploadContainer').innerHTML = '';
    addEditImageField();

    // Populate form fields with existing data
    document.getElementById('edit_room_id').value = room.room_id;
    document.getElementById('editCategoryDropdown').value = room.category_id;

    // Update property display
    const selectedOption = document.getElementById('editCategoryDropdown').options[document.getElementById('editCategoryDropdown').selectedIndex];
    if (selectedOption) {
      document.getElementById('editPropertyDisplay').value = selectedOption.dataset.propertyName || '';
    }

    document.getElementById('edit_room_number').value = room.room_number;
    document.getElementById('edit_room_name').value = room.room_name || '';
    document.getElementById('edit_floor_number').value = room.floor_number || '';
    document.getElementById('edit_building_block').value = room.building_block || '';
    document.getElementById('edit_room_status').value = room.room_status;
    document.getElementById('edit_override_price_per_night').value = room.override_price_per_night || '';
    document.getElementById('edit_override_price_per_hour').value = room.override_price_per_hour || '';
    document.getElementById('edit_override_price_day_use').value = room.override_price_day_use || '';
    document.getElementById('edit_internal_notes').value = room.internal_notes || '';

    // Load existing images
    loadExistingImages(roomId);

    // Render room amenities from room data (already fetched with the list)
    const roomAmenityIds = (room.amenities || []).map(a => a.amenity_id);
    renderAmenitiesCheckboxes(roomAmenityIds, 'editAmenitiesContainer');

    document.getElementById('editRoomModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeEditRoomModal() {
    document.getElementById('editRoomModal').classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('editRoomForm').reset();
    document.getElementById('editPropertyDisplay').value = '';
    document.getElementById('existingImagesContainer').innerHTML = '';
    document.getElementById('editImageUploadContainer').innerHTML = '';
    addEditImageField(); // Use the cropping-enabled image field
  }

  function addEditImageField() {
    const container = document.getElementById('editImageUploadContainer');
    const imageEntry = document.createElement('div');
    imageEntry.className = 'rm-image-entry';
    const entryId = 'edit_img_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    imageEntry.innerHTML = `
      <button type="button" class="rm-image-entry-remove" onclick="this.closest('.rm-image-entry').remove()"><i class="fas fa-times"></i></button>
      <div class="rm-form-group">
        <label>Image File</label>
        <input type="file" id="${entryId}_file" accept="image/*" onchange="handleImageSelect(this, '${entryId}')">
        <input type="hidden" name="room_images[]" id="${entryId}_input">
        <img id="${entryId}_preview" class="rm-image-preview" style="width: 100%; max-width: 150px; height: 112px; object-fit: cover; border-radius: 6px; margin-top: 8px; display: none;">
        <button type="button" id="${entryId}_cropbtn" class="rm-crop-btn" style="display: none;" onclick="reCropImage('${entryId}')"><i class="fas fa-crop"></i> Crop Again</button>
      </div>
      <div class="rm-form-group"><label>Image Type</label>
        <select name="image_type[]">
          <option value="interior">Interior</option><option value="exterior">Exterior</option>
          <option value="bathroom">Bathroom</option><option value="view">View</option>
          <option value="amenity">Amenity</option><option value="bed">Bed</option>
          <option value="balcony">Balcony</option><option value="other">Other</option>
        </select>
      </div>
      <div class="rm-form-group"><label>Caption</label><input type="text" name="image_caption[]" placeholder="Image caption"></div>
    `;
    container.appendChild(imageEntry);
  }

  function loadExistingImages(roomId) {
    const container = document.getElementById('existingImagesContainer');
    const images = roomImagesData[roomId] || [];

    if (images.length === 0) {
      container.innerHTML = '<p class="text-sm text-slate-500">No images uploaded yet.</p>';
      return;
    }

    container.innerHTML = images.map(img => `
      <div class="rm-existing-img" id="existing_img_${img.image_id}">
        <img src="../${img.image_url}" alt="${img.caption || ''}" id="existing_img_src_${img.image_id}">
        <div class="rm-existing-img-actions">
          <button type="button" class="rm-existing-img-replace" onclick="triggerReplaceImage('${img.image_id}')" title="Replace Image"><i class="fas fa-sync-alt"></i></button>
          <button type="button" class="rm-existing-img-del" onclick="markImageForDeletion('${img.image_id}', this)" title="Delete Image"><i class="fas fa-times"></i></button>
        </div>
        <input type="hidden" name="delete_images[]" id="delete_img_${img.image_id}" value="">
        <input type="hidden" name="replace_image_${img.image_id}" id="replace_img_${img.image_id}" value="">
        <input type="file" id="replace_file_${img.image_id}" accept="image/*" style="display:none" onchange="handleReplaceImageSelect(this, '${img.image_id}')">
        <p id="existing_img_caption_${img.image_id}">${img.caption || img.image_type}</p>
      </div>
    `).join('');
  }

  // Trigger file input for replacing an existing image
  function triggerReplaceImage(imageId) {
    document.getElementById('replace_file_' + imageId).click();
  }

  // Handle file selection for replacing existing image
  function handleReplaceImageSelect(fileInput, imageId) {
    if (fileInput.files && fileInput.files[0]) {
      const file = fileInput.files[0];
      const replaceInput = document.getElementById('replace_img_' + imageId);
      
      // Store reference for replacement
      currentFileInput = replaceInput;
      currentPreviewImg = document.getElementById('existing_img_src_' + imageId);
      
      const reader = new FileReader();
      reader.onload = function(e) {
        // Store original for re-cropping
        replaceInput.dataset.originalImage = e.target.result;
        replaceInput.dataset.imageId = imageId;
        openCropperModal(e.target.result, 'replace_' + imageId);
      };
      reader.readAsDataURL(file);
    }
  }

  // Apply crop for replaced image and update preview
  function applyReplaceCrop(imageId) {
    if (!cropper) return;
    
    const canvas = cropper.getCroppedCanvas({
      width: 350,
      height: 260,
      fillColor: '#fff',
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high'
    });
    
    if (!canvas) {
      alert('Could not generate cropped image. Please try again.');
      return;
    }
    
    // Convert to data URL
    const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
    
    // Update the hidden input with cropped data
    const replaceInput = document.getElementById('replace_img_' + imageId);
    replaceInput.value = croppedDataUrl;
    
    // Update the preview image
    const previewImg = document.getElementById('existing_img_src_' + imageId);
    previewImg.src = croppedDataUrl;
    previewImg.style.border = '2px solid #185fa5'; // Blue border to indicate replaced
    
    // Update caption text to show replaced
    const caption = document.getElementById('existing_img_caption_' + imageId);
    caption.innerHTML = '<span style="color: #185fa5; font-weight: 500;"><i class="fas fa-sync-alt"></i> Will be replaced</span>';
    
    // Close modal and cleanup
    closeCropperModal();
    
    showRmToast('Image will be replaced when you save changes');
  }

  function markImageForDeletion(imageId, button) {
    const input = document.getElementById(`delete_img_${imageId}`);
    const wrapper = button.closest('.rm-existing-img');
    if (input.value === imageId) {
      input.value = '';
      wrapper.style.opacity = '1';
    } else {
      input.value = imageId;
      wrapper.style.opacity = '0.4';
    }
  }

  // Filter categories based on selected property
  document.addEventListener('DOMContentLoaded', function() {
    const propertySelect = document.querySelector('select[name="property_id"]');
    const categorySelect = document.querySelector('select[name="category_id"]');

    if (propertySelect && categorySelect) {
      propertySelect.addEventListener('change', function() {
        const selectedPropertyId = this.value;
        const categoryOptions = categorySelect.querySelectorAll('option[data-property-id]');

        categoryOptions.forEach(option => {
          if (option.dataset.propertyId === selectedPropertyId || selectedPropertyId === '') {
            option.style.display = '';
          } else {
            option.style.display = 'none';
          }
        });

        // Reset category selection if current selection is not valid
        if (selectedPropertyId !== '' && categorySelect.value !== '') {
          const selectedOption = categorySelect.querySelector('option[value="' + categorySelect.value + '"]');
          if (selectedOption && selectedOption.dataset.propertyId !== selectedPropertyId) {
            categorySelect.value = '';
          }
        }
      });
    }
  });

  window.addEventListener('load', function() {
    <?php if (isset($error_message)): ?>
      showRmToast(<?php echo json_encode($error_message); ?>, 'error');
    <?php endif; ?>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'room_added') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showRmToast('Room added successfully!');
    }
    if (urlParams.get('success') === 'room_deleted') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showRmToast('Room deleted successfully!');
    }
    if (urlParams.get('success') === 'room_updated') {
      window.history.replaceState({}, document.title, window.location.pathname);
      showRmToast('Room updated successfully!');
    }
  });

  function filterTable() {
    const filter = (document.getElementById('searchInput')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value || '').toLowerCase();
    const rows = document.querySelectorAll('#roomsTableBody .room-row');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      const matchText = !filter || text.includes(filter);
      const matchStatus = !statusFilter || text.includes(statusFilter.replace(/_/g, ' '));
      row.style.display = (matchText && matchStatus) ? '' : 'none';
    });
  }
</script>