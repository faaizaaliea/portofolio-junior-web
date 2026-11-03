<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Jakarta');

const FILE_LOG = __DIR__ . '/data/log.txt';

/**
 * Meng-escape nilai agar aman ditampilkan di HTML.
 *
 * @param string $teks Nilai yang akan ditampilkan.
 * @return string Teks yang sudah di-escape.
 */
function e(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Membaca baris log terakhir (terbaru di atas).
 *
 * @param int $jumlah Jumlah baris maksimum.
 * @return array{baris: list<string>, galat: string}
 */
function bacaLog(int $jumlah = 20): array
{
    if (!is_file(FILE_LOG)) {
        return ['baris' => [], 'galat' => 'Berkas log belum ada. Buka cari.php dulu.'];
    }
    $semua = file(FILE_LOG, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($semua === false) {
        return ['baris' => [], 'galat' => 'Berkas log tidak bisa dibaca.'];
    }
    return ['baris' => array_slice(array_reverse($semua), 0, $jumlah), 'galat' => ''];
}

$log = bacaLog(20);
?>
<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Log Pengunjung</title>
<style>
body { font-family: system-ui, sans-serif; max-width: 720px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
pre { background: #f6f6f6; padding: .75rem; overflow-x: auto; }
</style>
</head>
<body>
<h1>Log Pengunjung (20 terakhir)</h1>
<p><a href='cari.php'>&laquo; Kembali ke Cari</a></p>
<?php if ($log['galat'] !== ''): ?>
<p role='alert'><?= e($log['galat']) ?></p>
<?php elseif ($log['baris'] === []): ?>
<p>Log masih kosong.</p>
<?php else: ?>
<pre><?php foreach ($log['baris'] as $baris) { echo e($baris), "\n"; } ?></pre>
<?php endif; ?>
</body>
</html>