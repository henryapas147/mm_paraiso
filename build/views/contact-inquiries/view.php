<?php
/** @var array $inquiry */
$inquiry = $inquiry ?? [];

$statusColors = [
    'new'      => 'badge-new',
    'read'     => 'badge-read',
    'replied'  => 'badge-replied',
    'archived' => 'badge-archived',
];
$statusLabels = [
    'new'      => 'New',
    'read'     => 'Read',
    'replied'  => 'Replied',
    'archived' => 'Archived',
];
$statusIcons = [
    'new'      => 'fa-circle-dot',
    'read'     => 'fa-eye',
    'replied'  => 'fa-check',
    'archived' => 'fa-box-archive',
];

$currentStatus      = $inquiry['status'] ?? 'new';
$currentStatusClass = $statusColors[$currentStatus]  ?? 'badge-archived';
$currentStatusLabel = $statusLabels[$currentStatus]  ?? ucfirst($currentStatus);
$currentStatusIcon  = $statusIcons[$currentStatus]   ?? 'fa-circle-dot';

$avatarInitials = '';
if (!empty($inquiry['full_name'])) {
    $parts = explode(' ', trim($inquiry['full_name']));
    foreach (array_slice($parts, 0, 2) as $p) {
        $avatarInitials .= strtoupper($p[0]);
    }
}

$csrfToken = htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8');
?>

<style>
/* ── Reset & base ─────────────────────────────── */
.ci-page *{box-sizing:border-box}
.ci-page{padding:1rem 1.25rem;width:100%;font-family:var(--font-sans,system-ui,sans-serif);color:var(--color-text-primary,#111)}

/* ── Topbar ───────────────────────────────────── */
.ci-topbar{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem}
.ci-breadcrumb{display:flex;align-items:center;gap:5px;font-size:13px;color:var(--color-text-secondary,#666)}
.ci-breadcrumb a{color:var(--color-text-secondary,#666);text-decoration:none;display:inline-flex;align-items:center;gap:4px}
.ci-breadcrumb a:hover{color:var(--color-text-primary,#111)}
.ci-breadcrumb .sep{opacity:.4;font-size:11px}
.ci-page-title{font-size:18px;font-weight:500;margin-top:.3rem}
.ci-meta-line{font-size:13px;color:var(--color-text-secondary,#666);margin-top:3px}
.ci-meta-line i{font-size:13px;vertical-align:-2px;margin-right:3px}

/* ── Back button ──────────────────────────────── */
.ci-back-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;border:.5px solid var(--color-border-secondary,#ccc);background:var(--color-background-primary,#fff);color:var(--color-text-primary,#111);font-size:13px;cursor:pointer;text-decoration:none;transition:background .15s;white-space:nowrap}
.ci-back-btn:hover{background:var(--color-background-secondary,#f5f5f5)}

/* ── Layout ───────────────────────────────────── */
.ci-layout{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(0,1fr);gap:1rem;align-items:start}
@media(max-width:860px){.ci-layout{grid-template-columns:1fr}}

/* ── Card ─────────────────────────────────────── */
.ci-card{background:var(--color-background-primary,#fff);border:.5px solid var(--color-border-tertiary,#e5e7eb);border-radius:12px;padding:1.25rem;margin-bottom:1rem}
.ci-card:last-child{margin-bottom:0}
.ci-card-title{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.07em;color:var(--color-text-tertiary,#9ca3af);margin-bottom:1rem}

/* ── Status badges ────────────────────────────── */
.ci-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:99px;font-size:12px;font-weight:500}
.badge-new{background:var(--color-background-danger,#fee2e2);color:var(--color-text-danger,#b91c1c)}
.badge-read{background:var(--color-background-info,#dbeafe);color:var(--color-text-info,#1d4ed8)}
.badge-replied{background:var(--color-background-success,#dcfce7);color:var(--color-text-success,#15803d)}
.badge-archived{background:var(--color-background-secondary,#f3f4f6);color:var(--color-text-secondary,#4b5563)}

/* ── Status row ───────────────────────────────── */
.ci-status-row{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:1rem}
.ci-status-select{padding:5px 10px;border-radius:8px;border:.5px solid var(--color-border-secondary,#d1d5db);background:var(--color-background-primary,#fff);color:var(--color-text-primary,#111);font-size:13px;cursor:pointer}
.ci-status-hint{font-size:12px;color:var(--color-text-tertiary,#9ca3af)}

/* ── Inline meta chips ────────────────────────── */
.ci-chips{display:flex;gap:12px;flex-wrap:wrap;font-size:13px;color:var(--color-text-secondary,#6b7280)}
.ci-chips span{display:inline-flex;align-items:center;gap:4px}
.ci-chips i{font-size:13px;vertical-align:-2px}

/* ── Message box ──────────────────────────────── */
.ci-msg-box{background:var(--color-background-secondary,#f9fafb);border-radius:8px;padding:1.25rem;margin-bottom:1rem}
.ci-msg-subject{font-size:16px;font-weight:500;margin-bottom:.75rem}
.ci-msg-body{font-size:14px;color:var(--color-text-secondary,#4b5563);line-height:1.75;white-space:pre-wrap}

/* ── Notes box ────────────────────────────────── */
.ci-notes-box{background:var(--color-background-warning,#fffbeb);border:.5px solid var(--color-border-warning,#fcd34d);border-radius:8px;padding:1.25rem}
.ci-card-notes{background:#fffbeb !important;border-color:#fcd34d !important}
.ci-notes-label{display:flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:var(--color-text-warning,#92400e);margin-bottom:.5rem}
.ci-notes-label small{font-weight:400;font-size:11px;color:var(--color-text-tertiary,#9ca3af)}
.ci-notes-footer{display:flex;align-items:center;gap:.75rem;margin-top:.6rem;flex-wrap:wrap}
.ci-saved-at{font-size:12px;color:var(--color-text-tertiary,#9ca3af)}

/* ── Buttons ──────────────────────────────────── */
.ci-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:none;transition:opacity .15s,background .15s;text-decoration:none}
.ci-btn:hover{opacity:.88}
.ci-btn-primary{background:#3b82f6;color:#fff}
.ci-btn-success{background:#22c55e;color:#fff}
.ci-btn-danger{background:#ef4444;color:#fff}
.ci-btn-ghost{background:transparent;border:.5px solid var(--color-border-secondary,#d1d5db);color:var(--color-text-primary,#111)}
.ci-btn-full{width:100%;justify-content:center}

/* ── Contact card ─────────────────────────────── */
.ci-avatar{width:44px;height:44px;border-radius:50%;background:var(--color-background-info,#dbeafe);display:flex;align-items:center;justify-content:center;font-weight:500;font-size:15px;color:var(--color-text-info,#1d4ed8);flex-shrink:0}
.ci-contact-name{font-size:15px;font-weight:500}
.ci-contact-email{font-size:13px;color:var(--color-text-info,#2563eb);text-decoration:none;display:block}
.ci-contact-email:hover{text-decoration:underline}
.ci-field-label{font-size:11px;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-tertiary,#9ca3af);margin-bottom:3px}
.ci-field-value{font-size:13px}
.ci-divider{border:none;border-top:.5px solid var(--color-border-tertiary,#e5e7eb);margin:1rem 0}

/* ── Reply form ───────────────────────────────── */
.ci-reply-form{display:none;margin-top:.75rem}
.ci-reply-form.open{display:block}
.ci-form-row{margin-bottom:.75rem}
.ci-form-label{font-size:11px;text-transform:uppercase;letter-spacing:.06em;color:var(--color-text-tertiary,#9ca3af);margin-bottom:4px;display:block}
.ci-form-input{width:100%;padding:7px 10px;border:.5px solid var(--color-border-secondary,#d1d5db);border-radius:8px;background:var(--color-background-primary,#fff);color:var(--color-text-primary,#111);font-size:13px;font-family:inherit}
.ci-form-input:focus{outline:none;box-shadow:0 0 0 2px rgba(59,130,246,.35)}
.ci-form-input[readonly]{background:var(--color-background-secondary,#f9fafb);color:var(--color-text-secondary,#6b7280)}
.ci-form-row textarea.ci-form-input{resize:vertical;line-height:1.5}

/* ── Email history ────────────────────────────── */
.ci-history-list{display:flex;flex-direction:column;gap:.5rem;max-height:380px;overflow-y:auto}
.ci-history-item{border-left:2px solid var(--color-border-tertiary,#e5e7eb);padding:.5rem .75rem;border-radius:0 8px 8px 0;background:var(--color-background-secondary,#f9fafb)}
.ci-history-item.ci-staff{border-left-color:#3b82f6}
.ci-history-item.ci-customer{border-left-color:#22c55e}
.ci-history-dir{font-size:11px;font-weight:500;display:inline-flex;align-items:center;gap:3px}
.ci-history-dir.ci-dir-staff{color:#3b82f6}
.ci-history-dir.ci-dir-customer{color:#22c55e}
.ci-history-subj{font-size:13px;font-weight:500;margin:.2rem 0}
.ci-history-preview{font-size:12px;color:var(--color-text-secondary,#6b7280);line-height:1.5}
.ci-history-time{font-size:11px;color:var(--color-text-tertiary,#9ca3af);margin-top:.25rem;display:flex;align-items:center;gap:3px}
.ci-history-empty{font-size:13px;color:var(--color-text-secondary,#6b7280)}

/* ── Metadata grid ────────────────────────────── */
.ci-meta-grid{display:grid;grid-template-columns:auto 1fr;gap:.45rem 1rem;font-size:12px;align-items:start}
.ci-meta-key{color:var(--color-text-tertiary,#9ca3af);white-space:nowrap}
.ci-meta-val{color:var(--color-text-secondary,#4b5563);word-break:break-all;font-family:var(--font-mono,monospace)}

/* ── Danger zone ──────────────────────────────── */
.ci-danger-card{border-color:var(--color-border-danger,#fca5a5) !important}
.ci-danger-title{color:var(--color-text-danger,#b91c1c)}
.ci-danger-hint{font-size:11px;color:var(--color-text-tertiary,#9ca3af);text-align:center;margin-top:.4rem}

/* ── Toast ────────────────────────────────────── */
.ci-toast-wrap{position:fixed;top:1rem;right:1rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;pointer-events:none}
.ci-toast{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;font-size:13px;font-weight:500;pointer-events:all;opacity:0;transform:translateX(20px);transition:opacity .2s,transform .2s;background:var(--color-background-primary,#fff);border:.5px solid var(--color-border-tertiary,#e5e7eb);color:var(--color-text-primary,#111);min-width:220px;max-width:340px;box-shadow:0 4px 16px rgba(0,0,0,.1)}
.ci-toast.ci-toast-show{opacity:1;transform:translateX(0)}
.ci-toast i{font-size:15px}
.ci-toast-success i{color:#22c55e}
.ci-toast-error   i{color:#ef4444}
.ci-toast-info    i{color:#3b82f6}
.ci-toast-warning i{color:#f59e0b}
.ci-toast-close{background:none;border:none;cursor:pointer;color:var(--color-text-secondary,#6b7280);font-size:16px;line-height:1;padding:0 2px;margin-left:auto}
</style>

<!-- PAGE CONTENT -->
<div class="ci-page">

  <!-- Topbar -->
  <div class="ci-card ci-topbar" style="margin-bottom:1rem">
    <div>
      <nav class="ci-breadcrumb" aria-label="Breadcrumb">
        <a href="./dashboard"><i class="fas fa-house" aria-hidden="true"></i> Dashboard</a>
        <span class="sep" aria-hidden="true">›</span>
        <a href="./contact-inquiries"><i class="fas fa-envelope" aria-hidden="true"></i> Contact Inquiries</a>
        <span class="sep" aria-hidden="true">›</span>
        <span aria-current="page">#<?php echo (int)$inquiry['inquiry_id']; ?></span>
      </nav>
      <h1 class="ci-page-title">Inquiry #<?php echo (int)$inquiry['inquiry_id']; ?></h1>
      <p class="ci-meta-line">
        <i class="fas fa-calendar" aria-hidden="true"></i>
        Received <?php echo date('F j, Y g:i A', strtotime($inquiry['created_at'])); ?>
      </p>
    </div>
    <a href="../contact-inquiries" class="ci-back-btn">
      <i class="fas fa-arrow-left" aria-hidden="true"></i> Back to inquiries
    </a>
  </div>

  <div class="ci-layout">

    <!-- ── LEFT COLUMN ── -->
    <div>

      <!-- Status card -->
      <div class="ci-card">
        <p class="ci-card-title">Status</p>
        <div class="ci-status-row">
          <span class="ci-badge <?php echo $currentStatusClass; ?>" id="statusBadge">
            <i class="fas <?php echo $currentStatusIcon; ?>" style="font-size:12px" aria-hidden="true"></i>
            <?php echo $currentStatusLabel; ?>
          </span>
          <select class="ci-status-select" id="statusSelect" onchange="ciUpdateStatus()">
            <option value="new"      <?php echo $currentStatus === 'new'      ? 'selected' : ''; ?>>Mark as New</option>
            <option value="read"     <?php echo $currentStatus === 'read'     ? 'selected' : ''; ?>>Mark as Read</option>
            <option value="replied"  <?php echo $currentStatus === 'replied'  ? 'selected' : ''; ?>>Mark as Replied</option>
            <option value="archived" <?php echo $currentStatus === 'archived' ? 'selected' : ''; ?>>Archive</option>
          </select>
          <span class="ci-status-hint">Change status →</span>
        </div>
        <div class="ci-chips">
          <span><i class="fas fa-clock" aria-hidden="true"></i>
            <?php
              $diff = time() - strtotime($inquiry['created_at']);
              if ($diff < 3600)      echo round($diff/60).' min ago';
              elseif ($diff < 86400) echo round($diff/3600).' hr ago';
              else                   echo round($diff/86400).' day(s) ago';
            ?>
          </span>
          <?php if (!empty($inquiry['ip_address'])): ?>
          <span><i class="fas fa-location-dot" aria-hidden="true"></i> <?php echo htmlspecialchars($inquiry['ip_address']); ?></span>
          <?php endif; ?>
          <?php if (!empty($inquiry['user_agent'])): ?>
          <span><i class="fas fa-desktop" aria-hidden="true"></i>
            <?php echo htmlspecialchars(substr($inquiry['user_agent'], 0, 40)); ?>…
          </span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Message card -->
      <div class="ci-card">
        <p class="ci-card-title">Message</p>
        <div class="ci-msg-box">
          <p class="ci-msg-subject"><?php echo htmlspecialchars($inquiry['subject'] ?? 'No Subject'); ?></p>
          <p class="ci-msg-body"><?php echo htmlspecialchars($inquiry['message']); ?></p>
        </div>
      </div>

      <!-- Staff notes -->
      <div class="ci-card ci-card-notes">
        <div class="ci-notes-label">
          <i class="fas fa-note-sticky" aria-hidden="true"></i>
          Staff notes
          <small>(internal only)</small>
        </div>
        <textarea id="notesField" class="ci-form-input" rows="4"
          placeholder="Add internal notes about this inquiry…"><?php echo htmlspecialchars($inquiry['notes'] ?? ''); ?></textarea>
        <div class="ci-notes-footer">
          <button class="ci-btn ci-btn-primary" onclick="ciUpdateNotes(this)">
            <i class="fas fa-floppy-disk" aria-hidden="true"></i> Save notes
          </button>
          <span class="ci-saved-at" id="notesSavedAt"></span>
        </div>
      </div>

    </div><!-- /left -->

    <!-- ── RIGHT COLUMN ── -->
    <div>

      <!-- Contact card -->
      <div class="ci-card">
        <p class="ci-card-title">Contact</p>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:1rem">
          <?php if (!empty($inquiry['profile_photo_url'])): ?>
          <img src="<?php echo htmlspecialchars($inquiry['profile_photo_url']); ?>"
               alt="<?php echo htmlspecialchars($inquiry['full_name']); ?>"
               class="ci-avatar" style="object-fit:cover;padding:0">
          <?php else: ?>
          <div class="ci-avatar" aria-hidden="true"><?php echo htmlspecialchars($avatarInitials ?: '?'); ?></div>
          <?php endif; ?>
          <div>
            <p class="ci-contact-name"><?php echo htmlspecialchars($inquiry['full_name']); ?></p>
            <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>" class="ci-contact-email">
              <?php echo htmlspecialchars($inquiry['email']); ?>
            </a>
          </div>
        </div>

        <div style="display:grid;gap:.6rem;margin-bottom:.5rem">
          <?php if (!empty($inquiry['replied_at'])): ?>
          <div>
            <p class="ci-field-label">Replied on</p>
            <p class="ci-field-value"><?php echo date('M j, Y g:i A', strtotime($inquiry['replied_at'])); ?></p>
          </div>
          <?php endif; ?>
          <div>
            <p class="ci-field-label">Received</p>
            <p class="ci-field-value"><?php echo date('M j, Y g:i A', strtotime($inquiry['created_at'])); ?></p>
          </div>
        </div>

        <hr class="ci-divider">

        <button class="ci-btn ci-btn-primary ci-btn-full" id="replyToggleBtn" onclick="ciToggleReply()">
          <i class="fas fa-paper-plane" aria-hidden="true"></i> Reply via email
        </button>

        <!-- Reply form -->
        <div class="ci-reply-form" id="replyForm">
          <div class="ci-form-row" style="margin-top:.75rem">
            <label class="ci-form-label" for="replyTo">To</label>
            <input class="ci-form-input" type="email" id="replyTo"
              value="<?php echo htmlspecialchars($inquiry['email']); ?>" readonly>
          </div>
          <div class="ci-form-row">
            <label class="ci-form-label" for="replySubject">Subject</label>
            <input class="ci-form-input" type="text" id="replySubject"
              value="Re: <?php echo htmlspecialchars($inquiry['subject'] ?? 'Your Inquiry'); ?>">
          </div>
          <div class="ci-form-row">
            <label class="ci-form-label" for="replyBody">Message</label>
            <textarea class="ci-form-input" id="replyBody" rows="6"
              placeholder="Type your reply here…"></textarea>
          </div>
          <div style="display:flex;gap:.5rem">
            <button class="ci-btn ci-btn-success" style="flex:1;justify-content:center" id="sendReplyBtn" onclick="ciSendReply()">
              <span id="sendReplyText"><i class="fas fa-paper-plane" aria-hidden="true"></i> Send</span>
              <span id="sendReplySpinner" style="display:none"><i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Sending…</span>
            </button>
            <button class="ci-btn ci-btn-ghost" onclick="ciToggleReply()">Cancel</button>
          </div>
        </div>
      </div>

      <!-- Email history -->
      <div class="ci-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
          <p class="ci-card-title" style="margin-bottom:0">Email history</p>
          <button class="ci-btn ci-btn-ghost" style="padding:4px 10px;font-size:12px" onclick="ciLoadEmailHistory()" title="Refresh">
            <i class="fas fa-rotate" aria-hidden="true"></i> Refresh
          </button>
        </div>
        <div id="emailHistoryContainer" class="ci-history-list">
          <p class="ci-history-empty">
            <i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Loading…
          </p>
        </div>
      </div>

      <!-- Metadata -->
      <div class="ci-card">
        <p class="ci-card-title">Metadata</p>
        <div class="ci-meta-grid">
          <span class="ci-meta-key">IP</span>
          <span class="ci-meta-val"><?php echo htmlspecialchars($inquiry['ip_address'] ?? 'N/A'); ?></span>
          <span class="ci-meta-key">User agent</span>
          <span class="ci-meta-val" title="<?php echo htmlspecialchars($inquiry['user_agent'] ?? ''); ?>" style="font-family:inherit;word-break:break-word">
            <?php
              $ua = $inquiry['user_agent'] ?? 'N/A';
              $browser = 'Unknown';
              $os = 'Unknown';
              if (preg_match('/(Chrome|Firefox|Safari|Edge|Opera|MSIE|Trident)[\/ ](\d+)/', $ua, $bm)) {
                $browser = $bm[1] . ' ' . $bm[2];
              }
              if (preg_match('/\(([^)]+)\)/', $ua, $om)) {
                $osParts = explode(';', $om[1]);
                $os = trim($osParts[0] ?? 'Unknown');
              }
              echo htmlspecialchars($browser . ' · ' . $os);
            ?>
          </span>
        </div>
      </div>

      <!-- Danger zone -->
      <div class="ci-card ci-danger-card">
        <p class="ci-card-title ci-danger-title">Danger zone</p>
        <button class="ci-btn ci-btn-danger ci-btn-full" onclick="ciDeleteInquiry()">
          <i class="fas fa-trash" aria-hidden="true"></i> Delete inquiry
        </button>
        <p class="ci-danger-hint">This action cannot be undone.</p>
      </div>

    </div><!-- /right -->
  </div><!-- /layout -->
</div><!-- /page -->

<!-- Toast container -->
<div class="ci-toast-wrap" id="ciToastWrap" aria-live="polite"></div>

<script>
(function () {
  /* ── Constants ── */
  const INQUIRY_ID   = <?php echo (int)$inquiry['inquiry_id']; ?>;
  const INQUIRY_STATUS = '<?php echo addslashes($currentStatus); ?>';
  const CSRF_TOKEN   = '<?php echo $csrfToken; ?>';

  const STATUS_MAP = {
    new:      { cls: 'badge-new',      label: 'New',      icon: 'fa-circle-dot' },
    read:     { cls: 'badge-read',     label: 'Read',     icon: 'fa-eye' },
    replied:  { cls: 'badge-replied',  label: 'Replied',  icon: 'fa-check' },
    archived: { cls: 'badge-archived', label: 'Archived', icon: 'fa-box-archive' },
  };

  /* ── Toast ── */
  window.ciShowToast = function (msg, type = 'success') {
    const icons = {
      success: 'fa-circle-check',
      error:   'fa-circle-xmark',
      info:    'fa-circle-info',
      warning: 'fa-triangle-exclamation',
    };
    const wrap  = document.getElementById('ciToastWrap');
    const toast = document.createElement('div');
    toast.className = `ci-toast ci-toast-${type}`;
    toast.innerHTML = `
      <i class="fas ${icons[type]}" aria-hidden="true"></i>
      <span style="flex:1">${msg}</span>
      <button class="ci-toast-close" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
    `;
    wrap.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('ci-toast-show'));
    setTimeout(() => {
      toast.classList.remove('ci-toast-show');
      setTimeout(() => toast.remove(), 250);
    }, 4500);
  };

  /* ── Update status ── */
  window.ciUpdateStatus = function () {
    const status = document.getElementById('statusSelect').value;
    const m      = STATUS_MAP[status] || STATUS_MAP.archived;
    const badge  = document.getElementById('statusBadge');

    fetch('../contact-inquiries/updateStatus', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${INQUIRY_ID}&status=${status}`,
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          badge.className = `ci-badge ${m.cls}`;
          badge.innerHTML = `<i class="fas ${m.icon}" style="font-size:12px" aria-hidden="true"></i> ${m.label}`;
          ciShowToast(`Status updated to <strong>${m.label}</strong>`, 'success');
        } else {
          ciShowToast(data.message || 'Failed to update status', 'error');
        }
      })
      .catch(() => ciShowToast('Network error — status not updated', 'error'));
  };

  /* ── Update notes ── */
  window.ciUpdateNotes = function (btn) {
    const notes = document.getElementById('notesField').value;
    const status = document.getElementById('statusSelect').value;

    btn.disabled   = true;
    btn.innerHTML  = '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Saving…';

    fetch('../contact-inquiries/updateStatus', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${INQUIRY_ID}&status=${status}&notes=${encodeURIComponent(notes)}`,
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          const now = new Date();
          document.getElementById('notesSavedAt').textContent =
            'Saved ' + now.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
          ciShowToast('Notes saved successfully', 'success');
        } else {
          ciShowToast(data.message || 'Failed to save notes', 'error');
        }
      })
      .catch(() => ciShowToast('Network error — notes not saved', 'error'))
      .finally(() => {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-floppy-disk" aria-hidden="true"></i> Save notes';
      });
  };

  /* ── Toggle reply form ── */
  window.ciToggleReply = function () {
    const form = document.getElementById('replyForm');
    const btn  = document.getElementById('replyToggleBtn');
    const isOpen = form.classList.toggle('open');
    btn.innerHTML = isOpen
      ? '<i class="fas fa-xmark" aria-hidden="true"></i> Close reply form'
      : '<i class="fas fa-paper-plane" aria-hidden="true"></i> Reply via email';
    btn.className = isOpen
      ? 'ci-btn ci-btn-ghost ci-btn-full'
      : 'ci-btn ci-btn-primary ci-btn-full';
  };

  /* ── Send reply ── */
  window.ciSendReply = async function () {
    const btn     = document.getElementById('sendReplyBtn');
    const btnText = document.getElementById('sendReplyText');
    const spinner = document.getElementById('sendReplySpinner');
    const to      = document.getElementById('replyTo').value;
    const subject = document.getElementById('replySubject').value.trim();
    const message = document.getElementById('replyBody').value.trim();

    if (!subject) {
      ciShowToast('Please enter a subject before sending.', 'warning');
      return;
    }
    if (!message) {
      ciShowToast('Please enter a message before sending.', 'warning');
      return;
    }

    btn.disabled         = true;
    btnText.style.display = 'none';
    spinner.style.display = 'inline';

    try {
      const res  = await fetch('../api/send-email-reply.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': CSRF_TOKEN,
        },
        body: JSON.stringify({
          inquiry_id:      INQUIRY_ID,
          recipient_email: to,
          recipient_name:  '<?php echo addslashes(htmlspecialchars($inquiry['full_name'], ENT_QUOTES)); ?>',
          subject,
          message,
        }),
      });
      const data = await res.json();

      if (data.success) {
        ciShowToast('Reply sent successfully via email!', 'success');
        document.getElementById('replyBody').value = '';
        document.getElementById('statusSelect').value = 'replied';
        ciUpdateStatus();
        ciLoadEmailHistory();
        const form = document.getElementById('replyForm');
        if (form.classList.contains('open')) setTimeout(() => ciToggleReply(), 1200);
      } else {
        ciShowToast(data.message || 'Failed to send email.', 'error');
      }
    } catch (err) {
      console.error(err);
      ciShowToast('Network error. Please try again.', 'error');
    } finally {
      btn.disabled          = false;
      btnText.style.display = 'inline';
      spinner.style.display = 'none';
    }
  };

  /* ── Load email history ── */
  window.ciLoadEmailHistory = async function () {
    const container = document.getElementById('emailHistoryContainer');
    container.innerHTML = '<p class="ci-history-empty"><i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Loading…</p>';

    try {
      const res  = await fetch(`../api/contact_inquiries.php?action=emailHistory&inquiry_id=${INQUIRY_ID}`);
      const data = await res.json();

      if (data.success && data.emails.length > 0) {
        container.innerHTML = data.emails.map(email => {
          const isInbound   = email.direction === 'inbound';
          const itemClass   = isInbound ? 'ci-customer' : 'ci-staff';
          const dirClass    = isInbound ? 'ci-dir-customer' : 'ci-dir-staff';
          const dirIcon     = isInbound ? 'fa-arrow-down' : 'fa-arrow-up';
          const dirLabel    = isInbound ? 'Customer reply' : 'Staff reply';
          const byLine      = isInbound
            ? (email.sender_name || email.sender_email || 'Customer')
            : (email.replied_by_name || 'Staff');
          const sentDate    = new Date(email.sent_at).toLocaleString([], {
            month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit'
          });

          // Strip quoted reply text
          let body = (email.message_body || '').replace(/\r\n/g, '\n');
          body = body.replace(/\nOn .{10,300}wrote:\s*$/si, '');
          body = body.replace(/\nOn .{10,300}wrote:\n[\s\S]*/i, '');
          body = body.split('\n').filter(l => !l.trimStart().startsWith('>')).join('\n').trim();

          const statusBadge = isInbound ? ''
            : email.status === 'sent'
              ? '<span style="background:var(--color-background-success,#dcfce7);color:var(--color-text-success,#15803d);font-size:11px;padding:2px 7px;border-radius:99px">sent</span>'
              : '<span style="background:var(--color-background-danger,#fee2e2);color:var(--color-text-danger,#b91c1c);font-size:11px;padding:2px 7px;border-radius:99px">failed</span>';

          return `
            <div class="ci-history-item ${itemClass}">
              <div style="display:flex;justify-content:space-between;align-items:center;gap:.5rem;flex-wrap:wrap">
                <span class="ci-history-dir ${dirClass}">
                  <i class="fas ${dirIcon}" style="font-size:11px" aria-hidden="true"></i> ${dirLabel}
                </span>
                ${statusBadge}
              </div>
              <p class="ci-history-subj">${escHtml(email.subject)}</p>
              ${body ? `<p class="ci-history-preview">${escHtml(body.substring(0, 120))}${body.length > 120 ? '…' : ''}</p>` : ''}
              <p class="ci-history-time">
                <i class="fas fa-clock" aria-hidden="true"></i> ${sentDate} · ${escHtml(byLine)}
              </p>
            </div>
          `;
        }).join('');
      } else {
        container.innerHTML = '<p class="ci-history-empty">No email history yet.</p>';
      }
    } catch (err) {
      console.error(err);
      container.innerHTML = '<p class="ci-history-empty">Failed to load email history.</p>';
    }
  };

  /* ── Delete inquiry ── */
  window.ciDeleteInquiry = function () {
    if (!confirm('Delete this inquiry permanently? This action cannot be undone.')) return;

    fetch('../contact-inquiries/delete', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${INQUIRY_ID}`,
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          ciShowToast('Inquiry deleted — redirecting…', 'info');
          setTimeout(() => { window.location.href = './contact-inquiries'; }, 1400);
        } else {
          ciShowToast(data.message || 'Failed to delete inquiry', 'error');
        }
      })
      .catch(() => ciShowToast('Network error — inquiry not deleted', 'error'));
  };

  /* ── Helpers ── */
  function escHtml(str) {
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
  }

  /* ── Init ── */
  document.addEventListener('DOMContentLoaded', ciLoadEmailHistory);
})();
</script>