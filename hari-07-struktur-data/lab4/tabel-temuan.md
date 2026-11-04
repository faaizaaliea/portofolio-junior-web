# Lab 4 - Tabel Temuan Code Review (`kode-buruk.js`)

| No | Pelanggaran | Lokasi | Prinsip | Perbaikan |
| --- | --- | --- | --- | --- |
| 1 | Nama `d`, `f`, `g`, `r`, `h`, `k`, `a`, `x`, `s`, `i`, `p`, `n` tidak bermakna | seluruh berkas | Penamaan | `daftarNilai`, `hitungRataRata`, `tentukanGrade`, `rataRata`, `hitungHargaDenganPajak` |
| 2 | `var` dipakai | semua deklarasi | Guideline modern | `const` dan `let` |
| 3 | Format padat, tanpa spasi dan indentasi | `f`, `g` | Format | Jalankan Prettier |
| 4 | `==` pada `r==80` | baris `if(r==80)` | Perbandingan aman | `===` (dan operator `>=` sesuai maksud) |
| 5 | Angka ajaib 85, 70, 55, 0.11, 80 | `g`, `h`, `k`, `if` | Magic number | Konstanta `BATAS_GRADE`, `PAJAK`, `BATAS_NILAI_BAGUS` |
| 6 | `h` dan `k` identik | dua fungsi pajak | DRY | Satu fungsi `hitungHargaDenganPajak` |
| 7 | `if` bersarang tiga tingkat | `g` | KISS / guard clause | Return awal berurutan |
| 8 | Tidak ada penanganan array kosong (`a.length = 0` menghasilkan `NaN`) | `f` | Error handling | Periksa dan kembalikan 0 |
| 9 | Titik koma tidak konsisten | `f`, `g`, `console.log(g(r))` | Konsistensi | Prettier / ESLint |
| 10 | Tidak ada komentar atau JSDoc | semua fungsi | Dokumentasi | JSDoc singkat per fungsi |

Tambahan: ukuran fungsi `f` dan `g` ditulis satu baris (sulit dibaca dan di-debug); variabel global `d` dan `r` menimbulkan risiko bentrok nama; `h`/`k` dideklarasikan tetapi tidak pernah dipanggil (kode mati).

Versi bersih: lihat [`versi-bersih.js`](versi-bersih.js).
