<?php
$userId = $_SESSION['user_id'] ?? null;

$payments = [];
try {
    $stmt = $pdo->prepare("
        SELECT p.*, r.check_in_date, r.check_out_date
        FROM payments p
        JOIN reservations r ON p.reservation_id = r.reservation_id
        WHERE r.customer_user_id = ?
        ORDER BY p.payment_date DESC
    ");
    $stmt->execute([$userId]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Table may not exist yet
}
?>

<div class="flex flex-wrap -mx-3 mb-6">
    <div class="w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border p-6">
            <h4 class="text-slate-700 dark:text-white">My Payments</h4>
            <p class="text-sm text-slate-400 dark:text-white/60">Your payment history and outstanding balances.</p>
        </div>
    </div>
</div>

<div class="flex flex-wrap -mx-3">
    <div class="w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="flex-auto p-6">
                <?php if (empty($payments)): ?>
                    <div class="text-center py-12 text-slate-400 dark:text-white/60">
                        <i class="ni ni-credit-card text-4xl mb-4 block"></i>
                        <p>No payment records found.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b dark:border-white/20">
                                    <th class="py-3 px-4 text-xs uppercase text-slate-400 dark:text-white/60">Date</th>
                                    <th class="py-3 px-4 text-xs uppercase text-slate-400 dark:text-white/60">Amount</th>
                                    <th class="py-3 px-4 text-xs uppercase text-slate-400 dark:text-white/60">Method</th>
                                    <th class="py-3 px-4 text-xs uppercase text-slate-400 dark:text-white/60">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payments as $p): ?>
                                <tr class="border-b dark:border-white/10">
                                    <td class="py-3 px-4 dark:text-white"><?php echo htmlspecialchars($p['payment_date'] ?? '-'); ?></td>
                                    <td class="py-3 px-4 dark:text-white">&#8369; <?php echo number_format((float)($p['amount'] ?? 0), 2); ?></td>
                                    <td class="py-3 px-4 dark:text-white"><?php echo htmlspecialchars($p['payment_method'] ?? '-'); ?></td>
                                    <td class="py-3 px-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            <?php echo $p['payment_status'] === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($p['payment_status'] === 'failed' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700'); ?>">
                                            <?php echo ucfirst(htmlspecialchars($p['payment_status'] ?? 'unknown')); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
