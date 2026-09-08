<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$db = getDB();
$gallery = $db->query("SELECT * FROM gallery ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Galeri Kegiatan';
require_once __DIR__ . '/../includes/layout_header.php';
?>

<main class="flex-1 mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <p class="text-sm font-semibold uppercase tracking-widest text-primary">Galeri Kegiatan</p>
    <h1 class="mt-3 text-4xl font-bold">Kegiatan Murid</h1>

    <?php if (empty($gallery)): ?>
    <p class="mt-8 text-muted-foreground">Belum ada foto galeri.</p>
    <?php else: ?>
    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($gallery as $g): ?>
        <div class="relative aspect-[4/3] overflow-hidden rounded-lg border bg-card">
            <img src="<?= esc($g['image']) ?>" alt="<?= esc($g['title']) ?>" class="h-full w-full object-cover">
            <div class="absolute inset-x-0 bottom-0 bg-black/50 px-3 py-2">
                <p class="text-sm text-white"><?= esc($g['title']) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../includes/layout_footer.php'; ?>
