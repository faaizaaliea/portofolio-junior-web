# Bug Hunt Hari 5

Bug Hunt ini dikerjakan dari **kode latihan dan proyek saya sendiri**.
Setiap berkas dijalankan, diuji dengan kasus tepi, lalu kesalahan yang ditemukan diklasifikasikan.

| No  | Berkas (versi awal)                     | Kode bermasalah                                                         | Jenis       | Gejala                                                                                                    | Perbaikan                                                                       |
| --- | --------------------------------------- | ----------------------------------------------------------------------- | ----------- | --------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------- |
| 1   | `nilai-fungsi.js` (dulu `latihan1.js`)  | `Number(await rl.question(...))` lalu `nilaiValid(nilai)`               | **Logic**   | Nilai kosong atau hanya spasi tersimpan sebagai 0, karena `Number("")` bernilai 0 dan lolos 0-100         | Fungsi `ubahKeAngka()` yang menghasilkan `NaN` untuk teks kosong, plus `assert` |
| 2   | `array-peserta.js` (dulu `latihan2.js`) | `peserta.reduce(...)` tanpa nilai awal                                  | **Runtime** | `TypeError: Reduce of empty array with no initial value` bila daftar kosong                               | Periksa `daftar.length === 0` dan kembalikan `null`                             |
| 3   | `lab3-dom` (versi awal)                 | Input tugas tanpa `<form>`, hanya tombol dengan `onclick`               | **Logic**   | Menekan Enter tidak menambah tugas                                                                        | Pakai `<form>` dengan event `submit` dan `preventDefault()`                     |
| 4   | `lab3-dom` (versi awal)                 | Tugas berupa `<span>` yang diklik; input hanya ber-`placeholder`        | **Logic**   | Tugas tidak bisa difokus atau ditandai lewat keyboard; input tidak punya label bagi pembaca layar         | Kotak centang di dalam `<label>`, tombol Hapus, dan `<label for>` pada input    |
| 5   | `hari-05-modul/index.html`              | `<script type="module">` dibuka lewat `file:///`                        | **Runtime** | Console: `Access to script at 'file:///.../js/app.js' from origin 'null' has been blocked by CORS policy` | Jalankan lewat server `http://` (Live Server atau `npx serve .`)                |
| 6   | `kalkulator/js/app.js`                  | Handler `keydown` memanggil `preventDefault()` untuk semua tombol cocok | **Logic**   | `Ctrl+C`, `Ctrl+-`, dan `Ctrl++` ikut dicegat, sehingga salin dan zoom browser tidak berfungsi            | `if (event.ctrlKey \|\| event.metaKey \|\| event.altKey) return;`               |
| 7   | `validator/js/app.js`                   | Ringkasan memakai `form.elements.nama.value` apa adanya                 | **Logic**   | Nama `"  Sari  "` tampil dengan spasi di tepi                                                             | Pakai `.trim()` pada nama                                                       |

## Ringkasan jenis kesalahan

| Jenis         | Jumlah | Nomor                                        |
| ------------- | ------ | -------------------------------------------- |
| Syntax error  | 0      | Semua berkas JavaScript lolos `node --check` |
| Runtime error | 2      | 2, 5                                         |
| Logic error   | 5      | 1, 3, 4, 6, 7                                |

## Catatan tambahan

- `tampilkanLaporan` pada versi awal sepanjang 70 baris dan `main` sekitar 100 baris. Dipecah menjadi fungsi kecil yang bertanggung jawab tunggal.
- Hanya 2 dari sekitar 35 fungsi yang punya JSDoc. Sekarang semua fungsi punya JSDoc.
- Lab 3 awal memakai `onclick` inline. Diganti `addEventListener`.
- `hari-05-modul/index.html` memakai `lang="en"`; diganti `lang="id"`.
- Fungsi pada `array-peserta.js` membaca variabel global `peserta`. Sekarang data dikirim sebagai parameter agar fungsi dapat diuji.
