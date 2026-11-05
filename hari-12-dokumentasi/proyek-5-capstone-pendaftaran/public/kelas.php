<?php
/**
 * kelas.php  daftar kelas (Read dari CRUD kelas): kuota, terisi, sisa, aksi.
 */
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();
$area = 'admin';

$kelas = daftarKelasDenganSisa(getConnection());
$judul = 'Kelas';
require __DIR__ . '/../templates/header.php';
?>
<div class='d-flex justify-content-between align-items-center mb-3'>
<h2 class='h4 mb-0'>Kelas Pelatihan</h2>
<a class='btn btn-primary btn-sm' href='kelas-form.php'>Tambah Kelas</a>
</div>
<div class='table-responsive'>
<table class='table table-striped align-middle'>
<thead><tr><th>Nama kelas</th><th>Kuota</th><th>Terisi</th><th>Sisa</th><th>Aksi</th></tr></thead>
<tbody>
<?php foreach ($kelas as $k): ?>
<tr>
<td><?= e($k['nama_kelas']) ?></td>
<td><?= (int) $k['kuota'] ?></td>
<td><?= (int) $k['terisi'] ?></td>
<td><?= (int) $k['sisa'] ?></td>
<td>
<a class='btn btn-sm btn-outline-secondary' href='kelas-form.php?id=<?= (int) $k['id'] ?>'>Ubah</a>
<form method='post' action='kelas-hapus.php' class='form-hapus d-inline' data-nama='<?= e($k['nama_kelas']) ?>'>
<input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>
<input type='hidden' name='id' value='<?= (int) $k['id'] ?>'>
<button class='btn btn-sm btn-outline-danger' type='submit'>Hapus</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if ($kelas === []): ?>
<tr><td colspan='5' class='text-center text-muted'>Belum ada kelas.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<?php require __DIR__ . '/../templates/footer.php';
