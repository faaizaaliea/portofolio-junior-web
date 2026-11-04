<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';

if (!empty($_SESSION['admin_login'])) {
    redirect('index.php');
}

$hashSandiAdmin = $konfigurasi['admin_password_hash'] ?? '';
if (
    !is_string($hashSandiAdmin)
    || $hashSandiAdmin === ''
    || password_get_info($hashSandiAdmin)['algo'] === null
) {
    throw new RuntimeException('Konfigurasi admin_password_hash belum diisi dengan hash yang valid.');
}

$galat = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekCsrf();
    $sandi = (string) ($_POST['sandi'] ?? '');

    if (password_verify($sandi, $hashSandiAdmin)) {
        session_regenerate_id(true);
        $_SESSION['admin_login'] = true;
        setFlash('Anda berhasil login.');
        redirect('index.php');
    }

    $galat = 'Kata sandi salah.';
}

$area = 'publik';
$judul = 'Login Admin - Pendaftaran Pelatihan';
require __DIR__ . '/../templates/header.php';
?>
<div class='row justify-content-center'>
<div class='col-md-5'>
<div class='card'>
<div class='card-body'>
<h1 class='h4 mb-3'>Login Admin</h1>
<?php if ($galat !== ''): ?>
<div class='alert alert-danger' role='alert'><?= e($galat) ?></div>
<?php endif; ?>
<form method='post' action='login.php'>
<input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>
<div class='mb-3'>
<label for='sandi' class='form-label'>Kata sandi</label>
<input id='sandi' name='sandi' type='password' class='form-control' autocomplete='current-password' required>
</div>
<button type='submit' class='btn btn-primary'>Masuk</button>
</form>
</div>
</div>
</div>
</div>
<?php require __DIR__ . '/../templates/footer.php';
