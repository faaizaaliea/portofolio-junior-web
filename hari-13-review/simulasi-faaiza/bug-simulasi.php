<?php
declare(strict_types=1);

/**
 * Menghitung sisa kuota.
 *
 * @param int $kuota Kuota total.
 * @param array $peserta Daftar peserta yang sudah terdaftar.
 * @return int Sisa kuota.
 */
function hitungSisaKuota(int $kuota, array $peserta): int
{
    $terisi = count($peserta); // BUG 1 (syntax): titik koma hilang -> ditambahkan
    return $kuota - $terisi;
}

/**
 * Menghitung rata-rata umur.
 *
 * @param array $daftarUmur Daftar umur.
 * @return float Rata-rata; 0.0 bila daftar kosong.
 */
function rataUmur(array $daftarUmur): float
{
    if ($daftarUmur === []) { // cegah pembagian nol
        return 0.0;
    }
    return array_sum($daftarUmur) / count($daftarUmur); // BUG 3 (logic): "- 1" dihapus
}

$daftar = [['nama' => 'Sari'], ['nama' => 'Budi']];
echo 'Sisa kuota: ' . hitungSisaKuota(30, $daftar) . '<br>';
echo 'Nama pertama: ' . ($daftar[0]['nama'] ?? 'Belum tersedia'); // BUG 2 (runtime): indeks 2 -> 0
