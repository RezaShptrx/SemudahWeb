<div align="center">
  <img src="https://github.com/RezaShptrx/SemudahWeb/blob/main/public/SEMUDAH-LOGO-3.png'"/>
  
  # 🚀 SEMUDAH (Sistem E-Pemesanan Mudah)
  
  [![Laravel Version](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
  [![React Version](https://img.shields.io/badge/React-19.x-61DAFB?style=for-the-badge&logo=react)](https://react.dev)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
  [![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

  <p align="center">
    <b>Unit Produksi Unggulan SMK Negeri 12 Jakarta Utara</b><br/>
    Platform Hybrid Pintar untuk Pemesanan Percetakan, Konveksi, Merchandise Custom, dan Solusi Digital Berbasis Online.
  </p>

  <p align="center">
    <a href="#-fitur-utama">Fitur Utama</a> •
    <a href="#-arsitektur-sistem">Arsitektur</a> •
    <a href="#-tech-stack">Tech Stack</a> •
    <a href="#-panduan-instalasi">Instalasi</a> •
    <a href="#-struktur-direktori">Struktur Folder</a>
  </p>
</div>

---

## 🌐 Gambaran Umum

**SEMUDAH** adalah solusi digital modern yang menggabungkan performa **Full-Stack PHP** dengan fleksibilitas **Single Page Application (SPA)**. Aplikasi ini dirancang khusus untuk mendigitalisasi alur kerja Unit Produksi sekolah, memungkinkan pelanggan memesan produk custom secara mandiri, melacak pesanan, hingga melakukan pembayaran otomatis tanpa harus mengantre secara fisik.

---

## ✨ Fitur Utama

### 🛒 Sisi Pelanggan (Public Portal)
* **Katalog Interaktif**: Antarmuka modern yang menampilkan seluruh produk siap cetak/custom dengan filter kategori.
* **Dynamic Form Rendering**: Formulir pemesanan beradaptasi secara dinamis menyesuaikan skema data spesifik produk.
* **Pintar Deteksi Dokumen**: Secara otomatis menghitung total halaman dari berkas `PDF`, `DOCX`, dan `PPTX` yang diunggah untuk kalkulasi biaya instan.
* **Live Tracking & Digital Invoice**: Pantau progresi status pengerjaan pesanan secara real-time cukup menggunakan Nomor Pesanan atau No. WhatsApp.

### 🔒 Sisi Manajemen (Admin & Petugas Dashboard)
* **React SPA Powered**: Panel kontrol super responsif yang dibangun dengan React 19, Axios, dan Tailwind CSS v4.
* **Visual Form Builder**: Fitur internal untuk menyusun, mengubah, dan mempublikasikan formulir produk kustom secara visual (Drag-and-Drop JSON Generator).
* **Manajemen Harga & Opsi Fleksibel**: Pengaturan biaya terperinci berdasarkan jenis warna cetak, ukuran kertas (A4, F4, A3), hingga modifier harga atribut merchandise.
* **Sistem Absensi Unit Produksi**: Terintegrasi log absensi harian untuk memonitor kehadiran siswa/petugas piket.
* **Keamanan Berlapis**: Proteksi endpoint API menggunakan token berbasis **JWT (JSON Web Token)** serta otorisasi berbasis peran memanfaatkan Spatie Permission.
* **Manajemen Berkas Otomatis**: Berkas dokumen pesanan yang diunggah oleh pelanggan otomatis dihapus secara permanen dari server sesaat setelah status pesanan diubah menjadi **SELESAI** demi efisiensi kapasitas penyimpanan.

---

## 📐 Arsitektur Sistem

Aplikasi ini mengimplementasikan konsep arsitektur **Hybrid Monolith**:

```text
                           ┌──────────────────────────────┐
                           │      SEMUDAH APPLICATION     │
                           └──────────────┬───────────────┘
                                          │
                  ┌───────────────────────┴───────────────────────┐
                  ▼                                               ▼
      [ CUSTOMER INTERFACE ]                            [ ADMIN DASHBOARD ]
  ┌──────────────────────────────┐                ┌──────────────────────────────┐
  │ Framework : Laravel Blade    │                │ Framework : React 19 (SPA)   │
  │ Reactive  : Livewire 3       │                │ Routing   : React Router v6  │
  │ JS Assets : Alpine.js        │                │ Charts    : Recharts         │
  │ Routing   : routes/web.php   │                │ Auth      : JWT via API      │
  │ Views     : resources/views  │                │ Routing   : routes/api.php   │
  └──────────────────────────────┘                └──────────────────────────────┘
```

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
| :--- | :--- |
| **Core Backend** | Laravel 13 & PHP 8.3 |
| **Database** | SQLite / MySQL |
| **Admin Frontend** | React 19, Vite, Tailwind CSS v4 |
| **Customer Frontend** | Laravel Blade, Livewire 3, Alpine.js |
| **API Securing** | JWT Authentication (`tymon/jwt-auth`) |
| **Role Management** | Spatie Laravel Permission |
| **Media Handler** | Spatie Media Library |

---

## 🚀 Panduan Instalasi

Ikuti instruksi berikut untuk memasang lingkungan pengembangan lokal:

### 1. Kloning Repositori & Dependensi Backend
```bash
git clone https://github.com/username/laravel-semudah.git
cd laravel-semudah
composer install
```

### 2. Environment Setup
Salin file konfigurasi lingkungan lingkungan dan generate application key:
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```
*Sesuaikan konfigurasi `.env` Anda, terutama pada bagian `DB_CONNECTION` dan API key Midtrans jika diperlukan.*

### 3. Database & Seeding
Jalankan migrasi tabel beserta data awal bawaan (seeds):
```bash
php artisan migrate:fresh --seed
```

### 4. Dependensi Frontend & Compiling
Pasang paket Node modul dan jalankan build aset via Vite:
```bash
npm install
npm run build
```

### 5. Menjalankan Aplikasi
Aktifkan server lokal Laravel dan hot-reload server Vite:
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```
Aplikasi Anda siap diakses melalui browser pada alamat `http://localhost:8000`.

---

## 📂 Struktur Direktori Utama

```text
laravel-semudah/
├── app/
│   ├── Enums/            # Enumerasi status order, payment, & method
│   ├── Http/Controllers/ # Endpoint API Admin & Controller Web Publik
│   ├── Livewire/Forms/   # Komponen formulir dinamis & statis (jasa-print, dll)
│   └── Services/         # Kelas logika bisnis (Midtrans, Page Calculator, dll)
├── database/             # Berkas skema Migrasi & Seeders sistem
├── resources/
│   ├── css/app.css       # Konfigurasi inti Tailwind CSS v4 & Dark Mode
│   ├── js/               # Seluruh source-code React SPA Admin Panel
│   └── views/            # Template Blade & Livewire untuk portal publik
└── routes/
    ├── api.php           # Routing API terproteksi JWT untuk panel admin
    └── web.php           # Routing web publik customer & catch-all admin route
```

---

## 🌗 Tema Aplikasi

Sistem mendukung deteksi preferensi tema gelap/terang (**Dark/Light Mode**) menggunakan mekanisme class-based utility dari Tailwind CSS v4.
* **Admin**: Pengaturan disimpan di `localStorage` dan dikontrol langsung via toggle ikon di header komponen `AdminLayout.jsx`.
* **Customer**: Dihandle secara reaktif menggunakan partials blade component berbasis Alpine.js.

---

## 👨‍💻 Kontributor & Pengembang

* **Reza Aditya Shaputra** - *Lead Developer / Full-stack Engineer* - [GitHub Profile](https://github.com/RezaShptrx)
* **Status Proyek**: Unit Produksi - SMK Negeri 12 Jakarta Utara (Jurusan Rekayasa Perangkat Lunak / RPL)

---
<div align="center">
  <sub>Terakhir diperbarui pada Juli 2026. Hak Cipta dilindungi Undang-Undang.</sub>
</div>
