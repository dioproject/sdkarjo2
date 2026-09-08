<?php
require_once __DIR__ . '/functions.php';
global $SCHOOL_PROFILE;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? $SCHOOL_PROFILE['name']) ?></title>
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
<body class="min-h-screen flex flex-col bg-background text-foreground">
    <header class="sticky top-0 z-50 border-b bg-background/95 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="<?= BASE_URL ?>/" class="flex items-center gap-3 font-semibold text-primary">
                <img src="<?= BASE_URL ?>/uploads/logo/logo.png" alt="Logo" class="h-10 w-10 object-contain">
                <span class="leading-tight">
                    SD Negeri
                    <span class="block text-sm font-medium text-muted-foreground">Karangrejo 02</span>
                </span>
            </a>
            <nav class="hidden items-center gap-1 md:flex">
                <?php
                $navItems = [
                    ['label' => 'Beranda', 'href' => BASE_URL . '/'],
                    ['label' => 'Pengumuman', 'href' => BASE_URL . '/pengumuman.php'],
                    ['label' => 'Profil', 'href' => BASE_URL . '/profil.php'],
                    ['label' => 'Galeri', 'href' => BASE_URL . '/galeri.php'],
                ];
                foreach ($navItems as $item):
                ?>
                <a href="<?= $item['href'] ?>" class="rounded-md px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground">
                    <?= $item['label'] ?>
                </a>
                <?php endforeach; ?>
                <a href="<?= BASE_URL ?>/admin/" class="ml-2 inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90">
                    Admin
                </a>
            </nav>
            <button id="mobile-menu-btn" class="md:hidden inline-flex items-center justify-center rounded-md p-2 text-muted-foreground hover:bg-muted" aria-label="Menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div id="mobile-menu" class="hidden border-t bg-background md:hidden">
            <nav class="mx-auto grid max-w-7xl gap-1 px-4 py-3">
                <?php foreach ($navItems as $item): ?>
                <a href="<?= $item['href'] ?>" class="rounded-md px-3 py-2 text-sm font-medium text-muted-foreground hover:bg-muted hover:text-foreground">
                    <?= $item['label'] ?>
                </a>
                <?php endforeach; ?>
                <a href="<?= BASE_URL ?>/admin/" class="mt-2 inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground">
                    Admin
                </a>
            </nav>
        </div>
    </header>
    <script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
    </script>
