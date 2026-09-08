import paramiko
import os
import sys
import time

HOST = "165.101.18.159"
USER = "dio"
PASS = "375gDGSv)&Gq"
PHP_DIR = r"C:\project\sdkarjo2\php"
REMOTE_DIR = "/var/www/sdkarjo2"
PORT = 7723


def ssh_exec(ssh, cmd, timeout=60):
    """Execute command and print output"""
    print(f"  >> {cmd}")
    stdin, stdout, stderr = ssh.exec_command(cmd, timeout=timeout)
    exit_code = stdout.channel.recv_exit_status()
    out = stdout.read().decode().strip()
    err = stderr.read().decode().strip()
    if out:
        print(f"     {out}")
    if err and exit_code != 0:
        print(f"     ERR: {err}")
    return exit_code, out, err


def upload_dir(sftp, local_dir, remote_dir):
    """Recursively upload directory"""
    for item in os.listdir(local_dir):
        local_path = os.path.join(local_dir, item)
        remote_path = remote_dir + "/" + item.replace("\\", "/")

        if os.path.isdir(local_path):
            try:
                sftp.mkdir(remote_path)
            except:
                pass
            upload_dir(sftp, local_path, remote_path)
        else:
            # Skip .gitkeep and large binary files
            if item == ".gitkeep":
                continue
            try:
                sftp.put(local_path, remote_path)
                print(f"  uploaded: {remote_path}")
            except Exception as e:
                print(f"  FAILED: {remote_path} - {e}")


print("=" * 50)
print("  SDKarjo2 - Deploy to VPS")
print("=" * 50)

# Connect
print("\n[1/7] Connecting to VPS...")
ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(HOST, username=USER, password=PASS, timeout=30)
print(f"  Connected to {HOST}")

# Check server info
print("\n[2/7] Checking server...")
ssh_exec(ssh, "uname -a")
ssh_exec(ssh, "cat /etc/os-release | head -3")

# Upload files
print("\n[3/7] Uploading files...")
sftp = ssh.open_sftp()
try:
    sftp.mkdir(REMOTE_DIR)
except:
    pass
upload_dir(sftp, PHP_DIR, REMOTE_DIR)
sftp.close()
print("  Upload complete!")

# Install packages
print("\n[4/7] Installing Nginx + PHP...")
ssh_exec(ssh, "sudo apt update -y", timeout=120)
ssh_exec(
    ssh,
    "sudo apt install -y nginx php-fpm php-sqlite3 php-mbstring php-xml php-curl",
    timeout=300,
)

# Detect PHP version
_, php_ver, _ = ssh_exec(
    ssh,
    "php -r 'echo PHP_MAJOR_VERSION.\".\".PHP_MINOR_VERSION;' 2>/dev/null || echo '8.2'",
)
print(f"  PHP version: {php_ver}")

# Setup directory and permissions
print("\n[5/7] Setting up directory & permissions...")
ssh_exec(ssh, f"sudo mkdir -p {REMOTE_DIR}")
ssh_exec(ssh, f"sudo chown -R www-data:www-data {REMOTE_DIR}")
ssh_exec(ssh, f"sudo chmod -R 755 {REMOTE_DIR}/public")
ssh_exec(ssh, f"sudo mkdir -p {REMOTE_DIR}/data")
ssh_exec(ssh, f"sudo chmod 777 {REMOTE_DIR}/data")
ssh_exec(ssh, f"sudo mkdir -p {REMOTE_DIR}/public/uploads")
ssh_exec(ssh, f"sudo chmod -R 777 {REMOTE_DIR}/public/uploads")

# Create Nginx config
print("\n[6/7] Configuring Nginx...")
nginx_conf = f"""server {{
    listen {PORT};
    server_name _;

    root {REMOTE_DIR}/public;
    index index.php;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;

    location / {{
        try_files $uri $uri/ /index.php?$query_string;
    }}

    location ~ \\.(hp)$ {{
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php{php_ver}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 300;
    }}

    location ~ /(?:config\\.php|schema\\.sql|install\\.php|includes|data) {{
        deny all;
    }}

    location ~ /\\.ht {{
        deny all;
    }}

    server_tokens off;
}}
"""

# Write nginx config
ssh_exec(
    ssh,
    f"echo '{nginx_conf}' | sudo tee /etc/nginx/sites-available/sdkarjo2 > /dev/null",
)
ssh_exec(
    ssh, "sudo ln -sf /etc/nginx/sites-available/sdkarjo2 /etc/nginx/sites-enabled/"
)
ssh_exec(ssh, "sudo rm -f /etc/nginx/sites-enabled/default")

# Test and restart
ssh_exec(ssh, "sudo nginx -t")
ssh_exec(ssh, "sudo systemctl restart nginx")
ssh_exec(ssh, "sudo systemctl enable nginx")

# Restart PHP-FPM
ssh_exec(ssh, f"sudo systemctl restart php{php_ver}-fpm")
ssh_exec(ssh, f"sudo systemctl enable php{php_ver}-fpm")

print("\n[7/7] Testing...")
ssh_exec(
    ssh,
    f"curl -s -o /dev/null -w '%{{http_code}}' http://localhost:{PORT}/ || echo 'curl not found'",
)

ssh.close()

print("\n" + "=" * 50)
print("  DEPLOYMENT SELESAI!")
print("=" * 50)
print(f"\n  Akses aplikasi:")
print(f"  - Homepage: http://165.101.18.159:{PORT}/")
print(f"  - Install:  http://165.101.18.159:{PORT}/install.php")
print(f"  - Admin:    http://165.101.18.159:{PORT}/admin/login.php")
print(f"\n  Langkah selanjutnya:")
print(f"  1. Buka http://165.101.18.159:{PORT}/install.php")
print(f"  2. Buat admin user")
print(f"  3. Hapus install.php")
