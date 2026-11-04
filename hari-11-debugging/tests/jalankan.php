<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/validasi.php';
$hasilUji = [];
function uji(array &$hasil, string $nama, mixed $diharapkan, mixed $aktual): void
{
$hasil[] = [$nama, $diharapkan === $aktual, $diharapkan, $aktual];
}
uji($hasilUji, 'HP 10 digit lolos', true, validNoHp('0812345678'));
uji($hasilUji, 'HP 9 digit gagal', false, validNoHp('081234567'));
uji($hasilUji, 'HP 14 digit gagal', false, validNoHp('08123456789012'));
uji($hasilUji, 'HP huruf gagal', false, validNoHp('08abc45678'));
uji($hasilUji, 'Sisa kuota normal', 5, hitungSisaKuota(30, 25));
uji($hasilUji, 'Sisa kuota tidak negatif', 0, hitungSisaKuota(30, 31));
uji($hasilUji, 'Nomor bukti', 'REG-2026-000007', formatNomorBukti(2026, 7));
// Kasus uji tambahan
uji($hasilUji, 'HP 11 digit lolos', true, validNoHp('08123456789'));
uji($hasilUji, 'HP tanpa awalan 08 gagal', false, validNoHp('0712345678'));
uji($hasilUji, 'Sisa kuota sama dengan nol', 0, hitungSisaKuota(30, 30));
uji($hasilUji, 'Sisa kuota kosong', 30, hitungSisaKuota(30, 0));
uji($hasilUji, 'Nomor bukti ID dua digit', 'REG-2026-000042', formatNomorBukti(2026, 42));
$gagal = 0;
foreach ($hasilUji as [$nama, $lolos, $diharapkan, $aktual]) {
if ($lolos) {
echo '[LOLOS] ' . $nama . PHP_EOL;
} else {
$gagal++;
echo '[GAGAL] ' . $nama . ' | diharapkan ' . var_export($diharapkan, true)
. ', aktual ' . var_export($aktual, true) . PHP_EOL;
}
}
echo PHP_EOL . ($gagal === 0 ? 'Semua uji lolos.' : $gagal . ' uji gagal.') .
PHP_EOL;
exit($gagal === 0 ? 0 : 1);
