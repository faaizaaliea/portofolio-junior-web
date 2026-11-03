<?php
declare(strict_types=1);

const HARI = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

/**
 * Mengonversi nilai angka menjadi grade huruf.
 *
 * @param int $nilai Nilai 0-100.
 * @return string Grade A, B, C, atau D.
 */
function konversiGrade(int $nilai): string
{
    return match (true) {
        $nilai >= 80 => 'A',
        $nilai >= 70 => 'B',
        $nilai >= 60 => 'C',
        default      => 'D',
    };
}

$nama = 'Sari';
$umur = 21;
$status = 'Mahasiswa';
$nilai = 80;
$indeksHari = (int) date('w');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1 - PHP Dasar</title>
</head>
<body>
    <h1>Lab 1 - PHP Dasar</h1>

    <h2>Variabel dan var_dump</h2>
    <p>Halo, saya <?= htmlspecialchars($nama) ?>, umur saya <?= $umur ?> tahun, dan status saya <?= htmlspecialchars($status) ?>.</p>
    <pre><?php var_dump($nama, $umur, $status); ?></pre>

    <h2>Tabel Perkalian 1-10</h2>
    <table border="1">
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <tr>
                <?php for ($j = 1; $j <= 10; $j++): ?>
                    <td><?= $i * $j ?></td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>

    <h2>Bilangan Ganjil dan Genap 1-20</h2>
    <?php
    $ganjil = [];
    $genap = [];
    for ($i = 1; $i <= 20; $i++) {
        if ($i % 2 === 0) {
            $genap[] = $i;
        } else {
            $ganjil[] = $i;
        }
    }
    ?>
    <p>Ganjil: <?= implode(' ', $ganjil) ?></p>
    <p>Genap: <?= implode(' ', $genap) ?></p>

    <h2>FizzBuzz 1-30</h2>
    <p>
        <?php for ($i = 1; $i <= 30; $i++): ?>
            <?php
            if ($i % 15 === 0) {
                echo 'FizzBuzz';
            } elseif ($i % 3 === 0) {
                echo 'Fizz';
            } elseif ($i % 5 === 0) {
                echo 'Buzz';
            } else {
                echo $i;
            }
            ?>
        <?php endfor; ?>
    </p>

    <h2>Konversi Nilai ke Grade</h2>
    <p>Nilai: <?= $nilai ?>, Grade: <?= konversiGrade($nilai) ?></p>

    <h2>Kalender</h2>
    <p>Hari ini adalah hari <?= HARI[$indeksHari] ?> (date('w') = <?= $indeksHari ?>).</p>
    <p>Waktu server: <?= date('d-m-Y H:i') ?></p>
</body>
</html>
