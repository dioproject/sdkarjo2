<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
global $SCHOOL_PROFILE;
$currentPage = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= esc($pageTitle ?? 'Dashboard') ?></title>
    <link rel="icon" href="<?= BASE_URL ?>/uploads/logo/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    background: 'hsl(42, 33%, 97%)',
                    foreground: 'hsl(219, 38%, 13%)',
                    card: 'hsl(0, 0%, 100%)',
                    primary: { DEFAULT: 'hsl(218, 57%, 22%)', foreground: 'hsl(40, 43%, 96%)' },
                    secondary: { DEFAULT: 'hsl(150, 28%, 92%)', foreground: 'hsl(155, 40%, 18%)' },
                    muted: { DEFAULT: 'hsl(215, 28%, 93%)', foreground: 'hsl(218, 15%, 43%)' },
                    accent: { DEFAULT: 'hsl(42, 59%, 56%)', foreground: 'hsl(219, 38%, 13%)' },
                    border: 'hsl(216, 22%, 86%)',
                    ring: 'hsl(42, 59%, 56%)',
                },
                borderRadius: { DEFAULT: '0.5rem' }
            }
        }
    }
    </script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="bg-background text-foreground">
    <div class="flex min-h-screen">
        <aside class="w-56 shrink-0 border-r bg-card p-4">
            <a href="<?= BASE_URL ?>/admin/" class="mb-6 flex items-center gap-2 text-lg font-bold text-primary">
                <img src="<?= BASE_URL ?>/uploads/logo/logo.png" alt="Logo" class="h-8 w-8 object-contain">
                Admin
            </a>
            <nav class="space-y-1">
                <?php
                $adminNav = [
                    ['href' => '/admin/', 'label' => 'Dashboard', 'icon' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
                    ['href' => '/admin/pengumuman.php', 'label' => 'Pengumuman', 'icon' => '<path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>'],
                    ['href' => '/admin/guru.php', 'label' => 'Guru', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
                    ['href' => '/admin/galeri.php', 'label' => 'Galeri', 'icon' => '<rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>'],
                    ['href' => '/admin/visi-misi.php', 'label' => 'Visi & Misi', 'icon' => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>'],
                    ['href' => '/admin/prestasi.php', 'label' => 'Prestasi', 'icon' => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>'],
                    ['href' => '/admin/statistik.php', 'label' => 'Statistik', 'icon' => '<line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/>'],
                ];
                foreach ($adminNav as $item):
                    $active = str_contains($currentPage, $item['href']) && $item['href'] !== '/admin/' ? 'bg-secondary text-foreground' : 'text-muted-foreground hover:bg-secondary hover:text-foreground';
                    if ($item['href'] === '/admin/' && $currentPage === '/admin/' . basename($_SERVER['SCRIPT_NAME'])) $active = 'bg-secondary text-foreground';
                ?>
                <a href="<?= BASE_URL . $item['href'] ?>" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium <?= $active ?>">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $item['icon'] ?></svg>
                    <?= $item['label'] ?>
                </a>
                <?php endforeach; ?>
            </nav>
        </aside>
        <div class="flex-1">
