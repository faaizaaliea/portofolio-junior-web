<?php
declare(strict_types=1);
function e(?string $teks): string
{
return htmlspecialchars($teks ?? '', ENT_QUOTES, 'UTF-8');
}
function redirect(string $url): never
{
header('Location: ' . $url);
exit;
}
function cekLoginAdmin(): void
{
if (empty($_SESSION['admin_login'])) {
setFlash('Silakan login untuk melanjutkan.');
redirect('login.php');
}
}
function tokenCsrf(): string
{
if (empty($_SESSION['csrf'])) {
$_SESSION['csrf'] = bin2hex(random_bytes(32));
}
return $_SESSION['csrf'];
}
function cekCsrf(): void
{
$kiriman = (string) ($_POST['csrf'] ?? '');
$token = (string) ($_SESSION['csrf'] ?? '');
if ($token === '' || !hash_equals($token, $kiriman)) {
http_response_code(403);
exit('Permintaan tidak valid.');
}
}
function setFlash(string $pesan): void
{
$_SESSION['flash'] = $pesan;
}
function ambilFlash(): ?string
{
$pesan = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
return $pesan;
}
function adminSudahLogin(): bool
{
return !empty($_SESSION['admin_login']);
}
