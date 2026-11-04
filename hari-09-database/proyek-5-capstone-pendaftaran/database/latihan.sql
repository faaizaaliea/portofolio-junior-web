-- Lab 1 - Latihan SQL (jalankan setelah skema.sql dan seed.sql)
USE db_pelatihan;

-- Soal 1: semua pendaftar diurutkan nama A-Z
SELECT id, nama, email, no_hp, kelas_id
FROM pendaftar
ORDER BY nama ASC;

-- Soal 2: 5 pendaftar terbaru
SELECT id, nama, email, tgl_daftar
FROM pendaftar
ORDER BY id DESC
LIMIT 5;

-- Soal 3: nama mengandung "an"
SELECT id, nama, email
FROM pendaftar
WHERE nama LIKE '%an%';

-- Soal 4: nama pendaftar beserta nama kelas (JOIN)
SELECT p.nama, k.nama_kelas
FROM pendaftar AS p
JOIN kelas AS k ON k.id = p.kelas_id;

-- Soal 5: jumlah pendaftar per kelas (GROUP BY)
SELECT k.nama_kelas, COUNT(p.id) AS jumlah_pendaftar
FROM kelas AS k
LEFT JOIN pendaftar AS p ON p.kelas_id = k.id
GROUP BY k.id, k.nama_kelas;

-- Soal 6: kelas yang belum memiliki pendaftar (LEFT JOIN ... IS NULL)
-- Hasil kosong jika semua kelas sudah punya pendaftar; itu wajar.
SELECT k.id, k.nama_kelas, k.kuota
FROM kelas AS k
LEFT JOIN pendaftar AS p ON p.kelas_id = k.id
WHERE p.id IS NULL;

-- Soal 7: ubah nomor HP satu pendaftar (WHERE wajib!)
UPDATE pendaftar
SET no_hp = '081234567890'
WHERE id = 1;

-- Soal 8: hapus satu pendaftar (WHERE wajib!)
DELETE FROM pendaftar
WHERE id = 9;

-- Soal 9: email ganda -> diharapkan error 1062 Duplicate entry
-- (jokowi@gmail.com sudah dipakai pendaftar id 1)
INSERT INTO pendaftar (nama, email, no_hp, kelas_id)
VALUES ('Joko Wiyatno', 'jokowi@gmail.com', '081234567890', 1);

-- Soal 10: hapus kelas yang masih punya pendaftar -> diharapkan error 1451
DELETE FROM kelas
WHERE id = 1;
