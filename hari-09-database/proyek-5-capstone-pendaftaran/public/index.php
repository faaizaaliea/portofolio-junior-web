<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

cekLoginAdmin();
$area = 'admin';

$pdo = getConnection();
$kata = trim((string) ($_GET['q'] ?? ''));
$perHalaman = 10;
$total = hitungPendaftar($pdo, $kata);
$jumlahHalaman = max(1, (int) ceil($total / $perHalaman));
$halaman = min($jumlahHalaman, max(1, (int) ($_GET['hal'] ?? 1)));
$daftar = ambilPendaftar($pdo, $kata, $perHalaman, ($halaman - 1) * $perHalaman);

$judul = 'Daftar Pendaftar';
require __DIR__ . '/../templates/header.php';
?>
<form method='get'>
    <label for='q'>Cari nama</label>
    <input id='q' name='q' value='<?= e($kata) ?>'>
    <button type='submit'>Cari</button>
</form>

<?php if ($daftar === []): ?>
    <p>Tidak ada data.</p>
<?php else: ?>
    <table>
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
                    <a href='ubah.php?id=<?= (int) $p['id'] ?>'>Ubah</a>
                    <form method='post' action='hapus.php' style='display:inline'>
                        <input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>
                        <input type='hidden' name='id' value='<?= (int) $p['id'] ?>'>
                        <button type='submit'>Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<nav aria-label='Halaman'>
    <?php for ($i = 1; $i <= $jumlahHalaman; $i++): ?>
        <a href='?hal=<?= $i ?>&q=<?= urlencode($kata) ?>'><?= $i ?></a>
    <?php endfor; ?>
</nav>
<?php require __DIR__ . '/../templates/footer.php'; ?>
