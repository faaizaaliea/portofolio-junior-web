# Lab 1 - Hasil Uji dan Pengukuran Linked List

## Uji LinkedList (`node app.js`)

| Kasus | Hasil |
| --- | --- |
| Daftar kosong: `toArray()` | `[]` |
| `remove` saat kosong | `false` |
| `addFirst(20)`, `addFirst(10)`, `addLast(30)` | `[10, 20, 30]` |
| `find(x => x === 20)` | `20` |
| `remove(10)` (kepala) | `true` -> `[20, 30]` |
| `remove(30)` (ekor, daftar dua elemen) | `true` -> `[20]` |
| `remove(30)` (tengah, daftar `[20, 30, 40]`) | `true` -> `[20, 40]` |
| `remove(40)` (ekor) | `true` -> `[20]` |
| `remove(20)` (satu-satunya elemen) | `true`, `isEmpty()` = `true` |
| `remove(99)` (tidak ada) | `false` |

## Pointer yang diubah pada `remove(data)`

```
Kepala (hapus A):     head -> [A] -> [B] -> [C]      head diarahkan ke B (lewat removeFirst)
                      head ------> [B] -> [C]

Tengah (hapus B):     [A] -> [B] -> [C]              A.next diarahkan ke C
                      [A] ---------> [C]

Ekor (hapus C):       [A] -> [B] -> [C] <- tail      B.next = null, tail diarahkan ke B
                      [A] -> [B] <- tail

Satu elemen (hapus A): head -> [A] <- tail           head = null, tail = null, size = 0

Tidak ada:            tidak ada pointer yang berubah, mengembalikan false
```

## Perbandingan kecepatan Queue (100.000 elemen, `console.time`)

| Implementasi | Pengukuran sendiri (`screenshot/`) | Pengukuran ulang | Kompleksitas `dequeue` |
| --- | --- | --- | --- |
| Queue array dengan `shift()` | 2,394 s | 1,001 s | O(n) per `shift`, O(n^2) total |
| `LinkedQueue` | 26,722 ms | 20,855 ms | O(1) per operasi, O(n) total |

Angka mutlak berbeda antarmesin, tetapi urutan besarnya konsisten: `LinkedQueue` sekitar 50-90 kali lebih cepat
karena `shift()` menggeser seluruh elemen array setiap kali dipanggil.
