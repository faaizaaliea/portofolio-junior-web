# Sistem Pendaftaran Pelatihan

Aplikasi web pendaftaran peserta pelatihan (PHP 8, MariaDB/MySQL, Bootstrap). Capstone Junior Web Programmer — **v1.0.0**.

## Fitur
- Form pendaftaran publik dengan validasi sisi klien dan server
- Panel admin (login, sesi, CSRF): daftar pendaftar (DataTables + filter kelas), tambah, ubah, hapus, cetak bukti PDF
- CRUD kelas dan kuota; pendaftaran/pindah kelas aman dari balapan (transaksi + `SELECT … FOR UPDATE`)
- Log aplikasi, uji otomatis, dokumentasi API otomatis

## Tampilan
| Form pendaftaran | Panel admin | Bukti PDF |
| --- | --- | --- |
| ![Form](docs/gambar/01-form-pendaftaran.png) | ![Admin](docs/gambar/06-panel-admin.png) | ![PDF](docs/gambar/13-bukti-pdf.png) |

> Tangkapan layar di atas diambil pada Hari 11; halaman Kelas dan filter kelas (baru di v1.0.0) belum ada gambarnya.

## Teknologi dan Library
PHP 8.1+, MariaDB/MySQL (InnoDB), Bootstrap 5.3.8 (MIT), DataTables 1.13.11 (MIT), jQuery 3.7.1 (MIT), SweetAlert2 11.26.25 (MIT), Dompdf v3.1.6 (LGPL-2.1), FPDF 1.9.0 (bebas pakai). Rincian: `docs/daftar-library.md`.

## Persyaratan
XAMPP/Laragon dengan PHP 8.1+, MySQL/MariaDB, Composer. Browser perlu internet (Bootstrap, DataTables, jQuery, SweetAlert2 dimuat dari CDN).

## Instalasi
1. Salin proyek ke `htdocs/pendaftaran` (atau `git clone` ke sana).
2. Impor skema: `mysql -u root < database/skema.sql` (atau impor lewat phpMyAdmin). Opsional data contoh: `database/data-contoh.sql`.
3. Salin `config/config.contoh.php` menjadi `config/config.php`, isi kredensial database.
4. Di folder proyek jalankan `composer install`.
5. Buat admin: `php database/buat-admin.php admin SandiKuat123` (sandi minimal 8 karakter).
6. Buka `http://localhost/pendaftaran/public/daftar.php` (publik) dan `http://localhost/pendaftaran/public/login.php` (admin).

Folder `storage/log/` harus dapat ditulis oleh server web (default XAMPP sudah bisa).

## Penggunaan singkat
Peserta mengisi `daftar.php`. Admin login di `login.php`, lalu mengelola pendaftar (`index.php`) dan kelas (`kelas.php`). Langkah lengkap: `docs/panduan-pengguna.md`.

## Struktur Folder
```
config/     konfigurasi (config.contoh.php) dan koneksi PDO
src/        logika: validasi, kelas, pendaftar, kuota, auth, helpers, log, bootstrap
public/     halaman web (document root) + js/
templates/  header, footer, bukti PDF
database/   skema.sql, data-contoh.sql, buat-admin.php
storage/    log/ (app.log)
tests/      jalankan.php (unit+integrasi), uji-http.sh (uji HTTP)
docs/       dokumentasi (lihat bawah)
```

## Pengujian
```
php tests/jalankan.php                          # 50 uji; memakai database uji db_pelatihan_uji (dibuat & dihapus otomatis)
BASE=http://localhost/pendaftaran/public ADMIN_USER=admin ADMIN_PASS=... bash tests/uji-http.sh   # 43 uji HTTP, membuat data uji
```
Skenario manual lengkap: `docs/kasus-uji.md`.

## Dokumentasi
- API otomatis: `docs/api/index.html` (phpDocumentor) dan `docs/api-js/index.html` (JSDoc)
- Peta modul: `docs/peta-modul.md`; dokumen modul: `docs/modul/`
- Arsitektur, ERD, struktur data: `docs/arsitektur.md`
- Panduan pengguna: `docs/panduan-pengguna.md`
- Kasus uji / laporan bug: `docs/kasus-uji.md`, `docs/laporan-bug.md`
- Library: `docs/daftar-library.md`; Doc Hunt: `docs/doc-hunt.md`; rilis: `docs/checklist-rilis.md`

Membuat ulang dokumentasi API: `php phpDocumentor.phar -d src -d config/database.php -t docs/api` dan `npx jsdoc public/js -r -d docs/api-js` (`phpDocumentor.phar` jangan di-commit).

## Keterbatasan yang diketahui
- Penguncian login berbasis sesi (latihan); produksi butuh pembatasan per IP/akun.
- Bukti PDF hanya dapat dicetak admin; peserta tidak mendapat unduhan otomatis.
- Pencarian DataTables berjalan di sisi klien (seluruh data dimuat) — cukup untuk ratusan baris, bukan puluhan ribu.

## Lisensi dan Penulis
Faaiza — lisensi MIT (`LICENSE`).
