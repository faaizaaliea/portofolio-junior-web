<?php
/**
 * Modul helper  fungsi kecil yang dipakai di banyak halaman:
 * escape output, redirect, token CSRF, dan pesan flash.
 *
 * ID Modul   : MOD-HLP-001
 * Dependensi : src/log.php
 *
 * @package    PendaftaranPelatihan
 * @author     Faaiza
 * @version    1.0.0
 * @since      0.1.0
 */
declare(strict_types=1);

/**
 * Meng-escape teks untuk HTML (pencegah XSS). Pakai pada SEMUA output dinamis.
 *
 * @param string|null $teks
 * @return string
 */
function e(?string $teks): string
{
    return htmlspecialchars($teks ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Mengalihkan browser ke URL lain lalu menghentikan skrip (pola PRG).
 *
 * @param string $url
 * @return never
 */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/**
 * Mengambil (dan membuat bila belum ada) token CSRF sesi.
 *
 * @return string 64 karakter heksadesimal.
 */
function tokenCsrf(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Memverifikasi token CSRF pada permintaan POST; jika salah → HTTP 403 dan berhenti.
 *
 * @return void
 */
function cekCsrf(): void
{
    $kiriman = (string) ($_POST['csrf'] ?? '');
    $token = (string) ($_SESSION['csrf'] ?? '');
    if ($token === '' || !hash_equals($token, $kiriman)) {
        catatLog('WARNING', 'CSRF ditolak pada ' . basename($_SERVER['SCRIPT_NAME'] ?? '?'));
        http_response_code(403);
        exit('Permintaan tidak valid.');
    }
}

/**
 * Menyimpan pesan sekali-tampil (flash) untuk halaman berikutnya.
 *
 * @param string $pesan
 * @return void
 */
function setFlash(string $pesan): void
{
    $_SESSION['flash'] = $pesan;
}

/**
 * Mengambil lalu menghapus pesan flash.
 *
 * @return string|null Null bila tidak ada pesan.
 */
function ambilFlash(): ?string
{
    $pesan = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $pesan;
}

/**
 * Membaca parameter bilangan bulat positif dari array (GET/POST).
 *
 * @param array  $sumber $_GET atau $_POST.
 * @param string $kunci
 * @return int|null Null bila tidak ada atau tidak valid.
 */
function ambilIdPositif(array $sumber, string $kunci = 'id'): ?int
{
    $id = filter_var($sumber[$kunci] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $id === false ? null : $id;
}
