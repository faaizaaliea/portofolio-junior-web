# Proyek 4 - Buku Tamu Berbasis File (PHP)

Buku tamu sederhana tanpa database. Pesan disimpan di `data/tamu.json`. Ada halaman admin untuk menghapus pesan dan mengekspor CSV.

## Struktur

```text
proyek-4-buku-tamu/
  index.php           # tampilan dan alur request: form, PRG, daftar, pencarian, paginasi
  admin.php           # login admin, hapus pesan, keluar
  ekspor.php          # unduh CSV (hanya admin)
  functions.php       # logika: validasi, JSON, pencarian, paginasi, batas kirim, sesi, CSRF, CSV
  config.php          # hash kata sandi admin (tidak di-commit, lihat config.example.php)
  config.example.php  # contoh isi config.php
  data/
    .htaccess         # Require all denied
    tamu.json         # dibuat otomatis saat pesan pertama tersimpan
    batas.json        # dibuat otomatis, catatan waktu kirim per IP (hash)
  README.md
```

## Menjalankan

1. Salin folder ke `htdocs/` (XAMPP) dan nyalakan Apache.
2. Salin `config.example.php` menjadi `config.php`, lalu isi `ADMIN_HASH` dengan hasil:

   ```bash
   php -r "echo password_hash('SANDI-ANDA', PASSWORD_DEFAULT), PHP_EOL;"
   ```

3. Buka `http://localhost/<folder>/proyek-4-buku-tamu/`. Admin ada di `admin.php`.
4. Pastikan ekstensi `mbstring` aktif (bawaan XAMPP).

Gunakan Apache, bukan `php -S`: server bawaan PHP mengabaikan `.htaccess`, sehingga `data/` tidak terlindungi.

## Fitur

- Validasi server dengan pesan per kolom; isian lama tetap tampil saat gagal.
- Penyimpanan JSON dengan `flock`; folder `data/` dan `.htaccess` dibuat otomatis. Berkas JSON rusak tidak ditimpa.
- Pesan terbaru di atas, pencarian nama lewat `?q=`, paginasi 5 pesan per halaman (`?hal=`).
- Batas kirim 1 pesan per 30 detik per IP (IP disimpan sebagai hash SHA-256).
- Pola Post/Redirect/Get setelah pesan tersimpan.
- Semua output di-escape dengan `htmlspecialchars`.
- Admin: login (`password_verify`), token CSRF, hapus pesan, ekspor CSV dengan perlindungan CSV injection.
- `data/` dilindungi `.htaccess`.

## Uji manual

| Kasus                               | Hasil yang diharapkan                                    |
| ----------------------------------- | -------------------------------------------------------- |
| Semua kolom kosong                  | Tiga pesan galat, isian tetap                            |
| Email salah                         | Galat di kolom email saja                                |
| Data valid                          | Redirect ke `index.php?status=sukses`, pesan paling atas |
| Refresh setelah sukses              | Tidak mengirim ulang (PRG)                               |
| Kirim lagi sebelum 30 detik         | "Tunggu N detik sebelum mengirim pesan lagi."            |
| Nama `<script>alert(1)</script>`    | Tampil sebagai teks                                      |
| Lebih dari 5 pesan                  | Muncul navigasi halaman                                  |
| Cari nama                           | Hanya pesan yang namanya cocok                           |
| Buka `/data/tamu.json` lewat Apache | 403 Forbidden                                            |
| Buka `ekspor.php` tanpa login       | 403 "Akses ditolak"                                      |
| Kirim form admin tanpa token CSRF   | 400 "Token tidak valid"                                  |
