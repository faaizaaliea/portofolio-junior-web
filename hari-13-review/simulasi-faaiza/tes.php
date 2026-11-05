<?php
declare(strict_types=1);
require_once __DIR__ . '/fungsi.php';

echo 'PHP ' . PHP_VERSION . '<br>';
echo 'pdo_mysql: ' . (extension_loaded('pdo_mysql') ? 'aktif' : 'TIDAK aktif') . '<br>';
try {
    koneksi();
    echo 'Koneksi database: berhasil';
} catch (PDOException $ex) {
    echo 'Koneksi database: gagal (' . e((string) $ex->getCode()) . ')';
}
