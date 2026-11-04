# Laporan Hari 8: PHP Terstruktur (Sintaks, Form, Validasi, Akses File)

Folder `hari-08-php/` berisi Lab 1 sampai 4 dan Proyek 4 (`proyek-4-buku-tamu/`). Semua halaman diuji lewat Apache XAMPP di `http://localhost/...`.

## 1. Latihan Lab

| Lab | Berkas                      | Isi                                                                                                       | Bukti                                                                                                                                            |
| --- | --------------------------- | --------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------ |
| 1   | `dasar.php`                 | Variabel dan `var_dump`, tabel perkalian, ganjil/genap, FizzBuzz, `match` grade, kalender                 | `screenshots/lab1-dasar.png`                                                                                                                     |
| 2   | `lab2.php`                  | `hitungRataRata`, `cariTertinggi`, `filterLulus`, `urutkanNilai`, `kelompokkanPerKelas`, tabel ber-escape | `screenshots/lab2-tabel.png`                                                                                                                     |
| 3   | `lab3.php`                  | Kalkulator BMI dengan validasi server                                                                     | `screenshots/lab3-kosong.png`, `lab3-huruf.png`, `lab3-negatif.png`, `lab3-valid.png`, `lab3-xss.png`                                            |
| 4   | `cari.php`, `lihat-log.php` | Pencarian GET, log pengunjung, JSON, CSV, penanganan galat                                                | `screenshots/lab4-cari.png`, `lab4-tidak-ada.png`, `lab4-log.png`, `lab4-json.png`, `lab4-csv.png`, `lab4-json-rusak.png`, `lab4-csv-hilang.png` |

## 2. Proyek 4: Buku Tamu Berbasis File

Detail fitur dan cara menjalankan ada di `proyek-4-buku-tamu/README.md`.

| Persyaratan modul                                  | Terpenuhi | Bukti                                                          |
| -------------------------------------------------- | --------- | -------------------------------------------------------------- |
| Validasi server per kolom; isian lama tetap        | Ya        | `screenshots/proyek-form-galat.png`                            |
| Simpan ke `data/tamu.json`; folder dibuat otomatis | Ya        | `screenshots/proyek-sukses.png`                                |
| Terbaru di atas; cari lewat `?q=`                  | Ya        | `screenshots/proyek-daftar-cari.png`                           |
| Output di-escape; PRG                              | Ya        | `screenshots/proyek-xss.png`                                   |
| `data/` dilindungi `.htaccess`                     | Ya        | `screenshots/proyek-403-data.png`                              |
| Fungsi bertipe + DocBlock, logika terpisah         | Ya        | `functions.php`                                                |
| Tantangan: paginasi 5 per halaman                  | Ya        | `screenshots/proyek-paginasi.png`                              |
| Tantangan: batas 1 pesan per 30 detik per IP       | Ya        | `screenshots/proyek-batas-30-detik.png`                        |
| Tantangan: hapus lewat halaman admin               | Ya        | `screenshots/proyek-admin-login.png`, `proyek-admin-hapus.png` |
| Tantangan: ekspor CSV                              | Ya        | `screenshots/proyek-ekspor-csv.png`                            |

## 3. Bukti uji XSS

Nama `<script>alert(1)</script>` dikirim pada buku tamu dan kolom BMI. Hasilnya tampil sebagai teks, tidak dijalankan: `screenshots/proyek-xss.png` dan `screenshots/lab3-xss.png`.

## 4. Bug Hunt

Berkas `bug-php/` dari instruktur belum tersedia. Tabel di bawah mengikuti daftar kesalahan di modul; kolom Berkas, Baris, dan Bukti diisi setelah `bug-php/` diperiksa.

| No  | Kode bermasalah                                                         | Gejala                            | Perbaikan                                    | Berkas dan baris | Bukti |
| --- | ----------------------------------------------------------------------- | --------------------------------- | -------------------------------------------- | ---------------- | ----- |
| 1   | `echo 'Halo'` tanpa titik koma                                          | Parse error                       | Tambahkan `;`                                |                  |       |
| 2   | `$nama = $_POST['nama'];` saat halaman pertama dibuka                   | Warning: Undefined array key      | `$_POST['nama'] ?? ''`                       |                  |       |
| 3   | `if ($a = 5)`                                                           | Selalu benar                      | `if ($a === 5)`                              |                  |       |
| 4   | `echo 'Nama: ' + $nama;`                                                | Hasil 0 atau error tipe           | Gunakan `.` untuk penggabungan               |                  |       |
| 5   | `echo $_POST['pesan'];`                                                 | XSS                               | `htmlspecialchars`                           |                  |       |
| 6   | `file_put_contents('data/log.txt', ...)` dengan folder `data` tidak ada | Warning: gagal membuka            | `mkdir` atau periksa `is_dir`                |                  |       |
| 7   | `json_decode` tanpa argumen `true` lalu `$d['nama']`                    | Error: Cannot use object as array | `json_decode($isi, true)`                    |                  |       |
| 8   | `header('Location: ...')` setelah ada output                            | Warning: headers already sent     | Panggil `header` sebelum output, lalu `exit` |                  |       |

## 5. Ceklis Code Review

| Butir                                                     | Ya/Tidak | Catatan                                                                                                               |
| --------------------------------------------------------- | -------- | --------------------------------------------------------------------------------------------------------------------- |
| Tidak ada `Undefined variable/array key` pada log error   | Ya       | Semua halaman diuji dengan `error_reporting(E_ALL)`; tidak ada warning atau notice                                    |
| Semua output pengguna melewati `htmlspecialchars`         | Ya       | Lewat fungsi `e()`; output statis tidak berasal dari pengguna                                                         |
| Tidak ada query/jalur berkas dari input pengguna          | Ya       | Path berkas memakai konstanta dan `__DIR__`                                                                           |
| Fungsi memiliki tipe dan DocBlock                         | Ya       | Seluruh fungsi pada Lab 1 sampai 4 dan Proyek 4                                                                       |
| Tidak ada `var_dump` atau kode uji tersisa                | Ya       | `var_dump` pada `dasar.php` memang diminta soal Lab 1; parameter `uji` di `cari.php` adalah fitur uji galat Lab 4     |
| `declare(strict_types=1)` dan indentasi 4 spasi konsisten | Ya       | Kode PHP memakai 4 spasi; templat HTML pada `index.php`, `admin.php`, dan `cari.php` ditulis tanpa indentasi tambahan |

## 6. Catatan keamanan dan repositori

- `data/tamu.json`, `data/log.txt`, dan `config.php` tidak di-commit (lihat `.gitignore`) karena berisi email tamu, alamat IP, dan hash kata sandi admin.
- `data/.htaccess` hanya berlaku di Apache. `php -S` mengabaikannya, jadi pengujian akses `data/` dilakukan lewat XAMPP.
- Batas kirim memakai `REMOTE_ADDR`; header proxy sengaja tidak dipercaya.

## 7. Riwayat commit

```text
fe7b205 (HEAD -> main, origin/main, origin/HEAD) Hari 8: Proyek 4 buku tamu berbasis file
93cd56c Merge remote main into local main
a55ec81 update
cf4d11a Refactor style.css
3185ccc update
fe2cae7 Tambah .gitignore untuk dependensi dan berkas lokal
9f2b9d9 Gabungkan perubahan remote dan pertahankan folder docs
0555963 docs
ef7f237 Add photo.png
42c8340 Add initial CSS styles for layout and components
32e7317 Add initial HTML structure for profile webpage
0e4336e Delete hari-01-instalasi/docs directory
6190850 Hari 3: CSS
fc4bf51 hari 2: html
6776337 Hari 1: bukti instalasi dan script uji
```
