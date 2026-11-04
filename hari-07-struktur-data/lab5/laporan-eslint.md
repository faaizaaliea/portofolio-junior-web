# Lab 5 - Laporan ESLint Sebelum dan Sesudah

Konfigurasi: `eslint.config.js` (rules `eqeqeq`, `no-var`, `prefer-const`, `no-unused-vars`) dan `.prettierrc`.
Perintah: `npx eslint <berkas>` dan `npx prettier --check .`

| Berkas | Error | Warning | Total |
| --- | --- | --- | --- |
| `kode-buruk.js` (sebelum) | 7 | 2 | 9 |
| `kode-bersih.js` (sesudah) | 0 | 0 | 0 |

## Rincian temuan sebelum perbaikan

| Aturan | Jumlah | Tingkat | Perbaikan |
| --- | --- | --- | --- |
| `no-var` | 6 | error | ganti `var` dengan `const` / `let` |
| `eqeqeq` | 1 | error | `==` menjadi `===` (atau `>=` sesuai maksud) |
| `no-unused-vars` | 2 | warning | fungsi `h` dan `k` tidak pernah dipanggil (kode mati); digabung menjadi `hitungHargaDenganPajak` dan dipakai |

`npx prettier --write .` dijalankan pada `kode-bersih.js` dan `eslint.config.js`; hasil akhir `npx prettier --check .`: seluruh berkas sesuai.
`kode-buruk.js` sengaja dimasukkan ke `.prettierignore` agar tetap menjadi contoh "sebelum".

## Catatan "Proyek 2"

Modul meminta lint juga dijalankan pada Proyek 2. Berkas Proyek 2 tidak ada di paket ini, jadi bagian itu belum dilaporkan.
Jalankan `npx eslint .` di folder Proyek 2 lalu tambahkan barisnya ke tabel di atas.
Untuk Proyek 3 hasilnya: `npm run lint` = 0 error, 0 warning.
