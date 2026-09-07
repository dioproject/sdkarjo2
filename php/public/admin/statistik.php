<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Kelola Statistik';
require_once __DIR__ . '/../../includes/admin_header.php';

$db = getDB();
$stats = $db->query("SELECT * FROM stats")->fetchAll();
?>

<main class="max-w-2xl px-6 py-12">
    <h1 class="mb-6 text-2xl font-bold">Kelola Statistik Beranda</h1>
    <form action="statistik_action.php" method="POST" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label for="id" class="block text-xs font-medium mb-1">ID (unik)</label>
            <input id="id" name="id" required class="w-36 border rounded-md px-3 py-2 text-sm" placeholder="guru / literasi">
        </div>
        <div>
            <label for="label" class="block text-xs font-medium mb-1">Label</label>
            <input id="label" name="label" required class="w-44 border rounded-md px-3 py-2 text-sm" placeholder="Guru dan tendik">
        </div>
        <div>
            <label for="value" class="block text-xs font-medium mb-1">Nilai</label>
            <input id="value" name="value" required class="w-24 border rounded-md px-3 py-2 text-sm" placeholder="24+">
        </div>
        <div>
            <label for="icon" class="block text-xs font-medium mb-1">Icon</label>
            <select id="icon" name="icon" class="border rounded-md px-3 py-2 text-sm">
                <?php foreach ($ICON_OPTIONS as $ic): ?>
                <option value="<?= $ic ?>"><?= $ic ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="bg-blue-900 text-white rounded-md px-4 py-2 text-sm font-medium hover:bg-blue-900/90">Simpan</button>
    </form>

    <?php if (empty($stats)): ?>
    <p class="text-sm text-muted-foreground">Belum ada data statistik.</p>
    <?php else: ?>
    <div class="space-y-2">
        <?php foreach ($stats as $s): ?>
        <div class="flex items-center justify-between rounded-md border p-3">
            <div>
                <p class="text-sm font-semibold"><?= esc($s['label']) ?></p>
                <p class="text-xs text-muted-foreground">ID: <?= esc($s['id']) ?> — Nilai: <?= esc($s['value']) ?> — Icon: <?= esc($s['icon']) ?></p>
            </div>
            <form action="statistik_action.php" method="POST" onsubmit="return confirm('Hapus statistik ini?')">
                <input type="hidden" name="id" value="<?= esc($s['id']) ?>">
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
