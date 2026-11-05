# Analisis Kebutuhan Sistem Pendaftaran Pelatihan

## Latar belakang

Lembaga pelatihan membutuhkan sistem daring agar peserta dapat mendaftar sendiri, admin dapat mengelola data pendaftar, dan peserta dapat memperoleh bukti pendaftaran (PDF).

## Pengguna

| Peran   | Deskripsi                                     | Hak akses                                                                               |
| ------- | --------------------------------------------- | --------------------------------------------------------------------------------------- |
| Peserta | Calon peserta pelatihan (publik, tanpa login) | Mengisi form pendaftaran, mencetak bukti pendaftaran                                    |
| Admin   | Petugas lembaga                               | Login, melihat/mencari daftar pendaftar, mengubah, menghapus, mengelola kelas dan kuota |

## Fitur wajib

1. Form pendaftaran dengan validasi server (nama, email unik, no. HP, kelas).
2. Daftar pendaftar untuk admin dengan pencarian nama dan paginasi (10 per halaman).
3. Ubah dan hapus data pendaftar (POST + token CSRF).
4. Cetak bukti pendaftaran PDF (Hari 10).
5. Login admin (Hari 11) dan pembatasan kuota kelas (Hari 11).

## Fitur tambahan

- Antarmuka Bootstrap, DataTables, dan SweetAlert2 (Hari 10).
- Kelola data kelas oleh admin.
- Laporan bug dan pengujian (Hari 11).

## Batasan

- Teknologi: PHP 8 (PDO), MySQL/MariaDB (InnoDB, utf8mb4), dijalankan di XAMPP/Apache.
- Tanpa framework; struktur `config/`, `src/`, `public/`, `templates/`, `database/`.
- Hanya satu peran admin; pembayaran dan notifikasi email di luar cakupan.
- Halaman `injeksi.php` hanya untuk latihan, terkunci ke localhost dan mode debug.

## Kebutuhan non-fungsional

- Keamanan: prepared statement, `htmlspecialchars`, token CSRF, `password_hash`, kredensial di luar repositori.
- Penanganan error: pesan umum ke pengguna, detail dicatat di `error_log`.
- Kode mengikuti PSR-12 dan `declare(strict_types=1)`.

## Definition of Done Sprint 0

CRUD berjalan, tidak ada Warning/Notice di log, semua query berparameter, output di-escape, `config.php` tidak ter-commit, `skema.sql` dapat diimpor dari nol.
