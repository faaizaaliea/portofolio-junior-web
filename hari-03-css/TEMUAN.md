# Tabel Temuan Hari 3

## A. Bug Hunt (dari kode profil.html dan style.css sendiri)

| No  | Gejala                                                             | Penyebab                                                                                                                                                              | Perbaikan                                                                                                     |
| --- | ------------------------------------------------------------------ | --------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------- |
| 1   | Teks footer tidak mengecil (font-size tidak berlaku)               | Titik koma hilang pada `color: var(--warna-gelap)` sehingga baris sesudahnya ikut rusak                                                                               | Tambahkan `;` di akhir baris `color`                                                                          |
| 2   | Tombol "Kirim Testimoni" tidak punya efek hover                    | Style hover ditulis untuk `.button`, tetapi HTML tidak memakai class itu                                                                                              | Pakai class `.tombol` di HTML dan CSS yang sama, tambahkan `:hover`                                           |
| 3   | Galeri hanya menumpuk ke bawah, tidak berbentuk grid               | `flex: 1 1 calc(25% - 20px)` dipasang pada gambar, sedangkan induknya `.grid-galeri` tidak punya `display: flex` atau `grid` (properti dipasang di anak, bukan induk) | Pindahkan ke induk: `.galeri { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }` |
| 4   | Hero tidak berada di tengah dan tidak setinggi layar               | `place-items: center` dipasang tanpa `display: grid` dan tanpa `min-height`                                                                                           | Tambahkan `display: grid; min-height: 70vh;`                                                                  |
| 5   | Padding atas-bawah section Testimoni hilang                        | `<section class="container">`: `.container` (class) mengalahkan `section` (tag) karena spesifisitas lebih tinggi                                                      | Bungkus isi dengan `<div class="kontainer">`, jangan beri class container pada section                        |
| 6   | Semua menu bergaris bawah ganda                                    | Semua link memakai class `active`, ditambah garis bawah bawaan link                                                                                                   | Hanya satu link yang diberi class `aktif`, dan beri aturan `a { text-decoration: none }`                      |
| 7   | Navbar di lebar 768px sangat tinggi, menu menumpuk di samping logo | Breakpoint `max-width: 768px` (seharusnya 767px / mobile-first) dan `nav` tidak diberi lebar penuh                                                                    | Mobile-first: menu kolom di ponsel, baris di `min-width: 768px`, `nav { width: 100% }`                        |
| 8   | Ada deklarasi CSS tidak valid di media query                       | Tertulis `flexbox` saja tanpa properti dan nilai                                                                                                                      | Hapus, ganti dengan aturan yang benar                                                                         |

## B. Masalah Responsif (Lab 5)

| No  | Masalah                                                                                     | Perbaikan                                                                            |
| --- | ------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------ |
| 1   | Di 768px navbar tinggi karena menu menumpuk di samping logo (breakpoint `max-width: 768px`) | Mobile-first: menu kolom + `nav {width:100%}` di ponsel, baris di `min-width: 768px` |
| 2   | Di 360px kartu layanan 2 kolom, terlalu sempit                                              | Dasar `1fr`, 3 kolom mulai `min-width: 768px`                                        |
| 3   | Tabel berpotensi membuat halaman bergulir horizontal                                        | Bungkus dengan `.tabel-bungkus {overflow-x: auto}`                                   |
| 4   | Di 360px header sticky setinggi 266px karena menu menumpuk satu per baris                   | Menu `flex-wrap: wrap` dengan gap kecil; tinggi header turun menjadi 119px           |
| 5   | Link di footer (`#0d6efd` di latar `#f3f4f6`) berkontras 4,09:1, di bawah 4,5:1             | Variabel `--warna-link: #0a58ca` (kontras 5,85:1 di footer, 6,44:1 di putih)         |

## C. Code Review Sejawat (diisi oleh teman)

| Butir                                       | Ya/Tidak | Catatan |
| ------------------------------------------- | -------- | ------- |
| Penamaan class deskriptif dan konsisten     |          |         |
| Tidak ada `style` inline dan `!important`   |          |         |
| Warna dan jarak memakai variabel            |          |         |
| Tampil baik di 360, 768, 1280 px            |          |         |
| Kontras dan fokus terlihat                  |          |         |
| Tautan dan gambar berfungsi di GitHub Pages |          |         |
