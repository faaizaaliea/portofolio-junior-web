<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';

const BATAS_GAGAL_LOGIN = 5;
const JEDA_KUNCI_DETIK = 900; // 15 menit sejak percobaan gagal terakhir

// BUG-05: admin yang sudah login tidak perlu melihat form lagi.
if (!empty($_SESSION['admin_id'])) {
    redirect('index.php');
    exit;
}

$galat = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekCsrf();
    $gagal = (int) ($_SESSION['gagal_login'] ?? 0);
    $terakhir = (int) ($_SESSION['gagal_login_terakhir'] ?? 0);

    // BUG-01: penguncian kedaluwarsa setelah JEDA_KUNCI_DETIK.
    if ($gagal > 0 && time() - $terakhir >= JEDA_KUNCI_DETIK) {
        $gagal = 0;
        unset($_SESSION['gagal_login'], $_SESSION['gagal_login_terakhir']);
    }

    if ($gagal >= BATAS_GAGAL_LOGIN) {
        $menit = (int) ceil((JEDA_KUNCI_DETIK - (time() - $terakhir)) / 60);
        $galat = 'Terlalu banyak percobaan. Coba lagi dalam ' . max(1, $menit) . ' menit.';
    } elseif (prosesLogin(
        getConnection(),
        trim((string) ($_POST['username'] ?? '')),
        (string) ($_POST['sandi'] ?? '')
    )) {
        unset($_SESSION['gagal_login'], $_SESSION['gagal_login_terakhir']);
        redirect('index.php');
        exit;
    } else {
        $_SESSION['gagal_login'] = $gagal + 1;
        $_SESSION['gagal_login_terakhir'] = time();
        $galat = 'Username atau kata sandi salah.';
    }
}

$judul = 'Login Admin';
require __DIR__ . '/../templates/header.php';
?>
<div class='row justify-content-center'>
<div class='col-md-5'>
<div class='card'><div class='card-body'>
<h2 class='h4'>Login Admin</h2>
<?php if ($galat !== ''): ?><div class='alert alert-danger'><?= e($galat) ?></div><?php endif; ?>
<form method='post'>
<input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>
<div class='mb-3'><label class='form-label' for='username'>Username</label>
<input class='form-control' id='username' name='username' autocomplete='username'></div>
<div class='mb-3'><label class='form-label' for='sandi'>Kata sandi</label>
<input class='form-control' id='sandi' name='sandi' type='password' autocomplete='current-password'></div>
<button class='btn btn-primary' type='submit'>Masuk</button>
</form>
</div></div>
</div>
</div>
<?php require __DIR__ . '/../templates/footer.php'; ?>
