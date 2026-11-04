<?php
declare(strict_types=1);

class KuotaPenuhException extends RuntimeException
{
}

function hitungSisaKuota(PDO $pdo, int $kelasId): int
{
    $stmt = $pdo->prepare(
        'SELECT k.kuota - COUNT(p.id)
           FROM kelas k LEFT JOIN pendaftar p ON p.kelas_id = k.id
          WHERE k.id = :id GROUP BY k.id'
    );
    $stmt->execute(['id' => $kelasId]);
    $sisa = $stmt->fetchColumn();
    return $sisa === false ? 0 : max(0, (int) $sisa);
}


function daftarKelasDenganSisa(PDO $pdo): array
{
    return $pdo->query(
        'SELECT k.*, GREATEST(k.kuota - COUNT(p.id), 0) AS sisa
           FROM kelas k LEFT JOIN pendaftar p ON p.kelas_id = k.id
          GROUP BY k.id ORDER BY k.id'
    )->fetchAll();
}


function daftarkanDenganKuota(PDO $pdo, array $data): bool
{
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('SELECT kuota FROM kelas WHERE id = :id FOR UPDATE');
        $stmt->execute(['id' => $data['kelas_id']]);
        $kuota = $stmt->fetchColumn();
        if ($kuota === false) {
            throw new KuotaPenuhException('Kelas tidak ditemukan.');
        }

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM pendaftar WHERE kelas_id = :id');
        $stmt->execute(['id' => $data['kelas_id']]);
        if ((int) $stmt->fetchColumn() >= (int) $kuota) {
            throw new KuotaPenuhException('Kuota kelas sudah penuh.');
        }

        if (!tambahPendaftar($pdo, $data)) { // false = email ganda (UNIQUE)
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
