<?php
/**
 * Modul kelas  CRUD kelas pelatihan dan struktur data pendukung
 * (hash map kelas-per-id dan daftar prioritas kelas hampir penuh).
 *
 * ID Modul   : MOD-KLS-001
 * Dependensi : src/validasi.php, PDO
 *
 * @package    PendaftaranPelatihan
 * @author     Faaiza
 * @version    1.0.0
 * @since      0.1.0
 */
declare(strict_types=1);

/**
 * Daftar kelas ringkas (id, nama) untuk dropdown, urut nama.
 *
 * @param PDO $pdo
 * @return array<int,array{id:int|string,nama_kelas:string}>
 */
function daftarKelas(PDO $pdo): array
{
    return $pdo->query('SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas')->fetchAll();
}

/**
 * Semua kelas beserta jumlah terisi dan sisa kuota (satu query, LEFT JOIN + GROUP BY).
 *
 * @param PDO $pdo
 * @return array<int,array> Kolom: id, nama_kelas, kuota, terisi, sisa.
 */
function daftarKelasDenganSisa(PDO $pdo): array
{
    return $pdo->query(
        'SELECT k.id, k.nama_kelas, k.kuota, COUNT(p.id) AS terisi,
                GREATEST(k.kuota - COUNT(p.id), 0) AS sisa
           FROM kelas k LEFT JOIN pendaftar p ON p.kelas_id = k.id
          GROUP BY k.id, k.nama_kelas, k.kuota ORDER BY k.nama_kelas'
    )->fetchAll();
}

/**
 * STRUKTUR DATA #1  hash map (array asosiatif) id → baris kelas.
 * Dipakai agar pencarian kelas per id O(1).
 *
 * @param array<int,array> $kelas Hasil daftarKelasDenganSisa().
 * @return array<int,array> Kunci = id kelas.
 */
function petaKelasById(array $kelas): array
{
    $peta = [];
    foreach ($kelas as $k) {
        $peta[(int) $k['id']] = $k;
    }
    return $peta;
}

/**
 * STRUKTUR DATA #2  daftar terurut berdasarkan sisa kuota (terkecil dulu, tetapi > 0).
 * Dipakai di dashboard admin: kelas yang hampir penuh tampil lebih dulu.
 *
 * @param array<int,array> $kelas Hasil daftarKelasDenganSisa().
 * @param int              $batas Jumlah maksimal yang dikembalikan.
 * @return array<int,array>
 */
function kelasHampirPenuh(array $kelas, int $batas = 3): array
{
    $kandidat = array_values(array_filter($kelas, static fn (array $k): bool => (int) $k['sisa'] > 0));
    usort($kandidat, static fn (array $a, array $b): int => ((int) $a['sisa'] <=> (int) $b['sisa']) ?: strcmp($a['nama_kelas'], $b['nama_kelas']));
    return array_slice($kandidat, 0, max(0, $batas));
}

/**
 * Satu kelas beserta jumlah terisi.
 *
 * @param PDO $pdo
 * @param int $id
 * @return array|null
 */
function ambilKelas(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare(
        'SELECT k.id, k.nama_kelas, k.kuota, COUNT(p.id) AS terisi
           FROM kelas k LEFT JOIN pendaftar p ON p.kelas_id = k.id
          WHERE k.id = :id GROUP BY k.id, k.nama_kelas, k.kuota'
    );
    $stmt->execute(['id' => $id]);
    $baris = $stmt->fetch();
    return $baris === false ? null : $baris;
}

/**
 * Memvalidasi input form kelas.
 *
 * @param array    $input    $_POST.
 * @param PDO      $pdo
 * @param int|null $idUbah   ID kelas yang sedang diubah (null saat tambah).
 * @param int      $terisi   Jumlah pendaftar kelas itu (kuota tidak boleh di bawahnya).
 * @return array{data:array,galat:array<string,string>}
 */
function validasiKelas(array $input, PDO $pdo, ?int $idUbah, int $terisi = 0): array
{
    $data = [
        'nama_kelas' => trim((string) ($input['nama_kelas'] ?? '')),
        'kuota' => (int) ($input['kuota'] ?? 0),
    ];
    $galat = [];
    if (!validNama($data['nama_kelas'])) {
        $galat['nama_kelas'] = 'Nama kelas 3–100 karakter.';
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM kelas WHERE nama_kelas = :n AND id <> :id');
        $stmt->execute(['n' => $data['nama_kelas'], 'id' => $idUbah ?? 0]);
        if ((int) $stmt->fetchColumn() > 0) {
            $galat['nama_kelas'] = 'Nama kelas sudah dipakai.';
        }
    }
    if (!validKuota($data['kuota'])) {
        $galat['kuota'] = 'Kuota 1–1000.';
    } elseif ($data['kuota'] < $terisi) {
        $galat['kuota'] = 'Kuota tidak boleh di bawah jumlah pendaftar saat ini (' . $terisi . ').';
    }
    return ['data' => $data, 'galat' => $galat];
}

/**
 * Menambah kelas.
 *
 * @param PDO   $pdo
 * @param array $d nama_kelas, kuota.
 * @return int ID kelas baru.
 */
function tambahKelas(PDO $pdo, array $d): int
{
    $pdo->prepare('INSERT INTO kelas (nama_kelas, kuota) VALUES (:nama_kelas, :kuota)')->execute($d);
    return (int) $pdo->lastInsertId();
}

/**
 * Mengubah kelas.
 *
 * @param PDO   $pdo
 * @param int   $id
 * @param array $d nama_kelas, kuota.
 * @return bool
 */
function ubahKelas(PDO $pdo, int $id, array $d): bool
{
    return $pdo->prepare('UPDATE kelas SET nama_kelas = :nama_kelas, kuota = :kuota WHERE id = :id')->execute($d + ['id' => $id]);
}

/**
 * Menghapus kelas. Ditolak (false) bila masih ada pendaftar (FK RESTRICT, SQLSTATE 23000).
 *
 * @param PDO $pdo
 * @param int $id
 * @return bool True bila terhapus; false bila masih dipakai pendaftar.
 * @throws PDOException Bila query gagal selain pelanggaran FK (SQLSTATE),
 *                      mis. koneksi putus. Tidak ditangkap di kelas-hapus.php; ditangani
 *                      handler global di bootstrap.php (dicatat ke log, HTTP 500).
 */
function hapusKelas(PDO $pdo, int $id): bool
{
    try {
        return $pdo->prepare('DELETE FROM kelas WHERE id = :id')->execute(['id' => $id]);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            return false;
        }
        throw $e;
    }
}
