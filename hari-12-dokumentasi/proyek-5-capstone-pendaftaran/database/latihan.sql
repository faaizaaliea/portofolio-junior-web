-- 1. Tampilkan semua pendaftar diurutkan nama A-Z
SELECT * 
FROM pendaftar 
ORDER BY nama ASC;

-- 2. Tampilkan 5 pendaftar terbaru
-- Mengurutkan berdasarkan tgl_daftar atau id dari yang paling besar (terbaru)
SELECT * 
FROM pendaftar 
ORDER BY tgl_daftar DESC 
LIMIT 5;

-- 3. Cari pendaftar yang namanya mengandung 'an'
SELECT * 
FROM pendaftar 
WHERE nama LIKE '%an%';

-- 4. Tampilkan nama pendaftar beserta nama kelas (JOIN)
SELECT pendaftar.nama, kelas.nama_kelas 
FROM pendaftar 
JOIN kelas ON pendaftar.kelas_id = kelas.id;


-- 5. Hitung jumlah pendaftar per kelas (GROUP BY)
SELECT kelas.nama_kelas, COUNT(pendaftar.id) AS total_pendaftar 
FROM kelas 
LEFT JOIN pendaftar ON kelas.id = pendaftar.kelas_id 
GROUP BY kelas.id, kelas.nama_kelas;

-- 6. Tampilkan kelas yang belum memiliki pendaftar (LEFT JOIN ... IS NULL)
SELECT kelas.nama_kelas 
FROM kelas 
LEFT JOIN pendaftar ON kelas.id = pendaftar.kelas_id 
WHERE pendaftar.id IS NULL;

-- 7. Ubah nomor HP satu pendaftar (dengan WHERE)
-- Contoh: Mengubah nomor HP pendaftar dengan id = 1
UPDATE pendaftar 
SET no_hp = '081299887766' 
WHERE id = 1;

-- 8. Hapus satu pendaftar
-- Contoh: Menghapus pendaftar dengan id = 2
DELETE FROM pendaftar 
WHERE id = 2;

-- 9. Coba masukkan email ganda (1062 Duplicate entry)
-- Menjalankan dua perintah INSERT berikut akan memicu error pada perintah kedua
-- karena kolom email memiliki constraint UNIQUE.
INSERT INTO pendaftar (nama, email, no_hp, kelas_id) 
VALUES ('Budi', 'budi@contoh.com', '081111', 1);

INSERT INTO pendaftar (nama, email, no_hp, kelas_id) 
VALUES ('Agus', 'budi@contoh.com', '082222', 2);
-- Output Error: ERROR 1062 (23000): Duplicate entry 'budi@contoh.com' for key 'pendaftar.email'

-- 10. Coba hapus kelas yang masih memiliki pendaftar (1451 Cannot delete or update a parent row)
-- Menjalankan perintah ini akan gagal jika kelas_id = 1 masih dipakai oleh tabel pendaftar
-- karena adanya constraint ON DELETE RESTRICT.
DELETE FROM kelas 
WHERE id = 1;
-- Output Error: ERROR 1451 (23000): Cannot delete or update a parent row: a foreign key constraint fails (`db_pelatihan`.`pendaftar`, CONSTRAINT `fk_pendaftar_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE)