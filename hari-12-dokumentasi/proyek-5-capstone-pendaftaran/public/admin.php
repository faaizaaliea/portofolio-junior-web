<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';
//cekLoginAdmin();
wajibLogin();
$pdo = getConnection();
$kata = trim((string) ($_GET['q'] ?? ''));
$perHalaman = 10;
$total = hitungPendaftar($pdo, $kata);
$jumlahHalaman = max(1, (int) ceil($total / $perHalaman));
$halaman = min($jumlahHalaman, max(1, (int) ($_GET['hal'] ?? 1)));
$daftar = ambilSemuaPendaftar($pdo, $kata, $perHalaman, ($halaman - 1) * $perHalaman);

$judul = 'Daftar Pendaftar';
require __DIR__ . '/../templates/header.php';
?>

<?php if ($daftar === []): ?>
  <p>Tidak ada data.</p>
<?php else: ?>
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
<?php endif; ?>


<?php require __DIR__ . '/../templates/footer.php'; ?>
