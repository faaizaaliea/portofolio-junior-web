# Bug Hunt Hari 4

Bug Hunt ini dikerjakan dari **kode latihan saya sendiri**.

| No  | File                                       | Kode bermasalah                                                         | Jenis                        | Gejala                                                                                                                                    | Perbaikan                                                                     |
| --- | ------------------------------------------ | ----------------------------------------------------------------------- | ---------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------- |
| 1   | `latihan4.js`                              | Variabel dideklarasikan `operator1`, tetapi dipakai `switch (operator)` | **Runtime** (ReferenceError) | Program berhenti: `ReferenceError: operator is not defined`                                                                               | Samakan nama variabel di deklarasi dan pemakaian (`operator`)                 |
| 2   | `latihan11.js` (lama)                      | `prompt()` dan `alert()` dijalankan lewat `node`                        | **Runtime** (ReferenceError) | `ReferenceError: prompt is not defined`                                                                                                   | `prompt/alert` hanya ada di browser; dipindah ke `latihan11.html`             |
| 3   | `latihan5.js` (lama)                       | Blok `if (hasilBMI < 18.5) ...` berada di luar `else`                   | **Logic**                    | Input tidak valid mencetak "Tidak valid" lalu tetap "Obesitas", karena `undefined < 18.5` bernilai `false` sehingga masuk `else` terakhir | Pindahkan penentuan kategori ke dalam `else` yang sama dengan perhitungan BMI |
| 4   | `latihan3.js` (lama)                       | `(durasi - 2) * 2000` memakai durasi apa adanya                         | **Logic**                    | 2,5 jam dihitung Rp4.000, seharusnya Rp5.000 (sisa jam dihitung penuh)                                                                    | `Math.ceil(durasi)` sebelum menghitung biaya                                  |
| 5   | `latihan3.js` (lama)                       | Tidak ada validasi `durasi <= 0`                                        | **Logic**                    | Durasi 0 jam tetap ditagih Rp3.000                                                                                                        | Tolak durasi `NaN` atau `<= 0`                                                |
| 6   | `latihan14.js` / `latihan15.js` (PBL lama) | `Number(inputNilai)` tanpa cek string kosong                            | **Logic**                    | Menekan Enter pada kolom nilai menghasilkan `Number("") = 0`, sehingga siswa tersimpan dengan nilai 0                                     | Cek `teksNilai === ""` sebelum konversi, selain `Number.isNaN`                |
| 7   | `latihan13.html` (lama)                    | Variabel `pesanError` tidak pernah diisi; pesan hanya `console.log`     | **Logic**                    | Pembagian dengan nol tidak menampilkan apa pun di halaman; input kosong dianggap 0                                                        | Isi `pesanError`, tampilkan lewat `textContent`, dan validasi input kosong    |
| 8   | `latihan2.md` (lama)                       | `JIKA bilangan MOD 2` tanpa pembanding; kalkulator hanya `INPUT angka2` | **Logic** (pada pseudocode)  | Kondisi tidak jelas dan data masukan tidak lengkap                                                                                        | `JIKA bilangan MOD 2 = 0`; baca `angka1`, `operator`, `angka2`                |

## Ringkasan jenis kesalahan

| Jenis         | Jumlah | Nomor                             |
| ------------- | ------ | --------------------------------- |
| Syntax error  | 0      | Semua berkas lolos `node --check` |
| Runtime error | 2      | 1, 2                              |
| Logic error   | 6      | 3, 4, 5, 6, 7, 8                  |

## Catatan tambahan

- Pada PBL lama, batas grade `85`, `70`, `55` ditulis langsung (_magic number_)
  dan diulang di dua tempat. Perbaikan: konstanta `BATAS_A`, `BATAS_B`, `BATAS_C`,
  dan grade dihitung sekali saat data dimasukkan.
- Penamaan tidak konsisten: `bil_1` (snake_case) dan campuran bahasa Inggris/Indonesia.
  Perbaikan: camelCase dan nama deskriptif.
