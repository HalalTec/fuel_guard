<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FuelGuard — 404</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center">
    <div class="text-center">
        <p class="text-8xl font-bold text-slate-300">404</p>
        <h1 class="text-2xl font-semibold text-slate-700 mt-2">Page Not Found</h1>
        <p class="text-slate-500 mt-1 text-sm">The route you requested does not exist.</p>
        <a href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/login"
           class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
            Back to Login
        </a>
    </div>
</body>
</html>
