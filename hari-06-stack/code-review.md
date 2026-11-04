# Code Review Hari 6

Berkas ditinjau: `structures.js`, `algoritma.js`, `lab1.js` sampai `lab5.js`, `editor/`, `antrian/`.

Kolom **Pemeriksaan mandiri** sudah diisi. Kolom **Dicek teman** diisi setelah bertukar kode dengan teman.

| Butir                                                         | Pemeriksaan mandiri | Dicek teman | Catatan                                                                                                                                           |
| ------------------------------------------------------------- | ------------------- | ----------- | ------------------------------------------------------------------------------------------------------------------------------------------------- |
| Struktur data dan UI dipisah berkas (modul)                   | Ya                  |             | `structures.js` hanya berisi `Stack` dan `Queue`; UI ada di `editor/js/editor.js` dan `antrian/js/antrian.js`, keduanya mengimpor `structures.js` |
| Struktur kosong dan satu elemen sudah diuji                   | Ya                  |             | `uji-struktur.js` (12 uji, `node uji-struktur.js`): pop/peek/dequeue/front pada kosong dan satu elemen; juga diuji di browser                     |
| Tidak ada akses langsung ke properti internal dari luar kelas | Ya                  |             | Properti privat `#items`, `#head`, `#tail`; UI memakai `toArray()` dan `size()`; ada uji bahwa `q.head` dan `s.items` bernilai `undefined`        |
| Nama method konsisten (push, pop, enqueue, dequeue)           | Ya                  |             | `push/pop/peek` pada Stack, `enqueue/dequeue/front` pada Queue; keduanya punya `isEmpty`, `size`, `clear`                                         |
| Alasan pemilihan struktur data tertulis                       | Ya                  |             | `analisis-struktur-data.md` (tiga studi kasus) dan komentar O(...) pada tiap method di `structures.js`                                            |

Catatan tambahan dari pemeriksaan mandiri:

- Tidak ada `innerHTML`; daftar antrean dibuat dengan `textContent`.
- Tidak ada `var`, `==`, atau `onclick` inline.
- Kode diformat dengan Prettier.
- Kelemahan yang diketahui: `Stack` berbatas membuang elemen terlama dengan `Array.shift` (O(n)) hanya saat batas tercapai (maksimal 50 elemen).

Reviewer: ********\_\_\_\_******** Tanggal: ********\_\_\_\_********

Perbaikan setelah masukan teman:

- ...
