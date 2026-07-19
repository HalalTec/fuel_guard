<?php
$pageTitle = 'My Trips';
$activeNav = 'my-trips';
require __DIR__ . '/../partials/header.php';

$totalTrips   = count($trips);
$flaggedTrips = count(array_filter($trips, fn($t) => (bool) $t['alert_flag']));
?>

<!-- Page heading -->
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">My Trips</h2>
    <p class="text-slate-500 text-sm mt-1">All trips recorded under your account, with fuel audit results.</p>
</div>

<!-- ── Stat cards ───────────────────────────────────────────────── -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="bg-blue-100 rounded-lg p-2.5">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800"><?= $totalTrips ?></p>
            <p class="text-xs text-slate-500 font-medium">Total Trips</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-green-200 p-5 flex items-center gap-4">
        <div class="bg-green-100 rounded-lg p-2.5">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-green-600"><?= $totalTrips - $flaggedTrips ?></p>
            <p class="text-xs text-slate-500 font-medium">Normal</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-red-200 p-5 flex items-center gap-4">
        <div class="bg-red-100 rounded-lg p-2.5">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-red-600"><?= $flaggedTrips ?></p>
            <p class="text-xs text-slate-500 font-medium">Flagged</p>
        </div>
    </div>

</div>

<!-- ── Trips table ──────────────────────────────────────────────── -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 10h18M3 14h18M3 6h18M3 18h18"/>
            </svg>
            <h3 class="font-semibold text-slate-700 text-sm">Trip History</h3>
        </div>
        <span class="text-xs text-slate-400"><?= $totalTrips ?> record<?= $totalTrips !== 1 ? 's' : '' ?></span>
    </div>

    <?php if (empty($trips)): ?>
    <div class="px-6 py-16 text-center text-slate-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p class="text-sm font-medium">No trips recorded yet.</p>
        <p class="text-xs mt-1">Trips assigned to you by an officer will appear here.</p>
    </div>
    <?php else: ?>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Vehicle</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Route</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Consumed</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Benchmark</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Variance</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <?php foreach ($trips as $trip):
                $isFlagged = (bool) $trip['alert_flag'];
                $variance  = (float) $trip['fuel_variance'];
                $rowClass  = $isFlagged ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-slate-50';
            ?>
                <tr class="<?= $rowClass ?> transition-colors">
                    <td class="px-4 py-3 text-slate-400 font-mono text-xs"><?= $trip['id'] ?></td>

                    <td class="px-4 py-3">
                        <p class="font-semibold text-slate-800"><?= htmlspecialchars($trip['plate_number']) ?></p>
                        <p class="text-xs text-slate-400"><?= htmlspecialchars($trip['vehicle_model']) ?></p>
                    </td>

                    <td class="px-4 py-3">
                        <p class="text-slate-700"><?= htmlspecialchars($trip['route_name']) ?></p>
                        <p class="text-xs text-slate-400"><?= number_format($trip['est_distance_km'], 1) ?> km</p>
                    </td>

                    <td class="px-4 py-3 text-right font-mono <?= $isFlagged ? 'text-red-700 font-semibold' : 'text-slate-700' ?>">
                        <?= number_format($trip['fuel_consumed'], 2) ?>L
                    </td>

                    <td class="px-4 py-3 text-right font-mono text-slate-500">
                        <?= number_format($trip['expected_fuel_liters'], 2) ?>L
                    </td>

                    <td class="px-4 py-3 text-right font-mono text-xs <?= $variance > 0 ? 'text-red-600 font-semibold' : 'text-green-600' ?>">
                        <?= $variance > 0 ? '+' : '' ?><?= number_format($variance, 2) ?>L
                    </td>

                    <td class="px-4 py-3 text-slate-500 text-xs whitespace-nowrap">
                        <?= date('d M Y', strtotime($trip['created_at'])) ?>
                        <br><span class="text-slate-400"><?= date('H:i', strtotime($trip['created_at'])) ?></span>
                    </td>

                    <td class="px-4 py-3">
                        <?php if ($isFlagged): ?>
                        <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 border border-red-200
                                     text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z"/>
                            </svg>
                            High Usage
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 border border-green-200
                                     text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            Normal
                        </span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
