# Changelog

Format mengikuti [Keep a Changelog](https://keepachangelog.com/id-ID/1.1.0/) dan [Semantic Versioning](https://semver.org/lang/id/).

## [1.0.0] - 2026-11-04

### Ditambahkan

- CRUD **kelas** (`kelas.php`, `kelas-form.php`, `kelas-hapus.php`): nama unik, kuota 1–1000, kuota tidak boleh di bawah jumlah pendaftar, hapus ditolak bila kelas berisi pendaftar.
- **Filter kelas** pada tabel pendaftar (DataTables) dan peringatan "kelas hampir penuh" di dashboard.
- Struktur data: hash map `petaKelasById()` dan daftar terurut `kelasHampirPenuh()` (lihat `docs/arsitektur.md`).
- Validasi sisi klien `public/js/validasi-form.js` (aturan sama dengan server).
- Log aplikasi `storage/log/app.log` (login gagal, kuota penuh, CSRF ditolak, exception, penghapusan).
- `database/skema.sql` lengkap (tabel `admin`, UNIQUE nama kelas, indeks `kelas_id`) dan `database/data-contoh.sql`.
- Uji otomatis: `tests/jalankan.php` (50 uji: fungsi murni + integrasi database uji) dan `tests/uji-http.sh` (43 uji HTTP).
- DocBlock seluruh fungsi publik, dokumen modul, dokumentasi API otomatis (phpDocumentor + JSDoc), README, panduan pengguna, arsitektur.
- `LICENSE` (MIT), `composer.json` dengan batas versi (`^3.1`, `^1.8`) dan `composer.lock`.

### Diubah

- `ubah.php` memeriksa kuota kelas tujuan **di dalam transaksi** (`ubahDenganKuota`) sebelumnya cek tanpa kunci.
- `getConnection()` memakai satu koneksi per permintaan (static).
- `config.contoh.php`: `debug` default `false`; kunci `admin_password_hash` yang tak terpakai dihapus.
- Exception handler menulis ke `storage/log/app.log` (bukan `error_log` bawaan PHP).
- Helper `ambilIdPositif()` menggantikan `filter_var` berulang; nomor bukti lewat `formatNomorBukti()`.

### Dihapus

- `public/injeksi.php` (halaman demo rentan SQL injection dari Hari 11).
- `public/js/daftar-pendaftar.js` (berkas sisa, tidak dimuat).
- `cekLoginAdmin()` (pembungkus mati yang menjadi sumber BUG-07).

### Diperbaiki

- `skema.sql` Hari 11: tabel `admin` tidak ada dan titik koma akhir hilang → instalasi dari nol gagal.

## [0.3.0] - 2026-11-02 (Hari 11)

### Ditambahkan

- `daftarkanDenganKuota()` + `KuotaPenuhException` (transaksi, `SELECT … FOR UPDATE`), login admin dengan penguncian 15 menit, `daftar.php` publik.

### Diperbaiki

- BUG-01 s.d. BUG-12 (lihat `docs/laporan-bug.md`).

## [0.2.0] - Hari 10

### Ditambahkan

- Antarmuka Bootstrap, DataTables, SweetAlert2; bukti PDF (Dompdf dan FPDF).

## [0.1.0] - Hari 9

### Ditambahkan

- CRUD pendaftar dan validasi dasar.
