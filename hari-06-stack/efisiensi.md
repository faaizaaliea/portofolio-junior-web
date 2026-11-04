# Pengukuran Efisiensi (Lab 5)

Dijalankan dengan `node lab5.js` sebanyak tiga kali.
Waktu dalam milidetik.

| Operasi                       | Run 1   | Run 2   | Run 3   | Rata-rata     | Big-O              |
| ----------------------------- | ------- | ------- | ------- | ------------- | ------------------ |
| `Array.includes` x 1000       | 114,470 | 214,855 | 117,390 | **148,905**   | O(n) per pencarian |
| `Set.has` x 1000              | 0,042   | 0,072   | 0,041   | **0,052**     | O(1) per pencarian |
| `Array.shift` hingga kosong   | 1.068   | 1.499   | 1.063   | **1.210,000** | O(n) per operasi   |
| `Queue.dequeue` hingga kosong | 15,586  | 25,026  | 8,141   | **16,251**    | O(1) per operasi   |

> Pengukuran dilakukan pada komputer sendiri. Hasil dapat berbeda
> bergantung pada spesifikasi perangkat dan kondisi sistem.

## Penjelasan selisih dengan Big-O

- **`includes` vs `has`.** Nilai yang dicari (`N - 1`) sengaja berada di ujung array, sehingga `includes` memeriksa hampir 100.000 elemen satu per satu (O(n)) pada setiap dari 1000 pencarian, sekitar 100 juta perbandingan. `Set.has` menghitung posisi lewat hash sehingga langsung ke tujuan (O(1)). Selisihnya sekitar **4.000 kali** (141 ms lawan 0,035 ms). Harganya: Set memakai memori tambahan dan tidak punya indeks.
- **`shift` vs `dequeue`.** `Array.shift` mengambil elemen pertama lalu menggeser semua elemen sisanya satu posisi (O(n)). Mengosongkan 100.000 elemen berarti kira-kira n²/2 = 5 miliar pergeseran, sehingga hampir satu detik. `Queue.dequeue` hanya menghapus satu properti dan menambah `head` (O(1)), sehingga total O(n) untuk seluruh antrean. Selisihnya sekitar **110 kali** pada N ini, dan semakin lebar bila N bertambah: dua kali lebih banyak data membuat `shift` kira-kira empat kali lebih lama, sedangkan `dequeue` dua kali.
- **Kesimpulan.** Mengganti struktur data (Array menjadi Set, Array menjadi Queue berpenanda) lebih berpengaruh daripada mengoptimalkan baris kode.
