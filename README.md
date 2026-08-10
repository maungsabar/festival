# 🏆 Festival Sekolah — Laravel 11

Aplikasi web pendaftaran lomba festival sekolah berbasis **Laravel 11** dengan sistem manajemen 3 level admin.

---

## 🛠️ Teknologi

| Layer | Stack |
|-------|-------|
| Framework | **Laravel 11** |
| PHP | **^8.2** |
| Database | **MySQL** (default) / PostgreSQL |
| Frontend | Blade + Tailwind CSS (CDN) |
| Auth | Session-based (custom middleware) |

---

## ⚙️ Instalasi

### 1. Extract & masuk folder

```bash
unzip festival-laravel.zip
cd festival-laravel
```

### 2. Install dependensi

```bash
composer install
```

### 3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Buat database MySQL

```sql
CREATE DATABASE festival_sekolah
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

Lalu edit `.env` sesuai kredensial MySQL kamu:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=festival_sekolah
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Migrasi + Seeder

```bash
php artisan migrate --seed
```

Reset dari awal:

```bash
php artisan migrate:fresh --seed
```

### 6. Storage link (untuk file upload)

```bash
php artisan storage:link
```

### 7. Jalankan

```bash
php artisan serve
```

Buka: **http://localhost:8000**

---

## 👤 Akun Login

| Role | Username | Password | Akses |
|------|----------|----------|-------|
| 👑 Super Admin | `superadmin` | `super123` | Semua data & lomba |
| ♂️ Admin Putra | `admin_putra` | `putra123` | Hanya putra |
| ♀️ Admin Putri | `admin_putri` | `putri123` | Hanya putri |

---

## 🌐 Deploy ke Shared Hosting (cPanel + MySQL)

### 1. Pastikan PHP 8.2+ aktif

Cek di **MultiPHP Manager** atau **PHP Selector** di cPanel.

### 2. Buat database di cPanel

Masuk ke **MySQL Databases** → buat database, buat user, lalu assign user ke database dengan privilege **ALL PRIVILEGES**.

### 3. Upload project ke subfolder

Upload semua file (termasuk folder `vendor/`) ke `~/festival/` — satu level di atas `public_html`.

### 4. Pindahkan isi `public/` ke `public_html/`

```
public_html/
├── index.php     ← dari festival/public/index.php
├── .htaccess     ← dari festival/public/.htaccess
└── ...
```

### 5. Edit `public_html/index.php` — ubah path

```php
require __DIR__.'/../festival/vendor/autoload.php';
(require_once __DIR__.'/../festival/bootstrap/app.php')
    ->handleRequest(Request::capture());
```

### 6. Setup `.env` production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://namadomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=cpaneluser_festival
DB_USERNAME=cpaneluser_dbuser
DB_PASSWORD=password_db

SESSION_DRIVER=database
CACHE_STORE=database
```

### 7. Jalankan via SSH / Terminal cPanel

```bash
cd ~/festival
php artisan migrate --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📂 Struktur File Penting (Laravel 11)

```
festival-laravel/
├── bootstrap/
│   ├── app.php           ← Pusat konfigurasi (middleware, routing, exceptions)
│   └── providers.php     ← Daftar service providers
├── app/
│   ├── Http/
│   │   ├── Controllers/  ← 5 controllers
│   │   └── Middleware/
│   │       └── AdminAuth.php
│   ├── Models/           ← User, Lomba, Pendaftar
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/       ← 6 file migrasi
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/      ← 14 Blade templates
├── routes/
│   ├── web.php
│   └── console.php
└── public/
    ├── index.php
    └── .htaccess
```

---

## 🗄️ Skema Database MySQL

### Tabel aplikasi

| Tabel | Keterangan |
|-------|------------|
| `users` | Data admin login |
| `lombas` | Data perlombaan |
| `pendaftars` | Data pendaftar peserta |

### Tabel sistem Laravel 11

| Tabel | Keterangan |
|-------|------------|
| `sessions` | Session storage |
| `cache` + `cache_locks` | Cache storage |
| `jobs`, `job_batches`, `failed_jobs` | Queue |
| `migrations` | Riwayat migrasi |

### Detail kolom `users`

| Kolom | Tipe MySQL | Keterangan |
|-------|------------|------------|
| id | bigint unsigned | PK auto increment |
| username | varchar(255) | unique |
| password | varchar(255) | bcrypt hash |
| role | enum('superadmin','admin_putra','admin_putri') | |
| created_at / updated_at | timestamp | |

### Detail kolom `lombas`

| Kolom | Tipe MySQL | Keterangan |
|-------|------------|------------|
| id | bigint unsigned | PK |
| nama_lomba | varchar(255) | |
| gender | enum('Putra','Putri') | |
| aktif | tinyint(1) | default 1 |
| created_at / updated_at | timestamp | |

### Detail kolom `pendaftars`

| Kolom | Tipe MySQL | Keterangan |
|-------|------------|------------|
| id | bigint unsigned | PK |
| nisn | varchar(10) | unique |
| nama | varchar(255) | |
| gender | enum('Putra','Putri') | |
| nama_sekolah | varchar(255) | |
| alamat_sekolah | text | |
| id_lomba | bigint unsigned | FK → lombas.id |
| file_kartu_siswa | varchar(255) | nama file |
| file_bukti_pembayaran | varchar(255) | nama file |
| status_verifikasi | enum('Belum','Terverifikasi','Ditolak') | default 'Belum' |
| created_at / updated_at | timestamp | |

