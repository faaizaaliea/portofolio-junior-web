<?php
$area = $area ?? 'publik';
$beranda = $area === 'admin' ? 'index.php' : 'daftar.php';
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
<body class='bg-light'>
<nav class='navbar navbar-expand-md navbar-dark bg-primary'>
<div class='container'>
<a class='navbar-brand' href='<?= e($beranda) ?>'>Pendaftaran Pelatihan</a>
<button class='navbar-toggler' type='button' data-bs-toggle='collapse'
data-bs-target='#menu' aria-controls='menu' aria-expanded='false' aria-label='Buka menu'>
<span class='navbar-toggler-icon'></span>
</button>
<div id='menu' class='collapse navbar-collapse'>
<ul class='navbar-nav ms-auto align-items-md-center'>
<?php if ($area === 'admin'): ?>
<li class='nav-item'><a class='nav-link' href='index.php'>Daftar Pendaftar</a></li>
<li class='nav-item'><a class='nav-link' href='tambah.php'>Tambah</a></li>
<li class='nav-item'>
<form method='post' action='logout.php' class='my-2 my-md-0 ms-md-2'>
<input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>
<button type='submit' class='btn btn-sm btn-light'>Logout</button>
</form>
</li>
<?php else: ?>
<li class='nav-item'><a class='nav-link' href='daftar.php'>Form Pendaftaran</a></li>
<li class='nav-item'><a class='nav-link' href='login.php'>Login Admin</a></li>
<?php endif; ?>
</ul>
</div>
</div>
</nav>
<main class='container py-4'>
<?php if ($pesan = ambilFlash()): ?>
<div id='flash' data-pesan='<?= e($pesan) ?>' hidden></div>
<noscript><div class='alert alert-info' role='status'><?= e($pesan) ?></div></noscript>
<?php endif; ?>
