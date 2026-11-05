<?php
/**
 * Modul pendaftar  query dan validasi data peserta (tanpa logika kuota;
 * kuota ada di kuota.php).
 *
 * ID Modul   : MOD-PND-001
 * Dependensi : config/database.php, src/validasi.php, src/kelas.php
 *
 * @package    PendaftaranPelatihan
 * @author     Faaiza
 * @version    1.0.0
 * @since      0.1.0
 */
declare(strict_types=1);

/**
 * Semua pendaftar (terbaru dulu) beserta nama kelas  sumber tabel DataTables.
 *
 * @param PDO $pdo
 * @return array<int,array>
 */
function ambilSemuaPendaftar(PDO $pdo): array
{
    return $pdo->query(
        'SELECT p.id, p.nama, p.email, p.no_hp, p.tgl_daftar, p.kelas_id, k.nama_kelas
           FROM pendaftar p JOIN kelas k ON k.id = p.kelas_id
          ORDER BY p.id DESC'
    )->fetchAll();
}

/**
 * Satu pendaftar berdasarkan id.
 *
 * @param PDO $pdo
 * @param int $id
 * @return array|null
 */
function ambilPendaftarById(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM pendaftar WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $baris = $stmt->fetch();
    return $baris === false ? null : $baris;
}

/**
 * Apakah email sudah dipakai pendaftar lain?
 *
 * @param PDO      $pdo
 * @param string   $email
 * @param int|null $kecualiId Abaikan baris ini (saat ubah data).
 * @return bool
 */
function emailSudahAda(PDO $pdo, string $email, ?int $kecualiId): bool
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM pendaftar WHERE email = :email AND id <> :id');
    $stmt->execute(['email' => $email, 'id' => $kecualiId ?? 0]);
    return (int) $stmt->fetchColumn() > 0;
}

/**
 * Memvalidasi & menormalkan input form pendaftar (sisi server - sumber kebenaran).
 *
 * @param array    $input  $_POST.
 * @param PDO      $pdo
 * @param int|null $idUbah ID yang sedang diubah, null saat tambah.
 * @return array{data:array,galat:array<string,string>}
 */
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
    if (!validNama($data['nama'])) {
        $galat['nama'] = 'Nama 3–100 karakter.';
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($data['email']) > 120) {
        $galat['email'] = 'Format email tidak valid.';
    } elseif (emailSudahAda($pdo, $data['email'], $idUbah)) {
        $galat['email'] = 'Email sudah terdaftar.';
    }
    if (!validNoHp($data['no_hp'])) {
        $galat['no_hp'] = 'Nomor HP diawali 08 dan 10-13 digit.';
    }
    if (!in_array($data['kelas_id'], array_map('intval', array_column(daftarKelas($pdo), 'id')), true)) {
        $galat['kelas_id'] = 'Pilih kelas yang tersedia.';
    }
    return ['data' => $data, 'galat' => $galat];
}

/**
 * INSERT pendaftar. Dipakai di dalam transaksi kuota (lihat daftarkanDenganKuota).
 *
 * @param PDO   $pdo
 * @param array $d nama, email, no_hp, alamat, kelas_id.
 * @return bool False bila email ganda (UNIQUE, SQLSTATE 23000).
 *
 * @throws PDOException Bila query gagal selain email ganda (SQLSTATE bukan 23000).
 *                      Dipanggil dari fungsi transaksi di kuota.php, yang melakukan
 *                      rollBack lalu melempar ulang; ditangani handler global.
 */
function tambahPendaftar(PDO $pdo, array $d): bool
{
    $sql = 'INSERT INTO pendaftar (nama, email, no_hp, alamat, kelas_id)
            VALUES (:nama, :email, :no_hp, :alamat, :kelas_id)';
    try {
        return $pdo->prepare($sql)->execute($d);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            return false;
        }
        throw $e;
    }
}

/**
 * UPDATE pendaftar.
 *
 * @param PDO   $pdo
 * @param int   $id
 * @param array $d nama, email, no_hp, alamat, kelas_id.
 * @return bool False bila email ganda.
 * @throws PDOException Bila query gagal selain email ganda (SQLSTATE bukan 23000).
 *                      Dipanggil dari fungsi transaksi di kuota.php, yang melakukan
 *                      rollBack lalu melempar ulang; ditangani handler global.
 */
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

/**
 * Menghapus pendaftar.
 *
 * @param PDO $pdo
 * @param int $id
 * @return bool
 * @throws PDOException Bila query gagal; tidak ditangkap, ditangani handler global.
 */
function hapusPendaftar(PDO $pdo, int $id): bool
{
    return $pdo->prepare('DELETE FROM pendaftar WHERE id = :id')->execute(['id' => $id]);
}

/**
 * Data untuk bukti pendaftaran PDF.
 *
 * @param PDO $pdo
 * @param int $id
 * @return array|null
 */
function ambilBuktiPendaftar(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare(
        'SELECT p.id, p.nama, p.email, p.no_hp, p.tgl_daftar, k.nama_kelas
           FROM pendaftar p JOIN kelas k ON k.id = p.kelas_id WHERE p.id = :id'
    );
    $stmt->execute(['id' => $id]);
    $baris = $stmt->fetch();
    return $baris === false ? null : $baris;
}
