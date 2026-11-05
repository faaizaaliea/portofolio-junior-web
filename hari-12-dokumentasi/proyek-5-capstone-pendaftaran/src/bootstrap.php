<?php
/**
 * Bootstrap  dimuat paling awal oleh setiap halaman di public/.
 *
 * Tanggung jawab: sesi aman, memuat konfigurasi, mengatur tampilan galat,
 * memuat semua modul src/, dan menangani exception yang lolos (log + HTTP 500).
 *
 * ID Modul   : MOD-BOT-001
 * Dependensi : semua modul src/
 *
 * @package    PendaftaranPelatihan
 * @author     Faaiza
 * @version    1.0.0
 * @since      0.1.0
 */
declare(strict_types=1);

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => !empty($_SERVER['HTTPS']),
]);
session_start();

$berkasKonfigurasi = __DIR__ . '/../config/config.php';
if (!is_file($berkasKonfigurasi)) {
    http_response_code(500);
    exit('Konfigurasi belum ada. Salin config/config.contoh.php menjadi config/config.php (lihat README).');
}
$konfigurasi = require $berkasKonfigurasi;
ini_set('display_errors', !empty($konfigurasi['debug']) ? '1' : '0');
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/log.php';
require_once __DIR__ . '/validasi.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/kelas.php';
require_once __DIR__ . '/pendaftar.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/kuota.php';

set_exception_handler(function (Throwable $e) use ($konfigurasi): void {
    catatLog('ERROR', get_class($e) . ': ' . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine());
    http_response_code(500);
    echo 'Terjadi kesalahan pada sistem. Silakan coba lagi.';
    if (!empty($konfigurasi['debug'])) {
        echo '<pre>' . htmlspecialchars($e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine(), ENT_QUOTES, 'UTF-8') . '</pre>';
    }
});
