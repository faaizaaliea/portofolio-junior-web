<?php
declare(strict_types=1);
/** logout.php  logout; hanya POST + CSRF (mencegah logout lewat link/gambar). */
require_once __DIR__ . '/../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Metode tidak diizinkan.');
}

cekCsrf();
logout();
redirect('login.php');
