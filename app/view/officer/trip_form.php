<?php
$pageTitle = 'New Trip Entry';
$activeNav = 'trip';
require __DIR__ . '/../partials/header.php';
?>

<!-- Page heading -->
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Record New Trip</h2>
    <p class="text-slate-500 text-sm mt-1">Fill in all trip details. Fuel consumption is automatically audited against the route benchmark.</p>
</div>

<!-- ── Flash messages ──────────────────────────────────────────── -->
<?php if (!empty($_SESSION['success'])): ?>
<div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm mb-6">
    <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
    </svg>
    <span><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></span>
</div>
<?php endif; ?>

<?php if (!empty($_SESSION['warning'])): ?>
<div class="flex items-start gap-3 bg-amber-50 border border-amber-300 text-amber-800 px-4 py-3 rounded-xl text-sm mb-6">
    <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
    </svg>
    <span><?= htmlspecialchars($_SESSION['warning']); unset($_SESSION['warning']); ?></span>
</div>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
<div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-6">
    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
    </svg>
    <span><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
</div>
<?php endif; ?>

<!-- ── Trip form card ──────────────────────────────────────────── -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <h3 class="font-semibold text-slate-700 text-sm">Trip Details</h3>
    </div>

    <form action="<?= BASE_URL ?>/save-trip" method="POST" class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Vehicle -->
            <div>
                <label for="vehicle_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Vehicle <span class="text-red-500">*</span>
                </label>
                <select id="vehicle_id" name="vehicle_id" required
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                    <option value="">— Select vehicle —</option>
                    <?php foreach ($vehicles as $v): ?>
                    <option value="<?= $v['id'] ?>">
                        <?= htmlspecialchars($v['plate_number']) ?> — <?= htmlspecialchars($v['model']) ?>
                        (ODO: <?= number_format($v['current_odometer'], 1) ?> km)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Driver -->
            <div>
                <label for="driver_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Driver <span class="text-red-500">*</span>
                </label>
                <select id="driver_id" name="driver_id" required
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                    <option value="">— Select driver —</option>
                    <?php foreach ($drivers as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Route -->
            <div class="md:col-span-2">
                <label for="route_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Route <span class="text-red-500">*</span>
                </label>
                <select id="route_id" name="route_id" required
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                    <option value="">— Select route —</option>
                    <?php foreach ($routes as $r): ?>
                    <option value="<?= $r['id'] ?>">
                        <?= htmlspecialchars($r['route_name']) ?>
                        (<?= $r['est_distance_km'] ?> km — benchmark: <?= $r['expected_fuel_liters'] ?>L)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Start Odometer -->
            <div>
                <label for="start_odometer" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Start Odometer (km) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="start_odometer" name="start_odometer"
                       min="0" step="0.1" required
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800
                              placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                              focus:border-transparent"
                       placeholder="e.g. 45200.0">
            </div>

            <!-- End Odometer -->
            <div>
                <label for="end_odometer" class="block text-sm font-medium text-slate-700 mb-1.5">
                    End Odometer (km) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="end_odometer" name="end_odometer"
                       min="0" step="0.1" required
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800
                              placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                              focus:border-transparent"
                       placeholder="e.g. 45328.5">
            </div>

            <!-- Fuel Consumed -->
            <div class="md:col-span-2">
                <label for="fuel_consumed" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Fuel Consumed (litres) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" id="fuel_consumed" name="fuel_consumed"
                           min="0.1" step="0.01" required
                           class="w-full px-3 py-2.5 pr-16 border border-slate-200 rounded-lg text-sm text-slate-800
                                  placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                                  focus:border-transparent"
                           placeholder="e.g. 18.50">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium pointer-events-none">
                        litres
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">Values above the route benchmark will trigger an audit alert.</p>
            </div>

        </div><!-- /grid -->

        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-3">
            <button type="reset"
                    class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100
                           hover:bg-slate-200 rounded-lg transition-colors">
                Reset Form
            </button>
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 text-sm font-semibold text-white bg-blue-600
                           hover:bg-blue-700 active:bg-blue-800 rounded-lg shadow-sm transition-colors">
                Save Trip &amp; Run Audit
            </button>
        </div>

    </form>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
