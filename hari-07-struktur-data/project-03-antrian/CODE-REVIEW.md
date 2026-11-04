# Code Review dan Ceklis Guideline - Proyek 3

| Butir ceklis guideline                  | Ya/Tidak | Catatan                                                                                                                                                                                                                               |
| --------------------------------------- | -------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Penamaan deskriptif dan konsisten       | Ya       | Kelas PascalCase (`Antrian`, `RiwayatTeks`), fungsi berawalan kata kerja (`ambilNomor`, `tanganiKetik`), konstanta UPPER_SNAKE_CASE                                                                                                   |
| Indentasi dan format seragam (Prettier) | Ya       | `npm run format:cek`: semua berkas sesuai                                                                                                                                                                                             |
| Fungsi pendek, satu tanggung jawab      | Ya       | ESLint `max-lines-per-function` (maks 20 baris) lolos; render, logika, dan penanganan event dipisah                                                                                                                                   |
| Tidak ada duplikasi dan kode mati       | Ya       | `isiDaftar` dipakai ulang untuk dua daftar; tidak ada kode yang dikomentari                                                                                                                                                           |
| Tidak ada magic number                  | Ya       | `MAKS_RIWAYAT`, `AWALAN_NOMOR`, `PANJANG_DIGIT_NOMOR`, `MAKS_RIWAYAT_PANGGILAN`; ESLint `no-magic-numbers` lolos. Satu pengecualian: teks awal `Riwayat: 0/50` di `index.html` (langsung ditimpa `renderTombolRiwayat()` saat dimuat) |
| Error ditangani dengan pesan jelas      | Ya       | `ErrorStrukturKosong` ditampilkan sebagai pesan; galat tak terduga dilempar ulang; `TypeError`/`RangeError` untuk input tidak valid                                                                                                   |
| Struktur data tidak mengakses DOM       | Ya       | `structures.js`, `antrian.js`, `riwayat.js` tidak memakai `document`/`window`/`localStorage`                                                                                                                                          |
| Lolos ESLint tanpa error                | Ya       | `npm run lint`: 0 error, 0 warning                                                                                                                                                                                                    |

## Temuan review dan tindak lanjut

| Temuan                                                 | Tindakan                                                    |
| ------------------------------------------------------ | ----------------------------------------------------------- |
| Galat undo/redo tampil di area pesan antrean           | Diperbaiki: kini tampil di `#status-simpan` dekat catatan   |
| README merujuk `docs/screenshot.png` yang belum ada    | Diperbaiki: 3 tangkapan layar ditambahkan di `docs/`        |
| `README.md` belum diformat Prettier                    | Diperbaiki dengan `prettier --write .`                      |
| `Stack` berkapasitas memakai `shift()` (O(n), n <= 50) | Didokumentasikan di README sebagai trade-off yang disengaja |
