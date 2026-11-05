<?php
/**
 * index.php  dashboard admin: tabel pendaftar (DataTables), filter kelas,
 * dan ringkasan kelas hampir penuh. Wajib login.
 */
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
wajibLogin();
$area = 'admin';

$pdo = getConnection();
$daftar = ambilSemuaPendaftar($pdo);
$kelas = daftarKelasDenganSisa($pdo);
$petaKelas = petaKelasById($kelas);       // struktur data: hash map id → kelas
$hampirPenuh = kelasHampirPenuh($kelas);  // struktur data: daftar terurut sisa kuota

$judul = 'Daftar Pendaftar';
$pakaiDataTables = true;
require __DIR__ . '/../templates/header.php';
?>
<?php if ($hampirPenuh !== []): ?>
<div class='alert alert-warning py-2' role='status'>
<strong>Kelas hampir penuh:</strong>
<?php foreach ($hampirPenuh as $k): ?>
<span class='badge text-bg-warning'><?= e($k['nama_kelas']) ?>  sisa <?= (int) $k['sisa'] ?></span>
<?php endforeach; ?>
</div>
<?php endif; ?>
<div class='row g-2 mb-3'>
<div class='col-md-4'>
<label for='filterKelas' class='form-label mb-1'>Filter kelas</label>
<select id='filterKelas' class='form-select'>
<option value=''>Semua kelas</option>
<?php foreach ($kelas as $k): ?>
<option value='<?= e($k['nama_kelas']) ?>'><?= e($k['nama_kelas']) ?></option>
<?php endforeach; ?>
</select>
</div>
</div>
<div class='table-responsive'>
<table id='tabelPendaftar' class='table table-striped align-middle'>
<thead>
<tr><th>Nama</th><th>Email</th><th>No. HP</th><th>Kelas</th><th>Aksi</th></tr>
</thead>
<tbody>
<?php foreach ($daftar as $p): ?>
<?php $k = $petaKelas[(int) $p['kelas_id']] ?? null; ?>
<tr>
<td><?= e($p['nama']) ?></td>
<td><?= e($p['email']) ?></td>
<td><?= e($p['no_hp']) ?></td>
<td title='<?= $k ? 'Terisi ' . (int) $k['terisi'] . ' dari ' . (int) $k['kuota'] : '' ?>'><?= e($p['nama_kelas']) ?></td>
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
