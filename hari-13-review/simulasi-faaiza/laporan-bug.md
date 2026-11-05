# Laporan Bug - bug-simulasi.php

| ID | Jenis | Lokasi | Gejala / pesan error | Penyebab | Perbaikan | Re-test |
|----|-------|--------|----------------------|----------|-----------|---------|
| B-01 | Syntax | `hitungSisaKuota`, baris `$terisi = count($peserta)` | `Parse error: syntax error, unexpected token "return", expecting ";"` | Titik koma hilang | Menambah `;` | Jalankan `php -l bug-simulasi.php` |
| B-02 | Runtime | baris `echo 'Nama pertama: ' ...` | `Warning: Undefined array key 2` | Array hanya berisi 2 elemen (indeks 0 dan 1) | Memakai indeks `0` + `?? 'Belum tersedia'` | Jalankan `php bug-simulasi.php` |
| B-03 | Logic | `rataUmur` | Rata-rata selalu lebih kecil 1 dari seharusnya; array kosong menyebabkan `DivisionByZeroError` | Ada `- 1` setelah pembagian; array kosong tidak ditangani | Menghapus `- 1` dan menambah guard clause untuk array kosong | Uji `rataUmur([20, 30])` = 25 dan `rataUmur([])` = 0 |
