-- =============================================
-- Database Setup untuk XAMPP/phpMyAdmin
-- Aplikasi: Pelaporan Akademik Admin Panel
-- =============================================

-- =============================================
-- CARA SETUP DATABASE DAN MENJALANKAN WEB:
-- =============================================
-- 1. Pastikan XAMPP sudah running (Apache & MySQL)
-- 2. Buka phpMyAdmin di browser: http://localhost/phpmyadmin
-- 3. Klik tab "SQL" di menu atas
-- 4. Copy dan paste seluruh isi file ini
-- 5. Klik tombol "Go" atau "Kirim"
-- 6. Database dan semua tabel akan otomatis dibuat
-- 
-- SETELAH DATABASE SIAP:
-- 7. Buka terminal/cmd di folder project
-- 8. Jalankan: composer install (jika belum install dependencies)
-- 9. Copy file .env.example menjadi .env
-- 10. Edit .env, sesuaikan konfigurasi database:
--     DB_CONNECTION=mysql
--     DB_HOST=127.0.0.1
--     DB_PORT=3306
--     DB_DATABASE=pelaporan_akademik
--     DB_USERNAME=root
--     DB_PASSWORD=
-- 11. Jalankan: php artisan key:generate
-- 12. Jalankan: php artisan serve
-- 13. Buka browser: http://127.0.0.1:8000
-- 14. Login dengan salah satu akun admin di bawah
-- =============================================

-- Drop database jika sudah ada (HATI-HATI: akan menghapus semua data!)
-- DROP DATABASE IF EXISTS `pelaporan_akademik`;

-- Buat database baru
CREATE DATABASE IF NOT EXISTS `pelaporan_akademik` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Gunakan database yang baru dibuat
USE `pelaporan_akademik`;

-- =============================================
-- TABEL: migrations
-- Untuk tracking migrasi Laravel
-- =============================================
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL: admins
-- Menyimpan data administrator
-- =============================================
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','super_admin') NOT NULL DEFAULT 'admin',
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin accounts
INSERT INTO `admins` (`name`, `email`, `password`, `role`, `phone`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
('Super Administrator', 'superadmin@pelaporan.com', '$2y$12$.77P8b.xDmQIMxI.Mi8jI.Vbc6x6B26xmsxpJ9XgIXpYWuF5t3D96', 'super_admin', '081234567890', 'Jl. Pendidikan No. 123, Jakarta', 1, NOW(), NOW()),
('Admin', 'admin@pelaporan.com', '$2y$12$DSKyzlUyQj5wrj/xtC.KLuJ6nsGiuN12AgPCmMdT5fvCZqKMdF0iy', 'admin', '081234567891', 'Jl. Pendidikan No. 124, Jakarta', 1, NOW(), NOW());

-- =============================================
-- LOGIN CREDENTIALS
-- =============================================
-- Super Admin:
--   Email: superadmin@pelaporan.com
--   Password: superadmin123
--
-- Admin Biasa:
--   Email: admin@pelaporan.com
--   Password: admin123
-- =============================================

-- =============================================
-- TABEL: users
-- Menyimpan data pengguna aplikasi mobile
-- =============================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL: cache
-- Laravel cache storage
-- =============================================
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL: sessions
-- Laravel session storage
-- =============================================
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL: jobs
-- Laravel queue jobs
-- =============================================
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL: password_reset_tokens
-- Token untuk reset password
-- =============================================
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL: categories
-- Menyimpan kategori pelanggaran/laporan
-- PENTING: Harus dibuat SEBELUM tabel reports!
-- =============================================
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL: reports
-- Menyimpan data laporan dari mobile app
-- PENTING: Bergantung pada tabel users dan categories!
-- =============================================
CREATE TABLE IF NOT EXISTS `reports` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `media` json DEFAULT NULL,
  `status` enum('Diproses','Ditindaklanjuti','Selesai') NOT NULL DEFAULT 'Diproses',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` varchar(255) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `admin_response` text DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reports_user_id_foreign` (`user_id`),
  KEY `reports_category_id_foreign` (`category_id`),
  KEY `reports_status_index` (`status`),
  KEY `reports_created_at_index` (`created_at`),
  CONSTRAINT `reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reports_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- INSERT DATA AWAL: Categories
-- 6 kategori pelanggaran akademik
-- =============================================
INSERT INTO `categories` (`id`, `name`, `icon`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Plagiarisme', 'fas fa-copy', 'Laporan terkait plagiarisme atau menjiplak karya orang lain', NOW(), NOW()),
(2, 'Menyontek', 'fas fa-user-secret', 'Laporan terkait kecurangan saat ujian atau tugas', NOW(), NOW()),
(3, 'Titip Absen', 'fas fa-user-check', 'Laporan terkait penitipan absensi atau kehadiran palsu', NOW(), NOW()),
(4, 'Kecurangan Ujian', 'fas fa-exclamation-triangle', 'Laporan terkait kecurangan dalam pelaksanaan ujian', NOW(), NOW()),
(5, 'Pemalsuan Data', 'fas fa-file-signature', 'Laporan terkait pemalsuan dokumen atau data akademik', NOW(), NOW()),
(6, 'Lainnya', 'fas fa-ellipsis-h', 'Laporan pelanggaran atau kejadian lainnya', NOW(), NOW());

-- =============================================
-- INSERT DATA AWAL: Sample Users (Optional)
-- Password default untuk semua user: "password"
-- =============================================
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@example.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5iXgNBL3fOHPm', NULL, NOW(), NOW()),
(2, 'User Test 1', 'user1@example.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5iXgNBL3fOHPm', NULL, NOW(), NOW()),
(3, 'User Test 2', 'user2@example.com', NOW(), '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5iXgNBL3fOHPm', NULL, NOW(), NOW());

-- =============================================
-- INSERT DATA: Migration Records
-- =============================================
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2025_12_14_122703_create_reports_table', 1),
('2025_12_14_122813_create_categories_table', 1);

-- =============================================
-- SELESAI!
-- Database siap digunakan
-- =============================================
SELECT 'Database pelaporan_akademik berhasil dibuat!' as Status;
SELECT COUNT(*) as 'Total Admins' FROM `admins`;
SELECT COUNT(*) as 'Total Categories' FROM `categories`;
SELECT COUNT(*) as 'Total Users' FROM `users`;
SELECT COUNT(*) as 'Total Reports' FROM `reports`;

-- =============================================
-- LANGKAH SELANJUTNYA:
-- =============================================
-- 1. Tutup phpMyAdmin
-- 2. Setup file .env di project Laravel
-- 3. Jalankan: php artisan serve
-- 4. Akses: http://127.0.0.1:8000
-- 5. Login dengan akun Super Admin atau Admin di atas
-- =============================================
