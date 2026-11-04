-- soal 1 urutkan pendaftar A-Z
SELECT * 
FROM pendaftar
order by nama;

-- soal 2 urutkan 5 pendaftar terbaru
SELECT *
FROM pendaftar
order by id desc
limit 5;

-- soal 3 nama yang mengandung an
SELECT *
FROM pendaftar
WHERE nama LIKE '%an%';

-- soal 4 tampilkan nama pendaftar beserta nama kelas
SELECT p.nama, k.nama_kelas
FROM pendaftar AS p
JOIN kelas AS k ON p.kelas_id = k.id;

-- soal 5 hitung jumlah pendaftar per kelas
SELECT k.nama_kelas, COUNT(p.id) AS jumlah_pendaftar
FROM kelas AS k
LEFT JOIN pendaftar AS p ON p.kelas_id = k.id
GROUP BY k.id, k.nama_kelas;

-- soal 6 tampilkan kelas yang belum memiliki pendaftar
SELECT k.*
FROM kelas AS k
LEFT JOIN pendaftar AS p ON p.kelas_id = k.id
WHERE p.id IS NULL;

-- soal 7 ubah nomor HP satu pendaftar
UPDATE pendaftar
SET no_hp = '081234567890'
WHERE id_pendaftar = 1;

-- soal 8 hapus satu pendaftar
DELETE FROM pendaftar
WHERE id_pendaftar = 8;

-- soal 9 uji email ganda (ganti email dengan email yang sudah ada)
INSERT INTO pendaftar (nama, email, no_hp, id_kelas)
VALUES ('Joko Wiyatno', 'jokowi@gmail.com', '081234567890', 5);

-- soal 10 uji penghapusan kelas yang masih memiliki pendaftar
DELETE FROM kelas
WHERE id = 5;
