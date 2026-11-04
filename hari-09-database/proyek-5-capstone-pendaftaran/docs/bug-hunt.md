# Bug Hunt Hari 9

| No  | Gejala                                    | Penyebab                                        | Perbaikan                                      | Lokasi (berkas/baris) | Status |
| --- | ----------------------------------------- | ----------------------------------------------- | ---------------------------------------------- | --------------------- | ------ |
| 1   | `SQLSTATE[HY000] [1045] Access denied`    | Kredensial salah di `config.php`                | Perbaiki pengguna/sandi                        |                       | ☐      |
| 2   | `SQLSTATE[HY000] [1049] Unknown database` | Nama database salah atau belum diimpor          | Impor `skema.sql`, samakan nama                |                       | ☐      |
| 3   | `SQLSTATE[23000] 1062 Duplicate entry`    | Email ganda tidak divalidasi                    | Periksa `emailSudahAda`, tangani pesan ramah   |                       | ☐      |
| 4   | `Unknown column 'nama_kelas'` pada query  | Kolom bukan di tabel yang di-SELECT (lupa JOIN) | Tambah `JOIN kelas`                            |                       | ☐      |
| 5   | Query digabung string dari `$_GET['id']`  | Rentan SQL Injection                            | Prepared statement                             |                       | ☐      |
| 6   | `LIMIT '10'` menimbulkan error sintaks    | Nilai LIMIT terikat sebagai string              | `bindValue(..., PDO::PARAM_INT)`               |                       | ☐      |
| 7   | Seluruh data terhapus                     | `DELETE` tanpa `WHERE`                          | Tambah `WHERE id = :id`, uji pada salinan data |                       | ☐      |
