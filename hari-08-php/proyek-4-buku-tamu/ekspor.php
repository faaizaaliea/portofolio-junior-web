<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';

mulaiSesi();
if (!adalahAdmin()) {
    http_response_code(403);
    exit('Akses ditolak. Masuk sebagai admin dulu.');
}

$namaBerkas = 'buku-tamu-' . date('Ymd-His') . '.csv';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $namaBerkas . '"');

echo "\xEF\xBB\xBF"; // BOM agar Excel membaca UTF-8
echo eksporCsv(array_reverse(ambilSemuaTamu()));