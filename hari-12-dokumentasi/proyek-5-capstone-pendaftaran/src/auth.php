<?php
/**
 * Modul autentikasi admin  login berbasis sesi, password_hash/verify,
 * perlindungan halaman admin dan logout bersih.
 *
 * ID Modul   : MOD-AUT-001
 * Dependensi : config/database.php, src/helpers.php, src/log.php
 *
 * @package    PendaftaranPelatihan
 * @author     Faaiza
 * @version    1.0.0
 * @since      0.1.0
 */
declare(strict_types=1);

/** Hash bcrypt palsu agar waktu respons sama baik username ada maupun tidak. */
const HASH_PALSU = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

/**
 * Mencari admin berdasarkan username.
 *
 * @param PDO    $pdo
 * @param string $username
 * @return array|null Baris admin (id, username, password_hash) atau null.
 */
function cariAdmin(PDO $pdo, string $username): ?array
{
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admin WHERE username = :u');
    $stmt->execute(['u' => $username]);
    $baris = $stmt->fetch();
    return $baris === false ? null : $baris;
}

/**
 * Memproses login. password_verify SELALU dijalankan (anti user-enumeration via waktu),
 * hash di-upgrade bila algoritma usang, dan ID sesi diganti saat berhasil.
 *
 * @param PDO    $pdo
 * @param string $username
 * @param string $sandi    Kata sandi polos dari form.
 * @return bool True bila berhasil (sesi admin_id terisi).
 */
function prosesLogin(PDO $pdo, string $username, string $sandi): bool
{
    $admin = cariAdmin($pdo, $username);

    $hash = $admin['password_hash'] ?? HASH_PALSU;
    $cocok = password_verify($sandi, $hash);
    if ($admin === null || !$cocok) {
        catatLog('WARNING', 'Login gagal untuk username "' . substr($username, 0, 50) . '"');
        return false;
    }

    if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
        $stmt = $pdo->prepare('UPDATE admin SET password_hash = :p WHERE id = :id');
        $stmt->execute(['p' => password_hash($sandi, PASSWORD_DEFAULT), 'id' => $admin['id']]);
    }

    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int) $admin['id'];
    return true;
}

/**
 * Apakah sesi saat ini milik admin yang sudah login?
 *
 * @return bool
 */
function adminSudahLogin(): bool
{
    return !empty($_SESSION['admin_id']);
}

/**
 * Menjaga halaman admin: bila belum login → pesan flash lalu ke login.php.
 *
 * @return void
 */
function wajibLogin(): void
{
    if (!adminSudahLogin()) {
        setFlash('Silakan login untuk melanjutkan.');
        redirect('login.php');
    }
}

/**
 * Mengakhiri sesi admin sepenuhnya (data, cookie, sesi server).
 *
 * @return void
 */
function logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
