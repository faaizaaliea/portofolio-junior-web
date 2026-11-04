# Ceklis Code Review Hari 5

Berkas ditinjau: `nilai-fungsi.js`, `array-peserta.js`, `lab3-dom/`, `hari-05-modul/`, dan `proyek-02-kalkulator/`.

Kolom **Pemeriksaan mandiri** sudah diisi. Kolom **Dicek teman** diisi setelah bertukar kode dengan teman.

| Butir                                                  | Pemeriksaan mandiri | Dicek teman | Catatan                                                                                               |
| ------------------------------------------------------ | ------------------- | ----------- | ----------------------------------------------------------------------------------------------------- |
| Tidak ada fungsi lebih dari sekitar 20 baris           | Hampir              |             | Terpanjang: `main` 30 baris (menu berulang dan `switch`) dan `buatItem` 23 baris (membuat elemen DOM) |
| Tidak ada duplikasi kode (DRY)                         | Ya                  |             | Grade lewat satu fungsi `tentukanGrade`; objek `aturan` validator disederhanakan                      |
| Event dipasang dengan `addEventListener`, bukan inline | Ya                  |             | Tidak ada `onclick` inline; event delegation dipakai pada kalkulator dan daftar tugas                 |
| Data pengguna ditampilkan dengan `textContent`         | Ya                  |             | Tidak ada `innerHTML`; uji `<b>` dan `<img onerror>` tampil sebagai teks                              |
| Kasus tepi diuji dan terdokumentasi                    | Ya                  |             | Lihat `README.md` dan `bug-hunt.md`                                                                   |
| Fungsi publik memiliki JSDoc                           | Ya                  |             | Semua fungsi pada berkas yang ditinjau                                                                |

Reviewer: ____________________ Tanggal: ____________________

Perbaikan setelah masukan teman:

- ...
