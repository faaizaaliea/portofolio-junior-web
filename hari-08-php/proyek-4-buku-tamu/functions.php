<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

date_default_timezone_set('Asia/Jakarta');

const FOLDER_DATA = __DIR__ . '/data';
const FILE_TAMU   = FOLDER_DATA . '/tamu.json';
const FILE_BATAS  = FOLDER_DATA . '/batas.json';
const MAKS_PESAN  = 500;
const PER_HALAMAN = 5;
const JEDA_DETIK  = 30;

/* ========== Utilitas umum ========== */

/**
 * Meng-escape nilai agar aman ditampilkan di HTML.
 *
 * @param string|int $teks Nilai yang akan ditampilkan.
 * @return string Teks yang sudah di-escape.
 */
function e(string|int $teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

/**
 * Merapikan spasi berlebih (termasuk baris baru) menjadi satu spasi.
 *
 * @param string $teks Teks mentah.
 * @return string Teks yang sudah dirapikan.
 */
function bersihkanSpasi(string $teks): string
{
    return trim((string) preg_replace('/\s+/', ' ', $teks));
}

/**
 * Mengambil nilai teks dari array input; bukan string (mis. array) dianggap kosong.
 *
 * @param array<string, mixed> $input Sumber data (mis. $_POST).
 * @param string $kunci Nama kolom.
 * @return string Nilai teks atau string kosong.
 */
function teksDari(array $input, string $kunci): string
{
    $nilai = $input[$kunci] ?? '';
    return is_string($nilai) ? $nilai : '';
}

/**
 * Mengambil parameter $_GET sebagai teks yang sudah di-trim.
 *
 * @param string $kunci Nama parameter.
 * @return string Nilai parameter atau string kosong.
 */
function parameterGet(string $kunci): string
{
    return trim(teksDari($_GET, $kunci));
}

/* ========== Penyimpanan JSON ========== */

/**
 * Memastikan folder data/ ada dan dilindungi .htaccess.
 *
 * @return bool True jika folder dan .htaccess siap.
 */
function siapkanFolderData(): bool
{
    if (!is_dir(FOLDER_DATA) && !mkdir(FOLDER_DATA, 0755, true) && !is_dir(FOLDER_DATA)) {
        return false;
    }
    $htaccess = FOLDER_DATA . '/.htaccess';
    if (!file_exists($htaccess)) {
        $aturan = "<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n"
            . "<IfModule !mod_authz_core.c>\n    Order allow,deny\n    Deny from all\n</IfModule>\n";
        return file_put_contents($htaccess, $aturan) !== false;
    }
    return true;
}

/**
 * Membaca berkas JSON menjadi array.
 *
 * @param string $berkas Path berkas JSON.
 * @return array<int|string, mixed> Isi berkas, atau array kosong jika tidak ada/rusak.
 */
function bacaJson(string $berkas): array
{
    if (!is_file($berkas)) {
        return [];
    }
    $isi = file_get_contents($berkas);
    $data = json_decode($isi === false ? '[]' : $isi, true);
    return is_array($data) ? $data : [];
}

/**
 * Membaca, mengubah, dan menulis ulang berkas JSON dalam satu kunci (flock),
 * sehingga dua permintaan bersamaan tidak saling menimpa. Berkas yang rusak
 * tidak akan ditimpa.
 *
 * @param string $berkas Path berkas JSON.
 * @param callable(array<int|string, mixed>): array<int|string, mixed> $ubah Fungsi pengubah data.
 * @return bool True jika berhasil disimpan.
 */
function ubahJson(string $berkas, callable $ubah): bool
{
    if (!siapkanFolderData()) {
        return false;
    }
    $fp = fopen($berkas, 'c+');
    if ($fp === false) {
        return false;
    }
    try {
        if (!flock($fp, LOCK_EX)) {
            return false;
        }
        $isi = stream_get_contents($fp);
        $data = [];
        if ($isi !== false && trim($isi) !== '') {
            $data = json_decode($isi, true);
            if (!is_array($data)) {
                return false;
            }
        }
        $json = json_encode($ubah($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            return false;
        }
        rewind($fp);
        ftruncate($fp, 0);
        $berhasil = fwrite($fp, $json) !== false;
        fflush($fp);
        flock($fp, LOCK_UN);
        return $berhasil;
    } finally {
        fclose($fp);
    }
}

/* ========== Data tamu ========== */

/**
 * Memvalidasi input buku tamu.
 *
 * @param array<string, mixed> $input Data mentah (biasanya $_POST).
 * @return array{data: array<string, string>, galat: array<string, string>}
 */
function validasiTamu(array $input): array
{
    $data = [
        'nama'  => bersihkanSpasi(teksDari($input, 'nama')),
        'email' => trim(teksDari($input, 'email')),
        'pesan' => trim(teksDari($input, 'pesan')),
    ];

    $galat = [];
    $panjangNama = mb_strlen($data['nama']);
    if ($panjangNama < 3 || $panjangNama > 50) {
        $galat['nama'] = 'Nama 3 sampai 50 karakter.';
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($data['email']) > 100) {
        $galat['email'] = 'Format email tidak valid.';
    }
    $panjang = mb_strlen($data['pesan']);
    if ($panjang === 0 || $panjang > MAKS_PESAN) {
        $galat['pesan'] = 'Pesan wajib diisi, maksimal ' . MAKS_PESAN . ' karakter.';
    }
    return ['data' => $data, 'galat' => $galat];
}

/**
 * Mengambil semua pesan tamu (urutan lama ke baru).
 *
 * @return list<array<string, mixed>> Daftar tamu.
 */
function ambilSemuaTamu(): array
{
    return array_values(bacaJson(FILE_TAMU));
}

/**
 * Menyimpan satu pesan tamu baru (id dan waktu ditambahkan otomatis).
 *
 * @param array<string, string> $data Data tamu yang sudah tervalidasi.
 * @return bool True jika berhasil disimpan.
 */
function simpanTamu(array $data): bool
{
    return ubahJson(FILE_TAMU, function (array $daftar) use ($data): array {
        $data['id'] = $daftar === [] ? 1 : max(array_column($daftar, 'id')) + 1;
        $data['waktu'] = date('Y-m-d H:i:s');
        $daftar[] = $data;
        return $daftar;
    });
}

/**
 * Menghapus pesan tamu berdasarkan id.
 *
 * @param int $id Id pesan.
 * @return bool True jika penyimpanan berhasil.
 */
function hapusTamu(int $id): bool
{
    return ubahJson(
        FILE_TAMU,
        fn (array $daftar): array => array_values(
            array_filter($daftar, fn (array $tamu): bool => (int) $tamu['id'] !== $id)
        )
    );
}

/**
 * Mencari tamu berdasarkan nama (tidak peka huruf besar/kecil).
 *
 * @param list<array<string, mixed>> $daftar Daftar tamu.
 * @param string $kata Kata kunci; kosong berarti semua.
 * @return list<array<string, mixed>> Hasil pencarian.
 */
function cariTamu(array $daftar, string $kata): array
{
    if ($kata === '') {
        return $daftar;
    }
    return array_values(array_filter(
        $daftar,
        fn (array $tamu): bool => mb_stripos((string) $tamu['nama'], $kata) !== false
    ));
}

/* ========== Paginasi ========== */

/**
 * Memotong daftar menjadi satu halaman memakai array_slice.
 *
 * @param array<int, mixed> $daftar Seluruh data.
 * @param int $halaman Nomor halaman yang diminta (otomatis dibatasi ke rentang valid).
 * @param int $perHalaman Jumlah item per halaman.
 * @return array{item: array<int, mixed>, halaman: int, totalHalaman: int, total: int}
 */
function paginasi(array $daftar, int $halaman, int $perHalaman = PER_HALAMAN): array
{
    $total = count($daftar);
    $totalHalaman = max(1, (int) ceil($total / $perHalaman));
    $halaman = min(max(1, $halaman), $totalHalaman);
    return [
        'item'         => array_slice($daftar, ($halaman - 1) * $perHalaman, $perHalaman),
        'halaman'      => $halaman,
        'totalHalaman' => $totalHalaman,
        'total'        => $total,
    ];
}

/**
 * Membuat URL daftar tamu dengan kata pencarian dan nomor halaman.
 *
 * @param string $kata Kata pencarian (boleh kosong).
 * @param int $halaman Nomor halaman.
 * @return string URL relatif (belum di-escape untuk HTML).
 */
function urlDaftar(string $kata, int $halaman): string
{
    $param = [];
    if ($kata !== '') {
        $param['q'] = $kata;
    }
    if ($halaman > 1) {
        $param['hal'] = $halaman;
    }
    return 'index.php' . ($param === [] ? '' : '?' . http_build_query($param));
}

/* ========== Batas kirim per IP ========== */

/**
 * Mengambil alamat IP klien (REMOTE_ADDR; header proxy sengaja tidak dipercaya).
 *
 * @return string Alamat IP.
 */
function alamatIp(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? 'tidak-diketahui');
}

/**
 * Menghitung sisa detik sebelum IP boleh mengirim lagi.
 *
 * @param string $ip Alamat IP.
 * @param int|null $sekarang Waktu UNIX saat ini (untuk pengujian).
 * @return int Sisa detik; 0 berarti boleh mengirim.
 */
function sisaJeda(string $ip, ?int $sekarang = null): int
{
    $sekarang ??= time();
    $terakhir = (int) (bacaJson(FILE_BATAS)[hash('sha256', $ip)] ?? 0);
    return max(0, JEDA_DETIK - ($sekarang - $terakhir));
}

/**
 * Mencatat waktu kirim terakhir sebuah IP dan membuang catatan yang kedaluwarsa.
 * IP disimpan dalam bentuk hash.
 *
 * @param string $ip Alamat IP.
 * @param int|null $sekarang Waktu UNIX saat ini (untuk pengujian).
 * @return bool True jika berhasil dicatat.
 */
function catatKirim(string $ip, ?int $sekarang = null): bool
{
    $sekarang ??= time();
    return ubahJson(FILE_BATAS, function (array $catatan) use ($ip, $sekarang): array {
        foreach ($catatan as $kunci => $waktu) {
            if ($sekarang - (int) $waktu >= JEDA_DETIK) {
                unset($catatan[$kunci]);
            }
        }
        $catatan[hash('sha256', $ip)] = $sekarang;
        return $catatan;
    });
}

/* ========== Admin & CSV ========== */

/**
 * Memulai sesi dengan cookie yang lebih aman.
 */
function mulaiSesi(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax']);
    }
}

/**
 * Memeriksa kata sandi admin dan menandai sesi sebagai admin jika benar.
 *
 * @param string $sandi Kata sandi yang dimasukkan.
 * @return bool True jika benar.
 */
function cobaLogin(string $sandi): bool
{
    if (!password_verify($sandi, ADMIN_HASH)) {
        return false;
    }
    session_regenerate_id(true);
    $_SESSION['admin'] = true;
    return true;
}

/**
 * Apakah sesi saat ini admin?
 *
 * @return bool True jika admin.
 */
function adalahAdmin(): bool
{
    return ($_SESSION['admin'] ?? false) === true;
}

/**
 * Mengakhiri sesi admin.
 */
function keluarAdmin(): void
{
    $_SESSION = [];
    session_destroy();
}

/**
 * Mengambil (atau membuat) token CSRF untuk sesi ini.
 *
 * @return string Token CSRF.
 */
function tokenCsrf(): string
{
    if (empty($_SESSION['csrf']) || !is_string($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Memeriksa token CSRF yang dikirim form.
 *
 * @param mixed $token Token dari form.
 * @return bool True jika cocok.
 */
function cekCsrf(mixed $token): bool
{
    return is_string($token) && hash_equals(tokenCsrf(), $token);
}

/**
 * Mencegah CSV injection: sel yang diawali = + - @ diberi apostrof di depannya.
 *
 * @param string $teks Isi sel.
 * @return string Isi sel yang aman dibuka di Excel/Sheets.
 */
function amankanSelCsv(string $teks): string
{
    return preg_match('/^[=+\-@\t\r]/', $teks) === 1 ? "'" . $teks : $teks;
}

/**
 * Mengubah daftar tamu menjadi teks CSV.
 *
 * @param list<array<string, mixed>> $daftar Daftar tamu.
 * @return string Isi CSV (tanpa BOM).
 */
function eksporCsv(array $daftar): string
{
    $handle = fopen('php://temp', 'r+');
    if ($handle === false) {
        return '';
    }
    fputcsv($handle, ['id', 'waktu', 'nama', 'email', 'pesan'], ',', '"', '');
    foreach ($daftar as $tamu) {
        fputcsv($handle, [
            (string) $tamu['id'],
            (string) $tamu['waktu'],
            amankanSelCsv((string) $tamu['nama']),
            amankanSelCsv((string) $tamu['email']),
            amankanSelCsv((string) $tamu['pesan']),
        ], ',', '"', '');
    }
    rewind($handle);
    $csv = (string) stream_get_contents($handle);
    fclose($handle);
    return $csv;
}