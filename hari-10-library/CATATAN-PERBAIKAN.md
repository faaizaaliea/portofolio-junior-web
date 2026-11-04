# Catatan Perbaikan Hari 10

## Cara memakai zip ini
1. Salin isi `proyek/` ke proyekmu (timpa file yang sama). **Jangan timpa `config/config.php`**; file itu tidak ada di zip.
2. Hapus `daftar-library.md` lama di root, pakai `docs/daftar-library.md` (isi baris bertanda ISI).
3. Di folder `proyek/`, sinkronkan kunci setelah `composer.json` berubah:
   `composer update --lock` (bila gagal: `composer update dompdf/dompdf setasign/fpdf`), lalu `composer audit`.
4. Salin folder `docs/`, `hari-10-update/`, dan `screenshot/rename-screenshot.ps1` ke repo.
5. Uji di browser, lalu ambil screenshot sesuai `docs/DAFTAR-SCREENSHOT.md`.

## Yang diubah
| Berkas | Perubahan |
| --- | --- |
| `templates/footer.php` | Path skrip diperbaiki ke `js/app.js`; `js/daftar-pendaftar.js` dimuat hanya di halaman daftar |
| `public/js/app.js` | Hanya toast; DataTables dan konfirmasi hapus di `daftar-pendaftar.js` (tidak lagi ganda) |
| `templates/header.php` | Navbar Bootstrap dengan toggler, `bg-light` |
| `public/login.php` | Memakai template dan card Bootstrap, galat dengan `alert` |
| `public/daftar.php` (baru) | Form pendaftaran publik; `tambah.php` kini khusus admin |
| `public/cetak.php`, `cetak-fpdf.php` | Wajib login admin |
| `composer.json` | `name`, `php >=8.1`, versi `^3.1` dan `^1.9` |
| `.gitignore` | Menambah `public/injeksi.php` (demo Hari 9, jangan di-push) |
| `docs/` (baru) | Daftar library (pindah + bagian pembaruan), Lab 6, tabel temuan, daftar screenshot |

## Yang belum bisa saya lakukan
- Menjalankan aplikasi (tidak ada PHP/MySQL di sini), jadi uji manual perlu kamu lakukan.
- Mengisi hasil `composer outdated`, `npm outdated`, dan Lab 6 (harus dari terminalmu).
- Atribut SRI (`integrity`) pada CDN: hash harus disalin dari situs resmi library.
- Commit dan push, kuis, dan tabel ceklis review sejawat.
