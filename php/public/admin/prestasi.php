<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Kelola Prestasi';
require_once __DIR__ . '/../../includes/admin_header.php';

$db = getDB();
$achievements = $db->query("SELECT * FROM achievements ORDER BY created_at DESC")->fetchAll();
?>

<main class="max-w-2xl px-6 py-12">
    <h1 class="text-2xl font-bold">Kelola Prestasi</h1>
    <form action="prestasi_action.php" method="POST" class="mt-6 flex gap-3">
        <input type="hidden" name="action" value="create">
        <input name="title" required class="flex-1 border rounded-md px-3 py-2 text-sm" placeholder="Judul prestasi">
        <button type="submit" class="bg-blue-900 text-white rounded-md px-4 py-2 text-sm font-medium hover:bg-blue-900/90">Tambah</button>
    </form>
    <div class="mt-6 space-y-2">
        <?php if (empty($achievements)): ?>
        <p class="text-sm text-muted-foreground">Belum ada data prestasi.</p>
        <?php else: ?>
        <?php foreach ($achievements as $a): ?>
        <div class="flex items-center justify-between rounded-md border p-3">
            <p class="text-sm"><?= esc($a['title']) ?></p>
            <form action="prestasi_action.php" method="POST" onsubmit="return confirm('Hapus prestasi ini?')">
                <input type="hidden" name="id" value="<?= esc($a['id']) ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="inline-flex items-center justify-center p-2 text-red-600 hover:bg-red-50 rounded">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                </button>
            </form>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>
