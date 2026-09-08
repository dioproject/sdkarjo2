<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$slug = $_GET['slug'] ?? null;

if ($slug) {
    // Detail view
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM announcements WHERE slug = ? AND is_published = 1");
    $stmt->execute([$slug]);
    $announcement = $stmt->fetch();

    if (!$announcement) {
        http_response_code(404);
        echo "Pengumuman tidak ditemukan.";
        exit;
    }

    $pageTitle = $announcement['title'];
    require_once __DIR__ . '/../includes/layout_header.php';
    ?>
    <main class="flex-1 mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="<?= BASE_URL ?>/pengumuman.php" class="mb-8 inline-flex items-center gap-1.5 text-sm text-muted-foreground hover:text-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali ke Pengumuman
        </a>

        <article>
            <header class="mb-8">
                <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                    <?= esc($announcement['category']) ?>
                </span>
                <h1 class="mt-4 text-3xl font-bold"><?= esc($announcement['title']) ?></h1>
                <div class="mt-3 flex items-center gap-1.5 text-sm text-muted-foreground">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    <?= formatDateID($announcement['published_at']) ?>
                    <?php if ($announcement['author_name']): ?>
                    <span class="ml-3">oleh <?= esc($announcement['author_name']) ?></span>
                    <?php endif; ?>
                </div>
            </header>

            <div class="space-y-4 text-base leading-7 text-foreground prose-content">
                <?php echo renderTipTapContent($announcement['content']); ?>
            </div>
        </article>
    </main>
    <?php
    require_once __DIR__ . '/../includes/layout_footer.php';
} else {
    // List view
    $db = getDB();
    $announcements = $db->query("SELECT * FROM announcements WHERE is_published = 1 ORDER BY published_at DESC")->fetchAll();

    $pageTitle = 'Pengumuman Sekolah';
    require_once __DIR__ . '/../includes/layout_header.php';
    ?>
    <main class="flex-1 mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-widest text-primary">Berita dan Informasi</p>
            <h1 class="mt-3 text-4xl font-bold">Pengumuman Sekolah</h1>
        </div>
        <?php if (empty($announcements)): ?>
        <p class="text-muted-foreground">Belum ada pengumuman.</p>
        <?php else: ?>
        <div class="grid gap-5 md:grid-cols-3">
            <?php foreach ($announcements as $a): ?>
            <a href="?slug=<?= esc($a['slug']) ?>" class="group overflow-hidden rounded-lg border bg-card transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="p-5 space-y-4">
                    <div class="flex items-center justify-between gap-3">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold <?= getCategoryColor($a['category']) ?>">
                            <?= esc($a['category']) ?>
                        </span>
                        <span class="text-xs text-muted-foreground">
                            <?= formatDateID($a['published_at']) ?>
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold"><?= esc($a['title']) ?></h3>
                    <p class="line-clamp-3 text-sm leading-6 text-muted-foreground"><?= esc($a['excerpt']) ?></p>
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-primary group-hover:underline">
                        Baca selengkapnya
                        <svg class="h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
    <?php
    require_once __DIR__ . '/../includes/layout_footer.php';
}
?>
