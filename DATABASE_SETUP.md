# Setup Database MySQL untuk XAMPP

## 🎯 Langkah-langkah Setup Database

### Metode 1: Menggunakan phpMyAdmin (PALING MUDAH) ✅

1. **Jalankan XAMPP Control Panel**
   - Buka XAMPP Control Panel
   - Start Apache
   - Start MySQL

2. **Buka phpMyAdmin**
   - Buka browser dan akses: http://localhost/phpmyadmin
   - Atau klik tombol "Admin" di samping MySQL di XAMPP Control Panel

3. **Buat Database Baru**
   - Klik tab "Databases" atau "Basis Data"
   - Di kolom "Create database", ketik: `pelaporan_akademik`
   - Pilih Collation: `utf8mb4_unicode_ci`
   - Klik tombol "Create"

4. **Alternatif: Import SQL File**
   - Klik tab "SQL" di menu atas phpMyAdmin
   - Copy dan paste isi file `setup_database.sql`
   - Klik "Go" atau "Kirim"

### Metode 2: Menggunakan Command Line MySQL

```bash
# Jalankan di terminal (sesuaikan path XAMPP Anda)
"C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS pelaporan_akademik CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Metode 3: Biarkan Laravel Membuat Otomatis

Laravel akan mencoba membuat database otomatis saat migrasi pertama kali.

---

## 🚀 Setelah Database Dibuat

Jalankan migrasi Laravel untuk membuat tabel-tabel:

```bash
cd "D:\Pelaporan Akademik\pelaporan_akademik\admin_panel"
php artisan migrate:fresh --seed
```

Perintah di atas akan:
- ✅ Membuat semua tabel (users, categories, reports, dll)
- ✅ Mengisi data kategori default (6 kategori)
- ✅ Membuat 10 user dummy untuk testing

---

## 📊 Konfigurasi Database (.env)

Sudah dikonfigurasi dengan pengaturan berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pelaporan_akademik
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan:** 
- Jika MySQL XAMPP Anda menggunakan password, ubah `DB_PASSWORD=` menjadi `DB_PASSWORD=password_anda`
- Port default MySQL adalah 3306

---

## ✅ Verifikasi Database

Setelah setup, cek apakah database sudah berhasil:

```bash
php artisan migrate:status
```

Output yang diharapkan:
```
Migration name ............................................. Batch / Status
0001_01_01_000000_create_users_table ............................ [1] Ran
0001_01_01_000001_create_cache_table ............................ [1] Ran
0001_01_01_000002_create_jobs_table ............................. [1] Ran
2025_12_14_122703_create_reports_table .......................... [1] Ran
2025_12_14_122813_create_categories_table ....................... [1] Ran
```

---

## 🔧 Troubleshooting

### Error: "Access denied for user 'root'@'localhost'"
- Buka XAMPP Control Panel
- Pastikan MySQL sedang running (lampu hijau)
- Coba tambahkan password di .env jika MySQL Anda pakai password

### Error: "SQLSTATE[HY000] [1049] Unknown database"
- Database belum dibuat
- Ikuti Metode 1 (phpMyAdmin) untuk membuat database

### Error: "SQLSTATE[HY000] [2002] No connection could be made"
- MySQL di XAMPP belum jalan
- Buka XAMPP Control Panel dan klik "Start" di MySQL

### Port 3306 sudah dipakai
Ubah di .env:
```env
DB_PORT=3307
```
Dan ubah juga port MySQL di XAMPP Config.

---

## 📌 Files yang Dibutuhkan

- ✅ `.env` - Sudah dikonfigurasi untuk MySQL
- ✅ `setup_database.sql` - Script SQL untuk membuat database
- ✅ `migrations/` - File migrasi untuk membuat tabel
- ✅ `seeders/` - Seeder untuk data awal

---

## 🎉 Setelah Berhasil

Jalankan server Laravel:
```bash
php artisan serve
```

Akses admin panel di: http://localhost:8000
