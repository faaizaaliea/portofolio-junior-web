<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();
$area = 'admin';
$batalUrl = 'index.php';
$pdo = getConnection();
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range'
=> 1]]);
$pendaftar = $id === false || $id === null ? null : ambilPendaftarById($pdo, $id);
if ($pendaftar === null) {
http_response_code(404);
exit('Data tidak ditemukan.');
}
$kelas = daftarKelas($pdo);
$lama = $pendaftar;
$galat = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
cekCsrf();
$hasil = validasiPendaftar($_POST, $pdo, $id);
$lama = $hasil['data'];
$galat = $hasil['galat'];
if ($galat === [] && (int) $lama['kelas_id'] !== (int) $pendaftar['kelas_id']
&& hitungSisaKuota($pdo, (int) $lama['kelas_id']) <= 0) {
$galat['kelas_id'] = 'Kuota kelas tujuan sudah penuh.';
}
if ($galat === []) {
if (ubahPendaftar($pdo, $id, $lama)) {
setFlash('Data berhasil diubah.');
redirect('index.php');
}
$galat['email'] = 'Email sudah terdaftar.';
}
}
$judul = 'Ubah Pendaftar';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../templates/footer.php';
