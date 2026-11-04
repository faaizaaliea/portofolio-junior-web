<?php
declare(strict_types=1);
function ambilSemuaPendaftar(PDO $pdo): array
{
    $sql = 'SELECT p.id, p.nama, p.email, p.no_hp, p.tgl_daftar, k.nama_kelas
            FROM pendaftar p JOIN kelas k ON k.id = p.kelas_id
            ORDER BY p.id DESC';
    return $pdo->query($sql)->fetchAll();
}
function daftarKelas(PDO $pdo): array
{
return $pdo->query('SELECT id, nama_kelas FROM kelas ORDER BY
nama_kelas')->fetchAll();
}
function hitungPendaftar(PDO $pdo, string $kata): int
{
$stmt = $pdo->prepare('SELECT COUNT(*) FROM pendaftar WHERE nama LIKE :kata');
$stmt->execute(['kata' => '%' . $kata . '%']);
return (int) $stmt->fetchColumn();
}
function ambilPendaftar(PDO $pdo, string $kata, int $batas, int $offset): array
{
$sql = 'SELECT p.id, p.nama, p.email, p.no_hp, k.nama_kelas
FROM pendaftar p JOIN kelas k ON k.id = p.kelas_id
WHERE p.nama LIKE :kata
ORDER BY p.id DESC LIMIT :batas OFFSET :offset';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':kata', '%' . $kata . '%');
$stmt->bindValue(':batas', $batas, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
return $stmt->fetchAll();
}
function ambilPendaftarById(PDO $pdo, int $id): ?array
{
$stmt = $pdo->prepare('SELECT * FROM pendaftar WHERE id = :id');
$stmt->execute(['id' => $id]);
$baris = $stmt->fetch();
return $baris === false ? null : $baris;
}
function emailSudahAda(PDO $pdo, string $email, ?int $kecualiId): bool
{
$stmt = $pdo->prepare('SELECT COUNT(*) FROM pendaftar WHERE email = :email AND id
<> :id');
$stmt->execute(['email' => $email, 'id' => $kecualiId ?? 0]);
return (int) $stmt->fetchColumn() > 0;
}
function validasiPendaftar(array $input, PDO $pdo, ?int $idUbah): array
{
$data = [
'nama' => trim((string) ($input['nama'] ?? '')),
'email' => strtolower(trim((string) ($input['email'] ?? ''))),
'no_hp' => trim((string) ($input['no_hp'] ?? '')),
'alamat' => trim((string) ($input['alamat'] ?? '')),
'kelas_id' => (int) ($input['kelas_id'] ?? 0),
];
$galat = [];
if (mb_strlen($data['nama']) < 3) {
$galat['nama'] = 'Nama minimal 3 karakter.';
}
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
$galat['email'] = 'Format email tidak valid.';
} elseif (emailSudahAda($pdo, $data['email'], $idUbah)) {
$galat['email'] = 'Email sudah terdaftar.';
}
if (!preg_match('/^08[0-9]{8,11}$/', $data['no_hp'])) {
$galat['no_hp'] = 'Nomor HP diawali 08 dan 10-13 digit.';
}
if (!in_array($data['kelas_id'], array_column(daftarKelas($pdo), 'id'), true)) {
$galat['kelas_id'] = 'Pilih kelas yang tersedia.';
}
return ['data' => $data, 'galat' => $galat];
}
function tambahPendaftar(PDO $pdo, array $d): bool
{
$sql = 'INSERT INTO pendaftar (nama, email, no_hp, alamat, kelas_id)
VALUES (:nama, :email, :no_hp, :alamat, :kelas_id)';
try {
return $pdo->prepare($sql)->execute($d);
} catch (PDOException $e) {
if ($e->getCode() === '23000') {
return false; // pelanggaran UNIQUE email
}
throw $e;
}
}
function ubahPendaftar(PDO $pdo, int $id, array $d): bool
{
$sql = 'UPDATE pendaftar SET nama = :nama, email = :email, no_hp = :no_hp,
alamat = :alamat, kelas_id = :kelas_id WHERE id = :id';
try {
return $pdo->prepare($sql)->execute($d + ['id' => $id]);
} catch (PDOException $e) {
if ($e->getCode() === '23000') {
return false;
}
throw $e;
}
}
function hapusPendaftar(PDO $pdo, int $id): bool
{
return $pdo->prepare('DELETE FROM pendaftar WHERE id = :id')->execute(['id' =>
$id]);
}
function ambilBuktiPendaftar(PDO $pdo, int $id): ?array
{
    $sql = 'SELECT p.id, p.nama, p.email, p.no_hp, p.tgl_daftar, k.nama_kelas
            FROM pendaftar p JOIN kelas k ON k.id = p.kelas_id
            WHERE p.id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $baris = $stmt->fetch();
    return $baris === false ? null : $baris;
}
