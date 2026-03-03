# 📱 Integrasi Mobile App dengan Admin Panel

## 🔗 Base URL API

```
Development: http://localhost:8000/api/v1
Production: https://your-domain.com/api/v1

# Untuk Android Emulator:
http://10.0.2.2:8000/api/v1
```

---

## 📋 API Endpoints

### 1. **Autentikasi User**

#### POST `/user/auth`
Membuat atau mengambil data user (untuk Google Sign-In)

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "google_id": "123456789" // optional
}
```

**Response Success:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-12-14T10:00:00.000000Z",
    "updated_at": "2025-12-14T10:00:00.000000Z"
  }
}
```

---

### 2. **Ambil Semua Kategori**

#### GET `/categories`

**Response Success:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Kekerasan",
      "icon": "fas fa-fist-raised",
      "description": "Laporan terkait tindakan kekerasan fisik atau verbal",
      "created_at": "2025-12-14T10:00:00.000000Z",
      "updated_at": "2025-12-14T10:00:00.000000Z"
    }
  ]
}
```

---

### 3. **Buat Laporan Baru** ⭐ FITUR UTAMA

#### POST `/reports`

**Request (Multipart Form Data):**
```
user_id: 1
category_id: 2
title: "Kasus Bullying di Kelas"
description: "Terdapat aksi bullying yang dilakukan oleh..."
location: "Ruang Kelas 10A" // optional
media[]: file1.jpg // optional, multiple files
media[]: video1.mp4 // optional
```

**Response Success:**
```json
{
  "success": true,
  "message": "Report created successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "category_id": 2,
    "title": "Kasus Bullying di Kelas",
    "description": "Terdapat aksi bullying yang dilakukan oleh...",
    "location": "Ruang Kelas 10A",
    "media": ["storage/reports/file1.jpg", "storage/reports/video1.mp4"],
    "status": "Diproses",
    "is_verified": false,
    "verified_at": null,
    "verified_by": null,
    "rejection_reason": null,
    "admin_response": null,
    "responded_at": null,
    "category": {
      "id": 2,
      "name": "Bullying",
      "icon": "fas fa-user-slash"
    },
    "created_at": "2025-12-14T10:00:00.000000Z",
    "updated_at": "2025-12-14T10:00:00.000000Z"
  }
}
```

---

### 4. **Ambil Laporan User** ⭐ FITUR TRACKING

#### GET `/reports/user/{userId}`

**Response Success:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "category_id": 2,
      "category": {
        "id": 2,
        "name": "Bullying",
        "icon": "fas fa-user-slash"
      },
      "title": "Kasus Bullying di Kelas",
      "description": "Terdapat aksi bullying yang dilakukan oleh...",
      "location": "Ruang Kelas 10A",
      "media": ["storage/reports/file1.jpg"],
      "status": "Diproses",
      "is_verified": false,
      "verified_at": null,
      "verified_by": null,
      "rejection_reason": null,
      "admin_response": null,
      "responded_at": null,
      "created_at": "2025-12-14T10:00:00.000000Z",
      "updated_at": "2025-12-14T10:00:00.000000Z"
    }
  ]
}
```

---

### 5. **Detail Laporan** ⭐ STATUS VERIFIKASI

#### GET `/reports/{id}`

**Response Success:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "category_id": 2,
    "category": {
      "id": 2,
      "name": "Bullying",
      "icon": "fas fa-user-slash",
      "description": "Laporan terkait perundungan atau intimidasi"
    },
    "title": "Kasus Bullying di Kelas",
    "description": "Terdapat aksi bullying yang dilakukan oleh...",
    "location": "Ruang Kelas 10A",
    "media": ["storage/reports/file1.jpg"],
    "status": "Ditindaklanjuti",
    "is_verified": true,
    "verified_at": "2025-12-14T11:00:00.000000Z",
    "verified_by": "Admin",
    "rejection_reason": null,
    "admin_response": "Terima kasih atas laporannya. Kami sedang menangani kasus ini.",
    "responded_at": "2025-12-14T11:00:00.000000Z",
    "created_at": "2025-12-14T10:00:00.000000Z",
    "updated_at": "2025-12-14T11:00:00.000000Z"
  }
}
```

---

### 6. **Statistik Aplikasi**

#### GET `/statistics`

**Response Success:**
```json
{
  "success": true,
  "data": {
    "total_reports": 150,
    "by_status": [
      {
        "status": "Diproses",
        "total": 45
      },
      {
        "status": "Ditindaklanjuti",
        "total": 65
      },
      {
        "status": "Selesai",
        "total": 40
      }
    ],
    "by_category": [
      {
        "category_id": 1,
        "total": 30,
        "category": {
          "id": 1,
          "name": "Kekerasan"
        }
      }
    ]
  }
}
```

---

## 🎯 Status Laporan & Verifikasi

### Status Laporan (field: `status`)
1. **Diproses** - Laporan baru, sedang ditinjau admin
2. **Ditindaklanjuti** - Admin sedang menangani kasus
3. **Selesai** - Kasus telah diselesaikan

### Status Verifikasi (field: `is_verified`)

#### ✅ Terverifikasi (`is_verified: true`)
```json
{
  "is_verified": true,
  "verified_at": "2025-12-14T11:00:00.000000Z",
  "verified_by": "Admin",
  "rejection_reason": null
}
```
**UI di Mobile:**
- Badge hijau: "✓ Terverifikasi"
- Laporan valid dan ditindaklanjuti

#### ⏳ Pending (`is_verified: false`, `rejection_reason: null`)
```json
{
  "is_verified": false,
  "verified_at": null,
  "verified_by": null,
  "rejection_reason": null
}
```
**UI di Mobile:**
- Badge kuning: "⏱ Menunggu Verifikasi"
- Laporan sedang ditinjau admin

#### ❌ Ditolak (`rejection_reason: not null`)
```json
{
  "is_verified": false,
  "verified_at": "2025-12-14T11:00:00.000000Z",
  "verified_by": "Admin",
  "rejection_reason": "Bukti tidak cukup valid untuk ditindaklanjuti"
}
```
**UI di Mobile:**
- Badge merah: "✗ Ditolak"
- Tampilkan alasan: `rejection_reason`

---

## 💻 Contoh Implementasi Flutter

### 1. Service untuk API

```dart
class ReportService {
  static const String baseUrl = 'http://10.0.2.2:8000/api/v1';
  
  // Buat laporan baru
  Future<Map<String, dynamic>> createReport({
    required int userId,
    required int categoryId,
    required String title,
    required String description,
    String? location,
    List<File>? mediaFiles,
  }) async {
    var request = http.MultipartRequest(
      'POST',
      Uri.parse('$baseUrl/reports'),
    );
    
    request.fields['user_id'] = userId.toString();
    request.fields['category_id'] = categoryId.toString();
    request.fields['title'] = title;
    request.fields['description'] = description;
    if (location != null) request.fields['location'] = location;
    
    // Upload media files
    if (mediaFiles != null) {
      for (var file in mediaFiles) {
        request.files.add(
          await http.MultipartFile.fromPath('media[]', file.path)
        );
      }
    }
    
    var response = await request.send();
    var responseData = await response.stream.bytesToString();
    return json.decode(responseData);
  }
  
  // Ambil laporan user
  Future<List<dynamic>> getUserReports(int userId) async {
    final response = await http.get(
      Uri.parse('$baseUrl/reports/user/$userId'),
    );
    
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data['data'];
    }
    throw Exception('Gagal mengambil data laporan');
  }
  
  // Detail laporan
  Future<Map<String, dynamic>> getReportDetail(int reportId) async {
    final response = await http.get(
      Uri.parse('$baseUrl/reports/$reportId'),
    );
    
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data['data'];
    }
    throw Exception('Gagal mengambil detail laporan');
  }
}
```

### 2. Widget Status Verifikasi

```dart
Widget buildVerificationBadge(Map<String, dynamic> report) {
  if (report['is_verified'] == true) {
    return Container(
      padding: EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: Colors.green.shade100,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(Icons.check_circle, color: Colors.green, size: 16),
          SizedBox(width: 4),
          Text('Terverifikasi', style: TextStyle(color: Colors.green)),
        ],
      ),
    );
  } else if (report['rejection_reason'] != null) {
    return Container(
      padding: EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: Colors.red.shade100,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(Icons.cancel, color: Colors.red, size: 16),
          SizedBox(width: 4),
          Text('Ditolak', style: TextStyle(color: Colors.red)),
        ],
      ),
    );
  } else {
    return Container(
      padding: EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: Colors.orange.shade100,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(Icons.schedule, color: Colors.orange, size: 16),
          SizedBox(width: 4),
          Text('Pending', style: TextStyle(color: Colors.orange)),
        ],
      ),
    );
  }
}
```

---

## 🔄 Flow Lengkap User → Admin

1. **User membuat laporan** di mobile app
   - Upload foto/video bukti
   - Pilih kategori pelanggaran
   - Status awal: "Diproses", `is_verified: false`

2. **Admin menerima notifikasi** di dashboard
   - Muncul di "Menunggu Verifikasi"
   - Dapat melihat detail lengkap

3. **Admin melakukan verifikasi**:
   
   **Opsi A: Verifikasi ✅**
   - Klik "Verifikasi Laporan"
   - `is_verified: true`
   - User dapat melihat status "Terverifikasi"
   
   **Opsi B: Tolak ❌**
   - Klik "Tolak Laporan"
   - Input alasan penolakan
   - `rejection_reason` terisi
   - User dapat melihat alasan penolakan

4. **Admin update status & beri tanggapan**
   - Ubah status: Diproses → Ditindaklanjuti → Selesai
   - Berikan tanggapan admin
   - User menerima update real-time

5. **User tracking laporan**
   - Lihat history semua laporan
   - Filter by status & verifikasi
   - Baca tanggapan admin

---

## 🎨 Tampilan di Mobile App

### Card Laporan
```
┌─────────────────────────────────────┐
│ 🔴 Kekerasan                        │
│ Kasus Bullying di Kelas            │
│                                     │
│ ✅ Terverifikasi                   │
│ 📊 Ditindaklanjuti                 │
│ 📅 14 Des 2025, 10:00              │
│                                     │
│ 💬 Tanggapan Admin:                │
│ "Kami sedang menangani kasus ini"  │
└─────────────────────────────────────┘
```

---

## 🔐 Keamanan

- Validasi input di backend
- File upload max 10MB
- Format file: JPG, PNG, MP4, MOV
- Rate limiting untuk prevent spam
- CORS enabled untuk Flutter app

---

## 📊 Testing API

### Menggunakan Postman/Thunder Client:

1. **Test Create Report:**
```
POST http://localhost:8000/api/v1/reports
Body (form-data):
- user_id: 1
- category_id: 2
- title: Test Laporan
- description: Ini adalah test
- media[]: [upload file]
```

2. **Test Get User Reports:**
```
GET http://localhost:8000/api/v1/reports/user/1
```

3. **Test Get Categories:**
```
GET http://localhost:8000/api/v1/categories
```

---

**✅ API sudah terintegrasi penuh dengan sistem verifikasi admin!**
