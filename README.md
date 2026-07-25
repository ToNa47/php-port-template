# PHP Port Template

Template dasar aplikasi web PHP dengan sistem routing sederhana, koneksi database MySQL, dan pengiriman email via PHPMailer. Cocok dijadikan starting point untuk membangun dashboard, profil pengguna, atau sistem tiket/ticketing sederhana.

## ✨ Fitur

- **Routing sederhana** berbasis parameter `?page=` — hanya halaman yang benar-benar ada di folder `pages/` yang bisa diakses, sisanya otomatis diarahkan ke halaman 404.
- **Halaman bawaan**: `home`, `dashboard`, `profile`, `ticket`, `projects`.
- **Koneksi database MySQL (mysqli)** dengan kredensial diambil dari environment variable, jadi tidak ada data sensitif yang ikut ter-commit ke repo.
- **Error handling aman** — jika koneksi database gagal, detail error dicatat ke log server, sementara pengguna hanya melihat pesan generik.
- **Pengiriman email** menggunakan [PHPMailer](https://github.com/PHPMailer/PHPMailer) (terpasang lewat Composer).
- **Header keamanan dasar** (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`) sudah diset di `index.php`.
- **Background canvas partikel** (`assets/particles.js`) yang otomatis tampil di halaman-halaman tertentu (`home`, `dashboard`, `profile`, `projects`, `404`).
- **Folder cache** untuk kebutuhan penyimpanan sementara, dan `tickets.log` untuk pencatatan aktivitas tiket.

## 📁 Struktur Proyek

```
php-port-template/
├── assets/           # File statis (JS partikel, gambar, dll.)
├── cache/            # Direktori cache aplikasi
├── includes/         # File pendukung/helper yang dipakai bersama
├── pages/            # Halaman-halaman aplikasi (home, dashboard, profile, ticket, projects, 404)
├── vendor/           # Dependency Composer (PHPMailer, dll.)
├── composer.json     # Daftar dependency PHP
├── composer.lock
├── config.php        # Koneksi database (mysqli), baca kredensial dari environment variable
├── index.php         # Entry point & router aplikasi
├── style.css          # Stylesheet utama
└── tickets.log        # Log aktivitas tiket
```

## 🔧 Prasyarat

- PHP 8.0 atau lebih baru (menggunakan `declare(strict_types=1)`)
- Ekstensi `mysqli` aktif
- MySQL/MariaDB
- [Composer](https://getcomposer.org/)

## 🚀 Instalasi

1. Clone repository ini:
   ```bash
   git clone https://github.com/ToNa47/php-port-template.git
   cd php-port-template
   ```

2. Install dependency PHP:
   ```bash
   composer install
   ```

3. Siapkan environment variable untuk koneksi database. Buat file `.env` (atau atur langsung di konfigurasi web server) dengan nilai berikut:
   ```
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=my
   ```
   Jika environment variable tidak diset, aplikasi akan memakai nilai fallback untuk pengembangan lokal (`localhost` / `root` / password kosong / database `my`).

4. Buat database sesuai nama pada `DB_NAME`, lalu import skema/tabel yang dibutuhkan (sesuaikan dengan struktur tabel yang dipakai di masing-masing halaman/`includes`).

5. Jalankan dengan PHP built-in server untuk pengujian lokal:
   ```bash
   php -S localhost:8000
   ```
   Lalu buka `http://localhost:8000` di browser.

## 🖱️ Cara Kerja Routing

Aplikasi ini memakai satu entry point (`index.php`) yang membaca parameter `page` dari URL, misalnya:

```
http://localhost:8000/?page=dashboard
http://localhost:8000/?page=profile
http://localhost:8000/?page=ticket
```

Hanya nama halaman yang terdaftar dalam whitelist di `index.php` (`home`, `dashboard`, `profile`, `ticket`, `projects`) yang akan dirender. Selain itu, atau jika file halaman tidak ditemukan di folder `pages/`, pengguna akan diarahkan ke halaman `404`.

## ✉️ Konfigurasi Email (PHPMailer)

Proyek ini sudah menyertakan dependency `phpmailer/phpmailer` untuk kebutuhan pengiriman email (misalnya notifikasi tiket baru). Sesuaikan kredensial SMTP pada bagian kode yang memanggil PHPMailer (biasanya di dalam folder `includes/`) sebelum digunakan di production.

## 🔒 Catatan Keamanan

- Jangan pernah menaruh kredensial database asli langsung di `config.php` — gunakan environment variable.
- Header keamanan dasar sudah aktif di `index.php`, tapi tetap sesuaikan lagi (misalnya CSP) sesuai kebutuhan production.
- Pastikan folder `cache/` dan `tickets.log` tidak bisa diakses/dieksekusi langsung dari luar bila di-deploy ke server publik.

## 🤝 Kontribusi

Pull request dan saran perbaikan sangat terbuka. Silakan buka issue terlebih dahulu untuk perubahan besar agar bisa didiskusikan.

## 📄 Lisensi

Belum ada lisensi spesifik yang ditentukan untuk proyek ini. Tambahkan file `LICENSE` sesuai kebutuhan sebelum digunakan secara publik/komersial.
