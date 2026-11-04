<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();
$area = 'admin';

$pdo = getConnection();
$daftar = ambilSemuaPendaftar($pdo);

$judul = 'Daftar Pendaftar';
$pakaiDataTables = true;
require __DIR__ . '/../templates/header.php';
?>
<div class='table-responsive'>
<table id='tabelPendaftar' class='table table-striped align-middle'>
<thead>
<tr><th>Nama</th><th>Email</th><th>No. HP</th><th>Kelas</th><th>Aksi</th></tr>
</thead>
<tbody>
<?php foreach ($daftar as $p): ?>
<tr>
<td><?= e($p['nama']) ?></td>
<td><?= e($p['email']) ?></td>
<td><?= e($p['no_hp']) ?></td>
<td><?= e($p['nama_kelas']) ?></td>
<td>
<a class='btn btn-sm btn-outline-secondary' href='ubah.php?id=<?= (int) $p['id'] ?>'>Ubah</a>
<a class='btn btn-sm btn-outline-primary' href='cetak.php?id=<?= (int) $p['id'] ?>' target='_blank' rel='noopener'>Cetak</a>
<form method='post' action='hapus.php' class='form-hapus d-inline' data-nama='<?= e($p['nama']) ?>'>
<input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>
<input type='hidden' name='id' value='<?= (int) $p['id'] ?>'>
<button class='btn btn-sm btn-outline-danger' type='submit'>Hapus</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php require __DIR__ . '/../templates/footer.php';