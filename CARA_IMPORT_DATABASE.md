# 🗄️ Cara Import Database ke MySQL (XAMPP)

## 📋 File yang Diperlukan

- **setup_database.sql** - File SQL lengkap dengan semua tabel dan data awal
- Database yang akan dibuat: **pelaporan_akademik**

---

## 🚀 LANGKAH-LANGKAH LENGKAP

### ⚙️ PERSIAPAN AWAL

#### 1. Pastikan XAMPP Sudah Terinstall
- Download dari: https://www.apachefriends.org/
- Install XAMPP di komputer

#### 2. Jalankan XAMPP
1. Buka **XAMPP Control Panel**
2. Klik tombol **"Start"** pada **Apache**
3. Klik tombol **"Start"** pada **MySQL**
4. Tunggu hingga kedua service berwarna **hijau**
5. Status harus menunjukkan: **"Running"**

⚠️ **Catatan:** Jika port 80 atau 3306 sudah digunakan, ubah port di Config

---

## 📥 METODE 1: Import via phpMyAdmin - TAB SQL (TERMUDAH & TERCEPAT) ✅

### Langkah-langkah:

#### Step 1: Buka phpMyAdmin
1. Buka browser (Chrome, Firefox, Edge, dll)
2. Ketik di address bar: **`http://localhost/phpmyadmin`**
3. Tekan **Enter**
4. Halaman phpMyAdmin akan terbuka

#### Step 2: Buka Tab SQL
1. Di halaman phpMyAdmin, cari dan klik tab **"SQL"** di bagian atas
2. Akan muncul text area kosong untuk query SQL

#### Step 3: Copy Isi File SQL
1. Buka file **`setup_database.sql`** dengan text editor:
   - Notepad++ (recommended)
   - Visual Studio Code
   - Notepad biasa
2. **Tekan Ctrl+A** (Select All / Pilih Semua)
3. **Tekan Ctrl+C** (Copy)

#### Step 4: Paste & Execute
1. Kembali ke browser (phpMyAdmin)
2. Klik di dalam **text area SQL**
3. **Tekan Ctrl+V** (Paste)
4. Pastikan semua kode SQL ter-paste dengan lengkap
5. Scroll ke bawah
6. Klik tombol **"Go"** atau **"Kirim"** (pojok kanan bawah)

#### Step 5: Tunggu Proses
- Proses import akan berjalan (5-10 detik)
- Tunggu hingga muncul pesan sukses
- ✅ Akan muncul: **"X queries executed successfully"**

#### Step 6: Verifikasi Database
1. Lihat **sidebar kiri**, klik refresh jika perlu
2. Klik database **"pelaporan_akademik"**
3. Pastikan ada tabel-tabel berikut:
   - ✅ admins (2 rows)
   - ✅ categories (6 rows)
   - ✅ users (3 rows)
   - ✅ reports
   - ✅ cache
   - ✅ sessions
   - ✅ migrations
   - ✅ jobs, job_batches, failed_jobs
   - ✅ password_reset_tokens

---

## 📥 METODE 2: Import via phpMyAdmin - TAB IMPORT

### Langkah-langkah:
## 📥 METODE 2: Import via phpMyAdmin - TAB IMPORT

### Langkah-langkah:

#### Step 1: Buka phpMyAdmin
- Akses: **`http://localhost/phpmyadmin`**

#### Step 2: Klik Tab Import
1. Klik tab **"Import"** di menu atas phpMyAdmin
2. Akan muncul form upload file

#### Step 3: Pilih File SQL
1. Klik tombol **"Choose File"** atau **"Browse..."**
2. Cari file **`setup_database.sql`** di folder project
3. Pilih file tersebut

#### Step 4: Setting Import
- Format: **SQL** (sudah default)
- Charset: **utf8mb4_unicode_ci** (recommended)
- Biarkan setting lainnya default

#### Step 5: Execute Import
1. Scroll ke bawah
2. Klik tombol **"Go"** atau **"Import"**
3. Tunggu proses upload dan execute
4. ✅ Akan muncul: **"Import has been successfully finished"**

---

## 📥 METODE 3: Import via Command Line (ADVANCED)

### Untuk Windows:

```bash
# 1. Buka Command Prompt atau PowerShell
# 2. Masuk ke folder MySQL XAMPP
cd C:\xampp\mysql\bin

# 3. Import database (ganti path file sesuai lokasi Anda)
mysql -u root -p < "D:\Admin_PanelUpgrade\setup_database.sql"

# 4. Tekan Enter (password default: kosong, langsung Enter)
```

### Untuk Mac/Linux:

```bash
# 1. Buka Terminal
# 2. Masuk ke folder MySQL
cd /Applications/XAMPP/bin

# 3. Import database
./mysql -u root -p < /path/to/setup_database.sql

# 4. Masukkan password (default: kosong)
```

---

## ✅ CARA VERIFIKASI DATABASE BERHASIL

### 1. Cek di phpMyAdmin:
1. Buka `http://localhost/phpmyadmin`
2. Klik database **"pelaporan_akademik"** di sidebar kiri
3. Akan muncul daftar tabel
4. Klik tabel **"admins"** → Tab **"Browse"**
5. Pastikan ada 2 admin:
   - ✅ Super Administrator (superadmin@pelaporan.com)
   - ✅ Admin (admin@pelaporan.com)

### 2. Cek Tabel Categories:
1. Klik tabel **"categories"**
2. Tab **"Browse"**
3. Pastikan ada 6 kategori:
   - ✅ Plagiarisme
   - ✅ Menyontek
   - ✅ Titip Absen
   - ✅ Kecurangan Ujian
   - ✅ Pemalsuan Data
   - ✅ Lainnya

### 3. Jalankan SQL Query untuk Cek:
```sql
USE pelaporan_akademik;
SELECT COUNT(*) as total_admins FROM admins;
SELECT COUNT(*) as total_categories FROM categories;
SELECT COUNT(*) as total_users FROM users;
```

Hasil yang diharapkan:
- total_admins: **2**
- total_categories: **6**
- total_users: **3**

---

## 🎯 LANGKAH SETELAH IMPORT DATABASE

### 1. Setup File Environment (.env)

#### Cara 1: Copy Manual
1. Copy file **`.env.example`**
2. Rename menjadi **`.env`**

#### Cara 2: Via Command Line
```bash
# Windows
copy .env.example .env

# Mac/Linux
cp .env.example .env
```

### 2. Edit Konfigurasi Database di .env

Buka file **`.env`** dengan text editor, cari dan pastikan:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pelaporan_akademik
DB_USERNAME=root
DB_PASSWORD=
```

⚠️ **Penting:** 
- Jika MySQL XAMPP menggunakan password, isi `DB_PASSWORD`
- Jika port MySQL bukan 3306, ubah `DB_PORT`

### 3. Install Dependencies Laravel (Jika Belum)

```bash
composer install
```

Tunggu hingga semua package terinstall (bisa 5-10 menit)

### 4. Generate Application Key

```bash
php artisan key:generate
```

Akan muncul: **"Application key set successfully"**

### 5. Jalankan Web Server

```bash
php artisan serve
```

Output yang diharapkan:
```
INFO  Server running on [http://127.0.0.1:8000].
Press Ctrl+C to stop the server
```

### 6. Buka Web di Browser

1. Buka browser
2. Ketik: **`http://127.0.0.1:8000`** atau **`http://localhost:8000`**
3. Halaman login admin akan muncul

---

## 🔐 LOGIN CREDENTIALS

### Akun Super Admin (Akses Penuh):
- **Email:** `superadmin@pelaporan.com`
- **Password:** `superadmin123`

**Akses:**
- ✅ Dashboard & Statistik
- ✅ Kelola Laporan
- ✅ Kelola Kategori
- ✅ Manajemen Admin (CRUD admin lain)
- ✅ Manajemen Pengguna
- ✅ Pengaturan Sistem
- ✅ Profile Admin

### Akun Admin Biasa (Akses Terbatas):
- **Email:** `admin@pelaporan.com`
- **Password:** `admin123`

**Akses:**
- ✅ Dashboard & Statistik
- ✅ Kelola Laporan (tidak bisa hapus)
- ✅ Lihat Kategori (tidak bisa edit)
- ✅ Profile Admin
- ❌ Tidak bisa kelola kategori
- ❌ Tidak bisa kelola admin
- ❌ Tidak bisa kelola user
- ❌ Tidak bisa akses settings

### Test Users (Untuk Testing Mobile App):
- **Email:** `user1@example.com` atau `user2@example.com`
- **Password:** `password`

---

## ❌ TROUBLESHOOTING - SOLUSI MASALAH

### 🔴 Error: "Cannot connect to MySQL server"

**Penyebab:**
- MySQL belum running
- Port 3306 sudah digunakan aplikasi lain

**Solusi:**
1. Buka XAMPP Control Panel
2. Pastikan MySQL service **running** (hijau)
3. Jika tidak bisa start, klik **"Logs"** untuk cek error
4. Atau restart MySQL: Stop → Start

### 🔴 Error: "Database already exists"

**Solusi 1 - Hapus Database Lama:**
1. Buka phpMyAdmin
2. Klik database **"pelaporan_akademik"**
3. Klik tab **"Operations"**
4. Scroll ke bawah, klik **"Drop the database"**
5. Confirm, lalu import ulang file SQL

**Solusi 2 - Via SQL Query:**
```sql
DROP DATABASE IF EXISTS pelaporan_akademik;
```
Lalu jalankan file `setup_database.sql` lagi

### 🔴 Error: "Table already exists"

**Solusi:**
- Drop semua tabel atau hapus database
- Import ulang dari awal

### 🔴 Error: File SQL terlalu besar untuk diupload

**Solusi - Edit php.ini:**
1. Buka **XAMPP Control Panel**
2. Klik **"Config"** pada Apache → **"PHP (php.ini)"**
3. Cari dan ubah nilai berikut:
   ```ini
   upload_max_filesize = 128M
   post_max_size = 128M
   max_execution_time = 600
   memory_limit = 256M
   ```
4. Save file
5. **Restart Apache** di XAMPP
6. Import ulang file SQL

### 🔴 Error: Port 3306 sudah digunakan

**Solusi:**
1. **Matikan MySQL service lain** (misalnya MySQL Workbench)
2. Atau **ubah port MySQL** di XAMPP:
   - Klik Config → Service and Port Settings
   - Ubah Main Port 3306 ke 3307
   - Restart MySQL
   - Update file `.env`: `DB_PORT=3307`

### 🔴 Error: "SQLSTATE[HY000] [1049] Unknown database"

**Solusi:**
- Database belum dibuat atau nama salah
- Import ulang file `setup_database.sql`
- Pastikan nama database di `.env` sama: `pelaporan_akademik`

### 🔴 Error: "Access denied for user"

**Solusi:**
- Cek username dan password MySQL di `.env`
- Default XAMPP: username `root`, password kosong
- Jika sudah set password, masukkan di `DB_PASSWORD`

---

## 📞 BANTUAN & SUPPORT

Jika masih ada masalah:

1. **Cek Log Error MySQL:**
   - Lokasi: `C:\xampp\mysql\data\mysql_error.log`
   - Baca pesan error terakhir

2. **Cek Log Laravel:**
   - Lokasi: `storage/logs/laravel.log`
   - Error detail ada di sini

3. **Screenshot Error:**
   - Ambil screenshot error yang muncul
   - Catat langkah yang sudah dilakukan

4. **Hubungi Developer:**
   - Email support
   - GitHub Issues
   - Tim IT

---

## 📚 FILE & DOKUMENTASI TERKAIT

- 📄 **`setup_database.sql`** - File SQL untuk import database
- 📄 **`.env.example`** - Template konfigurasi environment
- 📄 **`DATABASE_SETUP.md`** - Dokumentasi lengkap database
- 📄 **`README.md`** - Dokumentasi utama project
- 📄 **`PERBAIKAN_PROFIL_DAN_USERS.md`** - Update fitur terbaru

---

## 🎉 SELESAI!

✅ **Database berhasil di-import!**
✅ **Web server sudah running!**
✅ **Siap digunakan untuk testing!**

### Langkah Terakhir:
1. ✅ Database: **SELESAI**
2. ✅ .env: **SELESAI**
3. ✅ Composer install: **SELESAI**
4. ✅ Key generate: **SELESAI**
5. ✅ Server running: **SELESAI**
6. 🎯 **Buka browser dan login!**

---

**Happy Coding! 🚀**

### Metode 3: Menggunakan Command Line MySQL

```bash
# Buka CMD atau PowerShell sebagai Administrator
# Ganti path sesuai lokasi XAMPP Anda

cd "D:\Pelaporan Akademik\pelaporan_akademik\admin_panel"

# Untuk XAMPP di C:\xampp
"C:\xampp\mysql\bin\mysql.exe" -u root < setup_database.sql

# Untuk XAMPP di D:\xampp
"D:\xampp\mysql\bin\mysql.exe" -u root < setup_database.sql
```

---

## 📊 Isi Database Setelah Import

### Tabel yang Dibuat (10 tabel):
1. **users** - Data pengguna (3 user sample)
2. **categories** - Kategori pelanggaran (6 kategori)
3. **reports** - Data laporan (kosong, siap terima data)
4. **cache** - Laravel cache
5. **cache_locks** - Cache locking
6. **sessions** - Laravel sessions
7. **jobs** - Laravel queue jobs
8. **job_batches** - Job batches
9. **failed_jobs** - Failed jobs log
10. **migrations** - Migration tracking

### Data Awal:

#### 6 Kategori Default:
1. Kekerasan (fas fa-fist-raised)
2. Bullying (fas fa-user-slash)
3. Pelanggaran Tata Tertib (fas fa-exclamation-triangle)
4. Penyalahgunaan Narkoba (fas fa-pills)
5. Pencurian (fas fa-mask)
6. Lainnya (fas fa-ellipsis-h)

#### 3 User Sample:
- **admin@example.com** - Admin
- **user1@example.com** - User Test 1
- **user2@example.com** - User Test 2

**Password default semua user**: `password`

---

## 🎯 Setelah Database Berhasil

### 1. Jalankan Server Laravel:
```bash
cd "D:\Pelaporan Akademik\pelaporan_akademik\admin_panel"
php artisan serve
```

### 2. Buka Admin Panel:
```
http://localhost:8000
```

### 3. Akses phpMyAdmin:
```
http://localhost/phpmyadmin
```

### 4. Cek Database:
- Buka database "pelaporan_akademik"
- Lihat tabel "categories" → harus ada 6 data
- Lihat tabel "users" → harus ada 3 data

---

## 🔧 Troubleshooting

### ❌ Error: "Access denied"
**Solusi:**
- Pastikan MySQL di XAMPP sedang running (hijau)
- Cek username di .env: harus `root`
- Cek password di .env: kosongkan jika default XAMPP

### ❌ Error: "Database already exists"
**Solusi:**
- Database sudah ada sebelumnya
- Di phpMyAdmin, klik database "pelaporan_akademik" → Operations → Drop Database
- Lalu import ulang file SQL

### ❌ MySQL tidak mau start di XAMPP
**Solusi:**
- Port 3306 mungkin dipakai aplikasi lain
- Buka XAMPP → Config (MySQL) → my.ini
- Ubah port dari 3306 ke 3307
- Restart XAMPP
- Update .env: `DB_PORT=3307`

### ❌ File SQL terlalu besar
**Solusi:**
- Jalankan via command line (Metode 3)
- Atau di phpMyAdmin → Import Settings → Increase max upload size

---

## 📌 Catatan Penting

- ✅ Database: `pelaporan_akademik`
- ✅ User MySQL: `root`
- ✅ Password MySQL: kosong (default XAMPP)
- ✅ Charset: `utf8mb4_unicode_ci`
- ✅ Engine: `InnoDB`
- ✅ Port: `3306` (default)

---

## ✅ Checklist Setup

- [ ] XAMPP sudah diinstall
- [ ] Apache & MySQL di XAMPP running (hijau)
- [ ] Database imported via phpMyAdmin
- [ ] File .env sudah dikonfigurasi (DB_CONNECTION=mysql)
- [ ] Server Laravel running (php artisan serve)
- [ ] Admin panel bisa diakses di http://localhost:8000

---

**🎉 Selamat! Database MySQL untuk XAMPP sudah siap digunakan!**
