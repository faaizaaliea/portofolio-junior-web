# Hari 6 - Struktur Data I: Array, Objek, Stack, dan Queue

Latihan dan PBL (bagian 1 Proyek 3) Diklat Junior Web Programmer: implementasi Stack dan Queue, editor Undo/Redo, dan Antrean Loket.

## Isi folder

| Berkas / folder             | Isi                                                                                  |
| --------------------------- | ------------------------------------------------------------------------------------ |
| `structures.js`             | Kelas `Stack` dan `Queue` (O(1)) dengan JSDoc dan properti privat                    |
| `algoritma.js`              | `kurungSeimbang`, `balikString`, `isPalindrom` (memakai Stack)                       |
| `lab1.js`                   | Lab 1: array dan objek data peserta (10 peserta, enam soal)                          |
| `lab2.js`                   | Lab 2: Map dan Set (frekuensi kata, duplikat email, irisan, `adaDuplikat`)           |
| `lab3-stack.js`             | Lab 3: uji Stack, kurung seimbang, balik string, palindrom                           |
| `lab4-queue.js`             | Lab 4: uji Queue (halaman ada di `antrian/`)                                         |
| `lab5.js`                   | Lab 5: pengukuran efisiensi                                                          |
| `uji-struktur.js`           | 12 uji otomatis (kasus kosong dan satu elemen)                                       |
| `editor/`                   | PBL Fitur A: editor catatan Undo/Redo (dua Stack, batas 50 langkah, debounce 500 ms) |
| `antrian/`                  | PBL Fitur B: Antrean Loket (nomor reset tiap hari, riwayat 5 nomor, tombol Reset)    |
| `efisiensi.md`              | Tabel hasil pengukuran Lab 5 dan penjelasan Big-O                                    |
| `analisis-struktur-data.md` | Analisis pemilihan struktur data (tiga studi kasus) dan jawaban pertanyaan lisan     |
| `bug-hunt.md`               | Tabel Bug Hunt dengan klasifikasi jenis kesalahan                                    |
| `code-review.md`            | Ceklis code review                                                                   |
| `screenshot/`               | Tangkapan layar hasil menjalankan kode (lihat `DAFTAR-SCREENSHOT.md`)                |

## Cara menjalankan

Latihan Node.js (Node 18 atau lebih baru):

```bash
cd hari-06-struktur-data
node uji-struktur.js
node lab1.js
node lab2.js
node lab3-stack.js
node lab4-queue.js
node lab5.js
```

Aplikasi web memakai ES Modules, jadi dibuka lewat server lokal (bukan klik dua kali berkas): klik kanan `index.html` lalu **Open with Live Server**, atau jalankan `npx serve .` di folder ini dan buka `/editor/` serta `/antrian/`.

## Fitur aplikasi

**Editor Undo/Redo** (`editor/`)

- Dua Stack: `undoStack` menyimpan versi teks sebelumnya, `redoStack` menyimpan versi yang dibatalkan.
- Mengetik hal baru mengosongkan `redoStack`.
- Riwayat dibatasi 50 langkah (`new Stack(50)`), snapshot disimpan setelah berhenti mengetik 500 ms (debounce), dan menekan Undo menyimpan dulu ketikan yang belum tersimpan.
- Tombol nonaktif bila stack kosong; info jumlah riwayat memakai `aria-live`.

**Antrean Loket** (`antrian/`)

- Ambil Nomor (`A001`, `A002`, ...), Panggil Berikutnya (FIFO), dan Reset.
- Nomor di-reset otomatis bila tanggal berganti (keadaan disimpan di `localStorage` bersama tanggalnya).
- Menampilkan 5 nomor terakhir yang sudah dipanggil; memanggil saat kosong menampilkan "Antrean kosong" tanpa menghapus nomor sebelumnya.

## Kasus tepi yang diuji

- [x] `pop`, `peek` pada Stack kosong tidak error (`undefined`)
- [x] `dequeue`, `front` pada Queue kosong tidak error (`undefined`)
- [x] Stack dan Queue dengan satu elemen
- [x] Queue dipakai kembali setelah kosong
- [x] Stack berbatas membuang elemen terlama
- [x] `kurungSeimbang("")` bernilai `true`, `")("` bernilai `false`
- [x] `adaDuplikat` pada array kosong dan satu elemen
- [x] Kalimat dengan spasi ganda dan huruf besar pada penghitung frekuensi
- [x] Undo sampai habis, Redo sampai ujung, dan mengetik baru menghapus jalur redo
- [x] Memanggil nomor saat antrean kosong
- [x] Antrean setelah halaman dimuat ulang dan setelah tanggal berganti
- [x] Tampilan 360 px tanpa scroll horizontal

## Hasil pengukuran efisiensi (ringkas)

| Operasi                       | Rata-rata (ms) | Big-O              |
| ----------------------------- | -------------- | ------------------ |
| `Array.includes` x 1000       | 148,905        | O(n) per pencarian |
| `Set.has` x 1000              | 0,052          | O(1) per pencarian |
| `Array.shift` hingga kosong   | 1.210,000      | O(n) per operasi   |
| `Queue.dequeue` hingga kosong | 16,251         | O(1) per operasi   |

Penjelasan lengkap ada di `efisiensi.md`.

## Tangkapan layar

| Berkas                              | Isi                                                |
| ----------------------------------- | -------------------------------------------------- |
| `screenshot/uji-struktur-lolos.png` | `node uji-struktur.js` berakhir 12 uji lolos       |
| `screenshot/lab3-stack-kurung.png`  | Keluaran `node lab3-stack.js`                      |
| `screenshot/lab4-queue-uji.png`     | Keluaran `node lab4-queue.js`                      |
| `screenshot/lab5-efisiensi.png`     | Keluaran `node lab5.js`                            |
| `screenshot/editor-undo-redo.png`   | Editor dengan teks, Undo/Redo aktif, info riwayat  |
| `screenshot/antrian-loket.png`      | Antrean berisi nomor, nomor dipanggil, dan riwayat |
