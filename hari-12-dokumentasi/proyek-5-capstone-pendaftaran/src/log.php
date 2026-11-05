<?php
/**
 * Modul log  pencatatan peristiwa penting ke berkas storage/log/app.log.
 *
 * ID Modul   : MOD-LOG-001
 * Dependensi : storage/log, tidak ada dependensi
 *
 * @package    PendaftaranPelatihan
 * @author     Faaiza
 * @version    1.0.0
 * @since      0.1.0
 */
declare(strict_types=1);

/**
 * Menulis satu baris log (waktu, level, pesan) dengan penguncian berkas.
 *
 * Kegagalan menulis log tidak boleh menjatuhkan aplikasi, sehingga galat ditelan.
 *
 * @param string $level  INFO | WARNING | ERROR.
 * @param string $pesan  Isi pesan (tanpa data pribadi/kata sandi).
 * @return void
 */
function catatLog(string $level, string $pesan): void
{
    $folder = __DIR__ . '/../storage/log';
    if (!is_dir($folder)) {
        @mkdir($folder, 0775, true);
    }
    $baris = sprintf("[%s] %s %s\n", date('Y-m-d H:i:s'), strtoupper($level), str_replace(["\r", "\n"], ' ', $pesan));
    @file_put_contents($folder . '/app.log', $baris, FILE_APPEND | LOCK_EX);
}
