<?php
declare(strict_types=1);

/**
 * Fungsi-fungsi inti Sistem Pendaftaran Kursus Singkat.
 *
 * Kegunaan : koneksi database, validasi, penyimpanan, dan pencarian peserta.
 * Riwayat  : 2026-11-04 versi awal simulasi (Faaiza).
 */

const PANJANG_NAMA_MIN = 3;
const POLA_NO_HP = '/^08[0-9]{8,11}$/'; // 08 + 8-11 digit = 10-13 digit

/**
 * Melakukan escape output agar aman dari XSS.
 *
 * @param string|null $teks Teks yang akan ditampilkan.
 * @return string Teks yang sudah di-escape.
 */
function e(?string $teks): string
{
    return htmlspecialchars($teks ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Membuat koneksi PDO ke MySQL berdasarkan config.php.
 *
 * @return PDO Koneksi yang siap dipakai (mode exception).
 * @throws PDOException Bila koneksi gagal (mis. SQLSTATE 1045 / 1049).
 */
function koneksi(): PDO
{
    $k = require __DIR__ . '/config.php';
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $k['host'], $k['nama_db']);

    return new PDO($dsn, $k['user'], $k['sandi'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}

/**
 * Mengambil daftar kursus untuk pilihan pada form.
 *
 * @param PDO $pdo Koneksi database.
 * @return array<int, array{id: int, nama_kursus: string}> Daftar kursus.
 * @throws PDOException Bila query gagal.
 */
function ambilKursus(PDO $pdo): array
{
    return $pdo->query('SELECT id, nama_kursus FROM kursus ORDER BY id')->fetchAll();
}

/**
 * Memvalidasi dan membersihkan input form pendaftaran (validasi sisi server).
 *
 * @param array<string, mixed> $input Data mentah dari $_POST.
 * @param int[] $idKursusValid Daftar id kursus yang boleh dipilih.
 * @return array{data: array<string, mixed>, galat: array<string, string>}
 *         Data yang sudah dibersihkan dan pesan galat per kolom (kosong bila valid).
 */
function validasiPeserta(array $input, array $idKursusValid): array
{
    $data = [
        'nama' => trim((string) ($input['nama'] ?? '')),
        'email' => strtolower(trim((string) ($input['email'] ?? ''))),
        'no_hp' => trim((string) ($input['no_hp'] ?? '')),
        'kursus_id' => (int) ($input['kursus_id'] ?? 0),
    ];
    $galat = [];

    if (mb_strlen($data['nama']) < PANJANG_NAMA_MIN) {
        $galat['nama'] = 'Nama minimal ' . PANJANG_NAMA_MIN . ' karakter.';
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $galat['email'] = 'Format email tidak valid.';
    }
    if (preg_match(POLA_NO_HP, $data['no_hp']) !== 1) {
        $galat['no_hp'] = 'Nomor HP diawali 08 dan 10-13 digit.';
    }
    if (!in_array($data['kursus_id'], array_map('intval', $idKursusValid), true)) {
        $galat['kursus_id'] = 'Pilih kursus yang tersedia.';
    }

    return ['data' => $data, 'galat' => $galat];
}

/**
 * Menyimpan satu peserta baru ke database.
 *
 * @param PDO $pdo Koneksi database.
 * @param array<string, mixed> $data Data hasil validasiPeserta().
 * @return int ID peserta yang baru dibuat.
 * @throws PDOException Bila gagal; kode '23000' berarti email sudah terdaftar.
 */
function simpanPeserta(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare(
        'INSERT INTO peserta (nama, email, no_hp, kursus_id)
         VALUES (:nama, :email, :no_hp, :kursus_id)'
    );
    $stmt->execute($data);

    return (int) $pdo->lastInsertId();
}

/**
 * Mencari peserta berdasarkan nama atau email (kosong = semua).
 *
 * @param PDO $pdo Koneksi database.
 * @param string $kata Kata kunci pencarian.
 * @return array<int, array<string, mixed>> Daftar peserta terbaru lebih dulu.
 * @throws PDOException Bila query gagal.
 */
function cariPeserta(PDO $pdo, string $kata): array
{
    // Prepared statement: kata kunci dikirim sebagai data, bukan bagian SQL.
    $stmt = $pdo->prepare(
        'SELECT p.id, p.nama, p.email, p.no_hp, k.nama_kursus
           FROM peserta p JOIN kursus k ON k.id = p.kursus_id
          WHERE p.nama LIKE :kata OR p.email LIKE :kata2
          ORDER BY p.id DESC'
    );
    $stmt->execute(['kata' => '%' . $kata . '%', 'kata2' => '%' . $kata . '%']);

    return $stmt->fetchAll();
}

/**
 * Mengambil satu peserta untuk bukti pendaftaran.
 *
 * @param PDO $pdo Koneksi database.
 * @param int $id ID peserta (minimal 1).
 * @return array<string, mixed>|null Data peserta, atau null bila tidak ada.
 * @throws PDOException Bila query gagal.
 */
function ambilPeserta(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare(
        'SELECT p.id, p.nama, p.email, p.no_hp, p.tgl_daftar, k.nama_kursus
           FROM peserta p JOIN kursus k ON k.id = p.kursus_id
          WHERE p.id = :id'
    );
    $stmt->execute(['id' => $id]);
    $baris = $stmt->fetch();

    return $baris === false ? null : $baris;
}

/**
 * Membuat nomor bukti, contoh: REG-2026-000007.
 *
 * @param int $id ID peserta.
 * @param string $tglDaftar Tanggal daftar (format MySQL).
 * @return string Nomor bukti.
 */
function nomorBukti(int $id, string $tglDaftar): string
{
    return sprintf('REG-%s-%06d', date('Y', strtotime($tglDaftar)), $id);
}
