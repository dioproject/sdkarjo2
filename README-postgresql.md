# Setup PostgreSQL untuk SD Negeri Karangrejo 02

Proyek ini telah diubah dari Supabase ke PostgreSQL native menggunakan Prisma.

## Setup Database

1. **Install dependencies:**
   ```bash
   npm install
   ```

2. **Setup PostgreSQL:**
   - Install PostgreSQL di komputer Anda
   - Buat database baru:
     ```sql
     CREATE DATABASE sd_karjo_db;
     ```

3. **Konfigurasi environment:**
   - Copy `.env.example` ke `.env.local`
   - Update `DATABASE_URL` dengan kredensial PostgreSQL Anda:
     ```
     DATABASE_URL=postgresql://username:password@localhost:5432/sd_karjo_db
     ```

4. **Setup Prisma:**
   ```bash
   npx prisma generate
   npx prisma db push
   ```

5. **Jalankan aplikasi:**
   ```bash
   npm run dev
   ```

## Fitur Admin

- **Login:** Email: `admin@sekolah.sch.id`, Password: `admin123`
- **Dashboard:** `/admin`
- **Login page:** `/admin/login`

## Struktur Database

Tabel `announcements` dengan kolom:
- `id` (UUID, primary key)
- `title` (text)
- `slug` (text, unique)
- `excerpt` (text)
- `content` (jsonb)
- `category` (enum: Akademik, Kegiatan, Prestasi, Informasi)
- `published_at` (timestamp)
- `is_published` (boolean)
- `author_name` (text, optional)
- `created_at`, `updated_at` (timestamp)

## Perubahan dari Supabase

1. **Dependencies:** Menghapus `@supabase/ssr` dan `@supabase/supabase-js`, menambahkan `@prisma/client` dan `prisma`
2. **Authentication:** Mengganti Supabase Auth dengan autentikasi sederhana
3. **Database:** Menggunakan Prisma ORM dengan PostgreSQL native
4. **Schema:** Menghapus RLS (Row Level Security) dan referensi ke `auth.users`