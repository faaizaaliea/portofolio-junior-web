# Kasus Uji - Capstone Sprint 2

Form pendaftaran publik = daftar.php (kolom nama, email, HP, alamat, kelas); HP valid: diawali 08, 10-13 digit.
Catatan: kelas yang penuh tetap tampil di dropdown dengan label "(sisa 0)" agar penolakan server bisa diuji.

Prasyarat: akun admin `admin` / `SandiKuat123`; kelas A dengan kuota 2; kelas B dengan kuota 1.

| ID    | Fitur       | Skenario                            | Data uji / langkah                                                                                          | Hasil diharapkan                                                                  | Hasil aktual | Status |
| ----- | ----------- | ----------------------------------- | ----------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------- | ------------ | ------ |
| TC-01 | Pendaftaran | Data valid                          | Nama "Budi Santoso", email unik, HP 0829982838484, kelas A                                                  | Berhasil, flash sukses, data tersimpan                                            |              |        |
| TC-02 | Pendaftaran | Nama kosong                         | Kosongkan nama                                                                                              | Ditolak, pesan galat di kolom nama, data tidak tersimpan                          |              |        |
| TC-03 | Pendaftaran | Email kosong                        | Kosongkan email                                                                                             | Ditolak, pesan galat di kolom email                                               |              |        |
| TC-04 | Pendaftaran | HP kosong                           | Kosongkan HP                                                                                                | Ditolak, pesan galat di kolom HP                                                  |              |        |
| TC-05 | Pendaftaran | Kelas belum dipilih                 | Biarkan pilihan kelas kosong                                                                                | Ditolak, pesan galat di kolom kelas                                               |              |        |
| TC-06 | Pendaftaran | Email ganda                         | Daftar dengan email yang sudah terdaftar                                                                    | Ditolak                                                                           |              |        |
| TC-07 | Pendaftaran | HP 9 digit                          | 081234567                                                                                                   | Ditolak (di bawah batas)                                                          |              |        |
| TC-08 | Pendaftaran | HP 10 digit                         | 0812345678                                                                                                  | Diterima (batas bawah)                                                            |              |        |
| TC-09 | Pendaftaran | HP 13 digit                         | 0812345678901                                                                                               | Diterima (batas atas)                                                             |              |        |
| TC-10 | Pendaftaran | HP 14 digit                         | 08123456789012                                                                                              | Ditolak (di atas batas)                                                           |              |        |
| TC-11 | Kuota       | Kuota tepat penuh                   | Kelas B (kuota 1) sudah 1 pendaftar; daftar lagi ke kelas B                                                 | Ditolak "Kuota kelas sudah penuh.", jumlah pendaftar tetap 1                      |              |        |
| TC-12 | Kuota       | Sisa kuota 1                        | Kelas A berisi 1 pendaftar; daftar 1 lagi                                                                   | Berhasil; pilihan kelas menampilkan "sisa 0" sesudahnya                           |              |        |
| TC-13 | Login       | Login benar                         | admin / SandiKuat123.                                                                                       | Masuk ke index.php                                                                |              |        |
| TC-14 | Login       | Login salah                         | admin / salah123                                                                                            | Tetap di login, "Username atau kata sandi salah."                                 |              |        |
| TC-15 | Login       | 5 kali gagal                        | Salah 5x lalu coba sandi benar pada percobaan ke-6                                                          | Ditolak "Terlalu banyak percobaan..."; terbuka lagi setelah 15 menit              |              |        |
| TC-16 | Akses       | Halaman admin tanpa login           | Buka index.php dalam sesi baru                                                                              | Dialihkan ke login.php                                                            |              |        |
| TC-17 | Akses       | Tambah/ubah/hapus/cetak tanpa login | Buka tambah.php, ubah.php?id=1, cetak.php?id=1, cetak-fpdf.php?id=1 langsung tanpa login; POST ke hapus.php | Semua dialihkan ke login.php (hapus: dialihkan/ditolak, data utuh)                |              |        |
| TC-18 | Akses       | daftar.php tetap publik             | Buka daftar.php tanpa login                                                                                 | Form tampil normal                                                                |              |        |
| TC-19 | Validasi ID | `?id=abc`                           | ubah.php?id=abc (sudah login)                                                                               | Ditolak / 404 rapi, tanpa error PHP                                               |              |        |
| TC-20 | Keamanan    | XSS pada nama                       | Nama `<script>alert(1)</script>`                                                                            | Disimpan/ditampilkan sebagai teks (ter-escape), tidak ada pop-up di index & cetak |              |        |
| TC-21 | Keamanan    | SQL Injection pencarian             | Cari `' OR '1'='1` dan `'; DROP TABLE pendaftar;--`                                                         | Diperlakukan sebagai teks biasa; tidak ada error SQL, tabel utuh                  |              |        |
| TC-22 | Keamanan    | Hapus tanpa token                   | Kirim POST ke hapus.php tanpa/dengan csrf salah                                                             | Ditolak, data tidak terhapus                                                      |              |        |
| TC-23 | Logout      | Logout via GET                      | Buka logout.php lewat URL                                                                                   | 405, sesi tetap aktif                                                             |              |        |
| TC-24 | Logout      | Logout via tombol Keluar            | Klik Keluar lalu tekan Back dan buka index.php                                                              | Dialihkan ke login.php                                                            |              |        |
| TC-26 | Tampilan    | JS termuat                          | Login, buka index.php, klik Hapus, lalu buka DevTools > Network                                             | Muncul dialog konfirmasi SweetAlert; js/app.js berstatus 200                      |              |        |
| TC-27 | Kuota       | Ubah pindah ke kelas penuh          | Admin ubah pendaftar ke kelas yang sisa 0                                                                   | Ditolak "Kuota kelas tujuan sudah penuh."                                         |              |        |
| TC-25 | Kuota       | Dua pendaftar bersamaan (race)      | Kelas sisa 1; jalankan 2 permintaan paralel, mis. `for i in 1 2; do curl ... & done`                        | Hanya 1 berhasil, 1 ditolak kuota penuh                                           |              |        |

## Ringkasan eksekusi

- Tanggal uji: 2 Nov 2026
- Penguji: Faaiza
- Total kasus: 27
- Lolos: 27
- Gagal: 0
- Re-test setelah perbaikan: Tidak ada
- Hasil `php tests/jalankan.php`: Semua uji lolos (12 pengujian otomatis).
