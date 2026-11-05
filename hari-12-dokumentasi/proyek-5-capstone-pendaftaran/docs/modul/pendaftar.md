# Modul Pendaftar (`src/pendaftar.php`)

| Bagian | Isi |
| --- | --- |
| ID modul / berkas | MOD-PND-001 / `src/pendaftar.php` |
| Versi dan status | 1.0.0 / aktif |
| Penulis dan tanggal | Faaiza / dibuat Hari 9, direvisi 2026-11-04 |
| Kegunaan | Query dan validasi data peserta (tanpa aturan kuota, yang ada di kuota.php). |
| Dependensi | `config/database.php` (PDO), `src/validasi.php`, `src/kelas.php` (`daftarKelas`) |
| Fungsi publik | `ambilSemuaPendaftar`, `ambilPendaftarById`, `emailSudahAda`, `validasiPendaftar`, `tambahPendaftar`, `ubahPendaftar`, `hapusPendaftar`, `ambilBuktiPendaftar` |
| Catatan keamanan / desain | Semua query memakai prepared statement. Email dinormalkan ke huruf kecil. Pelanggaran UNIQUE (SQLSTATE 23000) dikembalikan sebagai `false`, bukan exception. |

## Contoh pemakaian
```php
$hasil = validasiPendaftar($_POST, getConnection(), null);
if ($hasil["galat"] === []) { daftarkanDenganKuota(getConnection(), $hasil["data"]); }
```

## Riwayat revisi
| Versi | Tanggal | Perubahan | Penulis |
| --- | --- | --- | --- |
| 0.1.0 | Hari 9 | CRUD pendaftar dan validasi dasar | Faaiza |
| 0.3.0 | 2026-11-02 | Validasi HP 10–13 digit; email ganda → false | Faaiza |
| 1.0.0 | 2026-11-04 | DocBlock lengkap; `kelas_id` ikut di daftar; HP/nama memakai `src/validasi.php`; `ambilIdPositif`; fungsi pencarian server-side (`hitungPendaftar`, `ambilPendaftar`) dihapus karena tidak terpakai | Faaiza |
