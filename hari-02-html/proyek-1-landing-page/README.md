# Proyek 1: Landing Page Portofolio Pribadi

Kerangka halaman portofolio pribadi untuk Proyek 1 diklat Junior Web Programmer (Hari 2). Fokus pada struktur HTML5 semantik; tampilan dibuat sederhana dengan satu berkas CSS kecil.

## Tema

Portofolio pribadi: Faaiza Muqorribati Lil'Aaliea, calon Junior Web Programmer.

## Daftar halaman

| Halaman        | Isi                                                                                                 |
| -------------- | --------------------------------------------------------------------------------------------------- |
| `index.html`   | Landing page: hero, tentang, keahlian (3 kartu), galeri (6 gambar), tabel alat, form kontak, footer |
| `tentang.html` | Profil lengkap dan perjalanan belajar (halaman tambahan, tautan dengan path relatif)                |

## Struktur folder

```text
proyek-1-landing-page/
  index.html
  tentang.html
  css/style.css
  img/
  README.md
```

## Sumber gambar

- `img/foto.png`: foto milik sendiri.
- `img/galeri-*.png`: tangkapan layar milik sendiri dari praktik Hari 1 (XAMPP, terminal, DevTools).

## Catatan

- Form kontak memakai `action="proses.php"` seperti form pendaftaran di Hari 2. Berkas `proses.php` belum dibuat (materi Hari 8), sehingga mengirim form akan menampilkan 404. Validasi yang berjalan saat ini hanya validasi HTML5 di browser.
- Cara membuka: dengan Live Server di VS Code, atau salin folder ke `htdocs` XAMPP lalu buka `http://localhost/proyek-1-landing-page/`.
