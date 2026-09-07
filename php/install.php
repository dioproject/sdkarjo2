<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$db = getDB();

// Check if already installed
$existingUser = $db->query("SELECT COUNT(*) as cnt FROM users")->fetch()['cnt'] ?? 0;
if ($existingUser > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Already installed - show message and redirect
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sudah Terinstall</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-50 flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md bg-white rounded-lg shadow p-6 text-center">
            <div class="mb-4 text-green-500">
                <svg class="h-16 w-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold mb-2">Sudah Terinstall</h1>
            <p class="text-sm text-gray-500 mb-4">Aplikasi sudah diinstall. File install.php tidak dapat digunakan lagi.</p>
            <p class="text-sm text-gray-500 mb-4">Silakan hapus file ini dari server untuk keamanan.</p>
            <a href="public/admin/login.php" class="inline-block bg-blue-900 text-white rounded-md px-6 py-2 text-sm font-medium hover:bg-blue-900/90">
                Login Admin
            </a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $name = $_POST['name'] ?? 'Administrator';

    if ($email && $password) {
        $id = uuid();
        $hashed = hashPassword($password);

        $stmt = $db->prepare("INSERT OR REPLACE INTO users (id, email, password, name) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id, $email, $hashed, $name]);

        $message = "Setup berhasil! Admin user '$email' telah dibuat. Silakan login.";
        $success = true;
    } else {
        $message = "Email dan password harus diisi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - SD Negeri Karangrejo 02</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow p-6">
        <h1 class="text-xl font-bold mb-2">Setup Database</h1>
        <p class="text-sm text-gray-500 mb-6">Buat admin user untuk pertama kali.</p>

        <?php if ($message): ?>
        <div class="mb-4 p-3 rounded text-sm <?= $success ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
            <?= esc($message) ?>
            <?php if ($success): ?>
            <a href="public/admin/login.php" class="block mt-2 font-semibold underline">Login sekarang</a>
            <p class="mt-2 text-xs text-gray-500">Penting: Hapus file install.php dari server untuk keamanan.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" required class="w-full border rounded-md px-3 py-2 text-sm" placeholder="admin@sekolah.sch.id">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required class="w-full border rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text" name="name" value="Administrator" class="w-full border rounded-md px-3 py-2 text-sm">
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white rounded-md px-4 py-2 text-sm font-medium hover:bg-blue-900/90">
                Setup & Buat Admin
            </button>
        </form>
    </div>
</body>
</html>
