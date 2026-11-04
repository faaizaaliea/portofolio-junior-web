-- Data contoh: 3 kelas, 9 pendaftar (ada nama yang mengandung kata sama).
USE db_pelatihan;

INSERT INTO kelas (nama_kelas, kuota) VALUES
    ('Web Programming', 30),
    ('Desain Grafis', 25),
    ('UI/UX Designer', 40);

INSERT INTO pendaftar (nama, email, no_hp, alamat, kelas_id) VALUES
    ('Joko Widodo', 'jokowi@gmail.com', '089348738371', 'Jalan Kutai Utara Nomor 1', 1),
    ('Joko Sutepo', 'jokosu@gmail.com', '081736374883', 'Jalan Suka Suka Saya No. 23', 1),
    ('Prabowo Subianto', 'prabted@gmail.com', '081762734838', 'Jalan Kertanegara Nomor 4', 2),
    ('Teddy Indra Wijaya', 'bunted@gmail.com', '084721273839', 'Jalan Peluk Manjah No 34', 1),
    ('Indratno Waluyo', 'indratno@gmail.com', '081191837389', 'Jalan Siapa No 89', 3),
    ('Wijaya Dwi Kusuma', 'wdk@gmail.com', '081729387498', 'Jalan Hei Antek No. 23', 2),
    ('Siti Similikiti', 'sitisimi@gmail.com', '089192838371', 'Jalan Bapak Aing no 76', 3),
    ('Samsul Samuel Sullivan', 'samsul@gmail.com', '080191829938', 'Jalan Asam Sulfat no 89', 2),
    ('Siti Gatelen', 'sitelen@gmail.com', '081019828883', 'Jalan Gudik no 48', 2);
