<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

$pageTitle = 'Kelola Pengumuman';
require_once __DIR__ . '/../../includes/admin_header.php';

$db = getDB();
$announcements = $db->query("SELECT id, title, category, is_published, published_at FROM announcements ORDER BY published_at DESC")->fetchAll();
?>

<main class="px-6 py-12">
    <h1 class="mb-6 text-2xl font-bold">Kelola Pengumuman</h1>
    <div class="grid gap-6 lg:grid-cols-[1fr_0.6fr]">
        <div class="rounded-lg border bg-card">
            <div class="border-b p-4">
                <h2 class="text-lg font-semibold">Posting Pengumuman Baru</h2>
            </div>
            <div class="p-4">
                <form action="pengumuman_action.php" method="POST" class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-medium mb-1">Judul</label>
                        <input id="title" name="title" required class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Judul pengumuman">
                    </div>
                    <div>
                        <label for="excerpt" class="block text-sm font-medium mb-1">Ringkasan</label>
                        <textarea id="excerpt" name="excerpt" rows="3" required class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Ringkasan singkat"></textarea>
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium mb-1">Kategori</label>
                        <select id="category" name="category" class="w-full border rounded-md px-3 py-2 text-sm">
                            <?php foreach ($ANNOUNCEMENT_CATEGORIES as $cat): ?>
                            <option value="<?= $cat ?>"><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Isi Pengumuman</label>
                        <textarea id="content-editor" name="content" rows="8" class="w-full border rounded-md px-3 py-2 text-sm" placeholder="Tulis konten pengumuman di sini (HTML)"></textarea>
                        <p class="mt-1 text-xs text-muted-foreground">Tulis dalam format HTML. Contoh: &lt;h2&gt;Judul&lt;/h2&gt;&lt;p&gt;Isi paragraf&lt;/p&gt;</p>
                    </div>
                    <label class="flex items-center gap-2 text-sm">
                        <input name="isPublished" type="checkbox" class="h-4 w-4 rounded border" checked>
                        Publikasikan sekarang
                    </label>
                    <button type="submit" class="inline-flex items-center gap-2 bg-blue-900 text-white rounded-md px-4 py-2 text-sm font-medium hover:bg-blue-900/90">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
                        Simpan
                    </button>
                </form>
            </div>
        </div>

        <div class="rounded-lg border bg-card">
            <div class="border-b p-4">
                <h2 class="text-lg font-semibold">Daftar Pengumuman</h2>
            </div>
            <div class="p-4 space-y-3">
                <?php if (empty($announcements)): ?>
                <p class="text-sm text-muted-foreground">Belum ada pengumuman.</p>
                <?php else: ?>
                <?php foreach ($announcements as $a): ?>
                <div class="flex items-start justify-between gap-3 rounded-md border p-3">
                    <div>
                        <p class="text-sm font-semibold"><?= esc($a['title']) ?></p>
                        <p class="text-xs text-muted-foreground"><?= esc($a['category']) ?></p>
                    </div>
                    <form action="pengumuman_action.php" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
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
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>
