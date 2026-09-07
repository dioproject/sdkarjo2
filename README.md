# Website SD Negeri Karangrejo 02

Website resmi sekolah dasar berbasis **Next.js 14 App Router**, **Tailwind CSS**, **shadcn/ui**, **Prisma 7** (PostgreSQL), dan **Tiptap** rich-text editor.

## Fitur

- **Halaman publik** — Beranda, Pengumuman (list + detail), Profil Sekolah, Galeri
- **Admin panel** — Dashboard dengan sidebar navigasi, dilindungi autentikasi JWT
  - Kelola Pengumuman (rich-text editor Tiptap)
  - Kelola Guru & Tenaga Pendidik (upload foto)
  - Kelola Galeri (upload foto)
  - Edit Visi & Misi
  - Kelola Prestasi
  - Kelola Statistik Beranda (icon dinamis)
- **File upload** — Foto guru dan galeri disimpan di `public/uploads/`
- **Responsive** — Mobile-friendly dengan navbar hamburger menu

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Framework | Next.js 14 (App Router) |
| Styling | Tailwind CSS + shadcn/ui |
| Database | PostgreSQL |
| ORM | Prisma 7 + @prisma/adapter-pg |
| Auth | JWT (jose) + cookie |
| Editor | Tiptap |
| Runtime | Bun |

## Struktur Folder

```
src/
  app/
    admin/
      galeri/page.tsx
      guru/page.tsx
      login/page.tsx
      pengumuman/page.tsx
      prestasi/page.tsx
      statistik/page.tsx
      visi-misi/page.tsx
      actions.ts
      layout.tsx
      page.tsx
    api/upload/route.ts
    galeri/page.tsx
    pengumuman/
      [slug]/page.tsx
      page.tsx
    profil/page.tsx
    layout.tsx
    page.tsx
  components/
    admin/
      gallery-form.tsx
      teacher-form.tsx
      tiptap-editor.tsx
    ui/
    announcement-card.tsx
    footer.tsx
    navbar.tsx
    stat-icon.tsx
  lib/
    prisma.ts
    auth.ts
    types.ts
    utils.ts
    dummy-data.ts
prisma/
  schema.prisma
  seed.ts
prisma.config.ts
public/
  uploads/
    gallery/
    logo/
    teachers/
  favicon.ico
```

## Database

Tabel: `users`, `announcements`, `teachers`, `gallery`, `site_config`, `achievements`, `stats`

Schema lengkap ada di `prisma/schema.prisma`.

## Setup

### 1. Install dependencies

```bash
bun install
```

### 2. Konfigurasi environment

Buat file `.env` berdasarkan `.env.example`:

```env
DATABASE_URL=postgresql://username:password@localhost:5432/sd_karjo_db
JWT_SECRET=your-secret-key-here
```

### 3. Setup database

```bash
bun prisma generate
bun prisma db push
```

### 4. Seed admin user

```bash
bun run seed
```

Akun default: `sdnkarangrejo02official@gmail.com` / `sdkukarjo2`

### 5. Jalankan development server

```bash
bun run dev
```

Buka http://localhost:3000

## Build Production

```bash
bun run build
bun run start
```

## Prisma Studio

Untuk melihat/edit data langsung di browser:

```bash
bun prisma studio
```

## Deploy di Server Lokal

1. Clone/pull repo di server
2. Buat `.env` dengan `DATABASE_URL` dan `JWT_SECRET`
3. `bun install`
4. `bun prisma generate && bun prisma db push`
5. `bun run build && bun run start`

## Lisensi

Hak cipta © 2025 SD Negeri Karangrejo 02. Internal use.
