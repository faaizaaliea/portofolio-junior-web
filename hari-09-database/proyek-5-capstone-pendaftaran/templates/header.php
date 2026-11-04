<?php
/* templates/header.php — $area: 'admin' atau 'publik' (default publik) */
$area = $area ?? 'publik';
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title><?= e($judul ?? 'Pendaftaran Pelatihan') ?></title>
</head>
<body>
<header>
    <h1>Pendaftaran Pelatihan</h1>
    <nav>
        <?php if ($area === 'admin'): ?>
            <a href='index.php'>Daftar Pendaftar</a> | <a href='tambah.php'>Tambah</a> |
            <form method='post' action='logout.php' style='display:inline'>
                <input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>
                <button type='submit'>Logout</button>
            </form>
        <?php else: ?>
            <a href='tambah.php'>Form Pendaftaran</a> | <a href='login.php'>Login Admin</a>
        <?php endif; ?>
    </nav>
</header>
<main>
    <?php if ($pesan = ambilFlash()): ?><p role='status'><?= e($pesan) ?></p><?php endif; ?>
