# Lembar Kerja 1 - Eksplorasi Cara Kerja Web

Tujuan: membuktikan konsep T2-T5 (client-server, IP/port/DNS/domain, URL, HTTP/HTTPS) dengan alat yang sudah tersedia.

Lingkungan: Windows 11 Pro 25H2 (build 26200.9711), Google Chrome 155 dengan DevTools, Windows PowerShell/CMD.

## A. Network DevTools

### A.1 Request ke `https://example.com`

Bukti: `screenshots/lab1-devtools-example.png`

| Item                      | Hasil                                                                            |
| ------------------------- | -------------------------------------------------------------------------------- |
| Request URL               | `https://example.com/`                                                           |
| Request Method            | `GET`                                                                            |
| Status Code               | `304 Not Modified`                                                               |
| Remote Address (IP:port)  | `172.66.147.243:443`                                                             |
| Response header 1         | `server: cloudflare`                                                             |
| Response header 2         | `date: Mon, 02 Nov 2026 01:58:29 GMT`                                            |
| Response header 3         | `etag: "6ae7c111-241"`                                                           |
| Header lain yang terlihat | `last-modified: Sun, 01 Nov 2026 22:45:37 GMT`, `cf-cache-status: HIT`, `age: 1` |

Status 304 muncul karena browser sudah menyimpan salinan halaman di cache. Browser hanya menanyakan apakah isinya berubah (memakai `ETag`/`Last-Modified`), server menjawab "tidak berubah", dan browser memakai salinan lokalnya (lihat T5, tabel kode status). Port 443 menunjukkan koneksi memakai HTTPS. Remote Address `172.66.147.243` sama dengan salah satu IP hasil `nslookup` pada bagian B.

### A.2 Situs lain: `myut.ut.ac.id`

Bukti: `screenshots/lab1-situs-ut-devtools.png`

Halaman yang diamati: `https://myut.ut.ac.id/auth/login/v2`

| Ukuran                   | Nilai                                         |
| ------------------------ | --------------------------------------------- |
| Jumlah request           | 25 request                                    |
| Data yang ditransfer     | 215 kB transferred                            |
| Total ukuran sumber daya | 3,5 MB resources                              |
| Waktu muat               | Finish 15,56 detik; `DOMContentLoaded` 405 ms |

Pengelompokan berdasarkan tipe, dihitung dari 20 baris teratas yang terlihat pada screenshot (lima baris terbawah tidak ikut terlihat, jadi angka berikut adalah jumlah minimum):

| Tipe                    | Jumlah (minimum) | Contoh                                                                 |
| ----------------------- | ---------------: | ---------------------------------------------------------------------- |
| HTML (document)         |                1 | `v2`                                                                   |
| JavaScript (script)     |                7 | `app.5321676c.js`, `chunk-vendors.6f62df3d.js`                         |
| CSS                     |                1 | `app.e201f8cf.css` (muncul sebagai initiator font)                     |
| Gambar (png)            |                8 | `slide-1-min.png`, `slide-2-min.png`, `slide-3-min.png`, `favicon.png` |
| Font                    |                1 | `Interstate-Regular.otf`                                               |
| Fetch/XHR dan preflight |                3 | `collect?...` (Google Analytics), `MY_UT_WEB`                          |

Total yang ditransfer (215 kB) jauh lebih kecil daripada total sumber daya (3,5 MB) karena banyak berkas diambil dari disk cache browser (kolom Size menampilkan `(disk cache)`). Jadi tidak semua berkas diunduh ulang dari server.

### A.3 URL yang tidak ada

Bukti: `screenshots/lab1-devtools-404.png`

Request ke `https://example.com/halaman-ngawur` menghasilkan status `404` (tipe document, 0,5 kB, 93 ms). Status 404 berarti server sedang aktif dan dapat dihubungi, tetapi sumber daya pada path itu tidak ditemukan. Penyebab umum: path salah ketik, berkas sudah dihapus atau dipindah, atau tautan sudah tidak berlaku. Ini termasuk kelompok 4xx, yaitu kesalahan di sisi klien (T5).

## B. Perintah jaringan di terminal

Bukti: `screenshots/lab1-ping-nslookup-tracert.png` dan `screenshots/lab1-tracert-curl.png`

| Perintah                      | Hasil                                                                                                                                                                                                  |
| ----------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `ping -n 4 example.com`       | Membalas dari `104.20.23.154`, 4 terkirim 4 diterima (0% loss). Minimum 30 ms, maksimum 33 ms, **rata-rata 31 ms**, TTL 246.                                                                          |
| `nslookup example.com`        | Resolver: `XiaoQiang` (`192.168.31.1`, router lokal), jawaban *non-authoritative*. IPv6: `2606:4700:10::6814:179a` dan `2606:4700:10::ac42:93f3`; IPv4: `104.20.23.154` dan `172.66.147.243`.        |
| `tracert example.com`         | **13 hop** menuju `104.20.23.154` (hop 1 router lokal `192.168.31.1`, hop 13 tujuan). Hop 7 sekali time out (`*`), tetapi rute tetap selesai ("Trace complete").                                       |
| `curl.exe -I https://example.com` | Baris pertama `HTTP/1.1 200 OK`. Header: `Content-Type: text/html; charset=utf-8`, `Server: cloudflare`, `allow: GET, HEAD`, `cf-cache-status: HIT`, `Age: 8`. |

Satu nama domain dapat dipetakan ke beberapa alamat IP (IPv4 dan IPv6) karena example.com dilayani lewat jaringan Cloudflare. Itu sebabnya `ping` menuju `104.20.23.154`, sedangkan DevTools pada bagian A.1 mencatat `172.66.147.243`: keduanya ada di daftar jawaban `nslookup`. Server DNS yang menjawab adalah router lokal (`192.168.31.1`), yaitu *recursive resolver* pada langkah DNS nomor 3 (T3). Header `server: cloudflare` dan `last-modified` pada `curl` sama dengan yang terlihat di DevTools.

## C. File hosts (DNS lokal)

Bukti: `screenshots/lab1-hosts.png` dan `screenshots/lab1-ping-belajar-local.png`

Baris yang ditambahkan ke `C:\Windows\System32\drivers\etc\hosts`:

```text
127.0.0.1   belajar.local
```

Hasil `ping belajar.local`:

```text
Pinging belajar.local [127.0.0.1] with 32 bytes of data:
Reply from 127.0.0.1: bytes=32 time<1ms TTL=128
(4 balasan, 0% loss, rata-rata 0ms)
```

Nama `belajar.local` diarahkan ke `127.0.0.1` (loopback, komputer sendiri). File hosts diperiksa pada langkah DNS nomor 2: setelah browser memeriksa cache-nya (langkah 1), sistem operasi memeriksa cache dan file `hosts` sebelum meneruskan permintaan ke recursive resolver (langkah 3). Karena nama sudah ketemu di hosts, tidak ada permintaan ke server DNS di internet.

Setelah selesai, baris `127.0.0.1   belajar.local` dihapus dari file hosts. Bukti: `screenshots/lab1-hosts-dihapus.png`.

## D. Analisis domain

Bukti: `screenshots/lab1-whois-ut.png`

Domain yang dianalisis: `ut.ac.id` (host yang diamati adalah `myut.ut.ac.id`, jadi `myut` adalah subdomain).

| Item                | Hasil                               |
| ------------------- | ----------------------------------- |
| TLD                 | `.id` (country code TLD, Indonesia) |
| SLD                 | `ac` (akademik)                     |
| Domain terdaftar    | `ut.ac.id`                          |
| Subdomain           | `myut`                              |
| Registry            | PANDI (pengelola TLD `.id`)         |
| Registrar           | Tidak tersedia                      |
| Tanggal kedaluwarsa | Tidak tersedia                      |

Pencarian di who.is menampilkan "No WHOIS data was found for ut.ac.id" (hanya tersedia catatan historis dari 2009 sampai 2026, tanpa record saat ini), sehingga registrar dan tanggal kedaluwarsa tidak dapat dicatat. Domain ini adalah turunan `.ac.id`, yaitu domain `.id` untuk lembaga pendidikan tinggi (akademik).

## Lembar Kerja 1 (isi)

| No  | Pertanyaan                                                      | Jawaban                                                                                                                                                                                                                     |
| --- | --------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 1   | Status code dan header `server` dari `example.com`              | `curl -I`: `HTTP/1.1 200 OK`, header `server: cloudflare`. Di DevTools tercatat `304 Not Modified` (browser memakai salinan cache), `server: cloudflare`.                                                                   |
| 2   | IP hasil `nslookup` dan waktu rata-rata `ping`                  | IPv4 `172.66.147.243` dan `104.20.23.154`; IPv6 `2606:4700:10::ac42:93f3` dan `2606:4700:10::6814:179a`. Rata-rata ping 31 ms (`tracert`: 13 hop).                                                                                      |
| 3   | Jumlah request dan total ukuran pada situs yang diamati         | `myut.ut.ac.id/auth/login/v2`: 25 request, 215 kB transferred (3,5 MB resources), selesai dalam 15,56 detik. Minimal 1 HTML, 7 JS, 1 CSS, 8 gambar, 1 font.                                                                 |
| 4   | Arti status 404 dan penyebabnya                                 | 404 Not Found: server aktif tetapi sumber daya pada path itu tidak ditemukan. Penyebab: URL salah, berkas dihapus atau dipindah, tautan kedaluwarsa. Dibuktikan dengan `example.com/halaman-ngawur` yang mengembalikan 404. |
| 5   | Hasil `ping belajar.local` dan alasannya                        | Membalas dari `127.0.0.1`, 4 terkirim 4 diterima, rata-rata 0 ms. Alasan: baris `127.0.0.1 belajar.local` di file hosts dibaca OS pada langkah DNS nomor 2, sebelum permintaan ke DNS resolver.                             |
| 6   | Bagian URL: skema, host, path, query dari satu URL yang dipilih | URL `https://myut.ut.ac.id/auth/login/v2`: skema `https`; host `myut.ut.ac.id`; port 443 (bawaan HTTPS, tidak ditulis); path `/auth/login/v2`; query string tidak ada; fragment tidak ada.                                  |
