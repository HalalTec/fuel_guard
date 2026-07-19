<?php
$pageTitle = 'Account Settings';
$activeNav = 'settings';
require __DIR__ . '/../partials/header.php';
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Account Settings</h2>
    <p class="text-slate-500 text-sm mt-1">View your account details and update your password.</p>
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

<?php if (!empty($_SESSION['error'])): ?>
<div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-6">
    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
    </svg>
    <span><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Account info (read-only) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h3 class="font-semibold text-slate-700 text-sm">Account Information</h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Full Name</p>
                <p class="text-slate-800 font-medium"><?= htmlspecialchars($user['name'] ?? '') ?></p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Email Address</p>
                <p class="text-slate-800"><?= htmlspecialchars($user['email'] ?? '') ?></p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Role</p>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                             bg-blue-100 text-blue-700 border border-blue-200 capitalize">
                    <?= htmlspecialchars($user['role'] ?? '') ?>
                </span>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Member Since</p>
                <p class="text-slate-600 text-sm"><?= date('d M Y', strtotime($user['created_at'] ?? 'now')) ?></p>
            </div>
        </div>
    </div>

    <!-- Change password -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
            <h3 class="font-semibold text-slate-700 text-sm">Change Password</h3>
        </div>
        <form action="<?= BASE_URL ?>/save-settings" method="POST" class="p-6 space-y-4">

            <div>
                <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Current Password <span class="text-red-500">*</span>
                </label>
                <input type="password" id="current_password" name="current_password" required
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter current password">
            </div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-slate-700 mb-1.5">
                    New Password <span class="text-red-500">*</span>
                </label>
                <input type="password" id="new_password" name="new_password" required minlength="8"
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="At least 8 characters">
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Confirm New Password <span class="text-red-500">*</span>
                </label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8"
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Repeat new password">
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="w-full px-6 py-2.5 text-sm font-semibold text-white bg-blue-600
                               hover:bg-blue-700 active:bg-blue-800 rounded-lg shadow-sm transition-colors">
                    Update Password
                </button>
            </div>

        </form>
    </div>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
