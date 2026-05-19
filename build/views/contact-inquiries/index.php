<?php if (false) {} ?>

<style>
/* ── Reset & base ─────────────────────────────── */
.ci-list *{box-sizing:border-box}
.ci-list{font-family:var(--font-sans,system-ui,sans-serif);color:var(--color-text-primary,#111)}

/* ── Stats grid ───────────────────────────────── */
.ci-stats-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:.75rem;margin-bottom:1.5rem}
@media(max-width:680px){.ci-stats-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:420px){.ci-stats-grid{grid-template-columns:repeat(2,1fr)}}

.ci-stat-card{border-radius:12px;padding:1rem 1.1rem;cursor:pointer;border:.5px solid transparent;transition:border-color .15s,box-shadow .15s;text-decoration:none;display:block}
.ci-stat-card:hover{box-shadow:0 0 0 2px currentColor}
.ci-stat-card.active{box-shadow:0 0 0 2px var(--ci-accent)}

.ci-stat-label{font-size:12px;font-weight:500;margin-bottom:.35rem}
.ci-stat-count{font-size:28px;font-weight:600;line-height:1}

.ci-stat-all      {background:#fff;color:#374151;--ci-accent:#3b82f6}
.ci-stat-new      {background:#fef2f2;color:#dc2626;--ci-accent:#dc2626}
.ci-stat-read     {background:#eff6ff;color:#2563eb;--ci-accent:#2563eb}
.ci-stat-replied  {background:#f0fdf4;color:#16a34a;--ci-accent:#16a34a}
.ci-stat-archived {background:#f9fafb;color:#4b5563;--ci-accent:#6b7280}

/* dark mode */
@media(prefers-color-scheme:dark){
  .ci-stat-all     {background:var(--color-background-primary,#1e293b)}
  .ci-stat-new     {background:#450a0a}
  .ci-stat-read    {background:#1e3a5f}
  .ci-stat-replied {background:#052e16}
  .ci-stat-archived{background:var(--color-background-secondary,#334155)}
}

/* ── Search bar ───────────────────────────────── */
.ci-search-row{display:flex;gap:.5rem;margin-bottom:1.25rem;flex-wrap:wrap}
.ci-search-input{flex:1;min-width:200px;padding:8px 12px;border:.5px solid var(--color-border-secondary,#d1d5db);border-radius:8px;background:var(--color-background-primary,#fff);color:var(--color-text-primary,#111);font-size:14px;font-family:inherit}
.ci-search-input:focus{outline:none;box-shadow:0 0 0 2px rgba(59,130,246,.35)}
.ci-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;white-space:nowrap;transition:opacity .15s}
.ci-btn:hover{opacity:.85}
.ci-btn-primary{background:#3b82f6;color:#fff}
.ci-btn-ghost{background:transparent;border:.5px solid var(--color-border-secondary,#d1d5db);color:var(--color-text-primary,#111)}

/* ── Table card ───────────────────────────────── */
.ci-table-card{background:var(--color-background-primary,#fff);border:.5px solid var(--color-border-tertiary,#e5e7eb);border-radius:12px;overflow:hidden;margin-bottom:1.5rem}
.ci-table-header{padding:1.1rem 1.25rem;border-bottom:.5px solid var(--color-border-tertiary,#e5e7eb)}
.ci-table-title{font-size:16px;font-weight:500}
.ci-table-subtitle{font-size:13px;color:var(--color-text-secondary,#6b7280);margin-top:3px}

.ci-table{width:100%;border-collapse:collapse;font-size:13px}
.ci-table th{padding:.7rem 1rem;text-align:left;font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-tertiary,#9ca3af);border-bottom:.5px solid var(--color-border-tertiary,#e5e7eb);white-space:nowrap}
.ci-table td{padding:.75rem 1rem;border-bottom:.5px solid var(--color-border-tertiary,#e5e7eb);vertical-align:middle;color:var(--color-text-primary,#111)}
.ci-table tbody tr:last-child td{border-bottom:none}
.ci-table tbody tr{transition:background .12s}
.ci-table tbody tr:hover{background:var(--color-background-secondary,#f9fafb)}

/* ── Status badges ────────────────────────────── */
.ci-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:99px;font-size:12px;font-weight:500}
.badge-new     {background:var(--color-background-danger,#fee2e2);color:var(--color-text-danger,#b91c1c)}
.badge-read    {background:var(--color-background-info,#dbeafe);color:var(--color-text-info,#1d4ed8)}
.badge-replied {background:var(--color-background-success,#dcfce7);color:var(--color-text-success,#15803d)}
.badge-archived{background:var(--color-background-secondary,#f3f4f6);color:var(--color-text-secondary,#4b5563)}

/* ── Table states ─────────────────────────────── */
.ci-table-state{padding:3rem 1rem;text-align:center;color:var(--color-text-secondary,#6b7280)}
.ci-table-state i{font-size:2rem;display:block;margin-bottom:.5rem}
.ci-table-state p{font-size:14px}

/* ── Truncate ─────────────────────────────────── */
.ci-truncate{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;display:block}

/* ── Row states ───────────────────────────────── */
.ci-table tbody tr.ci-row-new td:first-child{border-left:3px solid #dc2626}
.ci-table tbody tr.ci-row-clickable{cursor:pointer}
.ci-table tbody tr.ci-row-clickable:hover{background:var(--color-background-secondary,#f0f4ff)}

/* ── Stat icon ────────────────────────────────── */
.ci-stat-icon{font-size:20px;display:block;margin-bottom:.35rem;opacity:.7}

/* ── Auto-refresh indicator ───────────────────── */
.ci-refresh-bar{display:flex;align-items:center;gap:8px;font-size:12px;color:var(--color-text-tertiary,#9ca3af);margin-bottom:.75rem}
.ci-refresh-dot{width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;animation:ci-pulse 2s infinite}
@keyframes ci-pulse{0%,100%{opacity:1}50%{opacity:.35}}

/* ── Pagination ───────────────────────────────── */
.ci-pagination{display:flex;justify-content:space-between;align-items:center;padding:.85rem 1.25rem;border-top:.5px solid var(--color-border-tertiary,#e5e7eb);flex-wrap:wrap;gap:.5rem}
.ci-pagination-text{font-size:13px;color:var(--color-text-secondary,#6b7280)}
.ci-pagination-btns{display:flex;gap:.5rem}
.ci-page-btn{padding:5px 12px;border-radius:8px;border:.5px solid var(--color-border-secondary,#d1d5db);background:var(--color-background-primary,#fff);color:var(--color-text-primary,#111);font-size:13px;cursor:pointer;transition:background .12s}
.ci-page-btn:hover{background:var(--color-background-secondary,#f9fafb)}
.ci-page-btn[disabled]{opacity:.4;cursor:default}

/* ── Toast ────────────────────────────────────── */
.ci-toast-wrap{position:fixed;top:1rem;right:1rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;pointer-events:none}
.ci-toast{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;font-size:13px;font-weight:500;pointer-events:all;opacity:0;transform:translateX(20px);transition:opacity .2s,transform .2s;background:var(--color-background-primary,#fff);border:.5px solid var(--color-border-tertiary,#e5e7eb);color:var(--color-text-primary,#111);min-width:200px;max-width:320px;box-shadow:0 4px 16px rgba(0,0,0,.1)}
.ci-toast.ci-toast-show{opacity:1;transform:translateX(0)}
.ci-toast i{font-size:15px}
.ci-toast-success i{color:#22c55e}
.ci-toast-error   i{color:#ef4444}
.ci-toast-info    i{color:#3b82f6}
.ci-toast-warning i{color:#f59e0b}
</style>

<!-- PAGE CONTENT -->
<div class="ci-list">

  <!-- Stats -->
  <div class="ci-stats-grid" id="statsContainer" role="group" aria-label="Filter by status">
    <?php
    $statDefs = [
      ['key' => 'all',      'label' => 'All',      'cls' => 'ci-stat-all'],
      ['key' => 'new',      'label' => 'New',      'cls' => 'ci-stat-new'],
      ['key' => 'read',     'label' => 'Read',     'cls' => 'ci-stat-read'],
      ['key' => 'replied',  'label' => 'Replied',  'cls' => 'ci-stat-replied'],
      ['key' => 'archived', 'label' => 'Archived', 'cls' => 'ci-stat-archived'],
    ];
    $statIcons = [
      'all'      => 'fa-envelope',
      'new'      => 'fa-circle-dot',
      'read'     => 'fa-eye',
      'replied'  => 'fa-check',
      'archived' => 'fa-box-archive',
    ];
    ?>
    <?php foreach ($statDefs as $s): ?>
    <a href="javascript:void(0)"
      onclick="ciSetStatus('<?php echo $s['key']; ?>')"
      class="ci-stat-card <?php echo $s['cls']; ?><?php echo $s['key'] === 'all' ? ' active' : ''; ?>"
      id="card-<?php echo $s['key']; ?>"
      aria-pressed="<?php echo $s['key'] === 'all' ? 'true' : 'false'; ?>">
      <i class="fas <?php echo $statIcons[$s['key']]; ?> ci-stat-icon" aria-hidden="true"></i>
      <p class="ci-stat-label"><?php echo $s['label']; ?></p>
      <p class="ci-stat-count" id="count-<?php echo $s['key']; ?>">–</p>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Search bar -->
  <div style="background:var(--color-background-primary,#fff);border:.5px solid var(--color-border-tertiary,#e5e7eb);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1rem">
    <div class="ci-search-row" style="margin-bottom:.75rem">
      <input type="hidden" id="statusFilter" value="all">
      <input type="text" id="searchInput" class="ci-search-input"
        placeholder="Search by name, email, subject or message…"
        aria-label="Search inquiries">
      <button class="ci-btn ci-btn-primary" onclick="ciDoSearch()">
        <i class="fas fa-search" aria-hidden="true"></i> Search
      </button>
      <button class="ci-btn ci-btn-ghost" id="clearBtn" style="display:none" onclick="ciClearSearch()">
        <i class="fas fa-xmark" aria-hidden="true"></i> Clear
      </button>
    </div>
    <div class="ci-refresh-bar" style="margin-bottom:0">
      <span class="ci-refresh-dot"></span>
      <span id="ciRefreshStatus">Auto-refreshing every 60s</span>
      <span id="ciLastRefreshed" style="margin-left:auto"></span>
    </div>
  </div>

  <!-- Table card -->
  <div class="ci-table-card">
    <div class="ci-table-header">
      <p class="ci-table-title">Contact Inquiries</p>
      <p class="ci-table-subtitle" id="totalCountText">Loading…</p>
    </div>

    <div style="overflow-x:auto">
      <table class="ci-table" aria-label="Contact inquiries">
        <thead>
          <tr>
            <th>Status</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Date</th>
            <th style="text-align:right">Action</th>
          </tr>
        </thead>
        <tbody id="inquiriesTableBody">
          <tr>
            <td colspan="7">
              <div class="ci-table-state">
                <i class="ti ti-loader-2" aria-hidden="true"></i>
                <p>Loading inquiries…</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="ci-pagination" id="paginationContainer" style="display:none">
      <p class="ci-pagination-text" id="paginationText"></p>
      <div class="ci-pagination-btns" id="paginationButtons"></div>
    </div>
  </div>

</div><!-- /ci-list -->

<!-- Toast container -->
<div class="ci-toast-wrap" id="ciToastWrap" aria-live="polite"></div>

<script>
(function () {
  /* ── State ── */
  let currentStatus = 'all';
  let currentSearch = '';
  let currentPage   = 1;

  const STATUS_BADGE = {
    new:      'badge-new',
    read:     'badge-read',
    replied:  'badge-replied',
    archived: 'badge-archived',
  };
  const STATUS_LABEL = {
    new: 'New', read: 'Read', replied: 'Replied', archived: 'Archived',
  };
  const CARD_ACCENT = {
    all: '#3b82f6', new: '#dc2626', read: '#2563eb', replied: '#16a34a', archived: '#6b7280',
  };

  /* ── Toast ── */
  function showToast(msg, type = 'info') {
    const icons = {
      success: 'fa-circle-check', error: 'fa-circle-xmark',
      info: 'fa-circle-info', warning: 'fa-triangle-exclamation',
    };
    const wrap  = document.getElementById('ciToastWrap');
    const toast = document.createElement('div');
    toast.className = `ci-toast ci-toast-${type}`;
    toast.innerHTML = `
      <i class="fas ${icons[type]}" aria-hidden="true"></i>
      <span style="flex:1">${msg}</span>
      <button style="background:none;border:none;cursor:pointer;color:inherit;font-size:16px;line-height:1;padding:0 2px" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
    `;
    wrap.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('ci-toast-show'));
    setTimeout(() => {
      toast.classList.remove('ci-toast-show');
      setTimeout(() => toast.remove(), 250);
    }, 4000);
  }

  /* ── Fetch stats ── */
  async function fetchStats() {
    try {
      const res  = await fetch('api/contact_inquiries.php?action=stats');
      const json = await res.json();
      if (json.success) renderStats(json.data);
    } catch (e) {
      console.error('Stats fetch failed:', e);
    }
  }

  function renderStats(stats) {
    ['all', 'new', 'read', 'replied', 'archived'].forEach(key => {
      const countEl = document.getElementById(`count-${key}`);
      const cardEl  = document.getElementById(`card-${key}`);
      if (countEl) countEl.textContent = stats[key] ?? 0;
      if (cardEl) {
        const isActive = currentStatus === key;
        cardEl.classList.toggle('active', isActive);
        cardEl.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        cardEl.style.boxShadow = isActive
          ? `0 0 0 2px ${CARD_ACCENT[key]}, 0 4px 12px rgba(0,0,0,.08)`
          : '0 2px 8px rgba(0,0,0,.06)';
      }
    });
  }

  /* ── Fetch inquiries ── */
  async function fetchInquiries() {
    const tbody = document.getElementById('inquiriesTableBody');
    tbody.innerHTML = `
      <tr><td colspan="7">
        <div class="ci-table-state">
          <i class="fas fa-spinner fa-spin" aria-hidden="true"></i>
          <p>Loading inquiries…</p>
        </div>
      </td></tr>
    `;

    try {
      const params = new URLSearchParams({
        status: currentStatus,
        search: currentSearch,
        page:   currentPage,
      });
      const res  = await fetch(`api/contact_inquiries.php?${params}`);
      const json = await res.json();

      if (json.success) {
        renderInquiries(json.data);
      } else {
        throw new Error(json.message || 'Unknown error');
      }
    } catch (e) {
      console.error(e);
      tbody.innerHTML = `
        <tr><td colspan="7">
          <div class="ci-table-state">
            <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
            <p>Failed to load inquiries. Please refresh.</p>
          </div>
        </td></tr>
      `;
    }
  }

  function renderInquiries({ inquiries, totalCount, page, perPage, totalPages }) {
    const tbody     = document.getElementById('inquiriesTableBody');
    const countText = document.getElementById('totalCountText');

    countText.textContent = `${totalCount} ${totalCount === 1 ? 'inquiry' : 'inquiries'} found`;

    if (!inquiries.length) {
      tbody.innerHTML = `
        <tr><td colspan="7">
          <div class="ci-table-state">
            <i class="fas fa-inbox" aria-hidden="true"></i>
            <p>No inquiries found.</p>
          </div>
        </td></tr>
      `;
      document.getElementById('paginationContainer').style.display = 'none';
      return;
    }

    tbody.innerHTML = inquiries.map(inq => {
      const badgeCls  = STATUS_BADGE[inq.status] || 'badge-archived';
      const badgeLbl  = STATUS_LABEL[inq.status] || inq.status;
      const isNew     = inq.status === 'new';
      const rowCls    = `ci-row-clickable${isNew ? ' ci-row-new' : ''}`;
      const viewUrl   = `./contact-inquiries/view?id=${inq.inquiry_id}`;

      // Relative date with full date tooltip
      const createdMs = new Date(inq.created_at).getTime();
      const diffSec   = Math.floor((Date.now() - createdMs) / 1000);
      let relDate;
      if (diffSec < 60)           relDate = 'Just now';
      else if (diffSec < 3600)    relDate = Math.floor(diffSec / 60) + ' min ago';
      else if (diffSec < 86400)   relDate = Math.floor(diffSec / 3600) + ' hr ago';
      else if (diffSec < 604800)  relDate = Math.floor(diffSec / 86400) + ' day(s) ago';
      else relDate = new Date(inq.created_at).toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });
      const fullDate  = new Date(inq.created_at).toLocaleString('en-US', { month:'short', day:'numeric', year:'numeric', hour:'numeric', minute:'2-digit' });

      const preview  = (inq.message || '').substring(0, 60) + (inq.message.length > 60 ? '…' : '');
      const nameWt   = isNew ? 'font-weight:600' : 'font-weight:500';

      return `
        <tr class="${rowCls}" onclick="window.location='${viewUrl}'">
          <td>
            <span class="ci-badge ${badgeCls}">${escHtml(badgeLbl)}</span>
          </td>
          <td>
            <span style="${nameWt}">${escHtml(inq.full_name)}</span>
          </td>
          <td>
            <a href="mailto:${escHtml(inq.email)}" style="color:var(--color-text-info,#2563eb);text-decoration:none"
              onclick="event.stopPropagation()">${escHtml(inq.email)}</a>
          </td>
          <td>
            <span class="ci-truncate" style="max-width:160px" title="${escHtml(inq.subject || '')}">${escHtml(inq.subject || '—')}</span>
          </td>
          <td>
            <span class="ci-truncate" style="max-width:200px;color:var(--color-text-secondary,#6b7280)" title="${escHtml(inq.message || '')}">${escHtml(preview)}</span>
          </td>
          <td style="white-space:nowrap;color:var(--color-text-secondary,#6b7280)" title="${fullDate}">${relDate}</td>
          <td style="text-align:right">
            <a href="${viewUrl}"
              style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:8px;font-size:12px;font-weight:500;background:#3b82f6;color:#fff;text-decoration:none;border:none"
              onclick="event.stopPropagation()">
              <i class="fas fa-eye" style="font-size:13px"></i> View
            </a>
          </td>
        </tr>
      `;
    }).join('');

    /* Pagination */
    const pagEl = document.getElementById('paginationContainer');
    if (totalPages > 1) {
      pagEl.style.display = 'flex';
      const start = (page - 1) * perPage + 1;
      const end   = Math.min(page * perPage, totalCount);
      document.getElementById('paginationText').textContent = `Showing ${start}–${end} of ${totalCount}`;

      const prevBtn = page > 1
        ? `<button class="ci-page-btn" onclick="ciSetPage(${page - 1})"><i class="fas fa-chevron-left" aria-hidden="true"></i> Previous</button>`
        : `<button class="ci-page-btn" disabled><i class="fas fa-chevron-left" aria-hidden="true"></i> Previous</button>`;
      const nextBtn = page < totalPages
        ? `<button class="ci-page-btn" onclick="ciSetPage(${page + 1})">Next <i class="fas fa-chevron-right" aria-hidden="true"></i></button>`
        : `<button class="ci-page-btn" disabled>Next <i class="fas fa-chevron-right" aria-hidden="true"></i></button>`;

      document.getElementById('paginationButtons').innerHTML = prevBtn + nextBtn;
    } else {
      pagEl.style.display = 'none';
    }
  }

  /* ── Helpers ── */
  function escHtml(str) {
    const d = document.createElement('div');
    d.textContent = String(str ?? '');
    return d.innerHTML;
  }

  function reload() {
    fetchStats();
    fetchInquiries();
  }

  /* ── Public API (called from inline handlers) ── */
  window.ciSetStatus = function (status) {
    currentStatus = status;
    currentPage   = 1;
    document.getElementById('statusFilter').value = status;
    reload();
  };

  window.ciSetPage = function (page) {
    currentPage = page;
    fetchInquiries();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  window.ciDoSearch = function () {
    currentSearch = document.getElementById('searchInput').value.trim();
    currentPage   = 1;
    document.getElementById('clearBtn').style.display = currentSearch ? 'inline-flex' : 'none';
    fetchInquiries();
  };

  window.ciClearSearch = function () {
    document.getElementById('searchInput').value = '';
    currentSearch = '';
    currentPage   = 1;
    document.getElementById('clearBtn').style.display = 'none';
    fetchInquiries();
  };

  /* ── Search on Enter ── */
  document.getElementById('searchInput').addEventListener('keydown', e => {
    if (e.key === 'Enter') ciDoSearch();
  });

  /* ── Auto-refresh ── */
  function updateLastRefreshed() {
    const el = document.getElementById('ciLastRefreshed');
    if (el) el.textContent = 'Last updated ' + new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
  }

  const originalReload = reload;
  function reloadWithTimestamp() {
    originalReload();
    updateLastRefreshed();
  }

  setInterval(() => {
    reloadWithTimestamp();
  }, 60000);

  /* ── Init ── */
  reloadWithTimestamp();
})();
</script>