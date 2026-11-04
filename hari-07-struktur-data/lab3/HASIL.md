# Lab 3 - Hasil Pencarian dan Pengurutan

Jalankan: `node pencarian-pengurutan.js` (data acak memakai benih tetap sehingga jumlah perbandingan bisa diulang).

## 1. Uji kebenaran

Seluruh uji LULUS untuk `bubbleSort`, `insertionSort`, `mergeSort`, dan `Array.sort` pada 5 kasus
(array kosong, satu elemen, sudah terurut, terbalik, berisi duplikat), serta `cariLinear` dan `cariBiner`
(data ada, tidak ada, kosong, satu elemen).

## 2. Jumlah perbandingan, 1.000 data

| Data | Bubble sort (dengan penanda tukar) | Insertion sort |
| --- | --- | --- |
| Acak | 499.200 | 247.892 |
| Terurut | 999 | 999 |
| Terbalik | 499.500 | 499.500 |

Penjelasan:
- Terbalik adalah kasus terburuk: keduanya mendekati n(n-1)/2 = 499.500, yaitu O(n^2).
- Terurut adalah kasus terbaik: keduanya hanya n-1 = 999 perbandingan, yaitu O(n). Bubble sort bisa berhenti cepat karena penanda `adaTukar`.
- Pada data acak insertion sort memakai sekitar setengah perbandingan bubble sort, karena insertion berhenti begitu posisi elemen ditemukan.

## 3. Waktu eksekusi

| Jumlah data | Bubble sort | Merge sort | `Array.sort` |
| --- | --- | --- | --- |
| 10.000 | 171,12 ms | 17,48 ms | 4,81 ms |
| 100.000 | tidak dijalankan (perkiraan ±17 detik: 171 ms × 100) | 94,92 ms | 36,27 ms |

Penjelasan Big-O:
- Bubble sort O(n^2): data naik 10 kali (10.000 -> 100.000) berarti kerja naik sekitar 100 kali, sehingga 171 ms menjadi sekitar 17 detik atau lebih. Karena itu bubble sort dilewati pada 100.000 data.
- Merge sort O(n log n): data naik 10 kali, waktu naik sekitar 5-11 kali (17 ms menjadi 95 ms), jauh lebih landai.
- `Array.sort` (O(n log n), diimplementasikan native) paling cepat; untuk produksi gunakan ini dengan pembanding `(a, b) => a - b`.
- Waktu bergantung mesin dan beban saat uji; yang konsisten adalah urutan dan laju pertumbuhannya.
