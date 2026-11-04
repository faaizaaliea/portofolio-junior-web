# Laporan Lingkungan Pengembangan Web

## 1. Spesifikasi Platform (KUK 1.1)

| Komponen                  | Spesifikasi                                                                                             |
| ------------------------- | ------------------------------------------------------------------------------------------------------- |
| Sistem operasi dan versi  | Microsoft Windows 11 Pro, Version 25H2 (OS Build 26200.9711)                                            |
| Arsitektur (32/64-bit)    | x64-based PC (64-bit)                                                                                   |
| RAM Total                 | 8.087 MB                                                                                                |
| Ruang disk kosong         | 6,7 GB (7.232.761.856 byte) dari total sekitar 105,9 GB pada drive C: (terpakai sekitar 99,2 GB)        |
| Hak akses (admin/standar) | Akun pengguna anggota grup Administrators; sesi terminal yang digunakan berjalan non-elevated (standar) |

**Bukti asesmen:**

1. `screenshots/spesifikasi-platform.png` (`systeminfo`: OS, arsitektur, RAM)
2. `screenshots/winver.png` (versi dan build Windows)
3. `screenshots/spesifikasi-disk-admin.png` (`Get-PSDrive C` untuk disk, `whoami /groups` untuk keanggotaan grup Administrators)

Catatan: perintah `wmic` yang disarankan modul tidak dikenali di Windows 11 ini, sehingga ruang disk diambil dengan `Get-PSDrive C` di PowerShell.

## 2. Kebutuhan Proyek dan Pemilihan Tools (KUK 1.2)

### 2.1 Kebutuhan teknis proyek

Berdasarkan kebutuhan diklat Junior Web Programmer, lingkungan pengembangan harus mampu:

1. Membuat dan mengedit dokumen HTML untuk membangun halaman web.
2. Menjalankan JavaScript melalui browser dan Node.js.
3. Menjalankan PHP melalui server web lokal.
4. Menyediakan layanan web lokal menggunakan Apache serta dukungan basis data MySQL/MariaDB.
5. Mengelola dependensi JavaScript dan PHP menggunakan npm dan Composer.
6. Melacak perubahan kode dan mengunggah repositori menggunakan Git dan GitHub.
7. Menguji request HTTP serta memeriksa hasilnya melalui browser DevTools dan terminal.

### 2.2 Matriks pemilihan server lokal

Skor 1-5: semakin tinggi skor, semakin sesuai dengan kebutuhan. Skor merupakan penilaian berdasarkan karakteristik tools dan kondisi perangkat yang digunakan.

| Kriteria                           |  XAMPP | Laragon |
| ---------------------------------- | -----: | ------: |
| Kesesuaian dengan Windows          |      5 |       5 |
| Kemudahan instalasi dan penggunaan |      5 |       5 |
| Kebutuhan sumber daya              |      3 |       4 |
| Dokumentasi dan komunitas          |      5 |       4 |
| Lisensi dan sumber unduhan resmi   |      5 |       5 |
| **Total**                          | **23** |  **23** |

Skor kedua tools seri (23 : 23). Laragon lebih ringan, yang relevan karena ruang disk kosong perangkat ini terbatas (sekitar 6,7 GB). Meski begitu, **XAMPP dipilih** sebagai pemutus seri karena:

- menyediakan Apache, MariaDB, PHP, dan phpMyAdmin dalam satu paket sesuai kebutuhan proyek nomor 3 dan 4;
- dokumentasi dan komunitasnya lebih luas, dan modul praktik (jalur `htdocs`, `C:\xampp\php`) mengacu pada XAMPP;
- sudah terbukti berjalan stabil pada perangkat ini (lihat bagian 4).

### 2.3 Matriks pemilihan code editor

| Kriteria                                                         | VS Code | Notepad++ |
| ---------------------------------------------------------------- | ------: | --------: |
| Kesesuaian dengan Windows                                        |       5 |         5 |
| Kemudahan instalasi dan penggunaan                               |       5 |         5 |
| Kebutuhan sumber daya                                            |       4 |         5 |
| Dokumentasi dan komunitas                                        |       5 |         5 |
| Lisensi dan sumber unduhan resmi                                 |       5 |         5 |
| Ekstensi, terminal terpadu, dan integrasi Git (kebutuhan proyek) |       5 |         2 |
| **Total**                                                        |  **29** |    **27** |

**Pilihan: Visual Studio Code.** Notepad++ lebih ringan, tetapi proyek ini memerlukan ekstensi pemrograman (Live Server, Prettier), terminal terpadu, dan integrasi Git yang tersedia langsung di VS Code. Kriteria terakhir ditambahkan karena ketiga fitur itu termasuk kebutuhan proyek nomor 1, 6, dan 7.

### 2.4 Tools yang digunakan

| Tools              | Fungsi                                   |
| ------------------ | ---------------------------------------- |
| Visual Studio Code | Editor kode                              |
| XAMPP / Apache     | Server web lokal                         |
| PHP                | Menjalankan script sisi server           |
| Node.js            | Menjalankan JavaScript di luar browser   |
| npm                | Mengelola paket JavaScript               |
| Composer           | Mengelola library PHP                    |
| Git                | Version control                          |
| GitHub             | Menyimpan dan mempublikasikan repositori |
| Chrome DevTools    | Memeriksa request dan response HTTP      |

## 3. Instalasi dan Konfigurasi Tools (KUK 2.1)

Instalasi dilakukan dengan mengunduh software dari sumber resmi, mengikuti prosedur pemasangan, mencatat versi dan lokasi, serta menguji fungsi setiap tools.

**Bukti lokasi dan keberadaan:** `screenshots/lokasi-tools.png` (`where code`, `where php`, `where node`, `where composer`, `where git`) dan `screenshots/installed-apps.png` (Settings > Apps > Installed apps: Visual Studio Code, Node.js, Composer).

### 3.1 Visual Studio Code

- Fungsi: mengedit HTML, JavaScript, PHP, dan Markdown.
- Versi: 1.142.0 (x64).
- Lokasi instalasi: `C:\Users\user\AppData\Local\Programs\Microsoft VS Code\` (instalasi per pengguna).
- Opsi: _Add to PATH_ aktif (perintah `code` dikenali dari terminal, yaitu `...\Microsoft VS Code\bin\code.cmd`).
- Ekstensi yang terlihat aktif pada status bar: Live Server (tombol _Go Live_, port 5500) dan Prettier.
- Verifikasi: `code --version`.

### 3.2 XAMPP dan PHP

- Paket: XAMPP for Windows 8.2.12, XAMPP Control Panel v3.3.0.
- Lokasi instalasi: `C:\xampp`.
- Lokasi PHP: `C:\xampp\php\php.exe`.
- Versi komponen: Apache 2.4.58 (Win64), PHP 8.2.12, MariaDB 10.4.32, phpMyAdmin 5.2.1.
- Document root: `C:\xampp\htdocs`.
- Port: Apache 80 dan 443, MySQL/MariaDB 3306 (tidak ada konflik port pada saat verifikasi).
- Konfigurasi: Apache dan MySQL dijalankan melalui XAMPP Control Panel (tombol _Start_ hingga modul berwarna hijau).
- Verifikasi: `http://localhost/dashboard/`, `http://localhost/phpmyadmin/`, dan `http://localhost/hari-01/tes.php`.

### 3.3 Node.js dan npm

- Versi Node.js: v24.22.0.
- Versi npm: 11.19.1.
- Lokasi instalasi: `C:\Program Files\nodejs\`.
- Opsi: _Add to PATH_ aktif (perintah `node` dan `npm` dikenali dari terminal).
- Verifikasi: `node -v` dan `npm -v`.

### 3.4 Composer

- Versi: 2.10.3.
- Lokasi instalasi: `C:\ProgramData\ComposerSetup\bin\`.
- PHP yang digunakan: `C:\xampp\php\php.exe` (terbaca pada keluaran `composer -V`: "PHP version 8.2.12 (C:\xampp\php\php.exe)").
- Verifikasi: `composer -V`.

### 3.5 Git

- Versi: 2.50.1.windows.1.
- Lokasi instalasi: `C:\Program Files\Git\cmd\git.exe`.
- Konfigurasi: `user.name` dan `user.email` sudah diatur, `init.defaultbranch=main`, dan `credential.helper=manager` (Git Credential Manager, sehingga autentikasi GitHub tidak memakai kata sandi akun).
- Verifikasi: `git --version` dan `git config --list` (`screenshots/git-config.png`).

### 3.6 Kendala yang dialami dan solusinya

| Kendala                                                                                          | Solusi                                                                                                                                   |
| ------------------------------------------------------------------------------------------------ | ---------------------------------------------------------------------------------------------------------------------------------------- |
| Sempat kebingungan karena folder `htdocs` tidak langsung terlihat.                               | Folder proyek disalin ke document root XAMPP, yaitu `C:\xampp\htdocs\hari-01`, sehingga dapat diakses lewat `http://localhost/hari-01/`. |
| Perintah `wmic logicaldisk ...` dari modul menampilkan "'wmic' is not recognized" di Windows 11. | Ruang disk diambil dengan `Get-PSDrive C` di PowerShell (lihat bagian 1).                                                                |

## 4. Verifikasi Tools dan Layanan (KUK 2.2)

Verifikasi dilakukan melalui perintah versi di terminal dan pengujian layanan yang relevan.

| Tools/layanan       | Perintah atau pengujian                 | Hasil aktual                                                          | Status   |
| ------------------- | --------------------------------------- | --------------------------------------------------------------------- | -------- |
| Visual Studio Code  | `code --version`                        | 1.142.0                                                               | Berhasil |
| PHP                 | `php -v`                                | PHP 8.2.12 (cli)                                                      | Berhasil |
| Node.js             | `node -v`                               | v24.22.0                                                              | Berhasil |
| npm                 | `npm -v`                                | 11.19.1                                                               | Berhasil |
| Composer            | `composer -V`                           | Composer 2.10.3                                                       | Berhasil |
| Git                 | `git --version`                         | 2.50.1.windows.1                                                      | Berhasil |
| Apache dan MySQL    | XAMPP Control Panel                     | Apache (port 80, 443) dan MySQL (port 3306) berstatus hijau (running) | Berhasil |
| Halaman utama XAMPP | Buka `http://localhost/dashboard/`      | Tampil "Welcome to XAMPP for Windows 8.2.12"                          | Berhasil |
| MariaDB/phpMyAdmin  | Buka `http://localhost/phpmyadmin/`     | Tampil phpMyAdmin 5.2.1, server MariaDB 10.4.32                       | Berhasil |
| Apache dan PHP      | Buka `http://localhost/hari-01/tes.php` | HTTP 200 OK                                                           | Berhasil |

**Bukti screenshot:**

1. `screenshots/verifikasi-versi-tools.png` (`php -v`, `node -v`, `npm -v`, `composer -V`, `git --version`)
2. `screenshots/code-version.png` (`code --version`)
3. `screenshots/xampp-apache-running.png` (Control Panel)
4. `screenshots/xampp-localhost.png` (halaman utama XAMPP)
5. `screenshots/xampp-phpmyadmin.png` (phpMyAdmin)
6. `screenshots/tes-php-browser.png` dan `screenshots/network-headers.png` (tes.php dan DevTools)

## 5. Script Uji (KUK 3.1 dan 3.2)

### 5.1 Pengujian HTML

File: `index.html`

Pengujian dilakukan dengan membuka halaman melalui Apache (`http://localhost/hari-01/index.html`) dan melalui Live Server di VS Code (`http://127.0.0.1:5500/`). Halaman menampilkan judul **Halo, Dunia Web!** dan informasi browser (`navigator.userAgent`) yang dihasilkan oleh JavaScript.

Hasil aktual: judul dan informasi browser (Chrome 155 di Windows NT 10.0) tampil benar. Pada Live Server, halaman berubah otomatis dari "Halo!" menjadi "Halo, Dunia Web!" setelah teks pada `<h1>` diubah dan file disimpan.

Bukti: `screenshots/tes-html-browser.png`, `screenshots/live-server-sebelum.png`, `screenshots/live-server-sesudah.png`

### 5.2 Pengujian JavaScript melalui Node.js

File: `tes.js`

Perintah:

```bash
node tes.js
```

Script menampilkan versi Node.js, sistem operasi, dan pesan keberhasilan.

Keluaran aktual:

```text
Node.js v24.22.0
OS: Windows_NT 10.0.26200
Instalasi Node.js berhasil!
```

Bukti: `screenshots/tes-node-terminal.png`

### 5.3 Pengujian PHP melalui Apache

File: `tes.php`

URL: `http://localhost/hari-01/tes.php`

Script menampilkan pesan keberhasilan, versi PHP, informasi server, metode request, dan waktu server.

Keluaran aktual di browser:

```text
Instalasi Berhasil! Lingkungan pemrograman siap digunakan.
Versi PHP: 8.2.12
Server: Apache/2.4.58 (Win64) OpenSSL/3.1.3 PHP/8.2.12
Metode request: GET
Waktu server: (waktu saat halaman diminta)
```

Header aktual dari tab Headers pada DevTools Network:

- Request Method: `GET`
- Status Code: `200 OK`
- Content-Type: `text/html; charset=UTF-8`
- Server: `Apache/2.4.58 (Win64) OpenSSL/3.1.3 PHP/8.2.12`
- Remote Address: `[::1]:80` (loopback IPv6, port 80)
- Header lain: `Content-Length: 197`, `X-Powered-By: PHP/8.2.12`

Bukti: `screenshots/tes-php-browser.png`, `screenshots/network-headers.png`

### 5.4 Pengujian HTTP menggunakan cURL

```bash
curl.exe -i http://localhost/hari-01/tes.php
curl.exe -I http://localhost/hari-01/index.html
curl.exe -I http://localhost/hari-01/tidak-ada.html
```

(Di PowerShell, `curl` adalah alias `Invoke-WebRequest`, sehingga dipakai `curl.exe`.)

Ringkasan hasil:

| URL                       | Status yang diharapkan | Status aktual | Header penting                                                                                    | Penjelasan                   |
| ------------------------- | ---------------------- | ------------- | ------------------------------------------------------------------------------------------------- | ---------------------------- |
| `/hari-01/tes.php`        | 200 OK                 | 200 OK        | `Content-Type: text/html; charset=UTF-8`, `X-Powered-By: PHP/8.2.12`, `Content-Length: 197`       | Script PHP berhasil diproses |
| `/hari-01/index.html`     | 200 OK                 | 200 OK        | `Content-Type: text/html`, `Last-Modified`, `ETag`, `Accept-Ranges: bytes`, `Content-Length: 425` | File HTML tersedia           |
| `/hari-01/tidak-ada.html` | 404 Not Found          | 404 Not Found | `Content-Type: text/html; charset=iso-8859-1`                                                     | File tidak ditemukan         |

Header `Server` pada ketiganya sama: `Apache/2.4.58 (Win64) OpenSSL/3.1.3 PHP/8.2.12`.

Bukti: `screenshots/curl-lokal.png`, `screenshots/tes-404-browser.png` (halaman "Not Found" di browser)

### 5.5 Perbedaan HTML statis dan PHP dinamis

File HTML statis dikirim oleh server sebagai dokumen yang sudah tersedia. Hal ini terlihat dari header `Last-Modified`, `ETag`, dan `Accept-Ranges` pada `index.html`, serta tidak adanya `X-Powered-By`. File PHP diproses di sisi server sebelum hasilnya dikirim ke browser: responsnya memuat `X-Powered-By: PHP/8.2.12` tetapi tanpa `ETag` dan `Last-Modified`, dan baris "Waktu server" berubah pada setiap request. Karena itu, browser tidak menjalankan kode PHP secara langsung ketika file dibuka menggunakan `file:///`. Script PHP harus diakses melalui server yang mendukung PHP, misalnya Apache melalui `http://localhost/...`.

## 6. Git dan GitHub (Lab 7)

- Repositori: `https://github.com/faaizaaliea/portofolio-junior-web`
- Folder Hari 1: `hari-01-instalasi/` berisi `index.html`, `tes.js`, dan `tes.php` (commit: "Hari 1: bukti instalasi dan script uji").
- Riwayat commit (`git log --oneline`):

```text
fe2cae7 (HEAD -> main) Tambah .gitignore untuk dependensi dan berkas lokal
9f2b9d9 (origin/main, origin/HEAD) Gabungkan perubahan remote dan pertahankan folder docs
0555963 docs
ef7f237 Add photo.png
42c8340 Add initial CSS styles for layout and components
32e7317 Add initial HTML structure for profile webpage
0e4336e Delete hari-01-instalasi/docs directory
6190850 Hari 3: CSS
fc4bf51 hari 2: html
6776337 Hari 1: bukti instalasi dan script uji
```

- Penghapusan `docs/` pada commit `0e4336e` (dilakukan dari antarmuka web GitHub) bentrok dengan perubahan lokal; konflik diselesaikan lewat merge pada `9f2b9d9` dengan mempertahankan folder `docs`, tanpa `--force`.
- Identitas Git, cabang bawaan `main`, dan Git Credential Manager sudah dikonfigurasi (bagian 3.5).
- Peta perjalanan request dan hasil Lab 1 didokumentasikan pada `peta-request.md` dan `lembar-kerja-1.md` di folder yang sama dengan laporan ini.

Bukti: `screenshots/github-repo.png`

## 7. Kesimpulan

Berdasarkan hasil instalasi dan pengujian, lingkungan pengembangan telah diverifikasi melalui perintah versi, script HTML, script JavaScript menggunakan Node.js, dan script PHP melalui Apache. Bukti screenshot, keluaran pengujian, serta kendala dan solusi dicatat untuk mendukung dokumentasi asesmen Junior Web Programmer.
