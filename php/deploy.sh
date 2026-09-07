#!/bin/bash
# ============================================================
# SDKarjo2 - VPS Deployment Script
# Jalankan di VPS Ubuntu setelah upload file project
# ============================================================

set -e

# === KONFIGURASI (ubah sesuai kebutuhan) ===
APP_DIR="/var/www/sdkarjo2"
DOMAIN="example.com"  # Ganti dengan domain Anda
PHP_VERSION="8.2"

echo "============================================"
echo "  SDKarjo2 - Deployment ke VPS"
echo "============================================"
echo ""

# === 1. Update System ===
echo "[1/8] Updating system..."
sudo apt update -y
sudo apt upgrade -y

# === 2. Install Nginx ===
echo "[2/8] Installing Nginx..."
sudo apt install nginx -y
sudo systemctl enable nginx
sudo systemctl start nginx

# === 3. Install PHP ===
echo "[3/8] Installing PHP $PHP_VERSION + extensions..."
sudo apt install php$PHP_VERSION-fpm php$PHP_VERSION-sqlite3 php$PHP_VERSION-mbstring php$PHP_VERSION-xml php$PHP_VERSION-curl -y

# === 4. Setup Directory ===
echo "[4/8] Setting up directory structure..."
sudo mkdir -p $APP_DIR
sudo chown -R www-data:www-data $APP_DIR
sudo chmod -R 755 $APP_DIR/public
sudo mkdir -p $APP_DIR/data
sudo chmod 777 $APP_DIR/data
sudo mkdir -p $APP_DIR/public/uploads
sudo chmod -R 777 $APP_DIR/public/uploads

# === 5. Create Nginx Config ===
echo "[5/8] Creating Nginx config..."
sudo tee /etc/nginx/sites-available/sdkarjo2 > /dev/null << 'EOF'
server {
    listen 80;
    server_name DOMAIN_PLACEHOLDER www.DOMAIN_PLACEHOLDER;

    root /var/www/sdkarjo2/public;
    index index.php;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Main location
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP processing
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/phpPHP_VERSION_PLACEHOLDER-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 300;
    }

    # Block access to sensitive files
    location ~ /(?:config\.php|schema\.sql|install\.php|includes|data) {
        deny all;
    }

    # Block .htaccess
    location ~ /\.ht {
        deny all;
    }

    # Cache static files
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|webp)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Disable server version
    server_tokens off;
}
EOF

# Replace placeholders
sudo sed -i "s/DOMAIN_PLACEHOLDER/$DOMAIN/g" /etc/nginx/sites-available/sdkarjo2
sudo sed -i "s/PHP_VERSION_PLACEHOLDER/$PHP_VERSION/g" /etc/nginx/sites-available/sdkarjo2

# === 6. Enable Site ===
echo "[6/8] Enabling site..."
sudo ln -sf /etc/nginx/sites-available/sdkarjo2 /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx

# === 7. Setup Permissions ===
echo "[7/8] Finalizing permissions..."
sudo chown -R www-data:www-data $APP_DIR
sudo chmod 777 $APP_DIR/data
sudo chmod -R 777 $APP_DIR/public/uploads

# === 8. Install SSL (Let's Encrypt) ===
echo "[8/8] Installing SSL certificate..."
echo ""
echo "Untuk SSL, jalankan:"
echo "  sudo apt install certbot python3-certbot-nginx -y"
echo "  sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN"
echo ""

echo ""
echo "============================================"
echo "  Deployment Selesai!"
echo "============================================"
echo ""
echo "Langkah selanjutnya:"
echo "1. Akses http://$DOMAIN/install.php"
echo "2. Buat admin user"
echo "3. Hapus install.php dari server"
echo "4. Install SSL (lihat command di atas)"
echo "5. Login di http://$DOMAIN/public/admin/login.php"
echo ""
