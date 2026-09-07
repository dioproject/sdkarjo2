<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

global $SCHOOL_PROFILE, $DEFAULT_STATS;

$db = getDB();

// Fetch data with fallbacks
try {
    $announcements = $db->query("SELECT * FROM announcements WHERE is_published = 1 ORDER BY published_at DESC LIMIT 3")->fetchAll();
} catch (Exception $e) { $announcements = []; }

try {
    $teachers = $db->query("SELECT * FROM teachers ORDER BY \"order\" ASC")->fetchAll();
} catch (Exception $e) { $teachers = []; }

try {
    $config = $db->query("SELECT * FROM site_config WHERE id = 'main'")->fetch();
} catch (Exception $e) { $config = null; }

try {
    $achievements = $db->query("SELECT * FROM achievements ORDER BY created_at DESC LIMIT 3")->fetchAll();
} catch (Exception $e) { $achievements = []; }

try {
    $stats = $db->query("SELECT * FROM stats")->fetchAll();
} catch (Exception $e) { $stats = []; }

$visi = $config['visi'] ?? $SCHOOL_PROFILE['vision'];
$misi = $config ? json_decode($config['misi'], true) : null;
if (empty($misi)) $misi = $SCHOOL_PROFILE['missions'];
$achievementList = $achievements ? array_column($achievements, 'title') : $SCHOOL_PROFILE['achievements'];
$statsToShow = $stats ?: $DEFAULT_STATS;

$pageTitle = $SCHOOL_PROFILE['name'];
require_once __DIR__ . '/../includes/layout_header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-primary text-primary-foreground">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b" alt="Suasana ruang kelas sekolah dasar" class="h-full w-full object-cover opacity-28">
        </div>
        <div class="relative mx-auto grid min-h-[620px] max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8">
            <div class="max-w-3xl">
                <p class="mb-4 text-sm font-semibold uppercase tracking-widest text-accent">Sekolah Dasar Negeri</p>
                <h1 class="text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl"><?= esc($SCHOOL_PROFILE['name']) ?></h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-primary-foreground/88"><?= esc($SCHOOL_PROFILE['tagline']) ?></p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>/pengumuman.php" class="inline-flex items-center justify-center rounded-md bg-accent px-6 py-3 text-sm font-medium text-accent-foreground hover:bg-accent/90">
                        Lihat Pengumuman
                    </a>
                    <a href="<?= BASE_URL ?>/profil.php" class="inline-flex items-center justify-center rounded-md border border-primary-foreground/40 bg-primary-foreground/10 px-6 py-3 text-sm font-medium text-primary-foreground hover:bg-primary-foreground/18">
                        Profil Sekolah
                    </a>
                </div>
            </div>
            <div class="grid gap-4 rounded-lg border border-primary-foreground/20 bg-primary-foreground/10 p-5 backdrop-blur">
                <?php foreach ($achievementList as $achievement): ?>
                <div class="flex items-start gap-3">
                    <svg class="mt-1 h-5 w-5 flex-none text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    <p class="text-sm leading-6 text-primary-foreground/90"><?= esc($achievement) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Visi & Misi -->
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr]">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-primary">Visi dan Misi</p>
                <h2 class="mt-3 text-3xl font-bold">Pendidikan yang hangat, tertib, dan berprestasi.</h2>
                <p class="mt-5 leading-7 text-muted-foreground"><?= esc($visi) ?></p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <?php foreach ($misi as $i => $mission): ?>
                <div class="rounded-lg border bg-card p-5">
                    <span class="mb-4 flex h-10 w-10 items-center justify-center rounded-md bg-secondary font-bold text-secondary-foreground">
                        <?= $i + 1 ?>
                    </span>
                    <p class="text-sm leading-6 text-muted-foreground"><?= esc($mission) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="border-y bg-white">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-12 sm:grid-cols-3 sm:px-6 lg:px-8">
            <?php foreach (array_slice($statsToShow, 0, 3) as $item): ?>
            <div class="flex items-center gap-4">
                <span class="flex h-12 w-12 items-center justify-center rounded-md bg-primary text-primary-foreground">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <?php
                        $iconPaths = [
                            'UsersRound' => '<path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/>',
                            'BookOpenCheck' => '<path d="M12 7v14"/><path d="M18 5v4a2 2 0 0 0 2 2h2"/><path d="M8 22H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2"/><path d="m9 12 2 2 4-4"/>',
                            'MapPin' => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
                            'School' => '<path d="M4 10h12"/><path d="M4 10v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M12 2v4"/><path d="m3 14 9-4 9 4"/>',
                            'GraduationCap' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 10 3 12 0v-5"/>',
                            'Building' => '<rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/>',
                            'DoorOpen' => '<path d="M11 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M11 21h4a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-2"/><path d="m12 7 4 4-4 4"/>',
                            'Trophy' => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>',
                            'Heart' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
                            'Star' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
                        ];
                        echo $iconPaths[$item['icon']] ?? $iconPaths['UsersRound'];
                        ?>
                    </svg>
                </span>
                <div>
                    <p class="text-2xl font-bold"><?= esc($item['value']) ?></p>
                    <p class="text-sm text-muted-foreground"><?= esc($item['label']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Pengumuman -->
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between gap-6">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-primary">Informasi Terbaru</p>
                <h2 class="mt-3 text-3xl font-bold">Pengumuman sekolah</h2>
            </div>
            <a href="<?= BASE_URL ?>/pengumuman.php" class="hidden sm:inline-flex items-center justify-center rounded-md border border-border bg-card px-4 py-2 text-sm font-medium hover:bg-muted">
                Semua pengumuman
            </a>
        </div>
        <?php if (empty($announcements)): ?>
        <p class="text-muted-foreground">Belum ada pengumuman.</p>
        <?php else: ?>
        <div class="grid gap-5 md:grid-cols-3">
            <?php foreach ($announcements as $a): ?>
            <a href="<?= BASE_URL ?>/pengumuman.php?slug=<?= esc($a['slug']) ?>" class="group overflow-hidden rounded-lg border bg-card transition hover:-translate-y-0.5 hover:shadow-md">
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
    </section>

    <!-- Guru -->
    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-widest text-primary">Profil</p>
            <h2 class="mt-3 text-3xl font-bold">Pendidik pilihan</h2>
        </div>
        <?php if (empty($teachers)): ?>
        <p class="text-muted-foreground">Belum ada data guru.</p>
        <?php else: ?>
        <div class="grid gap-5 md:grid-cols-3">
            <?php foreach ($teachers as $t): ?>
            <article class="overflow-hidden rounded-lg border bg-card">
                <?php if ($t['photo']): ?>
                <div class="relative aspect-[4/3]">
                    <img src="<?= esc($t['photo']) ?>" alt="<?= esc($t['name']) ?>" class="h-full w-full object-cover">
                </div>
                <?php endif; ?>
                <div class="p-5">
                    <h3 class="font-semibold"><?= esc($t['name']) ?></h3>
                    <p class="mt-1 text-sm text-muted-foreground"><?= esc($t['role']) ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . '/../includes/layout_footer.php'; ?>
