<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode tidak diizinkan.');
}

cekCsrf();
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($id !== false && $id !== null) {
    try {
        hapusKelas(getConnection(), $id);
        setFlash('Data kelas berhasil dihapus.');
    } catch (RuntimeException $e) {
        setFlash($e->getMessage()); // Jika gagal karena kelas sudah dipakai
    }
}
redirect('kelas.php');