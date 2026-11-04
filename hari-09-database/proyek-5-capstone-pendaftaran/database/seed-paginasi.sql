-- OPSIONAL: tambahan 15 data agar paginasi (>10 data) bisa diuji. Jalankan setelah seed.sql.
USE db_pelatihan;

INSERT INTO pendaftar (nama, email, no_hp, alamat, kelas_id) VALUES
    ('Peserta Uji 01', 'uji01@contoh.test', '081234560001', 'Alamat uji 1', 2),
    ('Peserta Uji 02', 'uji02@contoh.test', '081234560002', 'Alamat uji 2', 3),
    ('Peserta Uji 03', 'uji03@contoh.test', '081234560003', 'Alamat uji 3', 1),
    ('Peserta Uji 04', 'uji04@contoh.test', '081234560004', 'Alamat uji 4', 2),
    ('Peserta Uji 05', 'uji05@contoh.test', '081234560005', 'Alamat uji 5', 3),
    ('Peserta Uji 06', 'uji06@contoh.test', '081234560006', 'Alamat uji 6', 1),
    ('Peserta Uji 07', 'uji07@contoh.test', '081234560007', 'Alamat uji 7', 2),
    ('Peserta Uji 08', 'uji08@contoh.test', '081234560008', 'Alamat uji 8', 3),
    ('Peserta Uji 09', 'uji09@contoh.test', '081234560009', 'Alamat uji 9', 1),
    ('Peserta Uji 10', 'uji10@contoh.test', '081234560010', 'Alamat uji 10', 2),
    ('Peserta Uji 11', 'uji11@contoh.test', '081234560011', 'Alamat uji 11', 3),
    ('Peserta Uji 12', 'uji12@contoh.test', '081234560012', 'Alamat uji 12', 1),
    ('Peserta Uji 13', 'uji13@contoh.test', '081234560013', 'Alamat uji 13', 2),
    ('Peserta Uji 14', 'uji14@contoh.test', '081234560014', 'Alamat uji 14', 3),
    ('Peserta Uji 15', 'uji15@contoh.test', '081234560015', 'Alamat uji 15', 1);
