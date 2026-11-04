# Hari 5 - Fungsi, Array, DOM, dan Modularisasi

Latihan dan Proyek 2 (Kalkulator dan Form Validator) Diklat Junior Web Programmer.

## Isi folder

| Berkas                  | Isi                                                                              |
| ----------------------- | -------------------------------------------------------------------------------- |
| `nilai-fungsi.js`       | Lab 1: Pengolah Nilai versi fungsi, JSDoc, dan pengujian `console.assert`        |
| `array-peserta.js`      | Lab 2: method array pada data peserta (`filter`, `map`, `reduce`, `sort`, `Set`) |
| `lab3-dom/`             | Lab 3: counter dan daftar tugas (DOM dan event)                                  |
| `hari-05-modul/`        | Lab 4: modul ES (`export` dan `import`)                                          |
| `proyek-02-kalkulator/` | PBL: `kalkulator/` dan `validator/` (lihat README di dalamnya)                   |
| `bug-hunt.md`           | Tabel Bug Hunt dengan klasifikasi jenis kesalahan                                |
| `code-review.md`        | Ceklis code review                                                               |
| `screenshot/`           | Tangkapan layar hasil menjalankan kode                                           |

## Cara menjalankan

Latihan Node.js:

```bash
cd hari-05-dom
node nilai-fungsi.js     # bila semua pengujian lolos, assert tidak mencetak apa pun
node array-peserta.js
```

Aplikasi web (`lab3-dom/`, `hari-05-modul/`, dan proyek 2) dibuka lewat server lokal: klik kanan `index.html` lalu **Open with Live Server**, atau `npx serve .` di folder ini.

## Mengapa modul ES tidak jalan lewat `file:///` (Lab 4)

Saat `hari-05-modul/index.html` dibuka dengan klik dua kali (`file:///`), Console menampilkan:

```
Access to script at 'file:///.../js/app.js' from origin 'null' has been blocked by CORS policy
```

Browser memperlakukan halaman `file:///` sebagai origin `null` dan menolak memuat modul (`import`/`export`) dari origin lain karena kebijakan CORS. Modul ES hanya diizinkan lewat `http://` atau `https://`, jadi harus dijalankan dengan Live Server atau `npx serve .`.

## Kasus tepi yang diuji

Nilai kosong atau hanya spasi, huruf pada kolom angka, nilai di luar 0-100, array kosong, pembagian dengan nol, operator ganda, angka diawali titik, dan hasil yang sangat panjang. Daftar lengkap ada di `proyek-02-kalkulator/README.md` dan `bug-hunt.md`.

## Tangkapan layar

| Berkas                                  | Isi                                                          |
| --------------------------------------- | ------------------------------------------------------------ |
| `screenshot/lab1-assert-lolos.png`      | `node nilai-fungsi.js` berjalan tanpa pesan Assertion failed |
| `screenshot/lab1-laporan-nilai.png`     | Statistik, histogram, dan predikat siswa                     |
| `screenshot/lab1-input-tidak-valid.png` | Nilai kosong, huruf, dan 101 ditolak                         |
| `screenshot/lab2-array-peserta.png`     | Keluaran `node array-peserta.js`                             |
| `screenshot/lab3-counter-todo.png`      | Counter dan daftar tugas                                     |
| `screenshot/lab4-modul-berhasil.png`    | `hari-05-modul` lewat Live Server                            |
| `screenshot/lab4-modul-file-error.png`  | Error CORS saat dibuka lewat `file:///`                      |

Tangkapan layar kalkulator dan validator ada di `proyek-02-kalkulator/README.md`.
