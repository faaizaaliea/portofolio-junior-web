<?php
declare(strict_types=1);

/**
 * Menghitung Indeks Massa Tubuh (BMI).
 *
 * @param float $berat Berat badan dalam kg.
 * @param float $tinggiCm Tinggi badan dalam cm.
 * @return float Nilai BMI.
 */
function hitungBmi(float $berat, float $tinggiCm): float
{
    $tinggiM = $tinggiCm / 100;
    return $berat / ($tinggiM * $tinggiM);
}

/**
 * Menentukan kategori BMI.
 *
 * @param float $bmi Nilai BMI.
 * @return string Kurang, Normal, Lebih, atau Obesitas.
 */
function kategoriBmi(float $bmi): string
{
    return match (true) {
        $bmi < 18.5 => 'Kurang',
        $bmi < 25   => 'Normal',
        $bmi < 30   => 'Lebih',
        default     => 'Obesitas',
    };
}

/**
 * Memeriksa apakah teks adalah angka lebih dari nol.
 *
 * @param string $teks Isian dari form.
 * @return bool True jika angka valid dan lebih dari 0.
 */
function angkaPositif(string $teks): bool
{
    return filter_var($teks, FILTER_VALIDATE_FLOAT) !== false && (float) $teks > 0;
}

/**
 * Meng-escape teks agar aman ditampilkan di HTML.
 *
 * @param string $teks Teks mentah.
 * @return string Teks yang sudah di-escape.
 */
function e(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

$galat = [];
$hasil = null;
$berat = trim((string) ($_POST['berat'] ?? ''));
$tinggi = trim((string) ($_POST['tinggi'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!angkaPositif($berat)) {
        $galat['berat'] = 'Berat harus angka lebih dari 0.';
    }
    if (!angkaPositif($tinggi)) {
        $galat['tinggi'] = 'Tinggi harus angka lebih dari 0.';
    }
    if ($galat === []) {
        $bmi = hitungBmi((float) $berat, (float) $tinggi);
        $hasil = number_format($bmi, 1) . ' (' . kategoriBmi($bmi) . ')';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator BMI</title>
</head>
<body>
    <h1>Kalkulator BMI</h1>
    <form method="post">
        <p>
            <label for="berat">Berat (kg)</label>
            <input id="berat" name="berat" value="<?= e($berat) ?>">
            <?php if (isset($galat['berat'])): ?><span><?= e($galat['berat']) ?></span><?php endif; ?>
        </p>
        <p>
            <label for="tinggi">Tinggi (cm)</label>
            <input id="tinggi" name="tinggi" value="<?= e($tinggi) ?>">
            <?php if (isset($galat['tinggi'])): ?><span><?= e($galat['tinggi']) ?></span><?php endif; ?>
        </p>
        <button type="submit">Hitung</button>
    </form>
    <?php if ($hasil !== null): ?>
        <p>Hasil BMI: <strong><?= e($hasil) ?></strong></p>
    <?php endif; ?>
</body>
</html>
