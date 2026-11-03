# Bukti Hari 2: HTML5 (Struktur, Semantik, Tabel, dan Form)

Folder `hari-02-html/` berisi `profil.html` (Lab 1), situs multi-halaman `index.html`, `tentang.html`, `kontak.html`, dan `blog/artikel-1.html` (Lab 2), `jadwal.html` (Lab 3), serta `daftar.html` (Lab 4). Kerangka Proyek 1 ada di folder terpisah `proyek-1-landing-page/`.

## 1. Lab 2: path yang salah dan perbaikannya

Pengujian tautan pada situs multi-halaman menemukan satu path yang salah.

| Berkas                  | Tautan                 | Path salah                    | Gejala                                                | Perbaikan                    |
| ----------------------- | ---------------------- | ----------------------------- | ----------------------------------------------------- | ---------------------------- |
| Halaman beranda/tentang | "Baca artikel lengkap" | `href="/blog/artikel-1.html"` | Browser menampilkan `Cannot GET /blog/artikel-1.html` | `href="blog/artikel-1.html"` |

Penyebab: awalan `/` berarti path dari akar server (`127.0.0.1:5500/`), sedangkan proyek berada di subfolder `portofolio-junior-web/hari-02-html/`. Path relatif tanpa `/` dihitung dari folder berkas yang sedang dibuka (T3).

Bukti:

- `screenshots/lab2-kode-path-salah.png` dan `screenshots/lab2-path-salah-cannot-get.png` (sebelum)
- `screenshots/lab2-kode-path-benar.png` dan `screenshots/lab2-halaman-tentang.png` (sesudah)

Tautan lain yang diuji dan benar: `../index.html` dari `blog/artikel-1.html`, `mailto:`, tautan eksternal dengan `target="_blank"` dan `rel="noopener noreferrer"`, serta tautan `#` ke bagian halaman.

## 2. Lab 5: validasi W3C

Halaman diperiksa dengan W3C Markup Validator (validator.w3.org), dengan hasil "Document checking completed. No errors or warnings to show."

| Halaman                                          | Bukti                                                            |
| ------------------------------------------------ | ---------------------------------------------------------------- |
| `daftar.html`                                    | `screenshots/validator-daftar.png`                               |
| `jadwal.html`                                    | `screenshots/validator-jadwal.png`                               |
| `profil.html`                                    | `screenshots/validator-profil.png`                               |
| Halaman bernavbar (`index`, `tentang`, `kontak`) | `screenshots/validator-halaman-navbar-1.png`, `-2.png`, `-3.png` |

Pesan _"Trailing slash on void elements has no effect"_ yang tampil sebagai info (`screenshots/validator-info-trailing-slash.png`) bukan error. Itu berasal dari penulisan `<meta ... />` hasil Prettier.

Audit berikutnya menemukan `blog/artikel-1.html` memiliki satu error: elemen `<footer>` berada di luar `<body>`. Berkas itu sudah diperbaiki (lihat bagian 4).

## 3. Lab 5: audit Lighthouse (Accessibility dan SEO)

| Halaman                 | Accessibility | SEO | Temuan                                                                                 | Perbaikan                                                                                | Bukti                                                                                                 |
| ----------------------- | ------------: | --: | -------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| `profil.html` (sebelum) |            96 | 100 | _Touch targets do not have sufficient size or spacing_ (tautan navigasi terlalu kecil) | Ukuran dan jarak tautan navigasi diperbesar lewat `css/style.css` (`nav ul` dan `nav a`) | `screenshots/lighthouse-profil-sebelum.png`, `screenshots/lighthouse-profil-temuan-touch-target.png`  |
| `profil.html` (sesudah) |           100 | 100 | -                                                                                      | -                                                                                        | `screenshots/lighthouse-profil-sesudah.png`                                                           |
| `index.html`            |           100 | 100 | -                                                                                      | -                                                                                        | `screenshots/lighthouse-index.png`                                                                    |
| `tentang.html`          |           100 | 100 | -                                                                                      | -                                                                                        | `screenshots/lighthouse-tentang.png`                                                                  |
| `kontak.html`           |           100 | 100 | -                                                                                      | -                                                                                        | `screenshots/lighthouse-kontak.png`                                                                   |
| `jadwal.html` (sebelum) |            98 |  90 | _Document does not have a main landmark_                                               | Tambah `<main>`, `<h1>`, dan `<meta name="description">`                                 | `screenshots/lighthouse-jadwal-sebelum.png`, `screenshots/lighthouse-jadwal-temuan-main-landmark.png` |

Pengukuran ulang versi akhir (Lab 1 sampai 4 dan Proyek 1) dengan Lighthouse CLI 12 (bukan DevTools) memberi skor Accessibility 100 dan SEO 100 untuk setiap halaman, termasuk `jadwal.html`, `daftar.html`, dan `blog/artikel-1.html`.

## 4. Perbaikan setelah audit

| Berkas                                      | Perbaikan                                                                                                                                                                                                     |
| ------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `profil.html`, `blog/artikel-1.html`        | `lang="en"` diganti `lang="id"` sesuai bahasa isi halaman                                                                                                                                                     |
| `blog/artikel-1.html`                       | `<footer>` dipindah ke dalam `<body>`; ditambah `<main>`, meta description, dan tautan CSS (`../css/style.css`)                                                                                               |
| `index.html`, `tentang.html`, `kontak.html` | `<title>` dibuat unik (sebelumnya semuanya "Home - ..."); meta description dibuat unik                                                                                                                        |
| `index.html`                                | Anchor `#sumber-belajar` diberi target (`id="sumber-belajar"` pada section); tautan eksternal memakai `https://`; navbar menautkan semua halaman                                                              |
| `tentang.html`                              | Isi dibuat khas (sebelumnya salinan beranda)                                                                                                                                                                  |
| `kontak.html`                               | `id` section diganti dari `tentang-halaman` menjadi `hubungi-saya`                                                                                                                                            |
| `jadwal.html`                               | Ditambah `<h1>` dan `<main>`; "Aturan Kelas" menjadi heading `<h2>`; `scope="row"` pada Senin; total JP sejajar dengan kolom waktu                                                                            |
| `daftar.html`                               | Ditambah `<h1>`, meta description; radio jenis kelamin `required`; jenis kelamin dan minat dibungkus `fieldset` + `legend`                                                                                    |
| Semua halaman bergambar                     | Gambar `foto.png` (600x900) diberi `width="160" height="240"` agar rasio terjaga; foto dioptimalkan tanpa kehilangan kualitas menjadi sekitar 175 KB (di bawah target 200 KB); satu salinan di `img/foto.png` |
| Folder proyek                               | `coba.html` (berkas percobaan) dan `foto.png` duplikat di akar dihapus                                                                                                                                        |

## 5. Lab 4: pengujian form `daftar.html`

Pengujian di `http://localhost/portofolio-junior-web/hari-02-html/daftar.html`. Data uji pada screenshot adalah data contoh.

| Skenario                          | Hasil                                                                                                             | Bukti                                                                              |
| --------------------------------- | ----------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------- |
| Kirim form kosong                 | Browser menahan pengiriman dan menampilkan "Please fill out this field." pada Nama Lengkap (atribut `required`)   | `screenshots/form-kosong-required.png`                                             |
| Email tanpa `@` (`iyaiyaaja.com`) | "Please include an '@' in the email address. 'iyaiyaaja.com' is missing an '@'." (validasi bawaan `type="email"`) | `screenshots/form-email-salah.png`                                                 |
| No. HP `0181929293911`            | "Please match the requested format. Awali dengan 08, 10-13 digit" (atribut `pattern` dan `title`)                 | `screenshots/form-hp-salah.png`                                                    |
| Isian valid, `method="post"`      | Browser menuju `proses.php` dan server membalas `404 Not Found` karena skrip penerima baru dibuat pada Hari 8     | `screenshots/form-valid-404-browser.png`, `screenshots/form-valid-404-network.png` |

| Isian valid, `method="get"` | URL berubah menjadi `proses.php?nama=...&email=...&hp=...&lahir=...&jk=...`; semua data terlihat di address bar (karakter `@` dikodekan `%40`, spasi menjadi `+`). Hasilnya tetap 404 | `screenshots/form-get-query-string.png` |

Pada Network, `proses.php` bertipe `document` dengan status `404` (0,5 kB). Ini sesuai harapan: form dan validasi sisi klien berfungsi, sedangkan pemrosesan data adalah tugas server (Hari 8). Pesan galat sepenuhnya berasal dari browser, tanpa JavaScript.

Perbandingan: pada GET data ikut di URL (terlihat, bisa di-bookmark, tersimpan di riwayat, panjangnya terbatas), sedangkan pada POST data dikirim di body request sehingga URL tetap `proses.php`. Karena itu form pendaftaran memakai `method="post"`.

## 6. DevTools Elements dan Sources

| Pengamatan                                                                                                                                                                                                                                     | Bukti                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------ |
| Panel Elements pada `daftar.html`: `<html lang="id">`, `<head>`, dan `<body>` berisi `<main>`                                                                                                                                                  | `screenshots/devtools-elements-daftar.png` |
| `<body>` di DOM memiliki atribut tambahan (`data-new-gr-c-s-check-loaded`, `data-gr-ext-installed`) dan ada elemen `<grammarly-desktop-integration>` setelah `</body>`. Keduanya tidak ada di berkas sumber; ditambahkan oleh ekstensi browser | `screenshots/devtools-elements-daftar.png` |
| Panel Sources memperlihatkan berkas asli `daftar.html` apa adanya dari server                                                                                                                                                                  | `screenshots/devtools-sources-daftar.png`  |

Kesimpulan: DOM adalah hasil akhir yang dibangun browser dan dapat berbeda dari sumber (di sini karena ekstensi), sedangkan berkas di Sources dan View Source adalah teks yang dikirim server.

## 7. Proyek 1: Lighthouse

Validator W3C untuk `index.html` Proyek 1: "Document checking completed. No errors or warnings to show." (`screenshots/validator-proyek1-index.png`). Hanya ada pesan info tentang trailing slash pada elemen void, yang bukan error.

`proyek-1-landing-page` diukur dengan Lighthouse di DevTools: Accessibility 100, SEO 100 (`screenshots/lighthouse-proyek1-index.png`). Lighthouse menampilkan catatan "Clearing the browser cache timed out" pada proses itu, tetapi skor tetap keluar.

## 8. Catatan

- Pada `profil.html`, hanya satu temuan Lighthouse yang terdokumentasi (touch targets); modul meminta minimal dua.
- Bukti validator pada bagian 2 diambil pada versi berkas sebelum perbaikan bagian 4. Validasi ulang pada berkas akhir perlu dilakukan dan di-screenshot.
- Bug Hunt (`bug-html.html`) belum dikerjakan karena berkasnya belum tersedia.
