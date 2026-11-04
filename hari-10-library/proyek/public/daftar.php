<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';

// Halaman PUBLIK: siapa pun boleh mendaftar tanpa login.
$area = adminSudahLogin() ? 'admin' : 'publik';
$batalUrl = $area === 'admin' ? 'index.php' : 'daftar.php';

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
            redirect('daftar.php');
        }
        $galat['email'] = 'Email sudah terdaftar.';
    }
}
$judul = 'Form Pendaftaran';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../templates/footer.php';
