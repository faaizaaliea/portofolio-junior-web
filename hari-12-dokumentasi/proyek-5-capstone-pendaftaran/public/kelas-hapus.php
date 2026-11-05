<?php
/**
 * kelas-hapus.php  menghapus kelas. POST + CSRF + login; ditolak bila masih ada pendaftar.
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
    if (hapusKelas(getConnection(), $id)) {
        catatLog('INFO', 'Kelas id=' . $id . ' dihapus');
        setFlash('Kelas berhasil dihapus.');
    } else {
        setFlash('Kelas tidak dapat dihapus karena masih memiliki pendaftar.');
    }
}
redirect('kelas.php');
