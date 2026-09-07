#!/bin/bash
set -e

echo "==> Deploy SD Karangrejo 02 ke VPS"

# 1. Build di local/host (skip jika .next/standalone sudah ada)
if [ ! -d ".next/standalone" ]; then
    echo "==> Building Next.js..."
    if command -v bun &> /dev/null; then
        bun install
        bun run build
    else
        echo "ERROR: Bun not found and no pre-built artifacts!"
        echo "Please build locally first or install Bun:"
        echo "  curl -fsSL https://bun.sh/install | bash"
        exit 1
    fi
else
    echo "==> Using pre-built artifacts (.next/standalone found)"
fi

# 2. Stop container lama (jika ada)
echo "==> Stopping old containers..."
docker compose down || true

# 3. Build & start container baru
echo "==> Building & starting containers..."
docker compose up -d --build

# 4. Tunggu app ready
echo "==> Waiting for app to be ready..."
sleep 10

# 5. Run Prisma migrations
echo "==> Running Prisma migrations..."
docker compose exec -T app bun prisma db push

# 6. Setup Nginx (hanya sekali)
if [ ! -f /etc/nginx/sites-enabled/sedakarda ]; then
    echo "==> Setting up Nginx..."
    sudo cp nginx-sedakarda.conf /etc/nginx/sites-available/sedakarda
    sudo ln -s /etc/nginx/sites-available/sedakarda /etc/nginx/sites-enabled/
    sudo nginx -t
    sudo systemctl reload nginx
    echo "==> Nginx configured!"
else
    echo "==> Nginx already configured"
fi

echo "==> Deploy complete!"
echo "==> App running at http://sedakarda.neoncode.my.id"
docker compose ps
