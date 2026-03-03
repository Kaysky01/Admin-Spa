# Admin Panel - Pelaporan Akademik

Website admin panel untuk mengelola aplikasi mobile Pelaporan Akademik yang dibangun dengan Laravel 11.

## 🚀 Fitur

### Dashboard
- **Statistik Real-time**: Total laporan, pengguna, dan kategori
- **Grafik Interaktif**:
  - Line Chart: Laporan per bulan (6 bulan terakhir)
  - Pie Chart: Distribusi status laporan
  - Bar Chart: Laporan per kategori
- **Tabel Laporan Terbaru**: 10 laporan terbaru dengan quick access

### Kelola Laporan
- **Filter & Search**: Pencarian berdasarkan keyword, status, dan kategori
- **Detail Laporan**: Informasi lengkap laporan dengan media lampiran
- **Update Status**: Ubah status laporan (Diproses, Ditindaklanjuti, Selesai)
- **Tanggapan Admin**: Berikan feedback untuk setiap laporan
- **Hapus Laporan**: Menghapus laporan yang tidak valid

### Kelola Kategori
- **CRUD Kategori**: Tambah, edit, hapus kategori
- **Icon Support**: Integrasi dengan Font Awesome icons
- **Statistik**: Jumlah laporan per kategori
- **Validasi**: Tidak bisa hapus kategori yang memiliki laporan

### API untuk Mobile App
- **GET** `/api/v1/categories` - Ambil semua kategori
- **POST** `/api/v1/user/auth` - Autentikasi/register user
- **GET** `/api/v1/reports/user/{userId}` - Ambil laporan user
- **POST** `/api/v1/reports` - Buat laporan baru
- **GET** `/api/v1/reports/{id}` - Detail laporan
- **GET** `/api/v1/statistics` - Statistik aplikasi

## 📋 Requirements

- PHP >= 8.2
- Composer
- SQLite (default) atau MySQL/PostgreSQL
- Extension PHP: PDO, SQLite, Fileinfo

## ⚙️ Instalasi

### 1. Install Dependencies (Sudah dilakukan)
```bash
composer install
```

### 2. Setup Environment (Sudah dilakukan)
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Setup Database (Sudah dilakukan)
Database SQLite sudah dibuat dan migrasi sudah dijalankan.

### 4. Seed Data (Sudah dilakukan)
Kategori default sudah ditambahkan:
- Kekerasan
- Bullying
- Pelanggaran Tata Tertib
- Penyalahgunaan Narkoba
- Pencurian
- Lainnya

## 🏃‍♂️ Menjalankan Aplikasi

### Development Server
```bash
php artisan serve
```

Aplikasi akan berjalan di: `http://localhost:8000`

### Atau dengan custom port
```bash
php artisan serve --port=8080
```

## 📁 Struktur Project

```
admin_panel/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── API/
│   │       │   └── ReportApiController.php    # API endpoints
│   │       ├── DashboardController.php        # Dashboard & statistics
│   │       ├── ReportController.php           # Kelola laporan
│   │       └── CategoryController.php         # Kelola kategori
│   └── Models/
│       ├── Report.php                         # Model laporan
│       └── Category.php                       # Model kategori
├── database/
│   ├── migrations/                            # Database migrations
│   ├── seeders/
│   │   └── CategorySeeder.php                # Seeder kategori
│   └── database.sqlite                        # SQLite database
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php                  # Layout utama
│       ├── dashboard/
│       │   └── index.blade.php                # Dashboard dengan charts
│       ├── reports/
│       │   ├── index.blade.php                # List laporan
│       │   └── show.blade.php                 # Detail laporan
│       └── categories/
│           ├── index.blade.php                # List kategori
│           ├── create.blade.php               # Tambah kategori
│           └── edit.blade.php                 # Edit kategori
└── routes/
    ├── web.php                                # Web routes
    └── api.php                                # API routes
```

## 🎨 Teknologi yang Digunakan

- **Framework**: Laravel 11
- **Database**: SQLite (bisa diganti MySQL/PostgreSQL)
- **Frontend**: Bootstrap 5
- **Icons**: Font Awesome 6
- **Charts**: Chart.js
- **UI Components**: Blade Templates

## 📊 Database Schema

### Table: reports
- id (PK)
- user_id (FK → users)
- category_id (FK → categories)
- title
- description
- location
- media (JSON array)
- status (enum: Diproses, Ditindaklanjuti, Selesai)
- admin_response
- responded_at
- created_at
- updated_at

### Table: categories
- id (PK)
- name
- icon (Font Awesome class)
- description
- created_at
- updated_at

### Table: users
- id (PK)
- name
- email (unique)
- password
- created_at
- updated_at

## 🔗 Integrasi dengan Flutter App

### 1. Base URL
Update base URL API di Flutter app:
```dart
const String baseUrl = 'http://localhost:8000/api/v1';
// Atau jika menggunakan emulator Android:
// const String baseUrl = 'http://10.0.2.2:8000/api/v1';
```

### 2. Endpoint Examples

**Get Categories:**
```dart
GET /api/v1/categories
```

**Create Report:**
```dart
POST /api/v1/reports
Content-Type: multipart/form-data

Body:
- user_id
- category_id
- title
- description
- location (optional)
- media[] (files, optional)
```

**Get User Reports:**
```dart
GET /api/v1/reports/user/{userId}
```

## 🎯 Fitur yang Akan Datang

- [ ] Autentikasi admin dengan login
- [ ] Notifikasi real-time untuk laporan baru
- [ ] Export laporan ke Excel/PDF
- [ ] Dashboard analytics lebih detail
- [ ] Email notification untuk status update
- [ ] Multi-role admin (Super Admin, Moderator)

## 🐛 Troubleshooting

### Error: "file_put_contents(): failed to open stream"
```bash
# Berikan permission pada folder storage dan bootstrap/cache
chmod -R 775 storage bootstrap/cache
# Windows PowerShell:
icacls storage /grant Everyone:F /T
```

### Error: "Class 'PDO' not found"
Aktifkan extension PDO di php.ini:
```ini
extension=pdo_sqlite
extension=sqlite3
```

### Port sudah digunakan
```bash
# Gunakan port berbeda
php artisan serve --port=8080
```

## 📝 Notes

- Database menggunakan SQLite untuk kemudahan development
- Media files disimpan di `storage/app/public/reports`
- Untuk production, ubah `DB_CONNECTION` di `.env` ke MySQL/PostgreSQL
- Jangan lupa jalankan `php artisan storage:link` untuk public storage

## 👨‍💻 Developer

Admin Panel untuk Aplikasi Pelaporan Akademik

---

**Version:** 1.0.0  
**Laravel:** 11.x  
**PHP:** 8.2+
