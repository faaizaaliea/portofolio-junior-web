<?php
declare(strict_types=1);

$peserta = [
    ['nama' => 'Sari',  'kelas' => 'Web',  'nilai' => 90],
    ['nama' => 'Budi',  'kelas' => 'Web',  'nilai' => 68],
    ['nama' => 'Citra', 'kelas' => 'Data', 'nilai' => 82],
];

// ===== 1. Rata-rata & nilai tertinggi =====
/**
 * Menghitung rata-rata nilai peserta.
 *
 * @param array<int, array<string, mixed>> $daftar Daftar peserta.
 * @return float Rata-rata; 0.0 bila daftar kosong.
 */
function hitungRataRata(array $daftar): float
{
    if (count($daftar) === 0) {
        return 0.0; // cegah pembagian dengan nol
    }
    return array_sum(array_column($daftar, 'nilai')) / count($daftar);
}

/**
 * Mencari peserta dengan nilai tertinggi.
 *
 * @param array<int, array<string, mixed>> $daftar Daftar peserta.
 * @return array<string, mixed>|null Peserta tertinggi; null bila daftar kosong.
 */
function cariTertinggi(array $daftar): ?array
{
    if (count($daftar) === 0) {
        return null; // max([]) akan melempar error di PHP 8
    }
    $tertinggi = $daftar[0];
    foreach ($daftar as $row) {
        if ($row['nilai'] > $tertinggi['nilai']) {
            $tertinggi = $row;
        }
    }
    return $tertinggi;
}

// ===== 2. Filter lulus =====
/**
 * Menyaring peserta yang nilainya mencapai batas lulus.
 *
 * @param array<int, array<string, mixed>> $peserta Daftar peserta.
 * @param int $batas Nilai minimum lulus.
 * @return array<int, array<string, mixed>> Peserta yang lulus (indeks diset ulang).
 */
function filterLulus(array $peserta, int $batas = 70): array
{
    $lulus = array_filter($peserta, fn(array $p): bool => $p['nilai'] >= $batas);
    return array_values($lulus); // reset indeks
}

// ===== 3. Urutkan nilai (menurun), mengembalikan salinan =====
/**
 * Mengurutkan peserta dari nilai tertinggi (mengembalikan salinan).
 *
 * @param array<int, array<string, mixed>> $peserta Daftar peserta.
 * @return array<int, array<string, mixed>> Daftar terurut menurun.
 */
function urutkanNilai(array $peserta): array
{
    // array di PHP dilewatkan by value, jadi $peserta di sini sudah salinan
    usort($peserta, fn(array $a, array $b): int => $b['nilai'] <=> $a['nilai']);
    return $peserta;
}

// ===== 4. Kelompokkan per kelas =====
/**
 * Mengelompokkan peserta berdasarkan kelas.
 *
 * @param array<int, array<string, mixed>> $peserta Daftar peserta.
 * @return array<string, array<int, array<string, mixed>>> Peserta per kelas.
 */
function kelompokkanPerKelas(array $peserta): array
{
    $hasil = [];
    foreach ($peserta as $p) {
        $hasil[$p['kelas']][] = $p;
    }
    return $hasil;
}

// ===== Helper tampilan =====
/**
 * Meng-escape nilai agar aman ditampilkan di HTML.
 *
 * @param string|int|float $nilai Nilai yang ditampilkan.
 * @return string Teks yang sudah di-escape.
 */
function e(string|int|float $nilai): string
{
    return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
}

/**
 * Mengonversi nilai menjadi grade huruf.
 *
 * @param int $nilai Nilai peserta.
 * @return string Grade A sampai E.
 */
function konversiGrade(int $nilai): string
{
    return match (true) {
        $nilai >= 85 => 'A',
        $nilai >= 70 => 'B',
        $nilai >= 55 => 'C',
        $nilai >= 40 => 'D',
        default      => 'E',
    };
}

/**
 * Menampilkan tabel peserta lengkap dengan grade.
 *
 * @param array<int, array<string, mixed>> $daftar Daftar peserta.
 */
function tampilkanTabel(array $daftar): void
{
    if (count($daftar) === 0) {
        echo '<p><em>Tidak ada data.</em></p>';
        return;
    }
    echo '<table><thead><tr><th>No</th><th>Nama</th><th>Kelas</th><th>Nilai</th><th>Grade</th></tr></thead><tbody>';
    foreach ($daftar as $i => $p) {
        echo '<tr>'
            . '<td>' . e($i + 1) . '</td>'
            . '<td>' . e($p['nama']) . '</td>'
            . '<td>' . e($p['kelas']) . '</td>'
            . '<td>' . e($p['nilai']) . '</td>'
            . '<td>' . e(konversiGrade($p['nilai'])) . '</td>'
            . '</tr>';
    }
    echo '</tbody></table>';
}

$rataRata  = hitungRataRata($peserta);
$tertinggi = cariTertinggi($peserta);
$lulus     = filterLulus($peserta);
$terurut   = urutkanNilai($peserta);
$perKelas  = kelompokkanPerKelas($peserta);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fungsi Array Peserta</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 760px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        h2 { border-bottom: 2px solid #ddd; padding-bottom: .25rem; margin-top: 2rem; }
        table { border-collapse: collapse; margin-bottom: 1rem; }
        td, th { border: 1px solid #999; padding: 4px 12px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Fungsi Array Peserta</h1>

    <h2>Semua Peserta</h2>
    <?php tampilkanTabel($peserta); ?>

    <h2>Rata-rata &amp; Nilai Tertinggi</h2>
    <p>Rata-rata nilai: <strong><?= e(number_format($rataRata, 2, ',', '.')) ?></strong></p>
    <p>
        <?php if ($tertinggi !== null): ?>
            Nilai tertinggi: <strong><?= e($tertinggi['nama']) ?></strong> (<?= e($tertinggi['nilai']) ?>)
        <?php else: ?>
            Belum ada data peserta.
        <?php endif; ?>
    </p>

    <h2>Peserta Lulus (nilai &ge; 70)</h2>
    <?php tampilkanTabel($lulus); ?>

    <h2>Diurutkan dari Nilai Tertinggi</h2>
    <?php tampilkanTabel($terurut); ?>

    <h2>Dikelompokkan per Kelas</h2>
    <?php foreach ($perKelas as $kelas => $anggota): ?>
        <h3>Kelas <?= e($kelas) ?></h3>
        <?php tampilkanTabel($anggota); ?>
    <?php endforeach; ?>
</body>
</html>