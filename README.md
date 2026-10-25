# kosKu — Platform Pencarian Kos Berbasis Peta

> **Tagline:** *Cari kos, langsung dari peta.*

**kosKu** adalah platform website pencarian kos interaktif berbasis peta digital spasial. Pengguna dapat memilih wilayah, melihat sebaran kos di peta secara visual dan akurat, menerapkan filter rentang harga dan fasilitas, melihat detail kamar serta langsung menghubungi pemilik kos melalui WhatsApp dengan pesan otomatis terformat.

Proyek ini dibangun berdasarkan dokumen **Product Requirement Document (PRD) kosKu v1.0** dengan pendekatan desain *Monochrome Glassmorphic* (sudut membulat 20–28px, palet hitam pekat, putih, dan abu-abu lembut, serta peta OpenStreetMap berfilter arsitektural monokrom).

---

## 🌟 Fitur Utama

### 🧭 Untuk Pencari Kos (Publik)
- **Peta Interaktif (OpenStreetMap & Leaflet.js):** Peta dengan marker kustom monokrom (tipe Putra, Putri, Campur), zoom dinamis, dan sinkronisasi kartu.
- **Pemilihan Wilayah (GeoJSON Boundary):** Dropdown & pencarian wilayah dengan auto-zoom ke batas poligon spasial wilayah.
- **Filter & Pengurutan Cepat:**
  - Chip tipe kos (Semua, Putri, Putra, Campur).
  - Modal filter rentang harga (Min - Max) & ketersediaan kamar.
  - Checkbox fasilitas lengkap (WiFi, AC, Kamar Mandi Dalam, Kasur, Parkir Motor/Mobil, dll.).
  - Pengurutan harga (Termurah, Termahal, Terbaru, Kamar Terbanyak).
- **Cari Sekitar Saya (Browser Geolocation):** Menggunakan GPS peramban untuk mengurutkan kos terdekat dari lokasi pencari kos saat ini.
- **Detail Kos Komprehensif:**
  - Galeri foto interaktif & carousel pratinjau.
  - Alamat lengkap & mini map lokasi koordinat.
  - Status ketersediaan kamar secara *real-time*.
  - Rincian fasilitas (kamar & umum) serta tata tertib kos.
  - **Tombol WhatsApp otomatis:** Mengirimkan pesan pembuka langsung ke pemilik kos.
  - **Tombol Petunjuk Arah:** Langsung membuka rute navigasi di Google Maps.
- **Simpan Favorit (Local Storage):** Menandai kos favorit tanpa perlu login.
- **Bandingkan Kos (Side-by-Side Comparison):** Membandingkan 2–3 kos secara berdampingan (harga, fasilitas, tipe, kontak).

### ⚙️ Untuk Admin Pengelola
- **Autentikasi Admin:** Login aman dengan hashing password bcrypt dan sesi terlindungi filter.
- **Dashboard Eksekutif:**
  - Kartu KPI: Total kos, kos tersedia, jumlah wilayah.
  - Indikator Okupansi Kamar (Progress Ring SVG).
  - Grafik tren kunjungan 7 hari terakhir (Monochrome Chart.js).
  - Tabel kos terbaru & aksi cepat.
- **CRUD Kos Lengkap dengan Map Picker:**
  - Tambah, ubah, dan hapus data kos.
  - **Interactive Coordinate Picker:** Menentukan titik koordinat presisi langsung dengan klik atau menggeser (*drag*) pin marker pada peta.
  - Penanda kos unggulan (*featured*).
  - Pengaturan status publikasi (Aktif, Draft, Nonaktif).
- **Manajemen Galeri Foto:**
  - Unggah banyak foto sekaligus per kos.
  - Pengaturan foto sampul utama (*main photo*).
  - Penghapusan foto secara aman dari server.
- **Manajemen Wilayah & GeoJSON (GIS):**
  - CRUD wilayah, tipe, titik pusat, default zoom.
  - Input batas poligon GeoJSON untuk auto-fit wilayah.
- **Master Data Fasilitas:** CRUD fasilitas kamar & umum beserta ikon Bootstrap Icons.
- **Statistik & Analisis Konversi:**
  - Pelacakan jumlah penayangan (*views*).
  - Pelacakan klik tombol kontak WhatsApp (*conversion rate*).
  - Pelacakan klik navigasi rute Google Maps.

---

## 🛠️ Tech Stack

| Lapisan | Teknologi |
|---|---|
| **Backend Framework** | CodeIgniter 4 (PHP 8.1+) |
| **Database** | PostgreSQL 18 |
| **GIS & Peta** | Leaflet.js & OpenStreetMap (Tile Grayscale Filter) |
| **Frontend UI** | Bootstrap 5.3, Bootstrap Icons, Custom CSS Glassmorphism |
| **Tipografi** | Plus Jakarta Sans (Google Fonts) |
| **Grafik & Visualisasi** | Chart.js 4.4 & SVG Vector Rings |
| **Version Control** | Git & GitHub |

---

## 🚀 Panduan Instalasi Lokal

### 1. Prasyarat Sistem
- PHP 8.1 atau yang lebih baru (ekstensi `intl`, `pdo_pgsql`, `pgsql` aktif).
- PostgreSQL Server 14+ (sudah berjalan pada port 5432).
- Composer 2.x.
- Git.

### 2. Konfigurasi Database
Buat database baru di PostgreSQL:
```sql
CREATE DATABASE kosku_db;
```

### 3. Konfigurasi Environment (`.env`)
Pastikan file `.env` sudah dikonfigurasi dengan kredensial PostgreSQL lokal Anda:
```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = kosku_db
database.default.username = postgres
database.default.password = admin123
database.default.DBDriver = Postgre
database.default.DBPrefix = 
database.default.port = 5432
database.default.schema = public
```

### 4. Instalasi Dependensi & Migrasi Database
Jalankan perintah berikut di terminal projek:
```bash
# 1. Install dependensi
composer install

# 2. Jalankan migrasi tabel database
php spark migrate

# 3. Jalankan seeder data awal (Admin, Fasilitas, Wilayah GeoJSON, Sample Kos)
php spark db:seed KosKuSeeder
```

### 5. Menjalankan Server Pengembangan
```bash
php spark serve
```
Akses di peramban: **[http://localhost:8080](http://localhost:8080)**

---

## 🔐 Kredensial Login Admin Bawaan

| Keterangan | Nilai |
|---|---|
| **URL Login** | `http://localhost:8080/admin/login` |
| **Email** | `admin@kosku.id` |
| **Password** | `admin123` |

---

## 📂 Struktur Direktori Utama

```
projek/
├── app/
│   ├── Config/          # Konfigurasi Database, Routes, Filters, App
│   ├── Controllers/
│   │   ├── Admin/       # Auth, Dashboard, Kos, Wilayah, Fasilitas, Statistik
│   │   ├── Api.php      # REST API Spasial & Pelacakan
│   │   └── Home.php     # Controller Halaman Publik
│   ├── Database/
│   │   ├── Migrations/  # Skema 7 Tabel Relasional Spasial
│   │   └── Seeds/       # Seeder kosKu (GeoJSON, Admin, Fasilitas, Kos)
│   ├── Filters/         # AuthFilter untuk Panel Admin
│   ├── Models/          # Model Kos, Wilayah, Fasilitas, Foto, Kunjungan
│   └── Views/
│       ├── layouts/     # Master Layout Publik & Admin
│       ├── public/      # Explorer (Peta & List), Detail, Favorit, Tentang, Kontak
│       └── admin/       # Dashboard, Kos, Wilayah, Fasilitas, Statistik, Login
├── public/
│   ├── assets/
│   │   ├── css/         # kosku.css (Design System Monochrome Glassmorphic)
│   │   └── js/          # kosku-map.js (Leaflet Engine, Filter & Sync)
│   └── uploads/kos/     # Direktori penyimpanan foto kos
├── spark                # CodeIgniter CLI tool
└── composer.json
```

---

## 📄 Lisensi
Hak Cipta © 2026 **kosKu** — Dikembangkan sesuai spesifikasi PRD kosKu v1.0.
