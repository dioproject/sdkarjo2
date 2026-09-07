<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Visi & Misi';
require_once __DIR__ . '/../../includes/admin_header.php';

$db = getDB();
$config = $db->query("SELECT * FROM site_config WHERE id = 'main'")->fetch();
$misiList = $config ? json_decode($config['misi'], true) : [];
?>

<main class="max-w-2xl px-6 py-12">
    <h1 class="text-2xl font-bold">Edit Visi & Misi</h1>
    <form action="visi_misi_action.php" method="POST" class="mt-6 space-y-4">
        <div>
            <label for="visi" class="block text-sm font-medium mb-1">Visi</label>
            <input id="visi" name="visi" value="<?= esc($config['visi'] ?? '') ?>" required class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Visi sekolah">
        </div>
        <div>
            <label for="misi" class="block text-sm font-medium mb-1">Misi (satu per baris)</label>
            <textarea id="misi" name="misi" rows="6" required class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Misi 1&#10;Misi 2&#10;Misi 3"><?= esc(implode("\n", $misiList)) ?></textarea>
        </div>
        <button type="submit" class="bg-blue-900 text-white rounded-md px-4 py-2 text-sm font-medium hover:bg-blue-900/90">Simpan</button>
    </form>
</main>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>
