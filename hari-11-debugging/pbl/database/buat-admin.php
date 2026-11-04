<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit('Hanya dapat dijalankan dari terminal.');
}

// getConnection() membaca $konfigurasi (global). Di CLI bootstrap.php tidak dimuat, jadi muat sendiri.
$konfigurasi = require __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$username = $argv[1] ?? '';
$sandi = $argv[2] ?? '';

// BUG-03/04: username divalidasi (panjang & karakter) sebelum menyentuh database.
if (preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username) !== 1 || mb_strlen($sandi) < 8) {
    exit('Gunakan: php buat-admin.php <username 3-50 karakter: huruf/angka/_.-> <sandi minimal 8 karakter>' . PHP_EOL);
}

try {
    $stmt = getConnection()->prepare('INSERT INTO admin (username, password_hash) VALUES (:u, :p)');
    $stmt->execute(['u' => $username, 'p' => password_hash($sandi, PASSWORD_DEFAULT)]);
} catch (PDOException $e) {
    // 23000 = pelanggaran constraint (username UNIQUE sudah ada)
    if ($e->getCode() === '23000') {
        exit('Username "' . $username . '" sudah dipakai.' . PHP_EOL);
    }
    throw $e;
}

echo 'Admin dibuat.' . PHP_EOL;
