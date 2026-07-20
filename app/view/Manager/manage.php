<?php
$pageTitle = 'Manage Fleet';
$activeNav = 'manage';
require __DIR__ . '/../partials/header.php';
$csrfToken = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8');
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Fleet Management</h2>
    <p class="text-slate-500 text-sm mt-1">Create driver and officer accounts, register vehicles, and configure route benchmarks.</p>
</div>

<?php if (!empty($_SESSION['success'])): ?>
<div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
    <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
</div>
<?php endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
<div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
    <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-1">Add team member</h3>
        <p class="text-xs text-slate-500 mb-5">Create an officer or driver login.</p>
        <form action="<?= BASE_URL ?>/manage/user" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="name">Full name</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="name" name="name" maxlength="100" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="email">Email</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="email" name="email" type="email" maxlength="150" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="password">Temporary password</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="password" name="password" type="password" minlength="8" maxlength="72" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="password_confirmation">Confirm password</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="password_confirmation" name="password_confirmation" type="password" minlength="8" maxlength="72" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="role">Role</label>
                <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="role" name="role" required>
                    <option value="driver">Driver</option>
                    <option value="officer">Officer</option>
                </select>
            </div>
            <button class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700" type="submit">Create account</button>
        </form>
    </section>

    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-1">Add vehicle</h3>
        <p class="text-xs text-slate-500 mb-5">Register a vehicle for trip recording.</p>
        <form action="<?= BASE_URL ?>/manage/vehicle" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="plate_number">Plate number</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm uppercase" id="plate_number" name="plate_number" maxlength="20" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="model">Make and model</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="model" name="model" maxlength="100" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="current_odometer">Current odometer (km)</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="current_odometer" name="current_odometer" type="number" min="0" max="99999999.99" step="0.01" required>
            </div>
            <button class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700" type="submit">Add vehicle</button>
        </form>
    </section>

    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-1">Add route</h3>
        <p class="text-xs text-slate-500 mb-5">Set the distance and fuel benchmark.</p>
        <form action="<?= BASE_URL ?>/manage/route" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="route_name">Route name</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="route_name" name="route_name" maxlength="150" placeholder="Lagos to Ibadan" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="est_distance_km">Estimated distance (km)</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="est_distance_km" name="est_distance_km" type="number" min="0.01" max="999999.99" step="0.01" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="expected_fuel_liters">Expected fuel (litres)</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="expected_fuel_liters" name="expected_fuel_liters" type="number" min="0.01" max="999999.99" step="0.01" required>
            </div>
            <button class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700" type="submit">Add route</button>
        </form>
    </section>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-700 text-sm">Team (<?= count($users) ?>)</h3></div>
        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="text-left px-4 py-3">Name</th><th class="text-left px-4 py-3">Role</th></tr></thead><tbody class="divide-y divide-slate-100">
        <?php foreach ($users as $user): ?><tr><td class="px-4 py-3"><p class="font-medium text-slate-800"><?= htmlspecialchars($user['name']) ?></p><p class="text-xs text-slate-400"><?= htmlspecialchars($user['email']) ?></p></td><td class="px-4 py-3 capitalize text-slate-600"><?= htmlspecialchars($user['role']) ?></td></tr><?php endforeach; ?>
        <?php if (!$users): ?><tr><td colspan="2" class="px-4 py-8 text-center text-slate-400">No officers or drivers yet.</td></tr><?php endif; ?>
        </tbody></table></div>
    </section>

    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-700 text-sm">Vehicles (<?= count($vehicles) ?>)</h3></div>
        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="text-left px-4 py-3">Vehicle</th><th class="text-right px-4 py-3">Odometer</th></tr></thead><tbody class="divide-y divide-slate-100">
        <?php foreach ($vehicles as $vehicle): ?><tr><td class="px-4 py-3"><p class="font-medium text-slate-800"><?= htmlspecialchars($vehicle['plate_number']) ?></p><p class="text-xs text-slate-400"><?= htmlspecialchars($vehicle['model']) ?></p></td><td class="px-4 py-3 text-right text-slate-600 whitespace-nowrap"><?= number_format((float) $vehicle['current_odometer'], 2) ?> km</td></tr><?php endforeach; ?>
        <?php if (!$vehicles): ?><tr><td colspan="2" class="px-4 py-8 text-center text-slate-400">No vehicles yet.</td></tr><?php endif; ?>
        </tbody></table></div>
    </section>

    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-700 text-sm">Routes (<?= count($routes) ?>)</h3></div>
        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="text-left px-4 py-3">Route</th><th class="text-right px-4 py-3">Benchmark</th></tr></thead><tbody class="divide-y divide-slate-100">
        <?php foreach ($routes as $route): ?><tr><td class="px-4 py-3"><p class="font-medium text-slate-800"><?= htmlspecialchars($route['route_name']) ?></p><p class="text-xs text-slate-400"><?= number_format((float) $route['est_distance_km'], 2) ?> km</p></td><td class="px-4 py-3 text-right text-slate-600 whitespace-nowrap"><?= number_format((float) $route['expected_fuel_liters'], 2) ?> L</td></tr><?php endforeach; ?>
        <?php if (!$routes): ?><tr><td colspan="2" class="px-4 py-8 text-center text-slate-400">No routes yet.</td></tr><?php endif; ?>
        </tbody></table></div>
    </section>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>