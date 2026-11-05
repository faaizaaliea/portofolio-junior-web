<?php
/**
 * ubah.php  admin mengubah pendaftar; pindah kelas diperiksa kuotanya dalam transaksi.
 */
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();
$area = 'admin';
$batalUrl = 'index.php';

$pdo = getConnection();
$id = ambilIdPositif($_GET);
$pendaftar = $id === null ? null : ambilPendaftarById($pdo, $id);
if ($pendaftar === null) {
    http_response_code(404);
    exit('Data tidak ditemukan.');
}

$kelas = daftarKelasDenganSisa($pdo);
$lama = $pendaftar;
$galat = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekCsrf();
    $hasil = validasiPendaftar($_POST, $pdo, $id);
    $lama = $hasil['data'];
    $galat = $hasil['galat'];
    if ($galat === []) {
        try {
            if (ubahDenganKuota($pdo, $id, $lama, (int) $pendaftar['kelas_id'])) {
                setFlash('Data berhasil diubah.');
                redirect('index.php');
            }
            $galat['email'] = 'Email sudah terdaftar.';
        } catch (KuotaPenuhException $e) {
            $galat['kelas_id'] = 'Kuota kelas tujuan sudah penuh.';
        }
        $kelas = daftarKelasDenganSisa($pdo);
    }
}
$judul = 'Ubah Pendaftar';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../templates/footer.php';
