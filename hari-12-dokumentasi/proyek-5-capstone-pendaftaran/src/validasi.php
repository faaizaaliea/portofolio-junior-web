<?php
/**
 * Modul validasi  fungsi murni (tanpa database) sehingga mudah diuji otomatis.
 *
 * ID Modul   : MOD-VAL-001
 * Dependensi : tanpa dependensi
 *
 * @package    PendaftaranPelatihan
 * @author     Faaiza
 * @version    1.0.0
 * @since      0.1.0
 */
declare(strict_types=1);

/**
 * Memeriksa nomor HP Indonesia: diawali 08, total 10–13 digit.
 *
 * @param string $noHp Nomor tanpa spasi/tanda hubung.
 * @return bool
 */
function validNoHp(string $noHp): bool
{
    return preg_match('/^08[0-9]{8,11}$/', $noHp) === 1;
}

/**
 * Nama peserta/kelas: minimal 3 dan maksimal 100 karakter setelah di-trim.
 *
 * @param string $nama
 * @return bool
 */
function validNama(string $nama): bool
{
    $panjang = mb_strlen(trim($nama));
    return $panjang >= 3 && $panjang <= 100;
}

/**
 * Kuota kelas: bilangan bulat 1–1000.
 *
 * @param int $kuota
 * @return bool
 */
function validKuota(int $kuota): bool
{
    return $kuota >= 1 && $kuota <= 1000;
}

/**
 * Menghitung sisa kuota dari kuota dan jumlah terisi (tidak pernah negatif).
 *
 * @param int $kuota  Kapasitas kelas.
 * @param int $terisi Jumlah pendaftar saat ini.
 * @return int
 */
function sisaKuota(int $kuota, int $terisi): int
{
    return max(0, $kuota - $terisi);
}

/**
 * Membuat nomor bukti, contoh: REG-2026-000012.
 *
 * @param int $id    ID pendaftar (>= 1).
 * @param int $tahun Tahun pendaftaran.
 * @return string
 */
function formatNomorBukti(int $id, int $tahun): string
{
    return sprintf('REG-%d-%06d', $tahun, $id);
}

/**
 * Menetralkan sel CSV agar tidak dieksekusi sebagai rumus spreadsheet.
 *
 * @param string $teks
 * @return string Teks aman (diawali tanda petik jika berisi awalan = + - @).
 */
function amankanSelCsv(string $teks): string
{
    return preg_match('/^[=+\-@\t\r]/', $teks) === 1 ? "'" . $teks : $teks;
}
