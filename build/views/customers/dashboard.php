<?php
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


// Fetch summary counts for this customer
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
} catch (Exception $e) {
    // Tables may not exist yet; silently ignore
}
?>

<div class="flex flex-wrap -mx-3 mb-6">
    <div class="w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border p-6">
            <h4 class="text-slate-700 dark:text-white">Welcome back, <?php echo htmlspecialchars($displayName); ?>!</h4>
            <p class="text-sm text-slate-400 dark:text-white/60">Here's an overview of your account.</p>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="flex flex-wrap -mx-3 mb-6">

    <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 lg:w-1/3">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="mb-0 text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Reservations</p>
                    <h5 class="mb-0 font-bold dark:text-white"><?php echo $reservationCount; ?></h5>
                </div>
                <div class="flex items-center justify-center w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
                    <i class="ni ni-calendar-grid-58 text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 lg:w-1/3">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="mb-0 text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Completed Stays</p>
                    <h5 class="mb-0 font-bold dark:text-white"><?php echo $completedStays; ?></h5>
                </div>
                <div class="flex items-center justify-center w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
                    <i class="ni ni-check-bold text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 lg:w-1/3">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="mb-0 text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Pending Payments</p>
                    <h5 class="mb-0 font-bold dark:text-white"><?php echo $pendingPayments; ?></h5>
                </div>
                <div class="flex items-center justify-center w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-orange-500 to-yellow-400">
                    <i class="ni ni-credit-card text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Quick Links -->
<div class="flex flex-wrap -mx-3">
    <div class="w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border p-6">
            <h6 class="mb-4 dark:text-white">Quick Actions</h6>
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo $base_url; ?>customer/reservations" class="inline-block px-5 py-2.5 font-bold text-center text-white align-middle transition-all bg-gradient-to-tl from-blue-500 to-violet-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px text-sm shadow-md">
                    <i class="ni ni-calendar-grid-58 mr-2"></i> View Reservations
                </a>
                <a href="<?php echo $base_url; ?>customer/payments" class="inline-block px-5 py-2.5 font-bold text-center text-white align-middle transition-all bg-gradient-to-tl from-emerald-500 to-teal-400 border-0 rounded-lg cursor-pointer hover:-translate-y-px text-sm shadow-md">
                    <i class="ni ni-credit-card mr-2"></i> View Payments
                </a>
                <a href="<?php echo $base_url; ?>profile" class="inline-block px-5 py-2.5 font-bold text-center text-white align-middle transition-all bg-gradient-to-tl from-orange-500 to-yellow-400 border-0 rounded-lg cursor-pointer hover:-translate-y-px text-sm shadow-md">
                    <i class="ni ni-single-02 mr-2"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
