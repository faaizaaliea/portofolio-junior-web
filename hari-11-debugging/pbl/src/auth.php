<?php
declare(strict_types=1);

const HASH_PALSU = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

function cariAdmin(PDO $pdo, string $username): ?array
{
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admin WHERE username = :u');
    $stmt->execute(['u' => $username]);
    $baris = $stmt->fetch();
    return $baris === false ? null : $baris;
}

function prosesLogin(PDO $pdo, string $username, string $sandi): bool
{
    $admin = cariAdmin($pdo, $username);

    $hash = $admin['password_hash'] ?? HASH_PALSU;
    $cocok = password_verify($sandi, $hash);
    if ($admin === null || !$cocok) {
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

function wajibLogin(): void
{
    if (empty($_SESSION['admin_id'])) {
        setFlash('Silakan login untuk melanjutkan.');
        redirect('login.php');
        exit; 
    }
}

function logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
