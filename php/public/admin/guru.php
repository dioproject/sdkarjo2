<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Kelola Guru';
require_once __DIR__ . '/../../includes/admin_header.php';

$db = getDB();
$teachers = $db->query("SELECT * FROM teachers ORDER BY \"order\" ASC")->fetchAll();
?>

<main class="px-6 py-12">
    <h1 class="mb-6 text-2xl font-bold">Kelola Guru & Tenaga Pendidik</h1>

    <div class="rounded-lg border bg-card p-4 mb-6">
        <form action="guru_action.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="action" value="create">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama</label>
                    <input name="name" required class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Nama guru">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Jabatan</label>
                    <input name="role" required class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Contoh: Guru Kelas VI">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Foto (opsional)</label>
                <input name="photo" type="file" accept="image/*" class="w-full border rounded-md px-3 py-2 text-sm">
            </div>
            <button type="submit" class="bg-blue-900 text-white rounded-md px-4 py-2 text-sm font-medium hover:bg-blue-900/90">
                Tambah Guru
            </button>
        </form>
    </div>

    <?php if (empty($teachers)): ?>
    <p class="text-sm text-muted-foreground">Belum ada data guru.</p>
    <?php else: ?>
    <div class="space-y-2">
        <?php foreach ($teachers as $t): ?>
        <div class="flex items-center justify-between rounded-md border p-3">
            <div class="flex items-center gap-3">
                <?php if ($t['photo']): ?>
                <img src="<?= esc($t['photo']) ?>" alt="<?= esc($t['name']) ?>" class="h-10 w-10 rounded-full object-cover">
                <?php endif; ?>
                <div>
                    <p class="text-sm font-semibold"><?= esc($t['name']) ?></p>
                    <p class="text-xs text-muted-foreground"><?= esc($t['role']) ?></p>
                </div>
            </div>
            <form action="guru_action.php" method="POST" onsubmit="return confirm('Hapus guru ini?')">
                <input type="hidden" name="id" value="<?= esc($t['id']) ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="inline-flex items-center justify-center p-2 text-red-600 hover:bg-red-50 rounded">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                </button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>
