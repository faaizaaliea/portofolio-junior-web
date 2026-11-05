<?php
/**
 * daftar.php  form pendaftaran PUBLIK (tanpa login). Validasi server + kuota atomik.
 */
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';

$area = adminSudahLogin() ? 'admin' : 'publik';
$batalUrl = $area === 'admin' ? 'index.php' : 'daftar.php';

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
                setFlash('Pendaftaran berhasil. Terima kasih!');
                redirect('daftar.php?status=sukses');
            }
            $galat['email'] = 'Email sudah terdaftar.';
        } catch (KuotaPenuhException $e) {
            $galat['kelas_id'] = $e->getMessage();
        }
        $kelas = daftarKelasDenganSisa($pdo); // segarkan sisa kuota
    }
}
$judul = 'Form Pendaftaran';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../templates/footer.php';
