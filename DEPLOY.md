# Deploy SD Karjo 02 ke Ubuntu Server + Cloudflare Tunnel

Panduan ini membahas dari nol: setup server Ubuntu, Cloudflare Tunnel, GitHub Actions self-hosted runner, hingga CI/CD otomatis tiap push ke `main`.

```
┌──────────┐  push   ┌──────────────┐   trigger   ┌───────────────────────────┐
│  Laptop  │ ──────▶ │  GitHub repo │ ──────────▶ │  GitHub Actions           │
└──────────┘         └──────────────┘             │  • job ci (ubuntu-latest) │
                                                  │  • job deploy (self-hosted)│
                                                  └───────────────┬───────────┘
                                                                  │ ssh-less
                                                                  ▼
                                       ┌──────────────────────────────────────┐
                                       │  Ubuntu Server (rumah/kantor)        │
                                       │  ┌──────────┐ ┌─────────┐ ┌────────┐ │
                                       │  │ postgres │ │  app    │ │cflared │ │
                                       │  └──────────┘ └─────────┘ └────────┘ │
                                       └────────────────────┬─────────────────┘
                                                            │ outbound HTTPS
                                                            ▼
                                                   ┌─────────────────┐
                                                   │ Cloudflare Edge │  ←── publik
                                                   └─────────────────┘
```

Tidak butuh public IP, tidak butuh port-forward. Trafik publik masuk via Cloudflare Tunnel keluar dari server (outbound only).

---

## 0. Prasyarat

- 1 server Ubuntu 22.04 / 24.04 LTS (VPS atau mesin lokal yang menyala 24/7)
- Akses sudo / root ke server
- Domain yang sudah dikelola di Cloudflare (mis. `sdkarjo.sch.id`)
- Repo GitHub berisi project ini

---

## 1. Bootstrap server (sekali saja)

SSH ke server, lalu:

```bash
# 1) Clone repo sementara untuk akses scriptnya (atau scp scriptnya saja)
git clone https://github.com/<USER>/<REPO>.git /tmp/sdkarjo2
cd /tmp/sdkarjo2

# 2) Jalankan bootstrap (install Docker, buat user, siapkan /opt/sdkarjo2)
sudo bash scripts/bootstrap-ubuntu.sh
```

Yang dikerjakan script:
- Install Docker Engine + Compose plugin
- Buat user `sdkarjo` (anggota grup docker)
- Buat direktori `/opt/sdkarjo2` dan transfer ownership ke user `sdkarjo`
- Aktifkan UFW (hanya SSH yang dibuka — Cloudflare Tunnel keluar lewat outbound)

Pindah ke user layanan untuk langkah berikutnya:

```bash
sudo -iu sdkarjo
```

---

## 2. Buat Cloudflare Tunnel & dapatkan token

1. Buka [https://one.dash.cloudflare.com](https://one.dash.cloudflare.com) (Zero Trust dashboard)
2. **Networks → Tunnels → Create a tunnel**
3. Pilih **Cloudflared** → beri nama (`sdkarjo`) → **Save tunnel**
4. Di langkah "Install and run a connector", **salin nilai `--token`** (string panjang base64). Token inilah yang akan kita pakai sebagai `CLOUDFLARE_TUNNEL_TOKEN`.
5. Klik **Next** → konfigurasi **Public hostname**:
   - **Subdomain:** `www` (atau kosong)
   - **Domain:** `sdkarjo.sch.id` (pilih dari domain Anda)
   - **Service Type:** `HTTP`
   - **URL:** `app:3000` ← *nama service di docker-compose*
6. Klik **Save tunnel**.

DNS akan otomatis dibuatkan (CNAME ke `<tunnel-id>.cfargotunnel.com`).

---

## 3. Siapkan file `.env` di server

```bash
# sebagai user sdkarjo
cd /opt/sdkarjo2
# (file project akan di-rsync otomatis oleh GitHub Actions saat deploy pertama,
#  tapi untuk deploy pertama kita perlu bootstrap repo + .env terlebih dahulu)
git clone https://github.com/<USER>/<REPO>.git .

cp .env.production.example .env
nano .env
```

Isi minimal:

```env
POSTGRES_USER=sdkarjo
POSTGRES_PASSWORD=<openssl rand -base64 32>
POSTGRES_DB=sd_karjo_db
JWT_SECRET=<openssl rand -base64 64>
CLOUDFLARE_TUNNEL_TOKEN=<token dari langkah 2>
```

> **Penting:** file `.env` **tidak** di-commit ke repo dan **tidak** ditimpa oleh deploy berikutnya.

---

## 4. Pasang GitHub Actions self-hosted runner

Di GitHub: **Settings → Actions → Runners → New self-hosted runner → Linux x64**.
Salin perintah dari halaman tersebut, lalu di server jalankan sebagai user `sdkarjo`:

```bash
sudo -iu sdkarjo
mkdir -p ~/actions-runner && cd ~/actions-runner

# (perintah dari halaman GitHub, contoh — versi & token akan beda)
curl -o actions-runner-linux-x64.tar.gz -L \
  https://github.com/actions/runner/releases/download/vX.Y.Z/actions-runner-linux-x64-X.Y.Z.tar.gz
tar xzf actions-runner-linux-x64.tar.gz

# Konfigurasi — beri label `sdkarjo` agar workflow `runs-on: [self-hosted, linux, sdkarjo]` cocok
./config.sh \
  --url https://github.com/<USER>/<REPO> \
  --token <TOKEN_DARI_GITHUB> \
  --labels sdkarjo,linux,self-hosted \
  --unattended

# Pasang sebagai service systemd
sudo ./svc.sh install sdkarjo
sudo ./svc.sh start
sudo ./svc.sh status
```

Cek status runner di GitHub: **Settings → Actions → Runners** harus tampil **Idle**.

---

## 5. (Opsional) Set Variable repo di GitHub

Default direktori deploy `/opt/sdkarjo2`. Kalau mau override, di GitHub **Settings → Secrets and variables → Actions → Variables → New repository variable**:

| Name         | Value             |
|--------------|-------------------|
| `DEPLOY_DIR` | `/opt/sdkarjo2`   |

Tidak ada **secret** wajib karena rahasia produksi disimpan di file `.env` di server, bukan di GitHub.

---

## 6. Trigger deploy pertama

```bash
# Dari laptop
git add -A
git commit -m "chore: setup deployment automation"
git push origin main
```

GitHub Actions akan menjalankan:

1. **Job `ci`** (di GitHub-hosted runner): `bun install` → `prisma generate` → `bun run lint` → `bun run build`. Mendeteksi error sedini mungkin.
2. **Job `deploy`** (di self-hosted runner di server):
   - `rsync` source ke `/opt/sdkarjo2` (kecuali `.env`, `node_modules`, `.git`, `.next`)
   - `docker compose build --pull app`
   - `docker compose up -d` (postgres → app → cloudflared)
   - tunggu Postgres healthy
   - `bunx prisma db push` (sinkronisasi schema)
   - healthcheck HTTP ke `http://127.0.0.1:3000/`
   - prune image lama

Setelah workflow hijau, situs sudah live di `https://www.sdkarjo.sch.id` (sesuai hostname di Cloudflare Tunnel).

---

## 7. Seed admin user (sekali setelah deploy pertama)

```bash
# di server
cd /opt/sdkarjo2
bash scripts/seed.sh
```

Akun default: `sdnkarangrejo02official@gmail.com` / `sdkukarjo2`.
**Ganti password** segera setelah login pertama.

---

## 8. Operasional harian

| Tujuan                              | Perintah (di server, dari `/opt/sdkarjo2`)            |
|------------------------------------|--------------------------------------------------------|
| Lihat status semua container       | `docker compose ps`                                    |
| Lihat log app                       | `docker compose logs -f app`                           |
| Lihat log tunnel                    | `docker compose logs -f cloudflared`                   |
| Restart app                         | `docker compose restart app`                           |
| Jalankan ulang migrasi schema       | `docker compose exec -T app bunx prisma db push`       |
| Backup database                     | `docker compose exec -T postgres pg_dump -U sdkarjo sd_karjo_db > backup-$(date +%F).sql` |
| Restore database                    | `cat backup.sql \| docker compose exec -T postgres psql -U sdkarjo -d sd_karjo_db` |
| Manual deploy (tanpa Actions)       | `bash scripts/deploy.sh`                               |

Volume penting:
- `sdkarjo_postgres_data` — data Postgres
- `sdkarjo_uploads_data` — file upload (foto guru, galeri, logo)

Keduanya **tidak** terhapus saat `docker compose down` (selama tidak `down -v`).

---

## 9. Troubleshooting

**Workflow gagal di langkah `Pastikan .env tersedia`**
File `/opt/sdkarjo2/.env` belum dibuat. Lakukan langkah 3.

**Tunnel UP tapi domain return 502**
Pastikan service di Cloudflare Dashboard diset `http://app:3000` (bukan `localhost:3000`). `app` adalah nama service docker-compose.

**Runner offline di GitHub**
```bash
sudo systemctl status actions.runner.<repo>.<runner>.service
sudo systemctl restart actions.runner.<repo>.<runner>.service
```

**Image build error karena memori**
Tambah swap:
```bash
sudo fallocate -l 2G /swapfile && sudo chmod 600 /swapfile
sudo mkswap /swapfile && sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
```

**Reset total (hati-hati — hapus database!)**
```bash
docker compose down -v
```

---

## 10. Rotasi rahasia

Kalau JWT_SECRET / password DB bocor:
```bash
nano /opt/sdkarjo2/.env             # update nilai baru
docker compose up -d                # restart pakai env baru
```
JWT_SECRET baru akan menginvalidasi semua sesi admin (mereka harus login ulang) — itu memang yang diinginkan.
