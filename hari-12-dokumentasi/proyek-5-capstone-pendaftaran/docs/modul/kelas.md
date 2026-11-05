# Modul Kelas (`src/kelas.php`)

| Bagian | Isi |
| --- | --- |
| ID modul / berkas | MOD-KLS-001 / `src/kelas.php` |
| Versi dan status | 1.0.0 / aktif |
| Penulis dan tanggal | Faaiza / dibuat Hari 9, direvisi 2026-11-04 |
| Kegunaan | CRUD kelas pelatihan dan struktur data pendukung dashboard (hash map, daftar terurut). |
| Dependensi | `src/validasi.php`, PDO |
| Fungsi publik | `daftarKelas`, `daftarKelasDenganSisa`, `petaKelasById`, `kelasHampirPenuh`, `ambilKelas`, `validasiKelas`, `tambahKelas`, `ubahKelas`, `hapusKelas` |
| Catatan keamanan / desain | Nama kelas UNIQUE; kuota tidak boleh di bawah jumlah terisi; hapus ditolak (FK RESTRICT) bila ada pendaftar. |

## Contoh pemakaian
```php
$kelas = daftarKelasDenganSisa($pdo);
$peta  = petaKelasById($kelas);        // $peta[3]["nama_kelas"]
$top   = kelasHampirPenuh($kelas, 3);
```

## Riwayat revisi
| Versi | Tanggal | Perubahan | Penulis |
| --- | --- | --- | --- |
| 0.3.0 | 2026-11-02 | `daftarKelas`, `daftarKelasDenganSisa` (tersebar di pendaftar.php/kuota.php) | Faaiza |
| 1.0.0 | 2026-11-04 | Modul baru: CRUD kelas, `petaKelasById`, `kelasHampirPenuh` | Faaiza |
