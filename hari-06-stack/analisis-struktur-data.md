# Analisis Pemilihan Struktur Data (KUK 1.1 dan 1.2)

## 1. Daftar kontak ponsel dengan pencarian nama

| Bagian          | Isi                                                                                                                                                     |
| --------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Masalah         | Pengguna mengetik nama dan aplikasi harus menemukan kontaknya dengan cepat.                                                                             |
| Data            | Objek kontak `{ nama, nomor }`, perkiraan 200 sampai 5.000 kontak. Nama bisa kembar.                                                                    |
| Operasi dominan | **Pencarian menurut nama** (sangat sering), tambah kontak (jarang), hapus (jarang), tampil terurut abjad (sering).                                      |
| Alternatif      | (a) Array kontak. (b) `Map` dengan kunci nama. (c) Array terurut abjad dengan binary search.                                                            |
| Perbandingan    | Lihat tabel di bawah.                                                                                                                                   |
| Keputusan       | **`Map` dengan kunci nama (huruf kecil), nilainya array kontak** (menangani nama kembar). Untuk daftar terurut, urutkan salinan hanya saat ditampilkan. |

| Struktur                      | Cari nama persis   | Tambah                 | Kelebihan                           | Kekurangan                                               |
| ----------------------------- | ------------------ | ---------------------- | ----------------------------------- | -------------------------------------------------------- |
| Array                         | O(n)               | O(1) di ujung          | Sederhana, urutan terjaga           | Lambat bila kontak ribuan dan sering dicari              |
| **Map**                       | **O(1) rata-rata** | O(1)                   | Pencarian langsung                  | Tidak terurut; pencarian awalan ("Sa...") tidak didukung |
| Array terurut + binary search | O(log n)           | O(n) (sisip di tengah) | Terurut, mendukung pencarian awalan | Menambah kontak mahal                                    |

**Normalisasi nama.** Sebelum digunakan sebagai kunci `Map`, nama kontak dinormalisasi menggunakan `trim()` untuk menghapus spasi di awal dan akhir, serta `toLowerCase()` agar pencarian tidak membedakan huruf besar dan kecil. Dengan demikian, nama seperti "Andi", "ANDI", dan "andi" dapat dikelompokkan dalam kunci yang sama. Nilai `Map` berupa array kontak agar beberapa orang dengan nama yang sama tetap dapat disimpan.

Pencarian sambil mengetik (awalan) bukan keahlian Map. Bila fitur itu dibutuhkan, array terurut atau struktur Tree/Trie (hari berikutnya) lebih cocok. Untuk pencarian nama lengkap, Map paling sesuai.

## 2. Riwayat halaman yang dikunjungi (tombol Back dan Forward)

| Bagian          | Isi                                                                                                                                                                                                                                        |
| --------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Masalah         | Back kembali ke halaman sebelumnya, Forward maju lagi setelah Back.                                                                                                                                                                        |
| Data            | Alamat halaman (string), puluhan sampai ratusan entri.                                                                                                                                                                                     |
| Operasi dominan | Menaruh halaman baru, mengambil halaman terakhir (Back), mengembalikannya (Forward). Semuanya di **ujung terakhir**.                                                                                                                       |
| Alternatif      | (a) Array biasa dengan penanda posisi. (b) Dua Stack: `back` dan `forward`. (c) Queue.                                                                                                                                                     |
| Perbandingan    | Queue mengeluarkan yang **tertua**, padahal Back butuh yang **terbaru**, jadi tidak cocok. Array dengan indeks bisa, tetapi memotong riwayat saat menavigasi bercabang menjadi rumit. Dua Stack cocok karena semua operasi O(1) di puncak. |
| Keputusan       | **Dua Stack.**                                                                                                                                                                                                                             |

**Mengapa Stack (LIFO).** Halaman yang terakhir dibuka adalah yang pertama ingin dikembalikan saat menekan Back, tepat prinsip _Last In, First Out_.

**Cara tombol Forward bekerja** (sama seperti Undo/Redo di editor):

1. Membuka halaman baru: `push` halaman sekarang ke stack `back`, jadikan halaman baru sebagai halaman sekarang, lalu **kosongkan** stack `forward`.
2. Back: `push` halaman sekarang ke stack `forward`, lalu `pop` dari stack `back` menjadi halaman sekarang.
3. Forward: `push` halaman sekarang ke stack `back`, lalu `pop` dari stack `forward` menjadi halaman sekarang.

Tombol Back nonaktif bila stack `back` kosong, tombol Forward nonaktif bila stack `forward` kosong (`isEmpty()`).

## 3. Antrean pasien klinik dengan kasus darurat

| Bagian          | Isi                                                                                                                                                                                                                                                                                                                                                               |
| --------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Masalah         | Pasien dilayani menurut urutan datang, tetapi pasien darurat harus didahulukan.                                                                                                                                                                                                                                                                                   |
| Data            | Objek pasien `{ nomor, nama, prioritas }`, puluhan pasien per hari.                                                                                                                                                                                                                                                                                               |
| Operasi dominan | Tambah pasien (sering), ambil pasien berikutnya (sering), melihat siapa berikutnya.                                                                                                                                                                                                                                                                               |
| Alternatif      | (a) Queue biasa. (b) Satu Queue per tingkat prioritas. (c) Priority queue (heap). (d) Array yang selalu diurutkan menurut prioritas.                                                                                                                                                                                                                              |
| Perbandingan    | Lihat tabel di bawah.                                                                                                                                                                                                                                                                                                                                             |
| Keputusan       | **Priority queue.** Untuk klinik kecil dengan dua tingkat prioritas (darurat dan biasa), implementasi menggunakan **dua Queue terpisah** sudah cukup. Pasien darurat dilayani lebih dahulu, sedangkan pasien dengan tingkat prioritas sama tetap mengikuti FIFO. Jika tingkat prioritas semakin banyak atau lebih kompleks, gunakan priority queue berbasis heap. |

**Catatan keadilan layanan (starvation).** Sistem dua Queue yang selalu mendahulukan pasien darurat berisiko membuat pasien biasa menunggu terlalu lama apabila pasien darurat terus berdatangan. Dalam penerapan nyata, klinik perlu menggunakan kebijakan triase berdasarkan kondisi medis, bukan sekadar urutan prioritas statis. Pengendalian prioritas juga diperlukan agar pasien yang menunggu tetap mendapatkan pelayanan sesuai tingkat urgensinya.

**Mengapa Queue biasa tidak cukup.** Queue murni FIFO: pasien darurat yang datang terakhir harus menunggu semua pasien di depannya. Urutan layanan ditentukan oleh _kedaruratan_, bukan _waktu datang_, sedangkan Queue hanya tahu waktu datang.

| Struktur                  | Tambah     | Ambil berikutnya                 | Kelebihan                                              | Kekurangan                                                                           |
| ------------------------- | ---------- | -------------------------------- | ------------------------------------------------------ | ------------------------------------------------------------------------------------ |
| Queue biasa               | O(1)       | O(1)                             | Adil dan sederhana                                     | Tidak mengenal prioritas                                                             |
| Satu Queue per prioritas  | O(1)       | O(p), p = jumlah tingkat (kecil) | Sederhana, urutan datang tetap adil dalam satu tingkat | Tidak praktis bila tingkatnya banyak                                                 |
| **Priority queue (heap)** | O(log n)   | O(log n)                         | Prioritas apa pun, tetap efisien                       | Lebih rumit; pasien berprioritas sama perlu aturan tambahan (waktu datang) agar adil |
| Array terurut             | O(n) sisip | O(1) atau O(n) tergantung arah   | Mudah dibayangkan                                      | Sisip mahal                                                                          |

## Pertanyaan lisan: mengapa pilihan struktur data memengaruhi kecepatan program?

Karena struktur data menentukan **jumlah langkah komputasi** yang dibutuhkan untuk melakukan suatu operasi. Struktur data yang berbeda dapat memiliki kompleksitas waktu yang berbeda meskipun menyimpan data yang sama.

Contoh dari proyek hari ini (Lab 5): memeriksa apakah suatu nilai ada dalam 100.000 data sebanyak 1.000 kali. Pada `Array` menggunakan `includes`, setiap pencarian memeriksa elemen satu per satu (O(n)), dengan rata-rata waktu **148,905 ms**. Sementara itu, `Set` menggunakan `has` memiliki kompleksitas O(1) rata-rata dan membutuhkan hanya **0,052 ms**, sekitar **2.900 kali lebih cepat**.

Contoh lainnya adalah operasi antrean. `Array.shift` membutuhkan rata-rata **1.210 ms** untuk mengosongkan 100.000 elemen karena harus menggeser elemen yang tersisa (O(n) per operasi). Sebaliknya, `Queue.dequeue` dengan penanda `head` hanya membutuhkan **16,251 ms** karena tidak perlu menggeser elemen (O(1) per operasi).

Dengan demikian, pemilihan struktur data yang sesuai dengan operasi dominan dapat meningkatkan efisiensi program secara signifikan.
