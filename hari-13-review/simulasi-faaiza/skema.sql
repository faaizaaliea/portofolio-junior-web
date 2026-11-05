CREATE DATABASE IF NOT EXISTS db_simulasi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_simulasi;

CREATE TABLE IF NOT EXISTS kursus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kursus VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS peserta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    no_hp VARCHAR(20) NOT NULL,
    kursus_id INT NOT NULL,
    tgl_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_peserta_kursus FOREIGN KEY (kursus_id) REFERENCES kursus(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO kursus (nama_kursus) VALUES
    ('Web Development'),
    ('Mobile Development'),
    ('UI/UX Design'),
    ('SEO Optimization');
