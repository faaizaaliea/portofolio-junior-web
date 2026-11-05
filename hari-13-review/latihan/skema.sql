CREATE DATABASE db_pelatihan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_pelatihan;
CREATE TABLE kelas (
id INT AUTO_INCREMENT PRIMARY KEY,
nama_kelas VARCHAR(100) NOT NULL,
kuota INT NOT NULL DEFAULT 30
) ENGINE=InnoDB;
INSERT INTO kelas (nama_kelas, kuota) VALUES
('Web Development', 30),
('Mobile Development', 30),
('UI/UX Design', 30),
('SEO Optimization', 30);
CREATE TABLE pendaftar (
id INT AUTO_INCREMENT PRIMARY KEY,
nama VARCHAR(100) NOT NULL,
email VARCHAR(120) NOT NULL UNIQUE,
no_hp VARCHAR(20) NOT NULL,
kelas_id INT NOT NULL,
tgl_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT fk_pendaftar_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id)
ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;
CREATE INDEX idx_pendaftar_nama ON pendaftar (nama)