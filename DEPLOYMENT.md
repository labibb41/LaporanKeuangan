# Deployment Guide

Panduan ini untuk menjalankan aplikasi Laravel "Laporan Keuangan" di server teman atau VPS.

## Ringkasan Aplikasi

- Framework: Laravel 12
- PHP: minimal 8.2
- Database default: MySQL/MariaDB
- Frontend asset: Vite + Tailwind CSS
- Entry point web: `public/index.php`
- Document root web server harus mengarah ke folder `public/`, bukan ke root project.

Alur request:

```text
Domain / Cloudflare Tunnel
  -> web server atau php artisan serve
  -> public/index.php
  -> routes/web.php
  -> controller/view Laravel
```

## Kebutuhan Server

Install minimal:

- PHP 8.2 atau lebih baru
- Ekstensi PHP umum Laravel: `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `curl`
- Composer
- Node.js dan npm
- MySQL/MariaDB
- Nginx/Apache untuk deploy proper, atau `php artisan serve` untuk testing
- `cloudflared` jika ingin expose lewat Cloudflare Tunnel

## Setup Project

Clone atau upload project ke server, lalu masuk ke folder project.

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuai server:

```env
APP_NAME="Laporan Keuangan"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-kamu.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laporan_keuangan
DB_USERNAME=username_database
DB_PASSWORD=password_database

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

Jalankan migrasi database:

```bash
php artisan migrate --force
```

Opsional, isi data demo dan akun admin awal:

```bash
php artisan db:seed --force
```

Akun demo dari seeder:

```text
Email: admin@laporankeuangan.test
Password: password
```

Ganti email/password setelah login pertama.

## Optimasi Laravel Production

Setelah `.env`, database, dan asset siap:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Kalau ada perubahan `.env` atau route, clear cache dulu:

```bash
php artisan optimize:clear
```

## Permission Folder

Pastikan web server bisa menulis ke:

```text
storage/
bootstrap/cache/
```

Di Linux biasanya:

```bash
chmod -R ug+rw storage bootstrap/cache
```

Owner/group disesuaikan dengan user web server, misalnya `www-data`.

## Routing Utama

Routing Laravel ada di:

```text
routes/web.php
```

Route penting:

```text
/                         -> redirect ke login/dashboard
/login                    -> login
/dashboard                -> dashboard admin
/transaksi-operasional    -> database general transaksi
/operasional              -> operasional rekap
/pengeluaran              -> pengeluaran
/laporan                  -> laporan
/laporan/partner
/laporan/telly
/laporan/paguyuban
/laporan/pengeluaran
/laporan/keuangan
/admin/users              -> manajemen user
/hrd                      -> dashboard HRD
/hrd/gaji
/hrd/operasional
/hrd/keuangan
```

Untuk melihat semua route:

```bash
php artisan route:list
```

## Deploy Proper Dengan Nginx

Contoh konfigurasi Nginx:

```nginx
server {
    listen 80;
    server_name domain-kamu.com;

    root /path/ke/project/public;
    index index.php index.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Sesuaikan:

- `server_name`
- `root`
- versi socket PHP-FPM, misalnya `php8.2-fpm.sock`, `php8.3-fpm.sock`, atau sesuai server.

## Mode Testing Cepat

Kalau belum pakai Nginx, bisa jalankan:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Lalu akses lokal server:

```text
http://127.0.0.1:8000
```

Mode ini cocok untuk testing dan Cloudflare Tunnel sementara.

## Cloudflare Tunnel

Cloudflare Tunnel bisa dipakai agar aplikasi di server bisa diakses publik tanpa membuka port langsung.

Kebutuhan:

- Akun Cloudflare
- Domain yang DNS-nya dikelola Cloudflare, atau gunakan hostname tunnel yang disediakan Cloudflare
- `cloudflared` terinstall di server

Contoh alur:

```text
https://app.domain-kamu.com
  -> Cloudflare
  -> Cloudflare Tunnel
  -> http://127.0.0.1:80 atau http://127.0.0.1:8000
```

Jika pakai Nginx:

```bash
cloudflared tunnel --url http://127.0.0.1:80
```

Jika pakai `php artisan serve`:

```bash
php artisan serve --host=127.0.0.1 --port=8000
cloudflared tunnel --url http://127.0.0.1:8000
```

Untuk production, lebih rapi buat named tunnel dan route DNS di Cloudflare:

```bash
cloudflared tunnel login
cloudflared tunnel create laporan-keuangan
cloudflared tunnel route dns laporan-keuangan app.domain-kamu.com
```

Contoh file konfigurasi `~/.cloudflared/config.yml`:

```yaml
tunnel: laporan-keuangan
credentials-file: /home/user/.cloudflared/ID-TUNNEL.json

ingress:
  - hostname: app.domain-kamu.com
    service: http://127.0.0.1:80
  - service: http_status:404
```

Jalankan tunnel:

```bash
cloudflared tunnel run laporan-keuangan
```

## Checklist Sebelum Online

- `.env` sudah production: `APP_ENV=production`, `APP_DEBUG=false`
- `APP_URL` sudah sesuai domain HTTPS
- Database sudah dibuat dan migrasi sukses
- `npm run build` sudah sukses
- `php artisan config:cache`, `route:cache`, dan `view:cache` sukses
- Folder `storage` dan `bootstrap/cache` writable
- Akun admin sudah dibuat dan password default sudah diganti
- Web server root mengarah ke `public/`
- Cloudflare Tunnel mengarah ke service yang benar

## Troubleshooting

Jika halaman 500:

```bash
tail -f storage/logs/laravel.log
```

Jika route 404 padahal route ada:

- Pastikan document root ke `public/`
- Pastikan Nginx memakai `try_files $uri $uri/ /index.php?$query_string;`
- Jalankan `php artisan optimize:clear`

Jika asset CSS/JS tidak muncul:

```bash
npm install
npm run build
```

Jika session/login bermasalah:

```bash
php artisan migrate --force
php artisan optimize:clear
```

Jika Cloudflare Tunnel tidak membuka aplikasi:

- Pastikan aplikasi lokal jalan di port yang sama dengan konfigurasi tunnel.
- Cek `APP_URL`.
- Cek service target: `http://127.0.0.1:80` atau `http://127.0.0.1:8000`.
