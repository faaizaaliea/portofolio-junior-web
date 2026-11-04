<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';

// Halaman USER (publik): siapa pun boleh mendaftar. Admin yang login juga boleh memakainya.
$area = adminSudahLogin() ? 'admin' : 'publik';
$tujuanSetelahSimpan = $area === 'admin' ? 'index.php' : 'tambah.php';
$batalUrl = $tujuanSetelahSimpan;

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
            setFlash('Pendaftaran berhasil. Terima kasih!');
            redirect($tujuanSetelahSimpan);
        }
        $galat['email'] = 'Email sudah terdaftar.';
    }
}
$judul = 'Form Pendaftaran';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../templates/footer.php';
