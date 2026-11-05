# Sistem Pendaftaran Pelatihan (Proyek 5 Capstone, Sprint 0)

CRUD pendaftar pelatihan dengan PHP (PDO) dan MySQL/MariaDB.

## Menjalankan

1. Jalankan Apache dan MySQL (XAMPP), taruh folder ini di `htdocs/`.
2. Impor `database/skema.sql`, lalu `database/seed.sql` lewat phpMyAdmin (opsional: `seed-paginasi.sql`).
3. Salin `config/config.contoh.php` menjadi `config/config.php`, sesuaikan kredensial, dan isi `admin_password_hash`:
   `php -r "echo password_hash('kata-sandi-anda', PASSWORD_DEFAULT);"`
4. Buka `http://localhost/proyek-5-capstone-pendaftaran/public/tambah.php` (publik) atau `login.php` (admin).

## Struktur

```
config/      konfigurasi (config.php tidak di-commit) dan koneksi PDO
src/         bootstrap, helpers (CSRF, flash, escape), akses data + validasi
public/      satu-satunya folder yang diakses browser
templates/   header dan footer
database/    skema.sql, seed.sql, latihan.sql
docs/        analisis, backlog, ERD, wireframe, bug hunt, code review
screenshot/  bukti praktik (lab1, lab2, lab3)
```

## Catatan keamanan

- Seluruh query berparameter; output di-escape dengan `e()`; hapus/ubah memakai POST + token CSRF.
- `public/injeksi.php` hanya demo latihan (terkunci ke localhost + debug). Jangan di-deploy.
- Jangan commit `config/config.php`.

## Rencana

Hari 10: Bootstrap, DataTables, SweetAlert2, PDF. Hari 11: login admin, kuota kelas, pengujian. Hari 12: dokumentasi dan finalisasi.
