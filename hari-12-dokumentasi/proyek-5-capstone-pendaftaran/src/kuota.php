<?php
/**
 * Modul kuota  pendaftaran dan pemindahan kelas yang aman dari balapan (race
 * condition): transaksi + SELECT ... FOR UPDATE mengunci baris kelas sehingga
 * dua pendaftar bersamaan tidak bisa sama-sama merebut kursi terakhir.
 *
 * ID Modul   : MOD-KUO-001
 * Dependensi : src/pendaftar.php, src/log.php
 *
 * @package    PendaftaranPelatihan
 * @author     Faaiza
 * @version    1.0.0
 * @since      0.1.0
 */
declare(strict_types=1);

/** Dilempar saat kelas penuh atau tidak ada. */
class KuotaPenuhException extends RuntimeException
{
}

/**
 * Sisa kuota satu kelas (0 bila kelas tak ada).
 *
 * @param PDO $pdo
 * @param int $kelasId
 * @return int
 */
function hitungSisaKuota(PDO $pdo, int $kelasId): int
{
    $stmt = $pdo->prepare(
        'SELECT k.kuota - COUNT(p.id)
           FROM kelas k LEFT JOIN pendaftar p ON p.kelas_id = k.id
          WHERE k.id = :id GROUP BY k.id, k.kuota'
    );
    $stmt->execute(['id' => $kelasId]);
    $sisa = $stmt->fetchColumn();
    return $sisa === false ? 0 : max(0, (int) $sisa);
}

/**
 * Mengunci baris kelas dan memastikan masih ada kursi. HARUS dipanggil di dalam transaksi.
 *
 * @param PDO $pdo
 * @param int $kelasId
 * @return void
 * @throws KuotaPenuhException Kelas tidak ditemukan atau penuh.
 */
function pastikanAdaKursi(PDO $pdo, int $kelasId): void
{
    $stmt = $pdo->prepare('SELECT kuota FROM kelas WHERE id = :id FOR UPDATE');
    $stmt->execute(['id' => $kelasId]);
    $kuota = $stmt->fetchColumn();
    if ($kuota === false) {
        throw new KuotaPenuhException('Kelas tidak ditemukan.');
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM pendaftar WHERE kelas_id = :id');
    $stmt->execute(['id' => $kelasId]);
    if ((int) $stmt->fetchColumn() >= (int) $kuota) {
        catatLog('INFO', 'Kuota penuh untuk kelas_id=' . $kelasId);
        throw new KuotaPenuhException('Kuota kelas sudah penuh.');
    }
}

/**
 * Mendaftarkan peserta dengan pemeriksaan kuota atomik.
 *
 * @param PDO   $pdo
 * @param array $data nama, email, no_hp, alamat, kelas_id (sudah tervalidasi).
 * @return bool True berhasil; false bila email ganda (transaksi di-rollback).
 * @throws KuotaPenuhException Kelas penuh/tidak ada.
 * @throws Throwable           Galat database lain (transaksi di-rollback, dilempar ulang).
 */
function daftarkanDenganKuota(PDO $pdo, array $data): bool
{
    $pdo->beginTransaction();
    try {
        pastikanAdaKursi($pdo, (int) $data['kelas_id']);
        if (!tambahPendaftar($pdo, $data)) {
            $pdo->rollBack();
            return false;
        }
        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

/**
 * Mengubah data pendaftar; bila kelas berpindah, kursi kelas tujuan dikunci & diperiksa
 * dalam transaksi yang sama (memperbaiki cek kuota non-atomik pada Hari 11).
 *
 * @param PDO   $pdo
 * @param int   $id         ID pendaftar.
 * @param array $data       Data baru tervalidasi.
 * @param int   $kelasLama  Kelas sebelum diubah.
 * @return bool False bila email ganda.
 * @throws KuotaPenuhException Kelas tujuan penuh; ubah.php menampilkan galat pada kolom kelas.
 * @throws PDOException        Bila query gagal; transaksi dibatalkan (rollBack), lalu
 *                             dilempar ulang dan ditangani handler global (HTTP 500).
 */
function ubahDenganKuota(PDO $pdo, int $id, array $data, int $kelasLama): bool
{
    $pdo->beginTransaction();
    try {
        if ((int) $data['kelas_id'] !== $kelasLama) {
            pastikanAdaKursi($pdo, (int) $data['kelas_id']);
        }
        if (!ubahPendaftar($pdo, $id, $data)) {
            $pdo->rollBack();
            return false;
        }
        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}
