-- Skema Sistem Pendaftaran Pelatihan (Proyek 5 - Capstone)
-- Impor dari nol: phpMyAdmin > tab Import, atau tab SQL.
DROP DATABASE IF EXISTS db_pelatihan;
CREATE DATABASE db_pelatihan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_pelatihan;

CREATE TABLE kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(100) NOT NULL,
    kuota INT NOT NULL DEFAULT 30
) ENGINE=InnoDB;

CREATE TABLE pendaftar (
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

-- Rancangan Sprint 0: akun admin (dipakai penuh pada Hari 11).
-- Saat ini login memakai admin_password_hash di config/config.php.
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
