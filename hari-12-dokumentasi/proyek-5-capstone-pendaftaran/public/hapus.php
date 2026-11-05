<?php
/**
 * hapus.php  menghapus pendaftar. Hanya POST + CSRF + login admin.
 */
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Metode tidak diizinkan.');
}
cekCsrf();
$id = ambilIdPositif($_POST);
if ($id !== null) {
    hapusPendaftar(getConnection(), $id);
    catatLog('INFO', 'Pendaftar id=' . $id . ' dihapus oleh admin_id=' . (int) $_SESSION['admin_id']);
    setFlash('Data berhasil dihapus.');
}
redirect('index.php');
