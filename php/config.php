<?php
// ============================================================
// Deployment Config - Sesuaikan untuk server Anda
// ============================================================

// Base URL aplikasi (kosongkan jika deploy di root domain)
// Contoh: '' untuk example.com, '/school' untuk example.com/school
define('BASE_URL', getenv('APP_BASE_URL') ?: '');

// Database SQLite
define('DB_PATH', __DIR__ . '/data/database.sqlite');

// JWT Secret - GANTI ini di production!
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'sdkarjo2-change-this-secret-key-in-production');
define('JWT_EXPIRY', 60 * 60 * 24 * 7); // 7 days
define('COOKIE_NAME', 'admin-token');

// Upload directory (harus writable oleh web server)
define('UPLOAD_DIR', __DIR__ . '/public/uploads');

// ============================================================
// School Profile Data
// ============================================================

$SCHOOL_PROFILE = [
    'name' => 'SD Negeri Karangrejo 02',
    'tagline' => 'Membentuk generasi santun, cerdas, dan siap menghadapi masa depan.',
    'vision' => 'Terwujudnya sekolah dasar yang unggul dalam karakter, literasi, numerasi, dan kepedulian lingkungan.',
    'missions' => [
        'Menguatkan pembelajaran aktif yang menyenangkan dan berpusat pada murid.',
        'Membiasakan budaya disiplin, jujur, santun, dan gotong royong.',
        'Mengembangkan potensi akademik, seni, olahraga, dan kepemimpinan murid.',
        'Membangun komunikasi yang hangat antara sekolah, orang tua, dan masyarakat.',
    ],
    'achievements' => [
        'Juara 2 FLS3N Menulis Cerita Tingkat Kecamatan',
        'Harapan 3 FLS3N Pantomim Tingkat Kecamatan',
        'Harapan 2 MTQ Tingkat Kecamatan',
    ],
    'contact' => [
        'phone' => '085156145712',
        'email' => 'sdnkarangrejo02yosowilangun@gmail.com',
        'address' => 'Jl. Balai Desa Karangrejo, Kecamatan Yosowilangun, Kabupaten Lumajang, Jawa Timur 67382',
    ],
];

$DEFAULT_STATS = [
    ['id' => 'guru', 'label' => 'Guru dan tendik', 'value' => '24+', 'icon' => 'UsersRound'],
    ['id' => 'literasi', 'label' => 'Program literasi aktif', 'value' => '12', 'icon' => 'BookOpenCheck'],
    ['id' => 'ruangan', 'label' => 'Jumlah ruangan', 'value' => '8', 'icon' => 'DoorOpen'],
];

$ANNOUNCEMENT_CATEGORIES = ['Informasi', 'Akademik', 'Kegiatan', 'Prestasi'];

$ICON_OPTIONS = ['UsersRound', 'BookOpenCheck', 'MapPin', 'School', 'GraduationCap', 'Building', 'DoorOpen', 'Trophy', 'Heart', 'Star'];
