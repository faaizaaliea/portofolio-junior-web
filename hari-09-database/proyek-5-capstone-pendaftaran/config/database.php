<?php

declare(strict_types=1);

function getConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        global $konfigurasi;

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=utf8mb4',
            $konfigurasi['host'],
            $konfigurasi['nama_db']
        );

        $pdo = new PDO($dsn, $konfigurasi['user'], $konfigurasi['sandi'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    return $pdo;
}
