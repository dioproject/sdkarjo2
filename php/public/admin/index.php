<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

$db = getDB();
$session = getSession();

$announcements = $db->query("SELECT COUNT(*) as cnt FROM announcements")->fetch()['cnt'];
$teachers = $db->query("SELECT COUNT(*) as cnt FROM teachers")->fetch()['cnt'];
$gallery = $db->query("SELECT COUNT(*) as cnt FROM gallery")->fetch()['cnt'];

$pageTitle = 'Dashboard';
require_once __DIR__ . '/../../includes/admin_header.php';
?>

<main class="px-6 py-12">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold">Dashboard</h1>
            <p class="mt-2 text-muted-foreground">Kelola informasi resmi SD Negeri Karangrejo 02. Login sebagai <?= esc($session['email'] ?? '') ?>.</p>
        </div>
        <a href="logout_action.php" class="inline-flex items-center gap-2 rounded-md border border-border bg-card px-4 py-2 text-sm font-medium hover:bg-muted">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
            Keluar
        </a>
    </div>
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-lg border bg-card p-5">
            <p class="text-2xl font-bold"><?= $announcements ?></p>
            <p class="text-sm text-muted-foreground">Pengumuman</p>
        </div>
        <div class="rounded-lg border bg-card p-5">
            <p class="text-2xl font-bold"><?= $teachers ?></p>
            <p class="text-sm text-muted-foreground">Guru & Tendik</p>
        </div>
        <div class="rounded-lg border bg-card p-5">
            <p class="text-2xl font-bold"><?= $gallery ?></p>
            <p class="text-sm text-muted-foreground">Foto Galeri</p>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>
