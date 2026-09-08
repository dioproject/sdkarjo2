import paramiko
import os
import time

HOST = "165.101.18.159"
USER = "dio"
PASS = "375gDGSv)&Gq"
PHP_DIR = r"C:\project\sdkarjo2\php"
REMOTE_DIR = "/home/dio/sdkarjo2-php"

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(HOST, username=USER, password=PASS, timeout=30)


def ssh_exec(cmd, timeout=60):
    stdin, stdout, stderr = ssh.exec_command(cmd, timeout=timeout)
    code = stdout.channel.recv_exit_status()
    out = stdout.read().decode().strip()
    err = stderr.read().decode().strip()
    if out:
        print(f"  {out}")
    if err and code != 0:
        print(f"  ERR: {err}")
    return code


def upload_dir(sftp, local_dir, remote_dir):
    for item in os.listdir(local_dir):
        lp = os.path.join(local_dir, item)
        rp = remote_dir + "/" + item
        if os.path.isdir(lp):
            try:
                sftp.mkdir(rp)
            except:
                pass
            upload_dir(sftp, lp, rp)
        else:
            if item in (".gitkeep", "database.sqlite"):
                continue
            sftp.put(lp, rp)
            print(f"  uploaded: {item}")


print("[1/4] Uploading files...")
ssh_exec(f"mkdir -p {REMOTE_DIR}")
sftp = ssh.open_sftp()
upload_dir(sftp, PHP_DIR, REMOTE_DIR)
sftp.close()

print("[2/4] Creating docker network & directories...")
ssh_exec("sudo docker network create proxy 2>/dev/null || true")
ssh_exec(f"sudo mkdir -p {REMOTE_DIR}/data {REMOTE_DIR}/public/uploads")
ssh_exec(f"sudo chmod -R 777 {REMOTE_DIR}/data {REMOTE_DIR}/public/uploads")

print("[3/4] Building & starting container...")
ssh_exec(f"cd {REMOTE_DIR} && sudo docker compose down 2>/dev/null || true", timeout=30)
ssh_exec(f"cd {REMOTE_DIR} && sudo docker compose up -d --build", timeout=300)

print("[4/4] Checking status...")
time.sleep(5)
ssh_exec("sudo docker ps --filter name=sdkarjo2-php --format '{{.Status}}'")
ssh_exec("curl -s -o /dev/null -w '%{http_code}' http://localhost:7723/")

ssh.close()
print()
print("DONE!")
