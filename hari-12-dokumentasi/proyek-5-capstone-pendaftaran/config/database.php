<?php
/**
 * Modul koneksi database (PDO, satu koneksi per permintaan).
 *
 * @package    PendaftaranPelatihan
 * @version    1.0.0
 */
declare(strict_types=1);

/**
 * Mengembalikan koneksi PDO bersama (dibuat sekali, dipakai ulang).
 *
 * Mode galat EXCEPTION, fetch ASSOC, dan prepared statement asli
 * (EMULATE_PREPARES=false) supaya injeksi SQL tidak mungkin lewat parameter.
 *
 * @return PDO
 * @throws PDOException Jika MariaDB/MySQL tidak dapat dihubungi.
 */
function getConnection(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    global $konfigurasi;

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $konfigurasi['host'], $konfigurasi['nama_db']);
    $pdo = new PDO($dsn, $konfigurasi['user'], $konfigurasi['sandi'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}
