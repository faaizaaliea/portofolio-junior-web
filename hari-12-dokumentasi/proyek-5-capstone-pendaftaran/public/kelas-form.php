<?php
/**
 * kelas-form.php  tambah (tanpa ?id) atau ubah (?id=N) kelas. Wajib login.
 */
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();
$area = 'admin';

$pdo = getConnection();
$id = ambilIdPositif($_GET);
$kelasLama = null;
if (isset($_GET['id'])) {
    $kelasLama = $id === null ? null : ambilKelas($pdo, $id);
    if ($kelasLama === null) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
}

$lama = $kelasLama ?? ['nama_kelas' => '', 'kuota' => 30];
$galat = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekCsrf();
    $hasil = validasiKelas($_POST, $pdo, $id, (int) ($kelasLama['terisi'] ?? 0));
    $lama = $hasil['data'];
    $galat = $hasil['galat'];
    if ($galat === []) {
        if ($kelasLama === null) {
            tambahKelas($pdo, $lama);
            setFlash('Kelas berhasil ditambahkan.');
        } else {
            ubahKelas($pdo, (int) $id, $lama);
            setFlash('Kelas berhasil diubah.');
        }
        redirect('kelas.php');
    }
}
$judul = $kelasLama === null ? 'Tambah Kelas' : 'Ubah Kelas';
require __DIR__ . '/../templates/header.php';
?>
<div class='card'><div class='card-body'>
<form method='post' novalidate data-validasi='kelas'>
<input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>
<div class='mb-3'>
<label class='form-label' for='nama_kelas'>Nama kelas</label>
<input class='form-control <?= isset($galat['nama_kelas']) ? 'is-invalid' : '' ?>' id='nama_kelas' name='nama_kelas' maxlength='100' required value='<?= e((string) $lama['nama_kelas']) ?>'>
<?php if (isset($galat['nama_kelas'])): ?><div class='invalid-feedback'><?= e($galat['nama_kelas']) ?></div><?php endif; ?>
</div>
<div class='mb-3'>
<label class='form-label' for='kuota'>Kuota</label>
<input class='form-control <?= isset($galat['kuota']) ? 'is-invalid' : '' ?>' id='kuota' name='kuota' type='number' min='1' max='1000' required value='<?= (int) $lama['kuota'] ?>'>
<?php if (isset($galat['kuota'])): ?><div class='invalid-feedback'><?= e($galat['kuota']) ?></div><?php endif; ?>
</div>
<button class='btn btn-primary' type='submit'>Simpan</button>
<a class='btn btn-secondary' href='kelas.php'>Batal</a>
</form>
</div></div>
<?php require __DIR__ . '/../templates/footer.php';
