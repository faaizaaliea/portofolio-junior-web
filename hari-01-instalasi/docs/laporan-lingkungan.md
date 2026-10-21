# Laporan Lingkungan Pengembangan Web

## 1. Spesifikasi Platform (KUK 1.1)

| Komponen                  | Spesifikasi                                                |
| ------------------------- | ---------------------------------------------------------- |
| Sistem operasi dan versi  | Microsoft Windows 11 Pro, Version 10.0.26200 (Build 26200) |
| Arsitektur (32/64-bit)    | x64-based PC                                               |
| RAM Total                 | 8.087 MB                                                   |
| Ruang disk kosong         | 10,9 GB dari total 105 GB (drive C:)                       |
| Hak akses (admin/standar) | Administrator                                              |

**Bukti asesmen:** `screenshots/spesifikasi-platform.png`

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

Skor 1–5: semakin tinggi skor, semakin sesuai dengan kebutuhan. Skor merupakan penilaian berdasarkan karakteristik tools dan kondisi perangkat yang digunakan.

| Kriteria                           |  XAMPP | Laragon |
| ---------------------------------- | -----: | ------: |
| Kesesuaian dengan Windows          |      5 |       5 |
| Kemudahan instalasi dan penggunaan |      5 |       5 |
| Kebutuhan sumber daya              |      3 |       4 |
| Dokumentasi dan komunitas          |      5 |       4 |
| Lisensi dan sumber unduhan resmi   |      5 |       5 |
| **Total**                          | **23** |  **23** |

**Pilihan: XAMPP.** XAMPP dipilih karena menyediakan Apache, PHP, MariaDB, dan phpMyAdmin dalam satu paket serta memiliki dokumentasi yang luas. XAMPP juga digunakan untuk pengujian server lokal pada perangkat ini.

### 2.3 Matriks pemilihan code editor

| Kriteria                           | VS Code | Notepad++ |
| ---------------------------------- | ------: | --------: |
| Kesesuaian dengan Windows          |       5 |         5 |
| Kemudahan instalasi dan penggunaan |       5 |         5 |
| Kebutuhan sumber daya              |       4 |         5 |
| Dokumentasi dan komunitas          |       5 |         5 |
| Lisensi dan sumber unduhan resmi   |       5 |         5 |
| **Total**                          |  **24** |    **25** |

**Pilihan: Visual Studio Code.** Meskipun Notepad++ lebih ringan, VS Code dipilih karena mendukung ekstensi pemrograman, terminal terpadu, integrasi Git, dan fitur pengembangan web yang dibutuhkan.

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

### 3.1 Visual Studio Code

- Fungsi: mengedit HTML, JavaScript, PHP, dan Markdown.
- Versi: [Isi hasil `code --version`].
- Lokasi instalasi: [Isi sesuai perangkat].
- Opsi: [Catat opsi yang benar-benar dipilih, misalnya Add to PATH].
- Verifikasi: `code --version`.

### 3.2 XAMPP dan PHP

- Paket: XAMPP.
- Lokasi PHP: `C:\xampp\php\php.exe`.
- Versi PHP: 8.2.12.
- Document root: `C:\xampp\htdocs`.
- Komponen: Apache, PHP, dan MySQL/MariaDB sesuai instalasi.
- Konfigurasi: Apache dijalankan melalui XAMPP Control Panel.
- Verifikasi: akses `http://localhost/hari-01/tes.php`.

### 3.3 Node.js dan npm

- Versi Node.js: v24.22.0.
- Versi npm: 11.19.1.
- Lokasi instalasi: [Isi sesuai perangkat].
- Opsi: [Catat opsi instalasi aktual].
- Verifikasi: `node -v` dan `npm -v`.

### 3.4 Composer

- Versi: 2.10.3.
- Lokasi instalasi: [Isi jika diketahui].
- PHP yang digunakan: `C:\xampp\php\php.exe` jika memang dipilih pada instalasi Composer.
- Verifikasi: `composer -V`.

### 3.5 Git

- Versi: 2.50.1.windows.1.
- Lokasi instalasi: [Isi jika diketahui].
- Konfigurasi: identitas pengguna Git disesuaikan dengan akun yang digunakan.
- Verifikasi: `git --version`.

### 3.6 Kendala instalasi lainnya

Sempat kebingungan karena htdocs tidak ada.

## 4. Verifikasi Tools dan Layanan (KUK 2.2)

Verifikasi dilakukan melalui perintah versi di terminal dan pengujian layanan yang relevan.

| Tools/layanan      | Perintah atau pengujian                 | Hasil aktual     | Status   |
| ------------------ | --------------------------------------- | ---------------- | -------- |
| Visual Studio Code | `code --version`                        | 1.142.0          | Berhasil |
| PHP                | `php -v`                                | PHP 8.2.12       | Berhasil |
| Node.js            | `node -v`                               | v24.22.0         | Berhasil |
| npm                | `npm -v`                                | 11.19.1          | Berhasil |
| Composer           | `composer -V`                           | Composer 2.10.3  | Berhasil |
| Git                | `git --version`                         | 2.50.1.windows.1 | Berhasil |
| Apache dan PHP     | Buka `http://localhost/hari-01/tes.php` | HTTP 200         | Berhasil |

**Bukti screenshot:**

1. `screenshots/verifikasi-versi-tools.png`
2. `screenshots/xampp-apache-running.png`
3. `screenshots/tes-php-browser.png`
4. `screenshots/network-headers.png`

## 5. Script Uji (KUK 3.1 dan 3.2)

### 5.1 Pengujian HTML

File: `index.html`

Pengujian dilakukan dengan membuka halaman melalui browser. Halaman menampilkan judul **Halo, Dunia Web!** dan informasi browser yang dihasilkan oleh JavaScript.

Hasil aktual: Halaman dan informasi browser menampilkan hasil yang benar.

Bukti: `screenshots/tes-html-browser.png`

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

Hasil pengamatan: request `tes.php` memperoleh status HTTP `200` pada DevTools Network.

Catat header aktual berikut dari tab Headers:

- Request Method: [Isi hasil]
- Status Code: [Isi hasil]
- Content-Type: [Isi hasil]
- Server: [Isi hasil]

Bukti: `screenshots/network-headers.png`

### 5.4 Pengujian HTTP menggunakan cURL

```bash
curl -i http://localhost/hari-01/tes.php
curl -I http://localhost/hari-01/index.html
curl -I http://localhost/hari-01/tidak-ada.html
```

Ringkasan hasil:

| URL                       | Status yang diharapkan | Penjelasan                   |
| ------------------------- | ---------------------- | ---------------------------- |
| `/hari-01/tes.php`        | 200 OK                 | Script PHP berhasil diproses |
| `/hari-01/index.html`     | 200 OK                 | File HTML tersedia           |
| `/hari-01/tidak-ada.html` | 404 Not Found          | File tidak ditemukan         |

### 5.5 Perbedaan HTML statis dan PHP dinamis

File HTML statis dikirim oleh server sebagai dokumen yang sudah tersedia. File PHP diproses di sisi server sebelum hasilnya dikirim ke browser. Karena itu, browser tidak menjalankan kode PHP secara langsung ketika file dibuka menggunakan `file:///`. Script PHP harus diakses melalui server yang mendukung PHP, misalnya Apache melalui `http://localhost/...`.

## 6. Kesimpulan

Berdasarkan hasil instalasi dan pengujian, lingkungan pengembangan telah diverifikasi melalui perintah versi, script HTML, script JavaScript menggunakan Node.js, dan script PHP melalui Apache. Bukti screenshot, keluaran pengujian, serta kendala dan solusi dicatat untuk mendukung dokumentasi asesmen Junior Web Programmer.
