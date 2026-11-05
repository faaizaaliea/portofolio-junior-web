# Modul Autentikasi (`src/auth.php`)

| Bagian | Isi |
| --- | --- |
| ID modul / berkas | MOD-AUT-001 / `src/auth.php` |
| Versi dan status | 1.0.0 / aktif |
| Penulis dan tanggal | Faaiza / dibuat Hari 9, direvisi 2026-11-04 |
| Kegunaan | Autentikasi admin berbasis sesi dan `password_hash`. |
| Dependensi | `config/database.php`, `src/helpers.php`, `src/log.php` |
| Fungsi publik | `cariAdmin`, `prosesLogin`, `adminSudahLogin`, `wajibLogin`, `logout` |
| Catatan keamanan / desain | `password_verify` selalu dijalankan (hash palsu bila username tak ada) agar waktu respons setara; `session_regenerate_id(true)` saat login; pesan galat umum; rehash otomatis; logout via POST + CSRF; login gagal dicatat di log (tanpa sandi). |

## Contoh pemakaian
```php
require_once __DIR__ . "/../src/bootstrap.php";
wajibLogin(); // di awal setiap halaman admin
```

## Riwayat revisi
| Versi | Tanggal | Perubahan | Penulis |
| --- | --- | --- | --- |
| 0.3.0 | 2026-11-02 | Login admin, hash palsu, rehash, regenerasi sesi | Faaiza |
| 1.0.0 | 2026-11-04 | DocBlock; `adminSudahLogin` dipindah dari helpers; `cekLoginAdmin` dihapus; login gagal dicatat | Faaiza |
