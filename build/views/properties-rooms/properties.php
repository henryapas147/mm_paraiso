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
    --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    --font-mono: ui-monospace, SFMono-Regular, "SF Mono", Menlo, Consolas, monospace;
  }
  
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
  .page-title {
    font-size: 22px;
    font-weight: 500;
  }
  .page-sub {
    font-size: 13px;
    color: var(--color-text-secondary);
    margin-top: 2px;
  }
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
    margin-bottom: 24px;
  }
  .stat-card {
    background: var(--color-background-secondary);
    border-radius: var(--border-radius-md);
    padding: 16px 18px;
  }
  .stat-label {
    font-size: 12px;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 6px;
  }
  .stat-value {
    font-size: 26px;
    font-weight: 500;
    color: var(--color-text-primary);
  }
  .stat-value.blue {
    color: #185fa5;
  }
  .stat-value.green {
    color: #3b6d11;
  }
  .stat-value.amber {
    color: #854f0b;
  }
  .card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: var(--border-radius-lg);
  }
  .card-header {
    padding: 16px 20px;
    border-bottom: 0.5px solid var(--color-border-tertiary);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }
  .card-title {
    font-size: 16px;
    font-weight: 500;
  }
  .toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap;
  }
  .search-wrap {
    position: relative;
  }
  .search-wrap i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    font-size: 15px;
    pointer-events: none;
  }
  .search-input {
    padding: 7px 12px 7px 34px;
    font-size: 13px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: var(--color-background-secondary);
    color: var(--color-text-primary);
    outline: none;
    width: 180px;
  }
  .search-input:focus {
    border-color: var(--color-border-primary);
    background: var(--color-background-primary);
  }
  .filter-select {
    padding: 7px 12px;
    font-size: 13px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: var(--color-background-secondary);
    color: var(--color-text-primary);
    outline: none;
    cursor: pointer;
  }
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-secondary);
    background: var(--color-background-primary);
    color: var(--color-text-primary);
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
  }
  .btn:hover {
    background: var(--color-background-secondary);
  }
  .btn-primary {
    background: #185fa5;
    color: #fff;
    border-color: #185fa5;
  }
  .btn-primary:hover {
    background: #0c447c;
    border-color: #0c447c;
  }
  .btn i {
    font-size: 14px;
  }
  .table-wrap {
    overflow-x: auto;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    table-layout: fixed;
  }
  thead th {
    padding: 10px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 500;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 0.5px solid var(--color-border-tertiary);
    white-space: nowrap;
  }
  thead th.center {
    text-align: center;
  }
  thead th:nth-child(1) {
    width: 30%;
  }
  thead th:nth-child(2) {
    width: 14%;
  }
  thead th:nth-child(3) {
    width: 18%;
  }
  thead th:nth-child(4) {
    width: 16%;
  }
  thead th:nth-child(5) {
    width: 10%;
    text-align: center;
  }
  thead th:nth-child(6) {
    width: 12%;
    text-align: right;
  }
  tbody tr {
    border-bottom: 0.5px solid var(--color-border-tertiary);
    transition: background 0.1s;
  }
  tbody tr:last-child {
    border-bottom: none;
  }
  tbody tr:hover {
    background: var(--color-background-secondary);
  }
  td {
    padding: 12px 16px;
    vertical-align: middle;
  }
  .prop-avatar {
    width: 42px;
    height: 42px;
    border-radius: var(--border-radius-md);
    background: #e6f1fb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
  }
  .prop-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .prop-avatar i {
    font-size: 18px;
    color: #185fa5;
  }
  .prop-name {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
  }
  .prop-code {
    font-family: var(--font-mono);
    font-size: 11px;
    color: var(--color-text-secondary);
    margin-top: 2px;
  }
  .type-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    background: #eeedfe;
    color: #3c3489;
    white-space: nowrap;
    text-transform: capitalize;
  }
  .city-text {
    font-size: 13px;
    color: var(--color-text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .date-text {
    font-size: 12px;
    color: var(--color-text-secondary);
  }
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
  }
  .status-badge.active {
    background: #eaf3de;
    color: #27500a;
  }
  .status-badge.inactive {
    background: #f1efe8;
    color: #5f5e5a;
  }
  .status-badge::before {
    content: "";
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
    display: inline-block;
  }
  .actions-cell {
    display: flex;
    align-items: center;
    gap: 5px;
    justify-content: flex-end;
  }
  .icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-tertiary);
    background: transparent;
    color: var(--color-text-secondary);
    cursor: pointer;
    transition: all 0.15s;
    font-size: 14px;
  }
  .icon-btn:hover {
    background: var(--color-background-secondary);
    color: var(--color-text-primary);
    border-color: var(--color-border-secondary);
  }
  .icon-btn.view:hover {
    background: #e6f1fb;
    color: #0c447c;
    border-color: #85b7eb;
  }
  .icon-btn.rooms:hover {
    background: #eaf3de;
    color: #27500a;
    border-color: #97c459;
  }
  .icon-btn.edit:hover {
    background: #faeeda;
    color: #633806;
    border-color: #ef9f27;
  }
  .icon-btn.del:hover {
    background: #fcebeb;
    color: #791f1f;
    border-color: #f09595;
  }
  .empty-state {
    padding: 48px 24px;
    text-align: center;
    color: var(--color-text-secondary);
  }
  .empty-state i {
    font-size: 32px;
    margin-bottom: 12px;
    display: block;
    color: var(--color-text-tertiary);
  }

  .rooms-row td {
    padding: 0;
    background: var(--color-background-secondary);
  }
  .rooms-panel {
    padding: 20px;
  }
  .rooms-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }
  .rooms-panel-title {
    font-size: 14px;
    font-weight: 500;
  }
  .rooms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 10px;
  }
  .room-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: var(--border-radius-md);
    padding: 12px;
  }
  .room-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
  }
  .room-num {
    font-size: 13px;
    font-weight: 500;
  }
  .room-status {
    font-size: 10px;
    font-weight: 500;
    padding: 2px 7px;
    border-radius: 9999px;
  }
  .rs-available {
    background: #eaf3de;
    color: #27500a;
  }
  .rs-occupied {
    background: #fcebeb;
    color: #791f1f;
  }
  .rs-maintenance {
    background: #faeeda;
    color: #633806;
  }
  .rs-reserved {
    background: #e6f1fb;
    color: #0c447c;
  }
  .room-meta {
    font-size: 11px;
    color: var(--color-text-secondary);
    line-height: 1.7;
  }
  .room-price {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-primary);
    margin-top: 4px;
  }
  .view-toggle {
    display: flex;
    gap: 4px;
  }
  .vt-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-secondary);
    background: transparent;
    color: var(--color-text-secondary);
    cursor: pointer;
    font-size: 13px;
  }
  .vt-btn.active {
    background: #185fa5;
    color: #fff;
    border-color: #185fa5;
  }
  .rooms-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .room-list-item {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: var(--border-radius-md);
    padding: 10px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }
  .room-list-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .room-list-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border-top: 0.5px solid var(--color-border-tertiary);
    flex-wrap: wrap;
    gap: 10px;
  }
  .pag-info {
    font-size: 12px;
    color: var(--color-text-secondary);
  }
  .pag-controls {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .pag-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    padding: 0 6px;
    border-radius: var(--border-radius-md);
    border: 0.5px solid var(--color-border-secondary);
    background: transparent;
    color: var(--color-text-secondary);
    cursor: pointer;
    font-size: 12px;
  }
  .pag-btn:hover:not(:disabled) {
    background: var(--color-background-secondary);
    color: var(--color-text-primary);
  }
  .pag-btn.active {
    background: #185fa5;
    color: #fff;
    border-color: #185fa5;
  }
  .pag-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }
  .per-page-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--color-text-secondary);
  }

  .modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: 9000;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .modal-backdrop.open {
    display: flex;
  }
  .modal {
    background: var(--color-background-primary);
    border-radius: 16px;
    border: 0.5px solid var(--color-border-tertiary);
    width: 100%;
    max-width: 860px;
    max-height: 90vh;
    overflow-y: auto;
  }
  .modal-header {
    padding: 20px 24px;
    border-bottom: 0.5px solid var(--color-border-tertiary);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    background: var(--color-background-primary);
    z-index: 1;
  }
  .modal-title {
    font-size: 16px;
    font-weight: 500;
  }
  .modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--color-text-secondary);
    font-size: 20px;
    line-height: 1;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--border-radius-md);
  }
  .modal-close:hover {
    color: var(--color-text-primary);
    background: var(--color-background-secondary);
  }
  .modal-body {
    padding: 24px;
  }
  .modal-footer {
    padding: 16px 24px;
    border-top: 0.5px solid var(--color-border-tertiary);
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    position: sticky;
    bottom: 0;
    background: var(--color-background-primary);
  }
  .section-label {
    font-size: 11px;
    font-weight: 500;
    color: var(--color-text-tertiary);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 12px;
    margin-top: 20px;
    padding-bottom: 8px;
    border-bottom: 0.5px solid var(--color-border-tertiary);
  }
  .section-label:first-child {
    margin-top: 0;
  }
  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }
  .form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
  }
  .form-group.full {
    grid-column: 1/-1;
  }
  label {
    font-size: 12px;
    font-weight: 500;
    color: var(--color-text-secondary);
  }
  label .req {
    color: #a32d2d;
    margin-left: 2px;
  }
  input,
  select,
  textarea {
    font-family: var(--font-sans);
    font-size: 13px;
    color: var(--color-text-primary);
    background: var(--color-background-secondary);
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    padding: 8px 11px;
    outline: none;
    width: 100%;
    transition: border 0.15s;
  }
  input:focus,
  select:focus,
  textarea:focus {
    border-color: var(--color-border-primary);
    background: var(--color-background-primary);
  }
  textarea {
    resize: vertical;
    min-height: 72px;
  }
  .detail-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
  }
  .detail-col h4 {
    font-size: 11px;
    font-weight: 500;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.07em;
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 0.5px solid var(--color-border-tertiary);
  }
  .detail-item {
    margin-bottom: 12px;
  }
  .detail-item .dl {
    font-size: 11px;
    color: var(--color-text-secondary);
  }
  .detail-item .dv {
    font-size: 13px;
    color: var(--color-text-primary);
    margin-top: 1px;
  }
  .detail-images {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 20px;
  }
  .detail-img-wrap {
    border-radius: var(--border-radius-md);
    overflow: hidden;
    aspect-ratio: 4/3;
    background: var(--color-background-secondary);
    position: relative;
  }
  .detail-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .img-type-pill {
    position: absolute;
    top: 6px;
    right: 6px;
    background: rgba(0, 0, 0, 0.55);
    color: #fff;
    font-size: 10px;
    padding: 2px 7px;
    border-radius: 9999px;
  }
  .upload-zone {
    border: 0.5px dashed var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    padding: 20px;
    text-align: center;
    cursor: pointer;
    background: var(--color-background-secondary);
  }
  .upload-zone i {
    font-size: 24px;
    color: var(--color-text-secondary);
    margin-bottom: 6px;
  }
  .upload-zone p {
    font-size: 12px;
    color: var(--color-text-secondary);
  }
  .preview-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-top: 12px;
  }
  .preview-item {
    border-radius: var(--border-radius-md);
    overflow: hidden;
    aspect-ratio: 4/3;
    background: var(--color-background-secondary);
    border: 0.5px solid var(--color-border-tertiary);
    position: relative;
  }
  .preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .preview-remove {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 18px;
    height: 18px;
    background: rgba(0, 0, 0, 0.55);
    color: #fff;
    border: none;
    border-radius: 50%;
    font-size: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    padding: 11px 16px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    z-index: 9999;
    transform: translateY(80px);
    opacity: 0;
    transition: all 0.25s;
  }
  .toast.show {
    transform: none;
    opacity: 1;
  }
  .toast.success {
    border-color: #9fe1cb;
    color: #085041;
  }
  .toast.error {
    border-color: #f7c1c1;
    color: #791f1f;
  }

  /* ============================================================
     RESPONSIVE
     ============================================================ */

  /* ── Large desktop ≥ 1280px ─────────────────────────────── */
  @media (min-width: 1280px) {
    .page { padding: 28px 32px; }
    .search-input { width: 240px; }
  }

  /* ── Medium-large desktop 1025px – 1279px ───────────────── */
  @media (max-width: 1279px) {
    thead th:nth-child(1) { width: 30%; }
    thead th:nth-child(3) { width: 18%; }
    thead th:nth-child(4) { width: 16%; }
    .prop-name { max-width: 170px; }
  }

  /* ── Small laptop / large tablet 769px – 1024px ────────── */
  @media (max-width: 1024px) {
    thead th:nth-child(4),
    tbody td:nth-child(4) { display: none; }

    thead th:nth-child(1) { width: 34%; }
    thead th:nth-child(2) { width: 16%; }
    thead th:nth-child(3) { width: 22%; }
    thead th:nth-child(5) { width: 13%; }
    thead th:nth-child(6) { width: 15%; }

    .prop-name { max-width: 150px; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .modal { max-width: 760px; }
    .detail-grid { grid-template-columns: repeat(2, 1fr); }
  }

  /* ── Tablet 481px – 768px ───────────────────────────────── */
  @media (max-width: 768px) {
    .page { padding: 16px; }

    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }

    .card-header {
      flex-direction: column;
      align-items: stretch;
      gap: 10px;
    }
    .toolbar { width: 100%; flex-wrap: wrap; }
    .search-wrap  { flex: 1 1 auto; }
    .search-input { width: 100%; }
    .filter-select { flex: 1 1 auto; }
    .btn { white-space: nowrap; flex-shrink: 0; }

    thead th:nth-child(3),
    tbody td:nth-child(3) { display: none; }

    thead th:nth-child(1) { width: 40%; }
    thead th:nth-child(2) { width: 18%; }
    thead th:nth-child(5) { width: 17%; }
    thead th:nth-child(6) { width: 25%; }

    .prop-name { max-width: 120px; }

    .modal-backdrop { padding: 0; align-items: flex-end; }
    .modal {
      border-radius: 16px 16px 0 0;
      max-width: 100%;
      max-height: 95vh;
      overflow-y: auto;
      margin: 0;
    }
    .modal-header { border-radius: 16px 16px 0 0; }
    .modal-footer { border-radius: 0; }

    .form-grid { grid-template-columns: 1fr; }
    .form-group.full { grid-column: 1; }

    .detail-grid { grid-template-columns: 1fr; gap: 16px; }
    .detail-images { grid-template-columns: repeat(2, 1fr); }

    .rooms-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
    .preview-grid { grid-template-columns: repeat(3, 1fr); }

    .pagination { flex-direction: column; align-items: flex-start; gap: 8px; }
  }

  /* ── Mobile ≤ 480px ─────────────────────────────────────── */
  @media (max-width: 480px) {
    .page { padding: 12px; }

    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
    .stat-card  { padding: 12px 14px; }
    .stat-value { font-size: 22px; }

    thead th:nth-child(2),
    tbody td:nth-child(2) { display: none; }

    thead th:nth-child(1) { width: 52%; }
    thead th:nth-child(5) { width: 20%; }
    thead th:nth-child(6) { width: 28%; }

    .prop-name { max-width: 100px; font-size: 12px; }
    .prop-code { display: none; }
    .prop-avatar { width: 34px; height: 34px; }
    .prop-avatar i { font-size: 15px; }

    .icon-btn { width: 26px; height: 26px; font-size: 12px; }
    .actions-cell { gap: 3px; }

    .rooms-grid { grid-template-columns: 1fr; }
    .room-list-item { flex-direction: column; align-items: flex-start; gap: 6px; }
    .room-list-meta { flex-wrap: wrap; gap: 8px; }

    .preview-grid { grid-template-columns: repeat(2, 1fr); }
    .detail-images { grid-template-columns: 1fr; }

    .modal-body   { padding: 16px; }
    .modal-footer { padding: 12px 16px; }
    .modal-header { padding: 14px 16px; }

    .pag-controls { gap: 4px; }
    .pag-btn { min-width: 26px; height: 26px; font-size: 11px; }
  }

  /* ── Very small mobile ≤ 360px ──────────────────────────── */
  @media (max-width: 360px) {
    .page { padding: 10px; }

    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
    .stat-card  { padding: 10px 12px; }
    .stat-value { font-size: 20px; }
    .stat-label { font-size: 10px; }

    .prop-name  { max-width: 80px; font-size: 11px; }
    .prop-avatar { width: 30px; height: 30px; }
    .prop-avatar i { font-size: 13px; }

    .icon-btn   { width: 24px; height: 24px; font-size: 11px; }
    .actions-cell { gap: 2px; }

    .card-title { font-size: 14px; }
    .preview-grid { grid-template-columns: 1fr 1fr; gap: 6px; }

    .modal-footer { flex-direction: column; }
    .modal-footer .btn { width: 100%; justify-content: center; }

    .pag-btn { min-width: 24px; height: 24px; padding: 0 4px; font-size: 10px; }
  }

  /* ── Touch devices ──────────────────────────────────────── */
  @media (hover: none) and (pointer: coarse) {
    .icon-btn   { width: 36px; height: 36px; }
    .pag-btn    { min-width: 36px; height: 36px; }
    .vt-btn     { width: 34px; height: 34px; }
    .btn        { padding: 9px 16px; }
    input,
    select,
    textarea    { font-size: 16px; }
  }

  /* ── Landscape phone ────────────────────────────────────── */
  @media (max-width: 768px) and (orientation: landscape) {
    .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table       { table-layout: auto; min-width: 560px; }
    thead th:nth-child(2),
    thead th:nth-child(3) { display: table-cell; }
    tbody td:nth-child(2),
    tbody td:nth-child(3) { display: table-cell; }
    .modal-backdrop { align-items: center; padding: 12px; }
    .modal { border-radius: 16px; max-height: 92vh; }
    .modal-header { border-radius: 16px 16px 0 0; }
  }

  /* ── Print ──────────────────────────────────────────────── */
  @media print {
    .toolbar,
    .actions-cell,
    .pagination,
    .modal-backdrop { display: none !important; }
    .card { border: 1px solid #ccc; }
    .stats-grid { grid-template-columns: repeat(4, 1fr); }
    table { font-size: 11px; }
  }
</style>

<script>
  // Inject custom styles for SweetAlert buttons
  const style = document.createElement('style');
  style.textContent = `
    .swal-confirm-button {
      background-color: #d33 !important;
      color: white !important;
      border: none !important;
      padding: 12px 24px !important;
      border-radius: 8px !important;
      font-weight: 600 !important;
      font-size: 14px !important;
    }
    .swal-cancel-button {
      background-color: #6b7280 !important;
      color: white !important;
      border: none !important;
      padding: 12px 24px !important;
      border-radius: 8px !important;
      font-weight: 600 !important;
      font-size: 14px !important;
    }
  `;
  document.head.appendChild(style);

  // Data will be fetched from API on page load
  let propertiesData = [];
  let propertyImagesData = [];
  let propertyRoomsData = {};
  let regionsData = [];
  let provincesData = [];
  let citiesData = [];
  let barangaysData = [];
  let currentPage = 1;
  let itemsPerPage = 10;
  let totalItems = 0;
  let roomsViewMode = 'grid'; // 'grid' or 'list'
  let roomsDataCache = {};
  let csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";

  // Cropper state
  let cropper = null;
  let currentCropFile = null;
  let currentCropInput = null;
  let currentCropPreviewContainerId = null;
  let pendingFiles = []; // Queue of files to crop

  // Helper function to generate UUID in JavaScript
  function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      const r = Math.random() * 16 | 0;
      const v = c === 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
  }

  // Valid image_type ENUM values — single source of truth for both add and edit
  const IMAGE_TYPES = [
    { value: 'gallery',    label: 'Gallery' },
    { value: 'cover',      label: 'Cover' },
    { value: 'facilities', label: 'Facilities' },
    { value: 'amenities',  label: 'Amenities' },
    { value: 'map',        label: 'Map' },
    { value: 'other',      label: 'Other' }
  ];

  function buildImageTypeSelect(name, defaultValue = 'gallery') {
    const options = IMAGE_TYPES.map(t =>
      `<option value="${t.value}"${t.value === defaultValue ? ' selected' : ''}>${t.label}</option>`
    ).join('');
    return `<select name="${name}" class="mt-1 w-full px-2 py-1 text-xs border border-gray-300 dark:border-white/20 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">${options}</select>`;
  }

  // Upload property images (used by both create and edit)
  // Reads image_type from the per-image <select> rendered in the preview
  async function uploadPropertyImages(propertyId, files, previewContainerId) {
    const formData = new FormData();
    formData.append('property_id', propertyId);
    formData.append('csrf_token', csrfToken);

    const container = document.getElementById(previewContainerId);
    const typeSelects = container ? container.querySelectorAll('select[name^="image_type"]') : [];

    Array.from(files).forEach((file, index) => {
      formData.append('property_images', file);
      const imageType = typeSelects[index] ? typeSelects[index].value : 'gallery';
      formData.append(`image_type[${index}]`, imageType);
      formData.append(`sort_order[${index}]`, index);
    });

    const response = await fetch('../api/properties.php', {
      method: 'POST',
      body: formData
    });

    return response.json();
  }

  // Delete a single property image immediately
  async function deletePropertyImage(imageId, cardEl) {
    const confirmed = await Swal.fire({
      title: 'Delete image?',
      text: 'This cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Delete',
      customClass: { confirmButton: 'swal-confirm-button', cancelButton: 'swal-cancel-button' }
    });
    if (!confirmed.isConfirmed) return;

    const response = await fetch('../api/properties.php?action=delete_image', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
      body: JSON.stringify({ csrf_token: csrfToken, image_id: imageId })
    });
    const result = await response.json();
    if (result.success) {
      cardEl.remove();
      showToast('Image deleted successfully');
    } else {
      showToast(result.message, 'error');
    }
  }

  // Update image_type for an existing image
  async function updateImageType(imageId, newType) {
    const response = await fetch('../api/properties.php?action=update_image_type', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken },
      body: JSON.stringify({ csrf_token: csrfToken, image_id: imageId, image_type: newType })
    });
    const result = await response.json();
    if (!result.success) {
      showToast(result.message, 'error');
    }
  }

  // Cropper functions
  function openCropperModal(file, input, previewContainerId) {
    currentCropFile = file;
    currentCropInput = input;
    currentCropPreviewContainerId = previewContainerId;

    const reader = new FileReader();
    reader.onload = function(e) {
      const cropperImage = document.getElementById('cropperImage');
      cropperImage.src = e.target.result;

      // Destroy existing cropper if any
      if (cropper) {
        cropper.destroy();
      }

      // Initialize new cropper
      cropper = new Cropper(cropperImage, {
        aspectRatio: NaN, // Free aspect ratio
        viewMode: 1,
        autoCropArea: 0.8,
        responsive: true
      });
    };
    reader.readAsDataURL(file);

    const modal = document.getElementById('cropperModal');
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeCropperModal() {
    const modal = document.getElementById('cropperModal');
    modal.classList.remove('open');
    document.body.style.overflow = 'auto';

    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
    currentCropFile = null;
    currentCropInput = null;
    currentCropPreviewContainerId = null;
  }

  async function applyCrop() {
    if (!cropper || !currentCropFile || !currentCropInput) return;

    const canvas = cropper.getCroppedCanvas({
      maxWidth: 2048,
      maxHeight: 2048
    });

    canvas.toBlob(function(blob) {
      const croppedFile = new File([blob], currentCropFile.name, { type: currentCropFile.type });
      addCroppedFileToInput(croppedFile, currentCropInput, currentCropPreviewContainerId);
      closeCropperModal();
      processNextPendingFile();
    }, currentCropFile.type, 0.9);
  }

  function addCroppedFileToInput(file, input, previewContainerId) {
    const dataTransfer = new DataTransfer();
    const existingFiles = Array.from(input.files);
    dataTransfer.items.add(file);
    existingFiles.forEach(f => dataTransfer.items.add(f));
    input.files = dataTransfer.files;

    // Add to preview
    const previewContainer = document.getElementById(previewContainerId);
    const reader = new FileReader();
    reader.onload = function(e) {
      const previewDiv = document.createElement('div');
      previewDiv.className = 'relative';
      const index = Array.from(previewContainer.children).length;
      previewDiv.innerHTML = `
          <img src="${e.target.result}" alt="Preview ${index + 1}" style="width: 100%; height: 6rem; object-fit: cover; border-radius: 0.5rem;">
          <button type="button" onclick="removeImage(${index}, '${previewContainerId}')" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors">
            <i class="fas fa-times text-xs"></i>
          </button>
          ${buildImageTypeSelect('image_type[]')}
          <input type="text" name="image_captions[]" placeholder="Caption (optional)" class="mt-1 w-full px-2 py-1 text-xs border border-gray-300 dark:border-white/20 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-slate-800 dark:text-white">
        `;
      previewContainer.appendChild(previewDiv);
    };
    reader.readAsDataURL(file);
  }

  function processNextPendingFile() {
    if (pendingFiles.length > 0) {
      const next = pendingFiles.shift();
      openCropperModal(next.file, next.input, next.previewContainerId);
    }
  }

  // Fetch data from API on page load
  document.addEventListener('DOMContentLoaded', async function() {
    await loadGeographyData();
    await loadPropertyTypes();
    await fetchPropertiesData();
  });

  async function loadGeographyData() {
    try {
      regionsData = await fetchRegions();
    } catch (e) {
      console.error('Error loading geography data:', e);
    }
  }

  async function loadPropertyTypes() {
    try {
      const response = await fetch('../api/properties.php?action=all_types');
      const result = await response.json();
      if (result.success && result.data) {
        populateTypeFilter(result.data);
      }
    } catch (e) {
      console.error('Error loading property types:', e);
    }
  }

  function populateTypeFilter(types) {
    const typeFilter = document.getElementById('typeFilter');
    if (!typeFilter) return;

    typeFilter.innerHTML = '<option value="">All Types</option>';
    types.forEach(type => {
      const formattedType = type.replace(/_/g, ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
      typeFilter.innerHTML += `<option value="${type}">${formattedType}</option>`;
    });
  }

  async function loadPropertyGeographyData(properties) {
    try {
      // Collect unique codes
      const uniqueRegionCodes = [...new Set(properties.map(p => p.region_code).filter(Boolean))];
      const uniqueProvinceCodes = [...new Set(properties.map(p => p.province_code).filter(Boolean))];
      const uniqueCityCodes = [...new Set(properties.map(p => p.city_municipality_code).filter(Boolean))];
      const uniqueBarangayCodes = [...new Set(properties.map(p => p.barangay_code).filter(Boolean))];

      // Load provinces for unique regions
      const regionMap = new Map();
      for (const regionCode of uniqueRegionCodes) {
        const provinces = await fetchProvinces(regionCode);
        provincesData.push(...provinces);
        regionMap.set(regionCode, provinces);
      }

      // Load cities for unique provinces
      const provinceMap = new Map();
      for (const provinceCode of uniqueProvinceCodes) {
        const cities = await fetchCities(provinceCode);
        citiesData.push(...cities);
        provinceMap.set(provinceCode, cities);
      }

      // Load barangays for unique cities
      for (const cityCode of uniqueCityCodes) {
        const barangays = await fetchBarangays(cityCode);
        barangaysData.push(...barangays);
      }
    } catch (e) {
      console.error('Error loading property geography data:', e);
    }
  }

  function getRegionName(code) {
    if (!code) return '-';
    const region = regionsData.find(r => r.region_code === code);
    return region ? region.region_name : '-';
  }

  function getProvinceName(code) {
    if (!code) return '-';
    const province = provincesData.find(p => p.province_code === code);
    return province ? province.province_name : '-';
  }

  function getCityName(code) {
    if (!code) return '-';
    const city = citiesData.find(c => c.city_municipality_code === code);
    return city ? city.city_municipality_name : '-';
  }

  function getBarangayName(code) {
    if (!code) return '-';
    const barangay = barangaysData.find(b => b.barangay_code === code);
    return barangay ? barangay.barangay_name : '-';
  }

  async function fetchPropertiesData() {
    try {
      const offset = (currentPage - 1) * itemsPerPage;
      const response = await fetch(`../api/properties.php?action=list&limit=${itemsPerPage}&offset=${offset}`);
      const result = await response.json();
      if (result.success) {
        propertiesData = result.data.data || result.data;
        totalItems = result.data.total ?? result.total ?? propertiesData.length;

        // Load geography data for these properties
        await loadPropertyGeographyData(propertiesData);

        // Update stats
        document.getElementById('statTotal').textContent = totalItems;
        document.getElementById('statActive').textContent = propertiesData.filter(p => p.is_active).length;

        // Count unique property types
        const uniqueTypes = new Set(propertiesData.map(p => p.property_type).filter(t => t));
        document.getElementById('statTypes').textContent = uniqueTypes.size;

        // Fetch total rooms count
        try {
          const roomsRes = await fetch('../api/rooms.php?action=list');
          const roomsResult = await roomsRes.json();
          if (roomsResult.success) {
            const roomsTotal = roomsResult.data?.pagination?.total
              ?? roomsResult.data?.data?.length
              ?? roomsResult.data?.length
              ?? 0;
            document.getElementById('statRooms').textContent = roomsTotal;
          }
        } catch (e) {
          console.error('Error fetching rooms count:', e);
        }

        // Fetch images for each property
        for (const property of propertiesData) {
          const imgResponse = await fetch(`../api/properties.php?action=images&id=${property.property_id}`);
          const imgResult = await imgResponse.json();
          if (imgResult.success) {
            propertyImagesData[property.property_id] = imgResult.data;
          }
        }

        renderPropertiesTable();
      }
    } catch (error) {
      console.error('Error fetching properties:', error);
      showToast('Failed to load properties data', 'error');
    }
  }

  function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;

    const filtered = propertiesData.filter(property => {
      const matchesSearch = property.property_name.toLowerCase().includes(searchTerm) ||
        property.property_code.toLowerCase().includes(searchTerm) ||
        (property.city || '').toLowerCase().includes(searchTerm);
      const matchesType = !typeFilter || property.property_type === typeFilter;
      return matchesSearch && matchesType;
    });

    renderPropertiesTable(filtered);
  }

  function renderPropertiesTable(data = propertiesData) {
    const tbody = document.querySelector('#propertiesTableBody');
    if (!tbody) return;

    if (data.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="empty-state">
            <i class="fas fa-building"></i>
            No properties found. <a href="javascript:;" onclick="openAddPropertyModal()" class="text-blue-500 font-semibold">Add your first property</a>.
          </td>
        </tr>
      `;
    } else {
      tbody.innerHTML = data.map((property, index) => {
        const property_images = propertyImagesData[property.property_id] || [];
        const cover_image = property_images.find(img => img.image_type === 'cover') || property_images[0];

        let avatarHtml = '';
        if (cover_image) {
          avatarHtml = `
            <img src="../../${cover_image.image_url}" alt="${property.property_name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            
          `;
        } else {
          avatarHtml = `<i class="fas fa-building"></i>`;
        }

        const createdDate = property.created_at ? new Date(property.created_at).toLocaleString('en-US', {
          month: 'long',
          day: 'numeric',
          year: 'numeric',
          hour: 'numeric',
          minute: '2-digit',
          hour12: true
        }) : '-';

        const statusClass = property.is_active ? 'active' : 'inactive';
        const statusText = property.is_active ? 'Active' : 'Inactive';

        return `
          <tr class="property-row">
            <td>
              <div style="display: flex; align-items: center; gap: 12px;">
                <div class="prop-avatar">
                  ${avatarHtml}
                </div>
                <div>
                  <div class="prop-name">${property.property_name}</div>
                  <div class="prop-code">${property.property_code}</div>
                </div>
              </div>
            </td>
            <td>
              <span class="type-badge">${property.property_type ? property.property_type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ') : ''}</span>
            </td>
            <td>
              <div class="city-text">${getCityName(property.city_municipality_code)}${getProvinceName(property.province_code) !== '-' ? ', ' + getProvinceName(property.province_code) : ''}</div>
            </td>
            <td>
              <div class="date-text">${createdDate}</div>
            </td>
            <td class="center">
              <span class="status-badge ${statusClass}">${statusText}</span>
            </td>
            <td>
              <div class="actions-cell">
                <button onclick="viewPropertyDetails('${property.property_id}')" class="icon-btn view" title="View Details">
                  <i class="fas fa-eye"></i>
                </button>
                <button onclick="toggleRoomsDropdown('${property.property_id}')" class="icon-btn rooms" id="roomsBtn-${property.property_id}" title="View Rooms">
                  <i class="fa-solid fa-door-open"></i>
                </button>
                <button onclick="openEditPropertyModal('${property.property_id}')" class="icon-btn edit" title="Edit Property">
                  <i class="fas fa-pencil"></i>
                </button>
                <button onclick="confirmDeleteProperty('${property.property_id}', '${property.property_name}')" class="icon-btn del" title="Delete Property">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          <tr id="roomsDropdown-${property.property_id}" class="rooms-row hidden">
            <td colspan="6">
              <div class="rooms-panel">
                <div class="mb-3 flex items-center justify-between">
                  <h6 class="rooms-panel-title">Rooms for ${property.property_name}</h6>
                  <span class="text-sm text-slate-500 dark:text-white/60">Loading rooms...</span>
                </div>
              </div>
            </td>
          </tr>
        `;
      }).join('');
    }

    renderPagination();
  }

  function renderPagination() {
    const paginationContainer = document.getElementById('paginationContainer');
    if (!paginationContainer) return;

    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startItem = totalItems === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, totalItems);

    let paginationHtml = `
      <div class="pagination">
        <div class="pag-info">
          Showing ${startItem} to ${endItem} of ${totalItems} entries
        </div>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap">
          <div class="per-page-wrap">
            Show
            <select
              style="width: 64px; padding: 4px 8px; font-size: 12px"
              onchange="changeItemsPerPage(this.value)"
            >
              <option value="5" ${itemsPerPage === 5 ? 'selected' : ''}>5</option>
              <option value="10" ${itemsPerPage === 10 ? 'selected' : ''}>10</option>
              <option value="25" ${itemsPerPage === 25 ? 'selected' : ''}>25</option>
              <option value="50" ${itemsPerPage === 50 ? 'selected' : ''}>50</option>
            </select>
            entries
          </div>
          <div class="pag-controls">
            <button onclick="goToPage(1)" ${currentPage === 1 ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevrons-left"></i>
            </button>
            <button onclick="goToPage(currentPage - 1)" ${currentPage === 1 ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
            ${getPageNumbers().map(page => `
              <button onclick="goToPage(${page})" ${page === currentPage ? 'disabled' : ''} class="pag-btn ${page === currentPage ? 'active' : ''}">
                ${page}
              </button>
            `).join('')}
            <button onclick="goToPage(currentPage + 1)" ${currentPage === totalPages ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevron-right"></i>
            </button>
            <button onclick="goToPage(totalPages)" ${currentPage === totalPages ? 'disabled' : ''} class="pag-btn">
              <i class="fas fa-chevrons-right"></i>
            </button>
          </div>
        </div>
      </div>
    `;

    paginationContainer.innerHTML = paginationHtml;
  }

  function getPageNumbers() {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const pages = [];
    const maxVisible = 5;

    if (totalPages <= maxVisible) {
      for (let i = 1; i <= totalPages; i++) {
        pages.push(i);
      }
    } else {
      if (currentPage <= 3) {
        for (let i = 1; i <= 4; i++) {
          pages.push(i);
        }
        pages.push('...');
        pages.push(totalPages);
      } else if (currentPage >= totalPages - 2) {
        pages.push(1);
        pages.push('...');
        for (let i = totalPages - 3; i <= totalPages; i++) {
          pages.push(i);
        }
      } else {
        pages.push(1);
        pages.push('...');
        for (let i = currentPage - 1; i <= currentPage + 1; i++) {
          pages.push(i);
        }
        pages.push('...');
        pages.push(totalPages);
      }
    }

    return pages;
  }

  function goToPage(page) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    if (page < 1 || page > totalPages || page === currentPage) return;
    currentPage = page;
    fetchPropertiesData();
  }

  function changeItemsPerPage(value) {
    itemsPerPage = parseInt(value);
    currentPage = 1;
    fetchPropertiesData();
  }
</script>

<!-- PAGE CONTENT -->
<div class="page">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total properties</div>
      <div class="stat-value" id="statTotal">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Active</div>
      <div class="stat-value green" id="statActive">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total rooms</div>
      <div class="stat-value blue" id="statRooms">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Property types</div>
      <div class="stat-value amber" id="statTypes">—</div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title">All properties</span>
      <div class="toolbar">
        <div class="search-wrap">
          <i class="fas fa-search" aria-hidden="true"></i>
          <input
            type="text"
            class="search-input"
            id="searchInput"
            placeholder="Search…"
            oninput="filterTable()"
          />
        </div>
        <select class="filter-select" id="typeFilter" onchange="filterTable()">
          <option value="">All types</option>
        </select>
        <button class="btn btn-primary" onclick="openAddPropertyModal()">
          <i class="fas fa-plus" aria-hidden="true"></i> Add property
        </button>
      </div>
    </div>
    <div class="table-wrap">
      <table id="propsTable">
        <thead>
          <tr>
            <th>Property</th>
            <th>Type</th>
            <th>Location</th>
            <th>Added</th>
            <th class="center">Status</th>
            <th style="text-align: right">Actions</th>
          </tr>
        </thead>
        <tbody id="propertiesTableBody">
          <tr>
            <td colspan="6" class="p-6 text-center text-slate-400 dark:text-white/60 text-base">Loading properties...</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="paginationContainer"></div>
  </div>
</div>

<!-- Property Details Modal -->
<div id="propertyDetailsModal" class="modal-backdrop">
  <div class="modal" style="max-width: 1200px;">
    <div class="modal-header">
      <span class="modal-title" id="modalPropertyName">Property Details</span>
      <button class="modal-close" onclick="closePropertyDetailsModal()">
        <i class="fas fa-x"></i>
      </button>
    </div>
    <div class="modal-body" id="modalPropertyContent">
      <!-- Content will be populated by JavaScript -->
    </div>
  </div>
</div>

<!-- Add Property Modal -->
<div id="addPropertyModal" class="modal-backdrop">
  <div class="modal" style="max-width: 900px;">
    <div class="modal-header">
      <span class="modal-title">Add New Property</span>
      <button class="modal-close" onclick="closeAddPropertyModal()">
        <i class="fas fa-x"></i>
      </button>
    </div>
    <form id="addPropertyForm" onsubmit="handleAddPropertySubmit(event)">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
      <div class="modal-body">
        <div class="section-label">Basic information</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Property code <span class="req">*</span></label>
            <input type="text" name="property_code" required placeholder="e.g. RST-001" />
          </div>
          <div class="form-group">
            <label>Property name <span class="req">*</span></label>
            <input type="text" name="property_name" required placeholder="e.g. Sunset Beach Resort" />
          </div>
          <div class="form-group">
            <label>Property type <span class="req">*</span></label>
            <select name="property_type" id="property_type" required>
              <option value="">Loading...</option>
            </select>
          </div>
          <div class="form-group">
            <label>Tagline</label>
            <input type="text" name="tagline" placeholder="e.g. Your Paradise Awaits" />
          </div>
          <div class="form-group full">
            <label>Description</label>
            <textarea name="description" rows="2" placeholder="Property description…"></textarea>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="is_active">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>

        <div class="section-label">Location</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Region</label>
            <input type="text" id="region_code_display" list="region_list" placeholder="Select region" onchange="onRegionChange()" />
            <input type="hidden" id="region_code" name="region_code" />
            <datalist id="region_list"></datalist>
          </div>
          <div class="form-group">
            <label>Province</label>
            <input type="text" id="province_code_display" list="province_list" placeholder="Select province" onchange="onProvinceChange()" />
            <input type="hidden" id="province_code" name="province_code" />
            <datalist id="province_list"></datalist>
          </div>
          <div class="form-group">
            <label>City/Municipality</label>
            <input type="text" id="city_municipality_code_display" list="city_list" placeholder="Select city/municipality" onchange="onCityChange()" />
            <input type="hidden" id="city_municipality_code" name="city_municipality_code" />
            <datalist id="city_list"></datalist>
          </div>
          <div class="form-group">
            <label>Barangay</label>
            <input type="text" id="barangay_code_display" list="barangay_list" placeholder="Select barangay" onchange="onBarangayChange()" />
            <input type="hidden" id="barangay_code" name="barangay_code" />
            <datalist id="barangay_list"></datalist>
          </div>
          <div class="form-group full">
            <label>Address line 1</label>
            <input type="text" name="address_line1" id="address_line1" placeholder="Street address" />
          </div>
          <div class="form-group full">
            <label>Address line 2</label>
            <input type="text" name="address_line2" id="address_line2" placeholder="Apartment, suite, etc." />
          </div>
          <div class="form-group">
            <label>Postal code</label>
            <input type="text" name="postal_code" placeholder="e.g. 1000" />
          </div>
          <div class="form-group">
            <label>Country</label>
            <input type="text" name="country" value="Philippines" />
          </div>
          <div class="form-group">
            <label>Latitude</label>
            <input type="number" step="any" name="latitude" id="latitude" placeholder="e.g. 14.5995" />
          </div>
          <div class="form-group">
            <label>Longitude</label>
            <input type="number" step="any" name="longitude" id="longitude" placeholder="e.g. 120.9842" />
          </div>
          <div class="form-group full">
            <button type="button" onclick="geocodeAddress()" class="btn">
              <i class="fas fa-map-pin"></i> Get Coordinates from Address
            </button>
          </div>
        </div>

        <div class="section-label">Contact information</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Primary phone</label>
            <input type="text" name="phone_primary" placeholder="+63 912 345 6789" />
          </div>
          <div class="form-group">
            <label>Secondary phone</label>
            <input type="text" name="phone_secondary" placeholder="+63 912 345 6790" />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="info@resort.com" />
          </div>
          <div class="form-group">
            <label>Website</label>
            <input type="url" name="website_url" placeholder="https://www.resort.com" />
          </div>
          <div class="form-group full">
            <label>Facebook URL</label>
            <input type="url" name="facebook_url" placeholder="https://facebook.com/resort" />
          </div>
        </div>

        <div class="section-label">Check-in / check-out</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Check-in time</label>
            <input type="time" name="check_in_time" value="14:00" />
          </div>
          <div class="form-group">
            <label>Check-out time</label>
            <input type="time" name="check_out_time" value="12:00" />
          </div>
          <div class="form-group">
            <label>Earliest check-in</label>
            <input type="time" name="earliest_check_in" value="08:00" />
          </div>
          <div class="form-group">
            <label>Latest check-out</label>
            <input type="time" name="latest_check_out" value="22:00" />
          </div>
          <div class="form-group">
            <label>Late check-out fee</label>
            <input type="number" step="0.01" name="late_check_out_fee" value="0.00" />
          </div>
        </div>

        <div class="section-label">Settings</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Timezone</label>
            <select name="timezone">
              <option value="Asia/Manila" selected>Asia/Manila</option>
              <option value="Asia/Tokyo">Asia/Tokyo</option>
              <option value="Asia/Singapore">Asia/Singapore</option>
              <option value="Asia/Hong_Kong">Asia/Hong Kong</option>
              <option value="UTC">UTC</option>
            </select>
          </div>
          <div class="form-group">
            <label>Currency</label>
            <select name="currency_code">
              <option value="PHP" selected>PHP — Philippine Peso</option>
              <option value="USD">USD — US Dollar</option>
              <option value="EUR">EUR — Euro</option>
              <option value="SGD">SGD — Singapore Dollar</option>
              <option value="JPY">JPY — Japanese Yen</option>
            </select>
          </div>
          <div class="form-group">
            <label>Tax rate (%)</label>
            <input type="number" step="0.01" name="tax_rate_percent" value="0.00" />
          </div>
          <div class="form-group">
            <label>Sort order</label>
            <input type="number" name="sort_order" value="0" />
          </div>
        </div>

        <div class="section-label">Property images</div>
        <div class="upload-zone" onclick="document.getElementById('propertyImagesInput').click()">
          <i class="fas fa-cloud-upload"></i>
          <p>Click to upload or drag and drop</p>
          <p style="font-size: 11px; margin-top: 3px">JPG, PNG, WEBP · max 5 MB each</p>
          <input type="file" name="property_images[]" multiple accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" id="propertyImagesInput" style="display: none" onchange="handleImagePreview(this)" />
        </div>
        <div class="preview-grid" id="imagePreviewContainer"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="closeAddPropertyModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-device-floppy"></i> Add property
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Property Modal -->
<div id="editPropertyModal" class="modal-backdrop">
  <div class="modal" style="max-width: 900px;">
    <div class="modal-header">
      <span class="modal-title">Edit Property</span>
      <button class="modal-close" onclick="closeEditPropertyModal()">
        <i class="fas fa-x"></i>
      </button>
    </div>
    <form id="editPropertyForm" onsubmit="handleEditPropertySubmit(event)">
      <input type="hidden" name="property_id" id="edit_property_id">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
      <div class="modal-body">
        <div class="section-label">Basic information</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Property code <span class="req">*</span></label>
            <input type="text" name="property_code" id="edit_property_code" required />
          </div>
          <div class="form-group">
            <label>Property name <span class="req">*</span></label>
            <input type="text" name="property_name" id="edit_property_name" required />
          </div>
          <div class="form-group">
            <label>Property type <span class="req">*</span></label>
            <select name="property_type" id="edit_property_type" required>
              <option value="">Loading...</option>
            </select>
          </div>
          <div class="form-group">
            <label>Tagline</label>
            <input type="text" name="tagline" id="edit_tagline" />
          </div>
          <div class="form-group full">
            <label>Description</label>
            <textarea name="description" id="edit_description" rows="2"></textarea>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="is_active" id="edit_is_active">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>

        <div class="section-label">Location</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Region</label>
            <input type="text" id="edit_region_code_display" list="edit_region_list" placeholder="Select region" onchange="onEditRegionChange()" />
            <input type="hidden" id="edit_region_code" name="region_code" />
            <datalist id="edit_region_list"></datalist>
          </div>
          <div class="form-group">
            <label>Province</label>
            <input type="text" id="edit_province_code_display" list="edit_province_list" placeholder="Select province" onchange="onEditProvinceChange()" />
            <input type="hidden" id="edit_province_code" name="province_code" />
            <datalist id="edit_province_list"></datalist>
          </div>
          <div class="form-group">
            <label>City/Municipality</label>
            <input type="text" id="edit_city_municipality_code_display" list="edit_city_list" placeholder="Select city/municipality" onchange="onEditCityChange()" />
            <input type="hidden" id="edit_city_municipality_code" name="city_municipality_code" />
            <datalist id="edit_city_list"></datalist>
          </div>
          <div class="form-group">
            <label>Barangay</label>
            <input type="text" id="edit_barangay_code_display" list="edit_barangay_list" placeholder="Select barangay" onchange="onEditBarangayChange()" />
            <input type="hidden" id="edit_barangay_code" name="barangay_code" />
            <datalist id="edit_barangay_list"></datalist>
          </div>
          <div class="form-group full">
            <label>Address line 1</label>
            <input type="text" name="address_line1" id="edit_address_line1" />
          </div>
          <div class="form-group full">
            <label>Address line 2</label>
            <input type="text" name="address_line2" id="edit_address_line2" />
          </div>
          <div class="form-group">
            <label>Postal code</label>
            <input type="text" name="postal_code" id="edit_postal_code" />
          </div>
          <div class="form-group">
            <label>Country</label>
            <input type="text" name="country" id="edit_country" value="Philippines" />
          </div>
          <div class="form-group">
            <label>Latitude</label>
            <input type="number" step="any" name="latitude" id="edit_latitude" />
          </div>
          <div class="form-group">
            <label>Longitude</label>
            <input type="number" step="any" name="longitude" id="edit_longitude" />
          </div>
          <div class="form-group full">
            <button type="button" onclick="geocodeEditAddress()" class="btn">
              <i class="fas fa-map-pin"></i> Get Coordinates
            </button>
          </div>
        </div>

        <div class="section-label">Contact information</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Primary phone</label>
            <input type="tel" name="phone_primary" id="edit_phone_primary" />
          </div>
          <div class="form-group">
            <label>Secondary phone</label>
            <input type="tel" name="phone_secondary" id="edit_phone_secondary" />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" id="edit_email" />
          </div>
          <div class="form-group">
            <label>Website</label>
            <input type="url" name="website_url" id="edit_website_url" />
          </div>
          <div class="form-group full">
            <label>Facebook URL</label>
            <input type="url" name="facebook_url" id="edit_facebook_url" />
          </div>
        </div>

        <div class="section-label">Check-in / check-out</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Check-in time</label>
            <input type="time" name="check_in_time" id="edit_check_in_time" value="14:00" />
          </div>
          <div class="form-group">
            <label>Check-out time</label>
            <input type="time" name="check_out_time" id="edit_check_out_time" value="12:00" />
          </div>
          <div class="form-group">
            <label>Earliest check-in</label>
            <input type="time" name="earliest_check_in" id="edit_earliest_check_in" value="08:00" />
          </div>
          <div class="form-group">
            <label>Latest check-out</label>
            <input type="time" name="latest_check_out" id="edit_latest_check_out" value="22:00" />
          </div>
          <div class="form-group">
            <label>Late check-out fee</label>
            <input type="number" step="0.01" name="late_check_out_fee" id="edit_late_check_out_fee" value="0.00" />
          </div>
        </div>

        <div class="section-label">Settings</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Timezone</label>
            <select name="timezone" id="edit_timezone">
              <option value="Asia/Manila">Asia/Manila</option>
              <option value="Asia/Tokyo">Asia/Tokyo</option>
              <option value="Asia/Singapore">Asia/Singapore</option>
              <option value="Asia/Hong_Kong">Asia/Hong Kong</option>
              <option value="UTC">UTC</option>
            </select>
          </div>
          <div class="form-group">
            <label>Currency</label>
            <select name="currency_code" id="edit_currency_code">
              <option value="PHP">PHP — Philippine Peso</option>
              <option value="USD">USD — US Dollar</option>
              <option value="EUR">EUR — Euro</option>
              <option value="SGD">SGD — Singapore Dollar</option>
              <option value="JPY">JPY — Japanese Yen</option>
            </select>
          </div>
          <div class="form-group">
            <label>Tax rate (%)</label>
            <input type="number" step="0.01" name="tax_rate_percent" id="edit_tax_rate_percent" value="0.00" />
          </div>
          <div class="form-group">
            <label>Sort order</label>
            <input type="number" name="sort_order" id="edit_sort_order" value="0" />
          </div>
        </div>

        <div class="section-label">Existing images</div>
        <div class="preview-grid" id="editExistingImagesContainer"></div>

        <div class="section-label">Add new images</div>
        <div class="upload-zone" onclick="document.getElementById('editPropertyImagesInput').click()">
          <i class="fas fa-cloud-upload"></i>
          <p>Click to upload or drag and drop</p>
          <p style="font-size: 11px; margin-top: 3px">JPG, PNG, WEBP · max 5 MB each</p>
          <input type="file" name="property_images[]" multiple accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" id="editPropertyImagesInput" style="display: none" onchange="handleEditImagePreview(this)" />
        </div>
        <div class="preview-grid" id="editImagePreviewContainer"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="closeEditPropertyModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-device-floppy"></i> Update property
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Image Cropper Modal -->
<div id="cropperModal" class="modal-backdrop">
  <div class="modal" style="max-width: 800px;">
    <div class="modal-header">
      <span class="modal-title">Crop Image</span>
      <button class="modal-close" onclick="closeCropperModal()">
        <i class="fas fa-x"></i>
      </button>
    </div>
    <div class="modal-body">
      <div class="mb-4">
        <img id="cropperImage" src="" alt="Image to crop" style="max-width: 100%; max-height: 400px; display: block; margin: 0 auto;">
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn" onclick="closeCropperModal()">Cancel</button>
      <button type="button" class="btn btn-primary" onclick="applyCrop()">
        <i class="fas fa-check"></i> Apply Crop
      </button>
    </div>
  </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast"></div>

<script>
  // Toast notification function
  function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.classList.add('show');
    setTimeout(() => {
      toast.classList.remove('show');
    }, 3000);
  }

  async function viewPropertyDetails(propertyId) {
    const property = propertiesData.find(p => p.property_id === propertyId);
    if (!property) return;

    const modal = document.getElementById('propertyDetailsModal');
    const modalName = document.getElementById('modalPropertyName');
    const modalContent = document.getElementById('modalPropertyContent');

    modalName.textContent = property.property_name;
    modal.classList.add('open');

    // Get property images
    const images = propertyImagesData[propertyId] || [];

    // Fetch geography data to get names from codes
    let regionName = '-';
    let provinceName = '-';
    let cityName = '-';
    let barangayName = '-';

    if (property.region_code) {
      const regions = await fetchRegions();
      const region = regions.find(r => r.region_code === property.region_code);
      regionName = region ? region.region_name : '-';
    }

    if (property.province_code) {
      const provinces = await fetchProvinces(property.region_code);
      const province = provinces.find(p => p.province_code === property.province_code);
      provinceName = province ? province.province_name : '-';
    }

    if (property.city_municipality_code) {
      const cities = await fetchCities(property.province_code);
      const city = cities.find(c => c.city_municipality_code === property.city_municipality_code);
      cityName = city ? city.city_municipality_name : '-';
    }

    if (property.barangay_code) {
      const barangays = await fetchBarangays(property.city_municipality_code);
      const barangay = barangays.find(b => b.barangay_code === property.barangay_code);
      barangayName = barangay ? barangay.barangay_name : '-';
    }

    // Build images HTML
    let imagesHtml = '';
    if (images.length > 0) {
      imagesHtml = `
          <div class="mb-6">
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Property Images</h4>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
              ${images.map(img => {
                // The image_url is stored as 'uploads/properties/filename.png'
                // From build/pages/properties.php, we need to go up 2 levels to reach root
                const imagePath = `../../${img.image_url}`;
                console.log('Image URL from DB:', img.image_url);
                console.log('Full image path:', imagePath);
                return `
                <div class="relative group">
                  <img src="${imagePath}" alt="${img.caption || 'Property image'}" style="width: 100%; height: 12rem; object-fit: cover; border-radius: 0.5rem; cursor: pointer;" class="hover:opacity-90 transition-opacity bg-gray-200" onerror="console.log('Failed to load:', '${imagePath}'); this.style.display='none'; this.parentElement.innerHTML='<div style=\'width: 100%; height: 12rem; background: #e5e7eb; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 0.75rem;\'>Image not found: ${img.image_url}</div>'" onclick="window.open('${imagePath}', '_blank')">
                  ${img.caption ? `<p class="text-xs text-slate-600 dark:text-white/80 mt-1 truncate">${img.caption}</p>` : ''}
                  <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                    ${img.image_type}
                  </span>
                </div>
              `}).join('')}
            </div>
          </div>
        `;
    } else {
      imagesHtml = `
          <div class="mb-6">
            <h4 class="text-sm font-bold text-slate-500 dark:text-white/60 uppercase mb-3">Property Images</h4>
            <p class="text-sm text-slate-500 dark:text-white/60">No images uploaded for this property.</p>
          </div>
        `;
    }

    modalContent.innerHTML = `
        ${imagesHtml}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
          <div>
            <h4 class="text-base font-bold text-slate-500 dark:text-white/60 uppercase mb-4" style="font-size: 0.875rem; font-weight: bold; text-transform: uppercase; margin-bottom: 1rem;">Basic Information</h4>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Property Code</p>
                <p class="text-base font-semibold dark:text-white" style="font-size: 1rem; font-weight: 600;">${property.property_code}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Property Type</p>
                <p class="text-base font-semibold dark:text-white" style="font-size: 1rem; font-weight: 600;">${property.property_type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Tagline</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.tagline || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Description</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.description || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Status</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.is_active ? 'Active' : 'Inactive'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-base font-bold text-slate-500 dark:text-white/60 uppercase mb-4" style="font-size: 0.875rem; font-weight: bold; text-transform: uppercase; margin-bottom: 1rem;">Location</h4>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Address Line 1</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.address_line1 || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Address Line 2</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.address_line2 || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Region</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${regionName}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Province</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${provinceName}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">City/Municipality</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${cityName}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Barangay</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${barangayName}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Postal Code</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.postal_code || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Country</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.country}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Coordinates</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.latitude && property.longitude ? `${property.latitude}, ${property.longitude}` : '-'}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-base font-bold text-slate-500 dark:text-white/60 uppercase mb-4" style="font-size: 0.875rem; font-weight: bold; text-transform: uppercase; margin-bottom: 1rem;">Contact Information</h4>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Primary Phone</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.phone_primary || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Secondary Phone</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.phone_secondary || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Email</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.email || '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Website</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.website_url ? `<a href="${property.website_url}" target="_blank" class="text-blue-500 hover:underline">${property.website_url}</a>` : '-'}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Facebook</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.facebook_url ? `<a href="${property.facebook_url}" target="_blank" class="text-blue-500 hover:underline">${property.facebook_url}</a>` : '-'}</p>
              </div>
            </div>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-top: 2rem;">
          <div>
            <h4 class="text-base font-bold text-slate-500 dark:text-white/60 uppercase mb-4" style="font-size: 0.875rem; font-weight: bold; text-transform: uppercase; margin-bottom: 1rem;">Check-in/Check-out</h4>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Check-in Time</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.check_in_time}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Check-out Time</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.check_out_time}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Earliest Check-in</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.earliest_check_in}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Latest Check-out</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.latest_check_out}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Late Check-out Fee</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.currency_code || 'PHP'} ${parseFloat(property.late_check_out_fee).toFixed(2)}</p>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-base font-bold text-slate-500 dark:text-white/60 uppercase mb-4" style="font-size: 0.875rem; font-weight: bold; text-transform: uppercase; margin-bottom: 1rem;">Settings</h4>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Timezone</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.timezone}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Currency</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.currency_code}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Tax Rate</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.tax_rate_percent}%</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Created At</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${new Date(property.created_at).toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })}</p>
              </div>
              <div>
                <p class="text-sm text-slate-500 dark:text-white/60">Updated At</p>
                <p class="text-base dark:text-white" style="font-size: 1rem;">${property.updated_at ? new Date(property.updated_at).toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true }) : '-'}</p>
              </div>
            </div>
          </div>
        </div>
      `;

    modal.classList.add('open');
  }

  function closePropertyDetailsModal() {
    const modal = document.getElementById('propertyDetailsModal');
    modal.classList.remove('open');
  }

  // Toggle Rooms Dropdown Function
  async function toggleRoomsDropdown(propertyId) {
    const dropdown = document.getElementById('roomsDropdown-' + propertyId);
    const btn = document.getElementById('roomsBtn-' + propertyId);

    if (dropdown.classList.contains('hidden')) {
      dropdown.classList.remove('hidden');
      btn.innerHTML = '<i class="fas fa-chevron-up"></i>';

      // Fetch rooms for this property
      await fetchRoomsForProperty(propertyId);
    } else {
      dropdown.classList.add('hidden');
      btn.innerHTML = '<i class="fas fa-door-open"></i>';
    }
  }

  async function fetchRoomsForProperty(propertyId) {
    const dropdown = document.getElementById('roomsDropdown-' + propertyId);
    const contentDiv = dropdown.querySelector('div');

    try {
      const response = await fetch(`../api/rooms.php?action=by_property&property_id=${propertyId}`);
      const result = await response.json();

      if (result.success && result.data && result.data.length > 0) {
        roomsDataCache[propertyId] = result.data;
        renderRoomsInDropdown(propertyId, result.data);
      } else {
        contentDiv.innerHTML = `
          <div class="p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 mb-4">
              <i class="fas fa-door-open text-2xl text-blue-500 dark:text-blue-400"></i>
            </div>
            <p class="text-slate-500 dark:text-white/60 mb-4">No rooms found for this property.</p>
            <button onclick="window.location.href='rooms'" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-gradient-to-tl from-blue-500 to-violet-500 rounded-lg hover:from-blue-600 hover:to-violet-600 transition-all shadow-sm hover:shadow-md">
              <i class="fas fa-plus mr-2"></i>Add First Room
            </button>
          </div>
        `;
      }
    } catch (error) {
      console.error('Error fetching rooms:', error);
      contentDiv.innerHTML = `
        <div class="p-6 text-center">
          <p class="text-red-500">Error loading rooms. Please try again.</p>
        </div>
      `;
    }
  }

  function renderRoomsInDropdown(propertyId, rooms) {
    const dropdown = document.getElementById('roomsDropdown-' + propertyId);
    const contentDiv = dropdown.querySelector('div');

    let roomsHtml = `
      <div class="rooms-panel">
        <div class="rooms-panel-header">
          <span class="rooms-panel-title">Rooms (${rooms.length})</span>
          <div class="view-toggle">
            <button onclick="toggleRoomsViewMode('${propertyId}', 'grid')" class="vt-btn ${roomsViewMode === 'grid' ? 'active' : ''}" title="Grid View">
              <i class="fa-solid fa-border-all"></i>
            </button>
            <button onclick="toggleRoomsViewMode('${propertyId}', 'list')" class="vt-btn ${roomsViewMode === 'list' ? 'active' : ''}" title="List View">
              <i class="fas fa-list"></i>
            </button>
          </div>
        </div>
        <div id="roomsContainer-${propertyId}" class="${roomsViewMode === 'grid' ? 'rooms-grid' : 'rooms-list'}">
    `;

    rooms.forEach(room => {
      const statusClass = room.room_status === 'available' ? 'rs-available' :
        room.room_status === 'occupied' ? 'rs-occupied' :
        room.room_status === 'maintenance' ? 'rs-maintenance' :
        room.room_status === 'reserved' ? 'rs-reserved' : '';

      const priceDisplay = room.override_price_per_night ? `₱${parseFloat(room.override_price_per_night).toFixed(2)}/night` :
        room.override_price_per_hour ? `₱${parseFloat(room.override_price_per_hour).toFixed(2)}/hour` :
        room.override_price_per_day_use ? `₱${parseFloat(room.override_price_per_day_use).toFixed(2)}/day` : '-';

      if (roomsViewMode === 'grid') {
        roomsHtml += `
          <div class="room-card">
            <div class="room-header">
              <span class="room-num">${room.room_number || '-'}</span>
              <span class="room-status ${statusClass}">${room.room_status || '-'}</span>
            </div>
            <div class="room-meta">
              <div>Name: ${room.room_name || '-'}</div>
              <div>Floor: ${room.floor_number || '-'}</div>
              <div>Building: ${room.building_block || '-'}</div>
            </div>
            <div class="room-price">${priceDisplay}</div>
          </div>
        `;
      } else {
        roomsHtml += `
          <div class="room-list-item">
            <div class="room-list-left">
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 13px; font-weight: 500;">${room.room_number || '-'}</span>
                <span style="font-size: 12px; color: var(--color-text-secondary);">${room.room_name || '-'}</span>
              </div>
              <span class="room-status ${statusClass}">${room.room_status || '-'}</span>
            </div>
            <div class="room-list-meta">
              <span>Floor: ${room.floor_number || '-'}</span>
              <span>Building: ${room.building_block || '-'}</span>
              <span>${priceDisplay}</span>
            </div>
          </div>
        `;
      }
    });

    roomsHtml += `
        </div>
      </div>
    `;

    contentDiv.innerHTML = roomsHtml;
  }

  function toggleRoomsViewMode(propertyId, mode) {
    roomsViewMode = mode;
    const rooms = roomsDataCache[propertyId];
    if (rooms) {
      renderRoomsInDropdown(propertyId, rooms);
    }
  }

  // Add Property Modal Functions
  async function openAddPropertyModal() {
    const modal = document.getElementById('addPropertyModal');
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';

    // Wait for modal to be fully rendered
    await new Promise(resolve => setTimeout(resolve, 200));

    // Initialize geography dropdowns
    const regions = await fetchRegions();
    populateDatalist('region_list', regions, 'region_name', 'region_code');

    // Initialize property types dropdown
    await fetchPropertyTypes('property_type');
  }

  function closeAddPropertyModal() {
    const modal = document.getElementById('addPropertyModal');
    modal.classList.remove('open');
    document.body.style.overflow = 'auto';
    document.getElementById('addPropertyForm').reset();
    document.getElementById('imagePreviewContainer').innerHTML = '';
  }

  // Image Preview Function - queues files for cropping
  function handleImagePreview(input) {
    const previewContainer = document.getElementById('imagePreviewContainer');
    previewContainer.innerHTML = '';

    if (input.files && input.files.length > 0) {
      // Read files BEFORE clearing input
      const files = Array.from(input.files);

      // Clear input files - we'll add cropped versions back
      input.value = '';
      previewContainer.innerHTML = '';

      // Queue all files for cropping
      files.forEach((file, index) => {
        pendingFiles.push({ file, input, previewContainerId: 'imagePreviewContainer' });
      });

      // Start cropping the first file
      if (pendingFiles.length > 0) {
        const first = pendingFiles.shift();
        openCropperModal(first.file, first.input, first.previewContainerId);
      }
    }
  }

  function removeImage(index, previewContainerId = 'imagePreviewContainer') {
    const input = previewContainerId === 'imagePreviewContainer'
      ? document.getElementById('propertyImagesInput')
      : document.getElementById('editPropertyImagesInput');
    const previewContainer = document.getElementById(previewContainerId);

    // Remove file from input
    const filesArray = Array.from(input.files);
    filesArray.splice(index, 1);

    // Create new FileList
    const dataTransfer = new DataTransfer();
    filesArray.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;

    // Remove the preview card directly
    const cards = previewContainer.querySelectorAll('.relative');
    if (cards[index]) {
      cards[index].remove();
    }
  }

  // Delete Property Function (API-based)
  async function confirmDeleteProperty(propertyId, propertyName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Are you sure you want to delete "${propertyName}"? This action will also delete all associated images and cannot be undone.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'swal-confirm-button',
        cancelButton: 'swal-cancel-button'
      }
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const response = await fetch('../api/properties.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify({
              action: 'delete',
              property_id: propertyId,
              csrf_token: csrfToken
            })
          });
          const result = await response.json();

          if (result.success) {
            showToast('Property deleted successfully');
            // Refresh the data
            await fetchPropertiesData();
          } else {
            showToast(result.message || 'Failed to delete property', 'error');
          }
        } catch (error) {
          showToast('An error occurred while deleting the property', 'error');
        }
      }
    });
  }

  // Edit Property Modal Functions
  async function openEditPropertyModal(propertyId) {
    try {
      const property = propertiesData.find(p => p.property_id === propertyId);
      if (!property) return;

      // Initialize geography dropdowns
      const regions = await fetchRegions();
      populateDatalist('edit_region_list', regions, 'region_name', 'region_code');

      // Initialize property types dropdown
      await fetchPropertyTypes('edit_property_type');

      // Populate form fields with existing data
      document.getElementById('edit_property_id').value = property.property_id;
      document.getElementById('edit_property_code').value = property.property_code;
      document.getElementById('edit_property_name').value = property.property_name;
      document.getElementById('edit_property_type').value = property.property_type;
      document.getElementById('edit_tagline').value = property.tagline || '';
      document.getElementById('edit_description').value = property.description || '';
      document.getElementById('edit_address_line1').value = property.address_line1;
      document.getElementById('edit_address_line2').value = property.address_line2 || '';
      document.getElementById('edit_postal_code').value = property.postal_code || '';
      document.getElementById('edit_country').value = property.country;
      document.getElementById('edit_latitude').value = property.latitude || '';
      document.getElementById('edit_longitude').value = property.longitude || '';
      document.getElementById('edit_phone_primary').value = property.phone_primary || '';
      document.getElementById('edit_phone_secondary').value = property.phone_secondary || '';
      document.getElementById('edit_email').value = property.email || '';
      document.getElementById('edit_website_url').value = property.website_url || '';
      document.getElementById('edit_facebook_url').value = property.facebook_url || '';
      document.getElementById('edit_check_in_time').value = property.check_in_time || '14:00';
      document.getElementById('edit_check_out_time').value = property.check_out_time || '12:00';
      document.getElementById('edit_earliest_check_in').value = property.earliest_check_in || '08:00';
      document.getElementById('edit_latest_check_out').value = property.latest_check_out || '22:00';
      document.getElementById('edit_late_check_out_fee').value = property.late_check_out_fee || 0.00;
      document.getElementById('edit_timezone').value = property.timezone || 'Asia/Manila';
      document.getElementById('edit_currency_code').value = property.currency_code || 'PHP';
      document.getElementById('edit_tax_rate_percent').value = property.tax_rate_percent || 0.00;
      document.getElementById('edit_sort_order').value = property.sort_order || 0;
      document.getElementById('edit_is_active').value = property.is_active ? '1' : '0';

      // Populate geography dropdowns with existing data
      if (property.region_code) {
        document.getElementById('edit_region_code').value = property.region_code;
        const region = regions.find(r => r.region_code === property.region_code);
        if (region) {
          document.getElementById('edit_region_code_display').value = region.region_name;
          const provinces = await fetchProvinces(property.region_code);
          populateDatalist('edit_province_list', provinces, 'province_name', 'province_code');
        }
      }

      if (property.province_code) {
        document.getElementById('edit_province_code').value = property.province_code;
        const provinces = await fetchProvinces(property.region_code);
        const province = provinces.find(p => p.province_code === property.province_code);
        if (province) {
          document.getElementById('edit_province_code_display').value = province.province_name;
          const cities = await fetchCities(property.province_code);
          populateDatalist('edit_city_list', cities, 'city_municipality_name', 'city_municipality_code');
        }
      }

      if (property.city_municipality_code) {
        document.getElementById('edit_city_municipality_code').value = property.city_municipality_code;
        const cities = await fetchCities(property.province_code);
        const city = cities.find(c => c.city_municipality_code === property.city_municipality_code);
        if (city) {
          document.getElementById('edit_city_municipality_code_display').value = city.city_municipality_name;
          const barangays = await fetchBarangays(property.city_municipality_code);
          populateDatalist('edit_barangay_list', barangays, 'barangay_name', 'barangay_code');
        }
      }

      if (property.barangay_code) {
        document.getElementById('edit_barangay_code').value = property.barangay_code;
        const barangays = await fetchBarangays(property.city_municipality_code);
        const barangay = barangays.find(b => b.barangay_code === property.barangay_code);
        if (barangay) {
          document.getElementById('edit_barangay_code_display').value = barangay.barangay_name;
        }
      }
    } catch (error) {
      console.error('Error opening edit modal:', error);
    }

    // Open modal (always try to open even if there's an error)
    const modal = document.getElementById('editPropertyModal');
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';

    // Display existing images
    const images = propertyImagesData[propertyId] || [];
    const existingImagesContainer = document.getElementById('editExistingImagesContainer');
    existingImagesContainer.innerHTML = '';

    if (images.length > 0) {
      images.forEach(img => {
        const imgDiv = document.createElement('div');
        imgDiv.className = 'relative group';
        const typeSelect = buildImageTypeSelect(`existing_image_type_${img.image_id}`, img.image_type);
        imgDiv.innerHTML = `
            <img src="../../${img.image_url}" alt="${img.caption || 'Property image'}" style="width: 100%; height: 6rem; object-fit: cover; border-radius: 0.5rem;">
            <button type="button" onclick="deletePropertyImage('${img.image_id}', this.closest('.relative'))" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors" title="Delete image">
              <i class="fas fa-trash text-xs"></i>
            </button>
            ${typeSelect}
            ${img.caption ? `<p class="text-xs text-slate-600 dark:text-white/80 mt-1 truncate">${img.caption}</p>` : ''}
          `;
        // Wire the type select to call API on change
        const imgId = img.image_id;
        imgDiv.querySelector('select').addEventListener('change', function() {
          updateImageType(imgId, this.value);
        });
        existingImagesContainer.appendChild(imgDiv);
      });
    } else {
      existingImagesContainer.innerHTML = '<p class="text-sm text-slate-500 dark:text-white/60" style="grid-column: span 4 / span 4;">No images uploaded for this property.</p>';
    }
  }

  function closeEditPropertyModal() {
    const modal = document.getElementById('editPropertyModal');
    modal.classList.remove('open');
    document.body.style.overflow = 'auto';

    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }

    // Clear image preview
    const previewContainer = document.getElementById('editImagePreviewContainer');
    if (previewContainer) {
      previewContainer.innerHTML = '';
    }
  }


  function handleEditImagePreview(input) {
    const previewContainer = document.getElementById('editImagePreviewContainer');
    previewContainer.innerHTML = '';

    if (input.files && input.files.length > 0) {
      // Read files BEFORE clearing input
      const files = Array.from(input.files);

      // Clear input files - we'll add cropped versions back
      input.value = '';
      previewContainer.innerHTML = '';

      // Queue all files for cropping
      files.forEach((file, index) => {
        pendingFiles.push({ file, input, previewContainerId: 'editImagePreviewContainer' });
      });

      // Start cropping the first file
      if (pendingFiles.length > 0) {
        const first = pendingFiles.shift();
        openCropperModal(first.file, first.input, first.previewContainerId);
      }
    }
  }

  // Add Property Form Submission (API-based)
  async function handleAddPropertySubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Get selected geography codes
    const regionCode = document.getElementById('region_code').value;
    const provinceCode = document.getElementById('province_code').value;
    const cityCode = document.getElementById('city_municipality_code').value;
    const barangayCode = document.getElementById('barangay_code').value;

    // Prepare data for API (without property_id - let DB generate it)
    const data = {
      csrf_token: formData.get('csrf_token'),
      property_code: formData.get('property_code'),
      property_name: formData.get('property_name'),
      property_type: formData.get('property_type'),
      tagline: formData.get('tagline') || null,
      description: formData.get('description') || null,
      address_line1: formData.get('address_line1'),
      address_line2: formData.get('address_line2') || null,
      region_code: regionCode || null,
      province_code: provinceCode || null,
      city_municipality_code: cityCode || null,
      barangay_code: barangayCode || null,
      postal_code: formData.get('postal_code') || null,
      country: formData.get('country') || 'Philippines',
      latitude: formData.get('latitude') || null,
      longitude: formData.get('longitude') || null,
      phone_primary: formData.get('phone_primary') || null,
      phone_secondary: formData.get('phone_secondary') || null,
      email: formData.get('email') || null,
      website_url: formData.get('website_url') || null,
      facebook_url: formData.get('facebook_url') || null,
      check_in_time: formData.get('check_in_time') || '14:00:00',
      check_out_time: formData.get('check_out_time') || '12:00:00',
      earliest_check_in: formData.get('earliest_check_in') || '08:00:00',
      latest_check_out: formData.get('latest_check_out') || '22:00:00',
      late_check_out_fee: formData.get('late_check_out_fee') || 0.00,
      timezone: formData.get('timezone') || 'Asia/Manila',
      currency_code: formData.get('currency_code') || 'PHP',
      tax_rate_percent: formData.get('tax_rate_percent') || 0.00,
      is_active: formData.get('is_active') || 1,
      sort_order: formData.get('sort_order') || 0
    };

    try {
      console.log('Submitting property data:', data);

      const response = await fetch('../api/properties.php?action=create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': data.csrf_token
        },
        body: JSON.stringify(data)
      });

      console.log('Response status:', response.status);

      const result = await response.json();
      console.log('Response data:', result);

      if (result.success) {
        // Use the real property_id returned by the server
        const realPropertyId = result.data.property_id;

        // Upload images if any
        const imageInput = document.getElementById('propertyImagesInput');
        if (imageInput.files && imageInput.files.length > 0) {
          await uploadPropertyImages(realPropertyId, imageInput.files, 'imagePreviewContainer');
        }

        showToast(result.message || 'Property added successfully');
        closeAddPropertyModal();
        await fetchPropertiesData();
      } else {
        showToast(result.message || 'Failed to add property', 'error');
      }
    } catch (error) {
      console.error('Fetch error:', error);
      showToast('An error occurred while adding the property: ' + error.message, 'error');
    }
  }

  // Edit Property Form Submission (API-based)
  async function handleEditPropertySubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Get selected geography codes
    const regionCode = document.getElementById('edit_region_code').value;
    const provinceCode = document.getElementById('edit_province_code').value;
    const cityCode = document.getElementById('edit_city_municipality_code').value;
    const barangayCode = document.getElementById('edit_barangay_code').value;

    const data = {
      csrf_token: formData.get('csrf_token'),
      property_id: formData.get('property_id'),
      property_code: formData.get('property_code'),
      property_name: formData.get('property_name'),
      property_type: formData.get('property_type'),
      tagline: formData.get('tagline') || null,
      description: formData.get('description') || null,
      address_line1: formData.get('address_line1'),
      address_line2: formData.get('address_line2') || null,
      region_code: regionCode || null,
      province_code: provinceCode || null,
      city_municipality_code: cityCode || null,
      barangay_code: barangayCode || null,
      postal_code: formData.get('postal_code') || null,
      country: formData.get('country') || 'Philippines',
      latitude: formData.get('latitude') || null,
      longitude: formData.get('longitude') || null,
      phone_primary: formData.get('phone_primary') || null,
      phone_secondary: formData.get('phone_secondary') || null,
      email: formData.get('email') || null,
      website_url: formData.get('website_url') || null,
      facebook_url: formData.get('facebook_url') || null,
      check_in_time: formData.get('check_in_time') || '14:00:00',
      check_out_time: formData.get('check_out_time') || '12:00:00',
      earliest_check_in: formData.get('earliest_check_in') || '08:00:00',
      latest_check_out: formData.get('latest_check_out') || '22:00:00',
      late_check_out_fee: formData.get('late_check_out_fee') || 0.00,
      timezone: formData.get('timezone') || 'Asia/Manila',
      currency_code: formData.get('currency_code') || 'PHP',
      tax_rate_percent: formData.get('tax_rate_percent') || 0.00,
      is_active: formData.get('is_active') || 1,
      sort_order: formData.get('sort_order') || 0
    };

    try {
      console.log('Submitting update data:', data);

      const response = await fetch('../api/properties.php?action=update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': data.csrf_token
        },
        body: JSON.stringify(data)
      });

      console.log('Response status:', response.status);

      const result = await response.json();
      console.log('Response data:', result);

      if (result.success) {
        // Upload new images if any were selected
        const editImageInput = document.getElementById('editPropertyImagesInput');
        if (editImageInput && editImageInput.files && editImageInput.files.length > 0) {
          const imgResult = await uploadPropertyImages(data.property_id, editImageInput.files, 'editImagePreviewContainer');
          if (!imgResult.success) {
            console.error('Image upload failed:', imgResult.message);
          }
        }

        showToast(result.message || 'Property updated successfully');
        closeEditPropertyModal();
        await fetchPropertiesData();
      } else {
        showToast(result.message || 'Failed to update property', 'error');
      }
    } catch (error) {
      showToast('An error occurred while updating the property: ' + error.message, 'error');
    }
  }

  // Filter table function
  function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('propertiesTable');
    const rows = table.getElementsByClassName('property-row');

    for (let i = 0; i < rows.length; i++) {
      const row = rows[i];
      const cells = row.getElementsByTagName('td');
      let found = false;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
          found = true;
          break;
        }
      }

      row.style.display = found ? '' : 'none';
    }
  }

  // Geography dropdown functions

  // Geocoding function using OpenStreetMap Nominatim API
  async function geocodeAddress() {
    const addressLine1El = document.getElementById('address_line1');
    const cityDisplayEl = document.getElementById('city_municipality_code_display');
    const provinceDisplayEl = document.getElementById('province_code_display');

    if (!addressLine1El || !cityDisplayEl || !provinceDisplayEl) {
      console.log('Required elements not found, skipping geocoding');
      return;
    }

    const addressLine1 = addressLine1El.value;
    const cityDisplay = cityDisplayEl.value;
    const provinceDisplay = provinceDisplayEl.value;

    if (!cityDisplay) {
      console.log('City not selected, skipping geocoding');
      return;
    }

    try {
      const lat = document.getElementById('latitude');
      const lon = document.getElementById('longitude');
      if (!lat || !lon) {
        console.log('Latitude/Longitude elements not found, skipping geocoding');
        return;
      }
      const result = await nominatimGeocode(addressLine1, cityDisplay, provinceDisplay);
      if (result) {
        lat.value = result.lat;
        lon.value = result.lon;
      }
    } catch (error) {
      console.error('Geocoding error:', error);
    }
  }

  // Edit modal geocoding function
  async function geocodeEditAddress() {
    const addressLine1El = document.getElementById('edit_address_line1');
    const cityDisplayEl = document.getElementById('edit_city_municipality_code_display');
    const provinceDisplayEl = document.getElementById('edit_province_code_display');

    if (!addressLine1El || !cityDisplayEl || !provinceDisplayEl) {
      console.log('Required elements not found, skipping geocoding');
      return;
    }

    const addressLine1 = addressLine1El.value;
    const cityDisplay = cityDisplayEl.value;
    const provinceDisplay = provinceDisplayEl.value;

    if (!cityDisplay) {
      console.log('City not selected, skipping geocoding');
      return;
    }

    try {
      const lat = document.getElementById('edit_latitude');
      const lon = document.getElementById('edit_longitude');
      if (!lat || !lon) {
        console.log('Latitude/Longitude elements not found, skipping geocoding');
        return;
      }
      const result = await nominatimGeocode(addressLine1, cityDisplay, provinceDisplay);
      if (result) {
        lat.value = result.lat;
        lon.value = result.lon;
      }
    } catch (error) {
      console.error('Geocoding error:', error);
    }
  }

  // Shared Nominatim geocoding with fallback strategy
  async function nominatimGeocode(street, city, province) {
    const baseUrl = 'https://nominatim.openstreetmap.org/search';
    const headers = {
      'Accept-Language': 'en'
    };

    // Strategy 1: structured query with street + city + province
    if (street) {
      const params = new URLSearchParams({
        format: 'json',
        limit: 1,
        countrycodes: 'ph',
        street,
        city,
        county: province
      });
      const res = await fetch(`${baseUrl}?${params}`, {
        headers
      });
      const data = await res.json();
      if (data.length > 0) return data[0];
    }

    // Strategy 2: city + province structured
    const params2 = new URLSearchParams({
      format: 'json',
      limit: 1,
      countrycodes: 'ph',
      city,
      county: province
    });
    const res2 = await fetch(`${baseUrl}?${params2}`, {
      headers
    });
    const data2 = await res2.json();
    if (data2.length > 0) return data2[0];

    // Strategy 3: city only
    const params3 = new URLSearchParams({
      format: 'json',
      limit: 1,
      countrycodes: 'ph',
      city
    });
    const res3 = await fetch(`${baseUrl}?${params3}`, {
      headers
    });
    const data3 = await res3.json();
    if (data3.length > 0) return data3[0];

    console.log('No geocoding results found for:', city, province);
    return null;
  }

  async function fetchRegions() {
    try {
      const response = await fetch('../api/geocode.php?action=regions');
      const result = await response.json();
      if (result.success) {
        regionsData = result.data;
        return result.data;
      }
      return [];
    } catch (error) {
      console.error('Error fetching regions:', error);
      return [];
    }
  }

  async function fetchProvinces(regionCode) {
    try {
      const response = await fetch(`../api/geocode.php?action=provinces&region_code=${regionCode}`);
      const result = await response.json();
      if (result.success) {
        return result.data;
      }
      return [];
    } catch (error) {
      console.error('Error fetching provinces:', error);
      return [];
    }
  }

  async function fetchCities(provinceCode) {
    try {
      const response = await fetch(`../api/geocode.php?action=cities_municipalities&province_code=${provinceCode}`);
      const result = await response.json();
      if (result.success) {
        return result.data;
      }
      return [];
    } catch (error) {
      console.error('Error fetching cities:', error);
      return [];
    }
  }

  async function fetchBarangays(cityCode) {
    try {
      const response = await fetch(`../api/geocode.php?action=barangays&city_municipality_code=${cityCode}`);
      const result = await response.json();
      if (result.success) {
        return result.data;
      }
      return [];
    } catch (error) {
      console.error('Error fetching barangays:', error);
      return [];
    }
  }

  function populateDatalist(datalistId, data, labelField, codeField = null) {
    const datalist = document.getElementById(datalistId);
    if (!datalist) return;
    datalist.innerHTML = '';
    data.forEach(item => {
      const option = document.createElement('option');
      option.value = item[labelField];
      if (codeField) {
        option.dataset.code = item[codeField];
      }
      datalist.appendChild(option);
    });
  }

  function getSelectedCode(displayInputId, datalistId) {
    const displayInput = document.getElementById(displayInputId);
    const datalist = document.getElementById(datalistId);
    if (!displayInput || !datalist) return null;
    const selectedValue = displayInput.value;
    const option = Array.from(datalist.options).find(opt => opt.value === selectedValue);
    return option ? option.dataset.code : null;
  }

  // Add Property modal geography handlers
  async function onRegionChange() {
    const regionCode = getSelectedCode('region_code_display', 'region_list');
    document.getElementById('region_code').value = regionCode || '';
    if (regionCode) {
      const provinces = await fetchProvinces(regionCode);
      populateDatalist('province_list', provinces, 'province_name', 'province_code');
    }
    document.getElementById('province_code_display').value = '';
    document.getElementById('province_code').value = '';
    document.getElementById('city_municipality_code_display').value = '';
    document.getElementById('city_municipality_code').value = '';
    document.getElementById('barangay_code_display').value = '';
    document.getElementById('barangay_code').value = '';
  }

  async function onProvinceChange() {
    const provinceCode = getSelectedCode('province_code_display', 'province_list');
    document.getElementById('province_code').value = provinceCode || '';
    if (provinceCode) {
      const cities = await fetchCities(provinceCode);
      populateDatalist('city_list', cities, 'city_municipality_name', 'city_municipality_code');
    }
    document.getElementById('city_municipality_code_display').value = '';
    document.getElementById('city_municipality_code').value = '';
    document.getElementById('barangay_code_display').value = '';
    document.getElementById('barangay_code').value = '';
  }

  async function onCityChange() {
    const cityCode = getSelectedCode('city_municipality_code_display', 'city_list');
    document.getElementById('city_municipality_code').value = cityCode || '';
    if (cityCode) {
      const barangays = await fetchBarangays(cityCode);
      populateDatalist('barangay_list', barangays, 'barangay_name', 'barangay_code');
      // Auto-geocode when city is selected
      geocodeAddress();
    }
    document.getElementById('barangay_code_display').value = '';
    document.getElementById('barangay_code').value = '';
  }

  function onBarangayChange() {
    const barangayCode = getSelectedCode('barangay_code_display', 'barangay_list');
    document.getElementById('barangay_code').value = barangayCode || '';
    // Auto-geocode when barangay is selected
    if (barangayCode) {
      geocodeAddress();
    }
  }

  // Edit Property modal geography handlers
  async function onEditRegionChange() {
    const regionCode = getSelectedCode('edit_region_code_display', 'edit_region_list');
    document.getElementById('edit_region_code').value = regionCode || '';
    if (regionCode) {
      const provinces = await fetchProvinces(regionCode);
      populateDatalist('edit_province_list', provinces, 'province_name', 'province_code');
    }
    document.getElementById('edit_province_code_display').value = '';
    document.getElementById('edit_province_code').value = '';
    document.getElementById('edit_city_municipality_code_display').value = '';
    document.getElementById('edit_city_municipality_code').value = '';
    document.getElementById('edit_barangay_code_display').value = '';
    document.getElementById('edit_barangay_code').value = '';
  }

  async function onEditProvinceChange() {
    const provinceCode = getSelectedCode('edit_province_code_display', 'edit_province_list');
    document.getElementById('edit_province_code').value = provinceCode || '';
    if (provinceCode) {
      const cities = await fetchCities(provinceCode);
      populateDatalist('edit_city_list', cities, 'city_municipality_name', 'city_municipality_code');
    }
    document.getElementById('edit_city_municipality_code_display').value = '';
    document.getElementById('edit_city_municipality_code').value = '';
    document.getElementById('edit_barangay_code_display').value = '';
    document.getElementById('edit_barangay_code').value = '';
  }

  async function onEditCityChange() {
    const cityCode = getSelectedCode('edit_city_municipality_code_display', 'edit_city_list');
    document.getElementById('edit_city_municipality_code').value = cityCode || '';
    if (cityCode) {
      const barangays = await fetchBarangays(cityCode);
      populateDatalist('edit_barangay_list', barangays, 'barangay_name', 'barangay_code');
      // Auto-geocode when city is selected
      geocodeEditAddress();
    }
    document.getElementById('edit_barangay_code_display').value = '';
    document.getElementById('edit_barangay_code').value = '';
  }

  function onEditBarangayChange() {
    const barangayCode = getSelectedCode('edit_barangay_code_display', 'edit_barangay_list');
    document.getElementById('edit_barangay_code').value = barangayCode || '';
    // Auto-geocode when barangay is selected
    if (barangayCode) {
      geocodeEditAddress();
    }
  }

  // Fetch and populate property types
  async function fetchPropertyTypes(selectId) {
    try {
      const response = await fetch('../api/property_types.php?action=list');
      const result = await response.json();
      if (result.success) {
        const select = document.getElementById(selectId);
        if (select) {
          select.innerHTML = '<option value="">Select property type</option>';
          result.data.forEach(type => {
            const option = document.createElement('option');
            option.value = type.type_code;
            option.textContent = type.type_name;
            select.appendChild(option);
          });
        }
      }
    } catch (error) {
      console.error('Error fetching property types:', error);
    }
  }

  // No wrapper needed for openEditPropertyModal since it's already async and initializes dropdowns
</script>