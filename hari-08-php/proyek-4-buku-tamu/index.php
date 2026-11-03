<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';

siapkanFolderData();

$galat = [];
$lama = ['nama' => '', 'email' => '', 'pesan' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hasil = validasiTamu($_POST);
    $lama = $hasil['data'];
    $galat = $hasil['galat'];

    if ($galat === []) {
        $ip = alamatIp();
        $sisa = sisaJeda($ip);
        if ($sisa > 0) {
            $galat['umum'] = 'Tunggu ' . $sisa . ' detik sebelum mengirim pesan lagi.';
        } elseif (simpanTamu($hasil['data'])) {
            catatKirim($ip);
            header('Location: index.php?status=sukses');
            exit;
        } else {
            $galat['umum'] = 'Gagal menyimpan data. Silakan coba lagi.';
        }
    }
}

$kata = parameterGet('q');
$daftarTamu = array_reverse(cariTamu(ambilSemuaTamu(), $kata)); // terbaru di atas
$hal = paginasi($daftarTamu, (int) parameterGet('hal'));
?>
<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Buku Tamu</title>
</head>
<body>
<h1>Buku Tamu</h1>

<?php if (parameterGet('status') === 'sukses'): ?>
<p role='status'>Terima kasih, pesan Anda sudah tersimpan.</p>
<?php endif; ?>
<?php if (isset($galat['umum'])): ?>
<p role='alert'><?= e($galat['umum']) ?></p>
<?php endif; ?>

<form method='post' action='index.php'>
<p>
<label for='nama'>Nama</label><br>
<input id='nama' name='nama' value='<?= e($lama['nama']) ?>'>
<?php if (isset($galat['nama'])): ?><span><?= e($galat['nama']) ?></span><?php endif; ?>
</p>
<p>
<label for='email'>Email</label><br>
<input id='email' name='email' type='email' value='<?= e($lama['email']) ?>'>
<?php if (isset($galat['email'])): ?><span><?= e($galat['email']) ?></span><?php endif; ?>
</p>
<p>
<label for='pesan'>Pesan</label><br>
<textarea id='pesan' name='pesan' rows='4'><?= e($lama['pesan']) ?></textarea>
<?php if (isset($galat['pesan'])): ?><span><?= e($galat['pesan']) ?></span><?php endif; ?>
</p>
<button type='submit'>Kirim</button>
</form>

<h2>Daftar Tamu</h2>
<form method='get' action='index.php'>
<label for='q'>Cari nama</label>
<input id='q' name='q' value='<?= e($kata) ?>'>
<button type='submit'>Cari</button>
</form>

<?php if ($hal['total'] === 0): ?>
<p><?= $kata === '' ? 'Belum ada data.' : 'Tidak ada hasil untuk pencarian ini.' ?></p>
<?php else: ?>
<?php foreach ($hal['item'] as $tamu): ?>
<article>
<h3><?= e($tamu['nama']) ?></h3>
<p><small><?= e($tamu['waktu']) ?></small></p>
<p><?= nl2br(e($tamu['pesan'])) ?></p>
</article>
<?php endforeach; ?>

<nav aria-label='Paginasi'>
<?php if ($hal['halaman'] > 1): ?>
<a href='<?= e(urlDaftar($kata, $hal['halaman'] - 1)) ?>'>&laquo; Sebelumnya</a>
<?php endif; ?>
Halaman <?= e($hal['halaman']) ?> dari <?= e($hal['totalHalaman']) ?> (<?= e($hal['total']) ?> pesan)
<?php if ($hal['halaman'] < $hal['totalHalaman']): ?>
<a href='<?= e(urlDaftar($kata, $hal['halaman'] + 1)) ?>'>Berikutnya &raquo;</a>
<?php endif; ?>
</nav>
<?php endif; ?>

<p><a href='admin.php'>Admin</a></p>
</body>
</html>