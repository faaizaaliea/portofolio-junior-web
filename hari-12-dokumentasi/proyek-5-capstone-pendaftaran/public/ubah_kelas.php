<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();

$pdo = getConnection();
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$kelas = $id === false || $id === null ? null : ambilKelasById($pdo, $id);

if ($kelas === null) {
    http_response_code(404);
    exit('Data kelas tidak ditemukan.');
}

$lama = $kelas;
$galat = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekCsrf();
    $hasil = validasiKelas($_POST, $pdo, $id);
    $lama = $hasil['data'];
    $galat = $hasil['galat'];

    if ($galat === []) {
        ubahKelas($pdo, $id, $lama);
        setFlash('Data kelas berhasil diubah.');
        redirect('kelas.php');
    }
}

$judul = 'Ubah Kelas';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/_form_kelas.php';
require __DIR__ . '/../templates/footer.php';