<?php
$userId = $_SESSION['user_id'] ?? null;

$reservations = [];
try {
    $stmt = $pdo->prepare("
        SELECT r.*, p.property_name
        FROM reservations r
        LEFT JOIN properties p ON r.property_id = p.property_id
        WHERE r.customer_user_id = ?
        ORDER BY r.check_in_date DESC
    ");
    $stmt->execute([$userId]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Table may not exist yet
}
?>

<div class="flex flex-wrap -mx-3 mb-6">
    <div class="w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border p-6">
            <h4 class="text-slate-700 dark:text-white">My Reservations</h4>
            <p class="text-sm text-slate-400 dark:text-white/60">Your booking history and upcoming reservations.</p>
        </div>
    </div>
</div>

<div class="flex flex-wrap -mx-3">
    <div class="w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="flex-auto p-6">
                <?php if (empty($reservations)): ?>
                    <div class="text-center py-12 text-slate-400 dark:text-white/60">
                        <i class="ni ni-calendar-grid-58 text-4xl mb-4 block"></i>
                        <p>No reservations found.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b dark:border-white/20">
                                    <th class="py-3 px-4 text-xs uppercase text-slate-400 dark:text-white/60">Property</th>
                                    <th class="py-3 px-4 text-xs uppercase text-slate-400 dark:text-white/60">Check In</th>
                                    <th class="py-3 px-4 text-xs uppercase text-slate-400 dark:text-white/60">Check Out</th>
                                    <th class="py-3 px-4 text-xs uppercase text-slate-400 dark:text-white/60">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations as $r): ?>
                                <tr class="border-b dark:border-white/10">
                                    <td class="py-3 px-4 dark:text-white"><?php echo htmlspecialchars($r['property_name'] ?? '-'); ?></td>
                                    <td class="py-3 px-4 dark:text-white"><?php echo htmlspecialchars($r['check_in_date'] ?? '-'); ?></td>
                                    <td class="py-3 px-4 dark:text-white"><?php echo htmlspecialchars($r['check_out_date'] ?? '-'); ?></td>
                                    <td class="py-3 px-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            <?php echo $r['reservation_status'] === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($r['reservation_status'] === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'); ?>">
                                            <?php echo ucfirst(htmlspecialchars($r['reservation_status'] ?? 'unknown')); ?>
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
