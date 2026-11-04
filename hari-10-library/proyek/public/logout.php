<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}
cekCsrf();
$_SESSION = [];
session_regenerate_id(true);
setFlash('Anda berhasil logout.');
redirect('login.php');
