# Modul Kuota (`src/kuota.php`)

| Bagian | Isi |
| --- | --- |
| ID modul / berkas | MOD-KUO-001 / `src/kuota.php` |
| Versi dan status | 1.0.0 / aktif |
| Penulis dan tanggal | Faaiza / dibuat Hari 9, direvisi 2026-11-04 |
| Kegunaan | Mencegah kelas kelebihan peserta, termasuk saat dua permintaan bersamaan, lewat transaksi dan `SELECT … FOR UPDATE`. |
| Dependensi | `src/pendaftar.php`, `src/log.php`, PDO (InnoDB) |
| Fungsi publik | `KuotaPenuhException`, `hitungSisaKuota`, `pastikanAdaKursi`, `daftarkanDenganKuota`, `ubahDenganKuota` |
| Catatan keamanan / desain | Baris kelas dikunci selama transaksi; permintaan lain menunggu lalu melihat jumlah terbaru. Rollback pada semua jalur galat. Tabel harus InnoDB. |

## Contoh pemakaian
```php
try { daftarkanDenganKuota($pdo, $data); }
catch (KuotaPenuhException $e) { $galat["kelas_id"] = $e->getMessage(); }
```

## Riwayat revisi
| Versi | Tanggal | Perubahan | Penulis |
| --- | --- | --- | --- |
| 0.3.0 | 2026-11-02 | `daftarkanDenganKuota`, `KuotaPenuhException` | Faaiza |
| 1.0.0 | 2026-11-04 | `pastikanAdaKursi` diekstrak; `ubahDenganKuota` (BUG-12 kini atomik); log kuota penuh | Faaiza |
