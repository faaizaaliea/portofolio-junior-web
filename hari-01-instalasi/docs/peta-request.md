# Peta Perjalanan Request: `http://localhost/hari-01/tes.php`

## Ringkasan

```text
Klien (Browser Chrome)                                Server (laptop yang sama)
----------------------                       ------------------------------------------
1. Ketik URL
   http://localhost/hari-01/tes.php
2. Cek cache browser
3. OS: cek cache + hosts -> localhost
   = loopback (::1 / 127.0.0.1)
4. Sambung ke port 80  ---- TCP ---->        Apache 2.4.58 (web server, port 80/443)
5. Kirim request       --GET /hari-01/tes.php HTTP/1.1-->
                                             6. Path -> C:\xampp\htdocs\hari-01\tes.php
                                             7. Ekstensi .php? serahkan ke PHP 8.2.12
                                             8. PHP menjalankan skrip, menghasilkan HTML
                                                (database MariaDB tidak dipakai)
10. Render halaman  <--HTTP/1.1 200 OK + HTML--  9. Apache mengirim response
```

## Langkah demi langkah

### 1. Mengetik URL (T4: anatomi URL)

| Bagian           | Nilai pada URL ini                       |
| ---------------- | ---------------------------------------- |
| Skema (protokol) | `http` (tanpa enkripsi TLS, bukan HTTPS) |
| Host             | `localhost`                              |
| Port             | 80 (bawaan HTTP, tidak ditulis)          |
| Path             | `/hari-01/tes.php`                       |
| Query string     | tidak ada                                |
| Fragment         | tidak ada                                |

Browser berperan sebagai **klien** (T2): ia yang memulai komunikasi dengan mengirim **request**.

### 2. Memeriksa cache browser (T3: resolusi DNS, langkah 1)

Browser memeriksa cache-nya lebih dulu. Halaman dinamis dari PHP tidak disimpan lama, sehingga browser tetap melanjutkan ke jaringan.

### 3. Resolusi nama `localhost` (T3: IP, DNS, hosts)

Sistem operasi memeriksa cache dan file `hosts` (langkah DNS nomor 2). File hosts Windows berisi komentar bahwa nama `localhost` ditangani langsung oleh DNS client, dipetakan ke alamat loopback `127.0.0.1` (IPv4) atau `::1` (IPv6). Pada DevTools, Remote Address tercatat `[::1]:80`, artinya browser memakai IPv6 loopback. Karena sudah selesai di komputer sendiri, langkah ke recursive resolver, root server, server TLD, dan authoritative nameserver **tidak terjadi**. Itu juga sebabnya `localhost` bisa dipakai tanpa internet.

Pemetaan serupa dibuktikan pada Lab 1: baris `127.0.0.1 belajar.local` di file hosts membuat `ping belajar.local` dijawab `127.0.0.1`.

### 4. Menyambung ke port 80 (T3: port)

Browser membuka koneksi TCP ke alamat loopback pada **port 80**, port bawaan HTTP. Apache mendengarkan di port 80 dan 443 (XAMPP Control Panel: Apache hijau, port `80, 443`). Karena skemanya `http`, tidak ada negosiasi TLS dan data tidak dienkripsi (T5).

### 5. Mengirim request (T5: HTTP)

Browser mengirim request:

```http
GET /hari-01/tes.php HTTP/1.1
Host: localhost
User-Agent: Mozilla/5.0 ... Chrome/155.0.0.0 Safari/537.36
Accept: text/html,application/xhtml+xml,...
```

Request terdiri dari _request line_ (metode `GET`, path, versi), _header_, dan tanpa _body_ karena `GET` hanya mengambil data. Sifat HTTP yang **stateless** (T2) berlaku: server tidak mengingat request sebelumnya, setiap request berdiri sendiri.

### 6. Apache memetakan path ke berkas (T6: web server, document root)

Apache adalah **web server** (T6). Ia memetakan path `/hari-01/tes.php` ke berkas di **document root** XAMPP, yaitu `C:\xampp\htdocs\hari-01\tes.php`. Berkas di luar `htdocs` tidak bisa diakses lewat browser. Jika berkas tidak ada, Apache membalas `404 Not Found` (dibuktikan dengan `tidak-ada.html`).

### 7. Menyerahkan ke PHP (T2 dan T6: halaman dinamis)

Karena ekstensinya `.php`, Apache tidak mengirim berkas apa adanya, tetapi menyerahkannya ke **interpreter PHP** 8.2.12 (T6: application server/interpreter), seperti pada diagram T2 ("Dinamis? serahkan ke PHP"). Ini yang membedakan `.php` dengan `index.html`: halaman statis dikirim apa adanya, halaman dinamis dihasilkan ulang setiap kali di-request.

### 8. PHP menjalankan skrip

PHP menjalankan `tes.php` di server (server-side, T2) dan menghasilkan HTML:

- `PHP_VERSION` menjadi `8.2.12`
- `$_SERVER['SERVER_SOFTWARE']` menjadi `Apache/2.4.58 (Win64) OpenSSL/3.1.3 PHP/8.2.12`
- `$_SERVER['REQUEST_METHOD']` menjadi `GET`
- `date('d-m-Y H:i:s')` menjadi waktu server saat request diproses

Skrip ini **tidak** menyentuh database. MariaDB (port 3306) hanya ikut berjalan di XAMPP, tetapi pada alur ini langkah "PHP <-> query <-> database" pada diagram T2 tidak dipakai. Kode PHP sendiri tidak pernah sampai ke browser; browser hanya menerima hasil olahannya.

### 9. Apache mengirim response (T5: response)

```http
HTTP/1.1 200 OK
Date: Mon, 02 Nov 2026 02:22:40 GMT
Server: Apache/2.4.58 (Win64) OpenSSL/3.1.3 PHP/8.2.12
X-Powered-By: PHP/8.2.12
Content-Length: 197
Content-Type: text/html; charset=UTF-8

Instalasi Berhasil! Lingkungan pemrograman siap digunakan.<br>Versi PHP: 8.2.12<br>...
```

Response terdiri dari _status line_, _header_, dan _body_. Kode **200 OK** berarti sukses (kelompok 2xx). Header `Content-Type: text/html` memberi tahu browser bahwa isinya HTML.

### 10. Browser merender halaman

Browser membaca HTML pada body response dan menampilkannya: teks "Instalasi Berhasil! ...", versi PHP, informasi server, metode request, dan waktu server. Setiap kali halaman dimuat ulang, baris "Waktu server" berubah, bukti bahwa halaman ini dinamis.

## Perbandingan dengan jalur lain

| Kasus                                     | Yang terjadi                                                                                                              | Bukti                                                           |
| ----------------------------------------- | ------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------- |
| `http://localhost/hari-01/index.html`     | Apache mengirim berkas apa adanya (statis). Header memuat `Last-Modified`, `ETag`, `Accept-Ranges`, tanpa `X-Powered-By`. | `screenshots/curl-lokal.png`                                    |
| `http://localhost/hari-01/tes.php`        | Diproses PHP dulu (dinamis). Header memuat `X-Powered-By: PHP/8.2.12`, tanpa `ETag` dan `Last-Modified`.                  | `screenshots/curl-lokal.png`                                    |
| `http://localhost/hari-01/tidak-ada.html` | Apache membalas `404 Not Found` (kesalahan klien, 4xx).                                                                   | `screenshots/curl-lokal.png`, `screenshots/tes-404-browser.png` |
| `file:///C:/xampp/htdocs/hari-01/tes.php` | Tidak melewati Apache dan PHP, sehingga tidak ada request HTTP. Kode PHP tampil sebagai teks atau berkas hanya diunduh.   | (penjelasan T6)                                                 |

## Daftar istilah T2-T6 yang dipakai

klien, server, request, response, stateless (T2); IP, loopback, port, DNS, hosts, resolusi nama (T3); skema, host, path, query string, fragment (T4); HTTP, HTTPS, metode GET, status code 200/404, header, body (T5); web server, application server/interpreter, document root, database server (T6).

## Bukti

- `screenshots/network-headers.png`: DevTools Network, request `tes.php` (GET, 200 OK, `[::1]:80`, header Server dan Content-Type)
- `screenshots/tes-php-browser.png`: hasil render di browser
- `screenshots/curl-lokal.png`: hasil `curl` untuk `tes.php`, `index.html`, `tidak-ada.html`
- `screenshots/xampp-apache-running.png`: Apache (port 80, 443) dan MySQL (3306) berjalan
