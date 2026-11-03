<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';

mulaiSesi();
$galat = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!cekCsrf($_POST['csrf'] ?? null)) {
        http_response_code(400);
        exit('Token tidak valid. Muat ulang halaman lalu coba lagi.');
    }
    $aksi = teksDari($_POST, 'aksi');

    if ($aksi === 'login') {
        if (cobaLogin(teksDari($_POST, 'sandi'))) {
            header('Location: admin.php');
            exit;
        }
        $galat = 'Kata sandi salah.';
    } elseif ($aksi === 'hapus' && adalahAdmin()) {
        $id = filter_var(teksDari($_POST, 'id'), FILTER_VALIDATE_INT);
        if ($id !== false) {
            hapusTamu($id);
        }
        header('Location: admin.php?status=terhapus');
        exit;
    } elseif ($aksi === 'keluar') {
        keluarAdmin();
        header('Location: admin.php');
        exit;
    }
}

$admin = adalahAdmin();
$daftarTamu = $admin ? array_reverse(ambilSemuaTamu()) : [];
$token = tokenCsrf();
?>
<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Admin Buku Tamu</title>
</head>
<body>
<h1>Admin Buku Tamu</h1>

<?php if (!$admin): ?>
<?php if ($galat !== ''): ?><p role='alert'><?= e($galat) ?></p><?php endif; ?>
<form method='post' action='admin.php'>
<input type='hidden' name='csrf' value='<?= e($token) ?>'>
<input type='hidden' name='aksi' value='login'>
<p>
<label for='sandi'>Kata sandi</label><br>
<input id='sandi' name='sandi' type='password' required>
</p>
<button type='submit'>Masuk</button>
</form>
<?php else: ?>
<?php if (parameterGet('status') === 'terhapus'): ?><p role='status'>Pesan dihapus.</p><?php endif; ?>
<p>
<a href='ekspor.php'>Ekspor CSV</a> |
<a href='index.php'>Lihat buku tamu</a>
</p>
<form method='post' action='admin.php'>
<input type='hidden' name='csrf' value='<?= e($token) ?>'>
<input type='hidden' name='aksi' value='keluar'>
<button type='submit'>Keluar</button>
</form>

<?php if ($daftarTamu === []): ?>
<p>Belum ada data.</p>
<?php else: ?>
<?php foreach ($daftarTamu as $tamu): ?>
<article>
<h3><?= e($tamu['nama']) ?> &lt;<?= e($tamu['email']) ?>&gt;</h3>
<p><small><?= e($tamu['waktu']) ?> &middot; id <?= e($tamu['id']) ?></small></p>
<p><?= nl2br(e($tamu['pesan'])) ?></p>
<form method='post' action='admin.php' onsubmit="return confirm('Hapus pesan ini?')">
<input type='hidden' name='csrf' value='<?= e($token) ?>'>
<input type='hidden' name='aksi' value='hapus'>
<input type='hidden' name='id' value='<?= e($tamu['id']) ?>'>
<button type='submit'>Hapus</button>
</form>
</article>
<?php endforeach; ?>
<?php endif; ?>
<?php endif; ?>
</body>
</html>