<?php
// base_url is passed from Controller
if (!isset($base_url) || empty($base_url)) {
    $script_dir = dirname($_SERVER['SCRIPT_NAME']);
    $base_url = rtrim($script_dir, '/') . '/';
}

$userId = $_SESSION['user_id'] ?? null;
$displayName = 'Customer';
try {
    $nameStmt = $pdo->prepare("SELECT first_name, last_name FROM user_profiles WHERE user_id = ?");
    $nameStmt->execute([$userId]);
    $nameRow = $nameStmt->fetch(PDO::FETCH_ASSOC);
    if ($nameRow) {
        $displayName = trim(($nameRow['first_name'] ?? '') . ' ' . ($nameRow['last_name'] ?? '')) ?: 'Customer';
    }
} catch (Exception $e) {}

$reservationCount = 0;
$pendingPayments = 0;
$completedStays = 0;

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE customer_user_id = ?");
    $stmt->execute([$userId]);
    $reservationCount = (int)$stmt->fetchColumn();

    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE customer_user_id = ? AND reservation_status = 'completed'");
    $stmt2->execute([$userId]);
    $completedStays = (int)$stmt2->fetchColumn();

    $stmt3 = $pdo->prepare("SELECT COUNT(*) FROM payments p JOIN reservations r ON p.reservation_id = r.reservation_id WHERE r.customer_user_id = ? AND p.payment_status = 'pending'");
    $stmt3->execute([$userId]);
    $pendingPayments = (int)$stmt3->fetchColumn();
} catch (Exception $e) {}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500&display=swap');

:root {
    --gold: #C9A96E;
    --gold-light: #E8D5B0;
    --gold-dark: #A07840;
    --ink: #1A1714;
    --ink-soft: #3D3530;
    --parchment: #FAF7F2;
    --parchment-dark: #F0EBE1;
    --sage: #6B8068;
    --rose-dust: #B07060;
    --card-bg: #FFFFFF;
    --border: rgba(201,169,110,0.2);
}

.dash-wrap {
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    padding: 0 0 2rem;
}

/* ── Hero welcome banner ── */
.dash-hero {
    position: relative;
    overflow: hidden;
    background: var(--ink);
    border-radius: 20px;
    padding: 3rem 3.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}

.dash-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 60% 80% at 90% 50%, rgba(201,169,110,0.18) 0%, transparent 70%),
        radial-gradient(ellipse 40% 60% at -10% 100%, rgba(201,169,110,0.1) 0%, transparent 60%);
    pointer-events: none;
}

.dash-hero-deco {
    position: absolute;
    right: 3rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 9rem;
    opacity: 0.04;
    font-family: 'Cormorant Garamond', serif;
    font-weight: 600;
    letter-spacing: -4px;
    color: var(--gold);
    user-select: none;
    pointer-events: none;
    line-height: 1;
}

.dash-hero-text {
    position: relative;
    z-index: 1;
}

.dash-hero-eyebrow {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.7rem;
    font-weight: 500;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 0.6rem;
}

.dash-hero-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.6rem;
    font-weight: 400;
    color: #fff;
    margin: 0 0 0.5rem;
    line-height: 1.1;
}

.dash-hero-sub {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.45);
    font-weight: 300;
    letter-spacing: 0.01em;
}

.dash-hero-date {
    position: relative;
    z-index: 1;
    text-align: right;
    flex-shrink: 0;
}

.dash-hero-date-day {
    font-family: 'Cormorant Garamond', serif;
    font-size: 3.5rem;
    font-weight: 300;
    color: var(--gold);
    line-height: 1;
}

.dash-hero-date-month {
    font-size: 0.7rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.4);
}

/* ── Stat cards ── */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.75rem;
}

@media (max-width: 900px) { .stat-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 580px) { .stat-grid { grid-template-columns: 1fr; } }

.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.75rem 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1.25rem;
    position: relative;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(26,23,20,0.08);
}

.stat-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    opacity: 0;
    transition: opacity 0.25s ease;
}

.stat-card:hover::after { opacity: 1; }

.stat-card--blue::after   { background: linear-gradient(90deg, #4F8EF7, #8B5CF6); }
.stat-card--green::after  { background: linear-gradient(90deg, #10B981, #34D399); }
.stat-card--amber::after  { background: linear-gradient(90deg, var(--gold-dark), var(--gold)); }

.stat-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 1.2rem;
}

.stat-icon--blue  { background: linear-gradient(135deg, #EFF6FF, #DBEAFE); color: #3B82F6; }
.stat-icon--green { background: linear-gradient(135deg, #ECFDF5, #D1FAE5); color: #059669; }
.stat-icon--amber { background: linear-gradient(135deg, #FFFBEB, #FEF3C7); color: var(--gold-dark); }

.stat-body { flex: 1; }

.stat-label {
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #9CA3AF;
    margin-bottom: 0.35rem;
}

.stat-value {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.8rem;
    font-weight: 500;
    color: var(--ink);
    line-height: 1;
}

/* ── Quick actions ── */
.actions-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem 2.25rem;
}

.actions-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.25rem;
    font-weight: 500;
    color: var(--ink);
    margin: 0 0 1.4rem;
    display: flex; align-items: center; gap: 0.6rem;
}

.actions-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

.actions-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.875rem;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 500;
    letter-spacing: 0.02em;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.action-btn:hover { transform: translateY(-2px); }

.action-btn--primary {
    background: var(--ink);
    color: #fff;
}
.action-btn--primary:hover { background: var(--ink-soft); box-shadow: 0 6px 20px rgba(26,23,20,0.25); }

.action-btn--gold {
    background: linear-gradient(135deg, var(--gold-dark), var(--gold));
    color: #fff;
}
.action-btn--gold:hover { box-shadow: 0 6px 20px rgba(160,120,64,0.35); }

.action-btn--outline {
    background: transparent;
    color: var(--ink-soft);
    border: 1px solid var(--border);
}
.action-btn--outline:hover { border-color: var(--gold); color: var(--gold-dark); background: rgba(201,169,110,0.04); }

.action-btn i { font-size: 0.95rem; }
</style>

<div class="dash-wrap">

    <!-- Hero -->
    <div class="dash-hero">
        <div class="dash-hero-text">
            <p class="dash-hero-eyebrow">Dashboard Overview</p>
            <h1 class="dash-hero-name">Welcome, <?php echo htmlspecialchars($displayName); ?></h1>
            <p class="dash-hero-sub">Here's a summary of your account activity.</p>
        </div>
        <div class="dash-hero-date">
            <div class="dash-hero-date-day"><?php echo date('d'); ?></div>
            <div class="dash-hero-date-month"><?php echo date('F Y'); ?></div>
        </div>
        <div class="dash-hero-deco"><?php echo date('Y'); ?></div>
    </div>

    <!-- Stats -->
    <div class="stat-grid">

        <div class="stat-card stat-card--blue">
            <div class="stat-icon stat-icon--blue">
                <i class="ni ni-calendar-grid-58"></i>
            </div>
            <div class="stat-body">
                <p class="stat-label">Total Reservations</p>
                <div class="stat-value"><?php echo $reservationCount; ?></div>
            </div>
        </div>

        <div class="stat-card stat-card--green">
            <div class="stat-icon stat-icon--green">
                <i class="ni ni-check-bold"></i>
            </div>
            <div class="stat-body">
                <p class="stat-label">Completed Stays</p>
                <div class="stat-value"><?php echo $completedStays; ?></div>
            </div>
        </div>

        <div class="stat-card stat-card--amber">
            <div class="stat-icon stat-icon--amber">
                <i class="ni ni-credit-card"></i>
            </div>
            <div class="stat-body">
                <p class="stat-label">Pending Payments</p>
                <div class="stat-value"><?php echo $pendingPayments; ?></div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="actions-card">
        <h2 class="actions-title">Quick Actions</h2>
        <div class="actions-grid">
            <a href="<?php echo $base_url; ?>customer/reservations" class="action-btn action-btn--primary">
                <i class="ni ni-calendar-grid-58"></i> View Reservations
            </a>
            <a href="<?php echo $base_url; ?>customer/payments" class="action-btn action-btn--gold">
                <i class="ni ni-credit-card"></i> View Payments
            </a>
            <a href="<?php echo $base_url; ?>customer/profile" class="action-btn action-btn--outline">
                <i class="ni ni-single-02"></i> Edit Profile
            </a>
        </div>
    </div>

</div>