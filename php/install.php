<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $name = $_POST['name'] ?? 'Administrator';

    if ($email && $password) {
        $db = getDB();
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
