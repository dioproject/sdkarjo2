# SDKarjo2 - Deployment Guide

## Struktur Folder di VPS

```
/var/www/sdkarjo2/
├── public/              ← Document root (diakses web)
│   ├── index.php
│   ├── pengumuman.php
│   ├── profil.php
│   ├── galeri.php
│   ├── assets/
│   │   ├── css/style.css
│   │   └── js/app.js
│   ├── admin/
│   └── uploads/
├── config.php           ← DI LUAR public (aman)
├── schema.sql
├── install.php
├── data/                ← SQLite (chmod 777)
└── includes/            ← DI LUAR public (aman)
```

## Cara Deploy

### 1. Upload File ke VPS

```bash
# Dari komputer lokal
scp -r php/* root@YOUR_VPS_IP:/var/www/sdkarjo2/
```

### 2. Jalankan Setup Script

```bash
# SSH ke VPS
ssh root@YOUR_VPS_IP

# Jalankan script setup
cd /var/www/sdkarjo2
chmod +x deploy.sh
sudo ./deploy.sh
```

### 3. Install SSL (Optional tapi Recommended)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com
```

### 4. Install Aplikasi

1. Buka browser: `http://yourdomain.com/install.php`
2. Masukkan email dan password admin
3. Klik "Setup & Buat Admin"
4. Login di `http://yourdomain.com/admin/`

### 5. Hapus install.php

```bash
rm /var/www/sdkarjo2/install.php
```

## Multi-Project

Untuk menambah project lain di VPS yang sama:

1. Buat folder: `mkdir -p /var/www/project2/public`
2. Upload file project ke `/var/www/project2/`
3. Buat Nginx config baru di `/etc/nginx/sites-available/project2`
4. Enable: `ln -s /etc/nginx/sites-available/project2 /etc/nginx/sites-enabled/`
5. Reload: `sudo systemctl reload nginx`

## Troubleshooting

### SQLite "database is locked"
- Pastikan `data/` memiliki permission `chmod 777`
- Cek ownership: `chown -R www-data:www-data /var/www/sdkarjo2/data`

### Upload gagal
- Cek permission uploads: `chmod -R 777 /var/www/sdkarjo2/public/uploads`
- Cek ukuran upload di php.ini: `upload_max_filesize = 10M`

### Halaman 404
- Cek Nginx config: `sudo nginx -t`
- Cek root directory di config Nginx
- Cek `try_files` directive

### CSS/JS tidak load
- Pastikan `assets/` ada di dalam `public/`
- Cek BASE_URL di `config.php`

## Security Checklist

- [ ] Ganti `JWT_SECRET` di `config.php` dengan string random
- [ ] Hapus `install.php` setelah setup
- [ ] Install SSL certificate
- [ ] Pastikan `data/` tidak diakses via web
- [ ] Pastikan `includes/` tidak diakses via web
- [ ] Gunakan password yang kuat untuk admin
