<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();

$pdo = getConnection();
$lama = ['nama_kelas' => '', 'kuota' => 30]; // Nilai awal default
$galat = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekCsrf();
    $hasil = validasiKelas($_POST, $pdo, null);
    $lama = $hasil['data'];
    $galat = $hasil['galat'];

    if ($galat === []) {
        tambahKelas($pdo, $lama);  
        setFlash('Kelas baru berhasil ditambahkan.');
        redirect('kelas.php');
    }
}

$judul = 'Tambah Kelas';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/_form_kelas.php';
require __DIR__ . '/../templates/footer.php';