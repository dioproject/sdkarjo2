import type { Announcement, Teacher } from "@/lib/types";

export const schoolProfile = {
  name: "SD Negeri Karangrejo 02",
  tagline: "Membentuk generasi santun, cerdas, dan siap menghadapi masa depan.",
  vision:
    "Terwujudnya sekolah dasar yang unggul dalam karakter, literasi, numerasi, dan kepedulian lingkungan.",
  missions: [
    "Menguatkan pembelajaran aktif yang menyenangkan dan berpusat pada murid.",
    "Membiasakan budaya disiplin, jujur, santun, dan gotong royong.",
    "Mengembangkan potensi akademik, seni, olahraga, dan kepemimpinan murid.",
    "Membangun komunikasi yang hangat antara sekolah, orang tua, dan masyarakat."
  ],
  achievements: [
    "Juara 2 FLS3N Menulis Cerita Tingkat Kecamatan",
    "Harapan 3 FLS3N Pantomim Tingkat Kecamatan",
    "Harapan 2 MTQ Tingkat Kecamatan",
  ],
  contact: {
    phone: "085156145712",
    email: "sdnkarangrejo02yosowilangun@gmail.com",
    address: "Jl. Balai Desa Karangrejo, Kecamatan Yosowilangun, Kabupaten Lumajang, Jawa Timur 67382"
  }
};

export const announcements: Announcement[] = [
  {
    id: "1",
    title: "Jadwal Penilaian Sumatif Akhir Semester Genap",
    slug: "jadwal-psas-genap",
    excerpt:
      "Penilaian Sumatif Akhir Semester Genap akan dilaksanakan mulai 3 Juni 2026 dengan jadwal terlampir.",
    content: "<h2>Informasi PSAS</h2><p>Mohon orang tua membantu anak mempersiapkan alat tulis dan menjaga kesehatan.</p>",
    category: "Akademik",
    publishedAt: "2026-05-08",
    isPublished: true
  },
  {
    id: "2",
    title: "Kegiatan Market Day dan Pameran Karya Murid",
    slug: "market-day-pameran-karya",
    excerpt:
      "Sekolah mengundang orang tua untuk menghadiri Market Day dan pameran karya kelas 1-6.",
    content: "<p>Kegiatan dilaksanakan di halaman sekolah pada pukul 08.00 WIB.</p>",
    category: "Kegiatan",
    publishedAt: "2026-05-03",
    isPublished: true
  },
  {
    id: "3",
    title: "Prestasi Tim Cerdas Cermat SD Negeri Karangrejo 02",
    slug: "prestasi-cerdas-cermat",
    excerpt:
      "Tim cerdas cermat berhasil meraih juara tingkat kecamatan setelah melalui babak final yang ketat.",
    content: "<p>Selamat kepada para murid dan guru pembimbing.</p>",
    category: "Prestasi",
    publishedAt: "2026-04-26",
    isPublished: true
  }
];

export const teachers: Teacher[] = [
  {
    name: "Dra. Rahayu Lestari",
    role: "Kepala Sekolah",
    photo: "https://images.unsplash.com/photo-1494790108377-be9c29b29330"
  },
  {
    name: "Bapak Aditya Pramana, S.Pd.",
    role: "Guru Kelas VI",
    photo: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e"
  },
  {
    name: "Ibu Melati Anggraini, S.Pd.",
    role: "Guru Kelas III",
    photo: "https://images.unsplash.com/photo-1580894732444-8ecded7900cd"
  }
];
