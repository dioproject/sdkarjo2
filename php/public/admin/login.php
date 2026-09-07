<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Login Admin';

// Middleware already handles redirect if logged in
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-6">
        <h1 class="text-xl font-bold mb-4">Login Admin</h1>

        <?php if (isset($_GET['error'])): ?>
        <div class="mb-4 p-3 rounded text-sm bg-red-50 text-red-700 border border-red-200">
            Email atau password salah.
        </div>
        <?php endif; ?>

        <form method="POST" action="login_action.php" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <input id="email" name="email" type="email" required class="w-full border rounded-md px-3 py-2 text-sm" placeholder="admin@sekolah.sch.id">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <input id="password" name="password" type="password" required class="w-full border rounded-md px-3 py-2 text-sm">
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white rounded-md px-4 py-2 text-sm font-medium hover:bg-blue-900/90">
                Masuk
            </button>
        </form>
    </div>
</body>
</html>
