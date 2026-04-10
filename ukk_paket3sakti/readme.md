# 📢 Sistem Pengaduan Sekolah (E-Report)

Sistem Pengaduan Sekolah adalah aplikasi berbasis web modern yang dirancang untuk memudahkan siswa dalam menyampaikan aspirasi, laporan, atau keluhan terkait lingkungan sekolah. Aplikasi ini memastikan setiap laporan terdokumentasi dengan baik dan dapat ditindaklanjuti oleh petugas sekolah secara transparan.

---

## ✨ Fitur Utama

* **Sistem Login Multi-Role**: Akses yang dibedakan antara Admin, Petugas, dan Siswa.
* **Glassmorphism UI**: Antarmuka pengguna yang elegan, bersih, dan sepenuhnya responsif untuk perangkat mobile maupun desktop.
* **Manajemen Laporan**: 
    * **Siswa**: Mengirim laporan dengan lampiran foto dan memantau status secara real-time.
    * **Petugas**: Memvalidasi dan mengubah status laporan (Menunggu -> Proses -> Selesai).
    * **Admin**: Mengelola data pengguna (User Management) dan memantau seluruh aktivitas sistem.
* **Notifikasi & Feedback**: Siswa dapat melihat tanggapan langsung dari pihak sekolah melalui dashboard mereka.

---

## 🛠️ Teknologi yang Digunakan

* **Backend**: PHP 8 (Native)
* **Database**: MySQL / MariaDB
* **Frontend**: CSS3 (Glassmorphism Technique), HTML5
* **Icons & Fonts**: Font Awesome 6, Plus Jakarta Sans (Google Fonts)

---

## 🚀 Panduan Instalasi

1.  **Persiapan Folder**:
    Letakkan folder proyek Anda di direktori web server (contoh: `C:/xampp/htdocs/pengaduan_sekolah`).

2.  **Konfigurasi Database**:
    * Buka `phpMyAdmin`.
    * Buat database baru dengan nama `db_pengaduan_sekolah`.
    * Import struktur tabel (file `.sql`) atau pastikan tabel `users`, `pengaduan`, dan `kategori` sudah tersedia.

3.  **Pengaturan Koneksi**:
    Buka file `config/koneksi.php` dan sesuaikan kredensial database Anda:
    ```php
    $conn = mysqli_connect("localhost", "root", "", "db_pengaduan_sekolah");
    ```

4.  **Menjalankan Aplikasi**:
    Buka browser dan akses URL: `http://localhost/pengaduan_sekolah`

---

## 📂 Struktur Direktori

```text
├── admin/            # Halaman Dashboard Admin
├── petugas/          # Halaman Dashboard Petugas
├── siswa/            # Halaman Dashboard Siswa
├── auth/             # Proses Login & Logout
├── config/           # Koneksi Database
├── upload/           # Penyimpanan foto bukti laporan
└── assets/           # File pendukung (CSS, Icons, Images)

🔐 Akun Akses DefaultPeran (Role)UsernamePasswordAdminadminadmin123Petugaspetugaspetugas123Siswa(Gunakan menu Daftar)-