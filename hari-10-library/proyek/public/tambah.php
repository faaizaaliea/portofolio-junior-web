<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';

// Halaman ADMIN: tambah pendaftar. Form untuk publik ada di daftar.php.
cekLoginAdmin();
$area = 'admin';
$batalUrl = 'index.php';

$pdo = getConnection();
$kelas = daftarKelas($pdo);
$lama = ['nama' => '', 'email' => '', 'no_hp' => '', 'alamat' => '', 'kelas_id' => 0];
$galat = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekCsrf();
    $hasil = validasiPendaftar($_POST, $pdo, null);
    $lama = $hasil['data'];
    $galat = $hasil['galat'];
    if ($galat === []) {
        if (tambahPendaftar($pdo, $lama)) {
            setFlash('Pendaftar berhasil ditambahkan.');
            redirect('index.php');
        }
        $galat['email'] = 'Email sudah terdaftar.';
    }
}
$judul = 'Tambah Pendaftar';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../templates/footer.php';
