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

$pesan = ambilFlash();
?>
<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Login Admin - Pendaftaran Pelatihan</title>
</head>
<body>
<main>
<h1>Login Admin</h1>
<?php if ($pesan !== null): ?><p role='status'><?= e($pesan) ?></p><?php endif; ?>
<?php if ($galat !== ''): ?><p role='alert'><?= e($galat) ?></p><?php endif; ?>
<form method='post' action='login.php'>
<input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>
<p>
<label for='sandi'>Kata sandi</label><br>
<input id='sandi' name='sandi' type='password' autocomplete='current-password' required>
</p>
<button type='submit'>Masuk</button>
</form>
</main>
</body>
</html>
