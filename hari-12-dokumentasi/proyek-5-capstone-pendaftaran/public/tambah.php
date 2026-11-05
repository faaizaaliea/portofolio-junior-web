<?php
declare(strict_types=1);
/** tambah.php  admin menambah pendaftar (kuota tetap diperiksa atomik). */
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin(); // khusus admin; pendaftaran publik ada di daftar.php

$area = 'admin';
$batalUrl = 'index.php';

$pdo = getConnection();
$kelas = daftarKelasDenganSisa($pdo);
$lama = ['nama' => '', 'email' => '', 'no_hp' => '', 'alamat' => '', 'kelas_id' => 0];
$galat = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekCsrf();
    $hasil = validasiPendaftar($_POST, $pdo, null);
    $lama = $hasil['data'];
    $galat = $hasil['galat'];
    if ($galat === []) {
        try {
            if (daftarkanDenganKuota($pdo, $lama)) {
                setFlash('Pendaftar berhasil ditambahkan.');
                redirect('index.php');
            }
            $galat['email'] = 'Email sudah terdaftar.';
        } catch (KuotaPenuhException $e) {
            $galat['kelas_id'] = $e->getMessage();
        }
        $kelas = daftarKelasDenganSisa($pdo); // segarkan sisa kuota
    }
}
$judul = 'Tambah Pendaftar';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../templates/footer.php';
