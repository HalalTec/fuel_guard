<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FuelGuard — Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-950 to-slate-800 flex items-center justify-center p-4">

<div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row">

    <!-- ── Left branding panel ─────────────────────────────────── -->
    <div class="md:w-5/12 bg-gradient-to-b from-blue-700 to-blue-950 p-10 flex flex-col justify-between text-white">
        <div>
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-10">
                <div class="bg-amber-400 rounded-xl p-2">
                    <svg class="w-7 h-7 text-blue-900" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 5.08V3a1 1 0 0 0-2 0v2.08A5.002 5.002 0 0 0 12 10a5 5 0 0 0 3 4.58V20a2 2 0 0 1-4 0v-2a4 4 0 0 0-8 0v1a1 1 0 0 0 2 0v-1a2 2 0 0 1 4 0v2a4 4 0 0 0 8 0v-5.42A5.002 5.002 0 0 0 17 5.08zM12 13a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold leading-none">FuelGuard</h1>
                    <p class="text-blue-300 text-xs">Fleet Fuel Audit System</p>
                </div>
            </div>

            <h2 class="text-3xl font-bold leading-tight mb-3">Monitor every<br>litre.</h2>
            <p class="text-blue-200 text-sm leading-relaxed">
                Route-based benchmarking that detects anomalies in real time and flags potential theft before it escalates.
            </p>
        </div>

        <!-- Feature list -->
        <ul class="mt-8 space-y-3">
            <?php foreach ([
                'Route-based fuel benchmarks',
                'Automatic theft flagging',
                'Odometer sync on every trip',
                'Manager audit dashboard',
            ] as $feature): ?>
            <li class="flex items-center gap-2 text-blue-100 text-sm">
                <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <?= $feature ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- ── Right login form ────────────────────────────────────── -->
    <div class="md:w-7/12 p-10 flex flex-col justify-center">
        <h3 class="text-2xl font-bold text-slate-800 mb-1">Welcome back</h3>
        <p class="text-slate-500 text-sm mb-7">Sign in to your FuelGuard account</p>

        <!-- Flash error -->
        <?php if (!empty($_SESSION['error'])): ?>
        <div class="flex items-start gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-5">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
            </svg>
            <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth" method="POST" novalidate>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email address</label>
                <input
                    type="email" id="email" name="email" required autocomplete="email"
                    class="w-full px-4 py-3 border border-slate-200 rounded-lg text-sm text-slate-800
                           placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                           focus:border-transparent transition"
                    placeholder="you@example.com">
            </div>

            <div class="mb-7">
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                <input
                    type="password" id="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 border border-slate-200 rounded-lg text-sm text-slate-800
                           placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                           focus:border-transparent transition"
                    placeholder="••••••••">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold
                           py-3 px-4 rounded-lg transition-colors duration-200 text-sm shadow-sm">
                Sign In
            </button>
        </form>

        <!-- Demo credentials hint -->
        <div class="mt-8 p-4 bg-slate-50 border border-slate-200 rounded-lg">
            <p class="text-xs font-semibold text-slate-600 mb-2">Demo credentials (password: <code class="bg-slate-200 px-1 rounded">password</code>)</p>
            <div class="grid grid-cols-2 gap-x-4 text-xs text-slate-500">
                <div>
                    <p class="font-medium text-slate-600">Manager</p>
                    <p>manager@fuelguard.com</p>
                </div>
                <div>
                    <p class="font-medium text-slate-600">Officer</p>
                    <p>officer@fuelguard.com</p>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
