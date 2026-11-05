-- Skema lengkap Pendaftaran Pelatihan v1.0.0 (MariaDB 10.x / MySQL 8, InnoDB, utf8mb4).
-- Jalankan sekali:  mysql -u root < database/skema.sql
CREATE DATABASE IF NOT EXISTS db_pelatihan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_pelatihan;

CREATE TABLE IF NOT EXISTS kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(100) NOT NULL UNIQUE,
    kuota INT NOT NULL DEFAULT 30,
    CONSTRAINT ck_kelas_kuota CHECK (kuota >= 1)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pendaftar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    no_hp VARCHAR(20) NOT NULL,
    alamat TEXT NULL,
    kelas_id INT NOT NULL,
    tgl_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pendaftar_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;
CREATE INDEX idx_pendaftar_nama ON pendaftar (nama);
CREATE INDEX idx_pendaftar_kelas ON pendaftar (kelas_id);

CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
