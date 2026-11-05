<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Jakarta');

const FOLDER_DATA = __DIR__ . '/data';
const FILE_LOG    = FOLDER_DATA . '/log.txt';
const FILE_JSON   = FOLDER_DATA . '/peserta.json';
const FILE_CSV    = FOLDER_DATA . '/peserta.csv';

/* =====================  LOGIKA  ===================== */

/**
 * Data peserta (sama dengan data Lab 2).
 *
 * @return list<array{nama: string, kelas: string, nilai: int}>
 */
function dataPeserta(): array
{
    return [
        ['nama' => 'Sari',  'kelas' => 'Web',  'nilai' => 90],
        ['nama' => 'Budi',  'kelas' => 'Web',  'nilai' => 68],
        ['nama' => 'Citra', 'kelas' => 'Data', 'nilai' => 82],
    ];
}

/**
 * Meng-escape nilai agar aman ditampilkan di HTML.
 *
 * @param string|int $teks Nilai yang akan ditampilkan.
 * @return string Teks yang sudah di-escape.
 */
function e(string|int $teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Mengambil parameter $_GET sebagai teks.
 *
 * @param string $kunci Nama parameter.
 * @return string Nilai yang sudah di-trim.
 */
function parameterGet(string $kunci): string
{
    $nilai = $_GET[$kunci] ?? '';
    return is_string($nilai) ? trim($nilai) : '';
}

/**
 * Memastikan folder data/ ada (dibuat dengan mkdir bila belum) dan dilindungi .htaccess.
 *
 * @return bool True jika folder siap dipakai.
 */
function siapkanFolderData(): bool
{
    if (!is_dir(FOLDER_DATA) && !mkdir(FOLDER_DATA, 0755, true) && !is_dir(FOLDER_DATA)) {
        return false;
    }
    $htaccess = FOLDER_DATA . '/.htaccess';
    if (!file_exists($htaccess)) {
        return file_put_contents($htaccess, "Require all denied\n") !== false;
    }
    return true;
}

/**
 * Menyaring peserta berdasarkan sebagian nama (tidak peka huruf besar/kecil).
 *
 * @param list<array<string, mixed>> $peserta Daftar peserta.
 * @param string $kata Kata kunci; kosong berarti semua peserta.
 * @return list<array<string, mixed>> Peserta yang cocok.
 */
function cariPeserta(array $peserta, string $kata): array
{
    if ($kata === '') {
        return $peserta;
    }
    return array_values(array_filter(
        $peserta,
        fn (array $p): bool => stripos((string) $p['nama'], $kata) !== false
    ));
}

/**
 * Menambahkan satu baris "waktu | IP | kata kunci" ke data/log.txt.
 * Pemisah dan baris baru di kata kunci dibuang agar baris log tidak bisa dipalsukan.
 *
 * @param string $kata Kata kunci pencarian.
 * @return bool True jika berhasil ditulis.
 */
function catatLog(string $kata): bool
{
    if (!siapkanFolderData()) {
        return false;
    }
    $kata = substr(trim((string) preg_replace('/[\r\n|]+/', ' ', $kata)), 0, 100);
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? 'tidak-diketahui');
    $baris = date('Y-m-d H:i:s') . ' | ' . $ip . ' | ' . $kata . PHP_EOL;
    return file_put_contents(FILE_LOG, $baris, FILE_APPEND | LOCK_EX) !== false;
}

/**
 * Menyimpan array ke berkas JSON.
 *
 * @param string $berkas Path berkas.
 * @param array<int|string, mixed> $data Data yang disimpan.
 * @return string Pesan galat; string kosong jika berhasil.
 */
function simpanJson(string $berkas, array $data): string
{
    if (!siapkanFolderData()) {
        return 'Folder data/ tidak bisa dibuat.';
    }
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return 'Gagal membuat JSON: ' . json_last_error_msg();
    }
    return file_put_contents($berkas, $json, LOCK_EX) === false ? 'Gagal menulis berkas.' : '';
}

/**
 * Membaca berkas JSON; menangani berkas tidak ada dan JSON rusak.
 *
 * @param string $berkas Path berkas.
 * @return array{data: array<int|string, mixed>, galat: string}
 */
function bacaJson(string $berkas): array
{
    if (!is_file($berkas)) {
        return ['data' => [], 'galat' => 'Berkas ' . basename($berkas) . ' tidak ditemukan.'];
    }
    $isi = file_get_contents($berkas);
    if ($isi === false) {
        return ['data' => [], 'galat' => 'Berkas tidak bisa dibaca.'];
    }
    $data = json_decode($isi, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['data' => [], 'galat' => 'JSON rusak: ' . json_last_error_msg()];
    }
    return ['data' => is_array($data) ? $data : [], 'galat' => ''];
}

/**
 * Mengekspor peserta ke CSV (dengan baris judul) memakai fputcsv.
 *
 * @param string $berkas Path berkas.
 * @param list<array<string, mixed>> $peserta Daftar peserta.
 * @return string Pesan galat; string kosong jika berhasil.
 */
function eksporCsv(string $berkas, array $peserta): string
{
    if (!siapkanFolderData()) {
        return 'Folder data/ tidak bisa dibuat.';
    }
    $handle = fopen($berkas, 'w');
    if ($handle === false) {
        return 'Berkas CSV tidak bisa ditulis.';
    }
    try {
        flock($handle, LOCK_EX);
        fputcsv($handle, ['nama', 'kelas', 'nilai'], ',', '"', '');
        foreach ($peserta as $p) {
            fputcsv($handle, [(string) $p['nama'], (string) $p['kelas'], (string) $p['nilai']], ',', '"', '');
        }
        fflush($handle);
        flock($handle, LOCK_UN);
    } finally {
        fclose($handle);
    }
    return '';
}

/**
 * Mengimpor peserta dari CSV memakai fgetcsv (baris judul dilewati).
 *
 * @param string $berkas Path berkas.
 * @return array{data: list<array{nama: string, kelas: string, nilai: int}>, galat: string}
 */
function imporCsv(string $berkas): array
{
    if (!is_file($berkas)) {
        return ['data' => [], 'galat' => 'Berkas ' . basename($berkas) . ' tidak ditemukan.'];
    }
    $handle = fopen($berkas, 'r');
    if ($handle === false) {
        return ['data' => [], 'galat' => 'Berkas CSV tidak bisa dibaca.'];
    }
    $data = [];
    try {
        fgetcsv($handle, 0, ',', '"', ''); // lewati baris judul
        while (($baris = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            if (count($baris) < 3) {
                continue;
            }
            $data[] = ['nama' => (string) $baris[0], 'kelas' => (string) $baris[1], 'nilai' => (int) $baris[2]];
        }
    } finally {
        fclose($handle);
    }
    return ['data' => $data, 'galat' => ''];
}

/**
 * Menampilkan tabel peserta (semua isi di-escape).
 *
 * @param array<int|string, mixed> $peserta Daftar peserta.
 */
function tampilkanTabel(array $peserta): void
{
    if ($peserta === []) {
        echo '<p><em>Tidak ada data.</em></p>';
        return;
    }
    echo '<table><thead><tr><th>No</th><th>Nama</th><th>Kelas</th><th>Nilai</th></tr></thead><tbody>';
    $no = 1;
    foreach ($peserta as $p) {
        echo '<tr><td>' . e($no++) . '</td><td>' . e((string) $p['nama']) . '</td><td>'
            . e((string) $p['kelas']) . '</td><td>' . e((string) $p['nilai']) . '</td></tr>';
    }
    echo '</tbody></table>';
}

/* =====================  ALUR REQUEST  ===================== */

$tab = parameterGet('tab');
if (!in_array($tab, ['cari', 'json', 'csv'], true)) {
    $tab = 'cari';
}
$uji = parameterGet('uji'); // 'rusak' / 'hilang': mencoba penanganan galat

$kata = '';
$hasil = [];
$galat = [];
$sebelum = [];
$sesudah = [];
$dataCsv = [];

if ($tab === 'cari') {
    $kata = parameterGet('q');
    catatLog($kata); // setiap pembukaan halaman Cari dicatat
    $hasil = cariPeserta(dataPeserta(), $kata);
} elseif ($tab === 'json') {
    $pesan = simpanJson(FILE_JSON, dataPeserta());           // simpan
    if ($pesan !== '') {
        $galat[] = $pesan;
    }
    if ($uji === 'rusak') {
        file_put_contents(FILE_JSON, '{"nama": [rusak');
    } elseif ($uji === 'hilang' && is_file(FILE_JSON)) {
        unlink(FILE_JSON);
    }
    $baca = bacaJson(FILE_JSON);                             // baca kembali
    $sebelum = $baca['data'];
    $sesudah = $sebelum;
    if ($baca['galat'] !== '') {
        $galat[] = $baca['galat'];
    } else {
        $sesudah[] = ['nama' => 'Dewi', 'kelas' => 'Data', 'nilai' => 88]; // tambah satu
        $pesan = simpanJson(FILE_JSON, $sesudah);            // simpan ulang
        if ($pesan !== '') {
            $galat[] = $pesan;
        }
    }
} else { // csv
    $pesan = eksporCsv(FILE_CSV, dataPeserta());
    if ($pesan !== '') {
        $galat[] = $pesan;
    }
    if ($uji === 'hilang' && is_file(FILE_CSV)) {
        unlink(FILE_CSV);
    }
    $impor = imporCsv(FILE_CSV);
    $dataCsv = $impor['data'];
    if ($impor['galat'] !== '') {
        $galat[] = $impor['galat'];
    }
}
?>
<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>LAB 4 - Berkas dan Data</title>
<style>
body { font-family: system-ui, sans-serif; max-width: 720px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
table { border-collapse: collapse; margin: 1rem 0; }
td, th { border: 1px solid #999; padding: 4px 12px; text-align: left; }
th { background: #f0f0f0; }
pre { background: #f6f6f6; padding: .75rem; overflow-x: auto; }
nav a { margin-right: .75rem; }
nav a[aria-current] { font-weight: bold; }
</style>
</head>
<body>
<h1>LAB 4 - Berkas dan Data</h1>
<nav>
<?php foreach (['cari' => 'Cari', 'json' => 'JSON', 'csv' => 'CSV'] as $kunci => $label): ?>
<a href='cari.php?tab=<?= e($kunci) ?>'<?= $tab === $kunci ? " aria-current='page'" : '' ?>><?= e($label) ?></a>
<?php endforeach; ?>
<a href='lihat-log.php'>Log</a>
</nav>

<?php foreach ($galat as $g): ?>
<p role='alert'><?= e($g) ?></p>
<?php endforeach; ?>

<?php if ($tab === 'cari'): ?>
<h2>Cari Peserta</h2>
<form method='get' action='cari.php'>
<input type='hidden' name='tab' value='cari'>
<label for='q'>Nama</label>
<input id='q' name='q' value='<?= e($kata) ?>'>
<button type='submit'>Cari</button>
</form>
<?php if ($hasil === []): ?>
<p role='status'>Tidak ada peserta yang cocok dengan &ldquo;<?= e($kata) ?>&rdquo;.</p>
<?php else: ?>
<p><?= e(count($hasil)) ?> peserta ditemukan.</p>
<?php tampilkanTabel($hasil); ?>
<?php endif; ?>

<?php elseif ($tab === 'json'): ?>
<h2>JSON Peserta</h2>
<p>Uji galat: <a href='cari.php?tab=json&amp;uji=rusak'>JSON rusak</a> |
<a href='cari.php?tab=json&amp;uji=hilang'>berkas hilang</a> |
<a href='cari.php?tab=json'>normal</a></p>
<h3>Setelah dibaca kembali</h3>
<?php tampilkanTabel($sebelum); ?>
<h3>Setelah ditambah satu peserta dan disimpan ulang</h3>
<?php tampilkanTabel($sesudah); ?>

<?php else: ?>
<h2>CSV Peserta</h2>
<p>Uji galat: <a href='cari.php?tab=csv&amp;uji=hilang'>berkas hilang</a> |
<a href='cari.php?tab=csv'>normal</a></p>
<h3>Hasil impor dari data/peserta.csv</h3>
<?php tampilkanTabel($dataCsv); ?>
<?php endif; ?>
</body>
</html>