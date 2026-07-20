<?php
// Expected variables from the including view:
//   $pageTitle  (string)  — browser tab title suffix
//   $activeNav  (string)  — 'dashboard' | 'trip'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FuelGuard <?= isset($pageTitle) ? '— ' . htmlspecialchars($pageTitle) : '' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-100">

<!-- Top navigation bar -->
<nav class="bg-slate-900 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Brand -->
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17 5.08V3a1 1 0 0 0-2 0v2.08A5.002 5.002 0 0 0 12 10a5 5 0 0 0 3 4.58V20a2 2 0 0 1-4 0v-2a4 4 0 0 0-8 0v1a1 1 0 0 0 2 0v-1a2 2 0 0 1 4 0v2a4 4 0 0 0 8 0v-5.42A5.002 5.002 0 0 0 17 5.08zM12 13a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                </svg>
                <span class="text-white font-bold text-lg tracking-tight">FuelGuard</span>
                <span class="hidden sm:block text-slate-400 text-xs font-medium uppercase tracking-widest">Fleet Audit</span>
            </div>

            <!-- Nav links -->
            <div class="flex items-center gap-1">
                <?php if (($_SESSION['user_role'] ?? '') === 'manager'): ?>
                <a href="<?= BASE_URL ?>/dashboard"
                   class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                          <?= (($activeNav ?? '') === 'dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?>">
                    Dashboard
                </a>
                <a href="<?= BASE_URL ?>/manage"
                   class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                          <?= (($activeNav ?? '') === 'manage') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?>">
                    Manage
                </a>
                <a href="<?= BASE_URL ?>/settings"
                   class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                          <?= (($activeNav ?? '') === 'settings') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?>">
                    Settings
                </a>
                <?php elseif (($_SESSION['user_role'] ?? '') === 'driver'): ?>
                <a href="<?= BASE_URL ?>/my-trips"
                   class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                          <?= (($activeNav ?? '') === 'my-trips') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?>">
                    My Trips
                </a>
                <a href="<?= BASE_URL ?>/settings"
                   class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                          <?= (($activeNav ?? '') === 'settings') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?>">
                    Settings
                </a>
                <?php else: ?>
                <a href="<?= BASE_URL ?>/trip"
                   class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                          <?= (($activeNav ?? '') === 'trip') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?>">
                    New Trip
                </a>
                <a href="<?= BASE_URL ?>/settings"
                   class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                          <?= (($activeNav ?? '') === 'settings') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' ?>">
                    Settings
                </a>
                <?php endif; ?>
            </div>

            <!-- User info + logout -->
            <div class="flex items-center gap-3">
                <div class="hidden sm:block text-right">
                    <p class="text-white text-sm font-medium"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></p>
                    <p class="text-slate-400 text-xs capitalize"><?= htmlspecialchars($_SESSION['user_role'] ?? '') ?></p>
                </div>
                <form action="<?= BASE_URL ?>/logout" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit"
                            class="flex items-center gap-1.5 text-slate-300 hover:text-red-400 text-sm transition-colors px-2 py-1 rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>

<!-- Page content wrapper -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
