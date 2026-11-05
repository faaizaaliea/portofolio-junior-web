<?php
/**
 * templates/header.php  kepala halaman + navigasi.
 * Variabel: $area ('publik'|'admin'), $judul, $pakaiDataTables (bool).
 */
$area = $area ?? 'publik';
?>
<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title><?= e($judul ?? 'Pendaftaran Pelatihan') ?></title>
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css'>
<?php if (!empty($pakaiDataTables)): ?>
<link rel='stylesheet' href='https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css'>
<?php endif; ?>
</head>
<body>
<header class='container py-3 mb-3 border-bottom'>
<h1 class='h3'>Pendaftaran Pelatihan</h1>
<nav class='d-flex flex-wrap gap-2 align-items-center'>
<?php if ($area === 'admin'): ?>
<a href='index.php'>Daftar Pendaftar</a> | <a href='tambah.php'>Tambah Pendaftar</a> | <a href='kelas.php'>Kelas</a> |
<form method='post' action='logout.php' class='d-inline'>
<input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>
<button type='submit' class='btn btn-sm btn-outline-secondary'>Logout</button>
</form>
<?php else: ?>
<a href='daftar.php'>Form Pendaftaran</a> | <a href='login.php'>Login Admin</a>
<?php endif; ?>
</nav>
</header>
<main class='container'>
<?php if ($pesan = ambilFlash()): ?>
<div id='flash' data-pesan='<?= e($pesan) ?>' hidden></div>
<noscript><div class='alert alert-info' role='status'><?= e($pesan) ?></div></noscript>
<?php endif; ?>
