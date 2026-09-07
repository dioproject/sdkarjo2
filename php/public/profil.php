<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

global $SCHOOL_PROFILE;
$db = getDB();
$teachers = $db->query("SELECT * FROM teachers ORDER BY \"order\" ASC")->fetchAll();

$pageTitle = 'Profil Sekolah';
require_once __DIR__ . '/../includes/layout_header.php';
?>

<main class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
    <p class="text-sm font-semibold uppercase tracking-widest text-primary">Profil Sekolah</p>
    <h1 class="mt-3 text-4xl font-bold"><?= esc($SCHOOL_PROFILE['name']) ?></h1>

    <section class="mt-8 rounded-lg border bg-card p-6">
        <h2 class="text-2xl font-semibold">Sejarah Singkat</h2>
        <p class="mt-4 leading-7 text-muted-foreground">
            <?= esc($SCHOOL_PROFILE['name']) ?> berkembang sebagai sekolah dasar negeri yang dekat dengan masyarakat, menjaga budaya
            belajar yang tertib, dan memberi ruang bagi murid untuk tumbuh percaya diri melalui akademik, seni, olahraga,
            serta kepedulian lingkungan.
        </p>
    </section>

    <section class="mt-8">
        <h2 class="text-2xl font-semibold">Struktur dan Guru</h2>
        <div class="mt-5 grid gap-4 sm:grid-cols-3">
            <?php if (empty($teachers)): ?>
            <p class="text-muted-foreground">Belum ada data guru.</p>
            <?php else: ?>
            <?php foreach ($teachers as $t): ?>
            <div class="rounded-lg border bg-card p-5">
                <h3 class="font-semibold"><?= esc($t['name']) ?></h3>
                <p class="mt-1 text-sm text-muted-foreground"><?= esc($t['role']) ?></p>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../includes/layout_footer.php'; ?>
