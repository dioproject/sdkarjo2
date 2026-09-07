<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Kelola Galeri';
require_once __DIR__ . '/../../includes/admin_header.php';

$db = getDB();
$gallery = $db->query("SELECT * FROM gallery ORDER BY created_at DESC")->fetchAll();
?>

<main class="px-6 py-12">
    <h1 class="mb-6 text-2xl font-bold">Kelola Galeri</h1>

    <div class="rounded-lg border bg-card p-4 mb-6">
        <form action="galeri_action.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="action" value="create">
            <div>
                <label class="block text-sm font-medium mb-1">Judul</label>
                <input name="title" required class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Judul foto">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Foto</label>
                <input name="image" type="file" accept="image/*" required class="w-full border rounded-md px-3 py-2 text-sm">
            </div>
            <button type="submit" class="bg-blue-900 text-white rounded-md px-4 py-2 text-sm font-medium hover:bg-blue-900/90">
                Tambah Foto
            </button>
        </form>
    </div>

    <?php if (empty($gallery)): ?>
    <p class="text-sm text-muted-foreground">Belum ada foto galeri.</p>
    <?php else: ?>
    <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3">
        <?php foreach ($gallery as $g): ?>
        <div class="overflow-hidden rounded-md border">
            <img src="<?= esc($g['image']) ?>" alt="<?= esc($g['title']) ?>" class="aspect-video w-full object-cover">
            <div class="flex items-center justify-between p-2">
                <p class="text-xs font-medium truncate"><?= esc($g['title']) ?></p>
                <form action="galeri_action.php" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                    <input type="hidden" name="id" value="<?= esc($g['id']) ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="inline-flex items-center justify-center p-2 text-red-600 hover:bg-red-50 rounded">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>
