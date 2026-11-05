<?php
/**
 * Pengujian otomatis sederhana (tanpa PHPUnit)  php tests/jalankan.php
 *
 * Bagian A: fungsi murni (tanpa database).
 * Bagian B: integrasi dengan database UJI terpisah `db_pelatihan_uji`
 *           (dibuat ulang dari database/skema.sql; data asli tidak tersentuh).
 *           Dilewati bila MariaDB tidak dapat dihubungi.
 *
 * Kode keluar: 0 = semua lolos, 1 = ada yang gagal.
 */
declare(strict_types=1);

require_once __DIR__ . '/../src/log.php';
require_once __DIR__ . '/../src/validasi.php';

$lolos = 0;
$gagal = 0;

/**
 * Mencatat satu hasil uji dan mencetak [LOLOS]/[GAGAL].
 *
 * @param string $nama     Nama kasus uji.
 * @param mixed  $harapan  Nilai yang diharapkan.
 * @param mixed  $aktual   Nilai sebenarnya.
 */
function uji(string $nama, mixed $harapan, mixed $aktual): void
{
    global $lolos, $gagal;
    if ($harapan === $aktual) {
        $lolos++;
        echo "[LOLOS] $nama\n";
        return;
    }
    $gagal++;
    echo "[GAGAL] $nama  diharapkan " . var_export($harapan, true) . ', aktual ' . var_export($aktual, true) . "\n";
}

echo "== A. Fungsi murni ==\n";
uji('HP 10 digit', true, validNoHp('0812345678'));
uji('HP 13 digit', true, validNoHp('0812345678901'));
uji('HP 9 digit ditolak', false, validNoHp('081234567'));
uji('HP 14 digit ditolak', false, validNoHp('08123456789012'));
uji('HP tanpa awalan 08 ditolak', false, validNoHp('0712345678'));
uji('HP berisi huruf ditolak', false, validNoHp('08123abc789'));
uji('HP kosong ditolak', false, validNoHp(''));
uji('Nama 3 karakter', true, validNama('Eko'));
uji('Nama 2 karakter ditolak', false, validNama('Ei'));
uji('Nama spasi saja ditolak', false, validNama('    '));
uji('Nama 101 karakter ditolak', false, validNama(str_repeat('a', 101)));
uji('Kuota 1 valid', true, validKuota(1));
uji('Kuota 0 ditolak', false, validKuota(0));
uji('Kuota 1001 ditolak', false, validKuota(1001));
uji('Sisa kuota normal', 20, sisaKuota(30, 10));
uji('Sisa kuota penuh = 0', 0, sisaKuota(30, 30));
uji('Sisa kuota tidak negatif', 0, sisaKuota(30, 35));
uji('Nomor bukti', 'REG-2026-000012', formatNomorBukti(12, 2026));
uji('Nomor bukti ID besar', 'REG-2026-123456', formatNomorBukti(123456, 2026));
uji('CSV injeksi dinetralkan', "'=1+1", amankanSelCsv('=1+1'));
uji('CSV teks biasa tetap', 'Budi', amankanSelCsv('Budi'));

echo "\n== B. Integrasi database ==\n";
$berkas = __DIR__ . '/../config/config.php';
$konfigurasi = is_file($berkas) ? require $berkas : ['host' => 'localhost', 'user' => 'root', 'sandi' => ''];
$konfigurasi['nama_db'] = 'db_pelatihan_uji';
$konfigurasi['debug'] = true;

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/kelas.php';
require_once __DIR__ . '/../src/pendaftar.php';
require_once __DIR__ . '/../src/kuota.php';
require_once __DIR__ . '/../src/auth.php';

try {
    $root = new PDO(sprintf('mysql:host=%s;charset=utf8mb4', $konfigurasi['host']), $konfigurasi['user'], $konfigurasi['sandi'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    echo "[LEWAT] Database tidak dapat dihubungi (" . $e->getMessage() . "). Bagian B dilewati.\n";
    echo "\nRingkasan: $lolos lolos, $gagal gagal (bagian B dilewati)\n";
    exit($gagal === 0 ? 0 : 1);
}

$root->exec('DROP DATABASE IF EXISTS db_pelatihan_uji');
$skema = (string) file_get_contents(__DIR__ . '/../database/skema.sql');
$skema = preg_replace('/\bdb_pelatihan\b/', 'db_pelatihan_uji', $skema);
$root->exec($skema);

$pdo = getConnection();
$pdo->exec("INSERT INTO kelas (nama_kelas, kuota) VALUES ('Kelas Kecil', 2), ('Kelas Besar', 50)");
$kecil = (int) $pdo->query("SELECT id FROM kelas WHERE nama_kelas='Kelas Kecil'")->fetchColumn();
$besar = (int) $pdo->query("SELECT id FROM kelas WHERE nama_kelas='Kelas Besar'")->fetchColumn();

/** Membuat array data pendaftar valid dengan email unik. */
$data = fn (string $nama, int $kelas): array => [
    'nama' => $nama, 'email' => strtolower($nama) . '@contoh.test', 'no_hp' => '081234567890', 'alamat' => '', 'kelas_id' => $kelas,
];

uji('Daftar pertama berhasil', true, daftarkanDenganKuota($pdo, $data('Ani', $kecil)));
uji('Daftar kedua berhasil', true, daftarkanDenganKuota($pdo, $data('Budi', $kecil)));
uji('Sisa kuota kelas kecil = 0', 0, hitungSisaKuota($pdo, $kecil));
try {
    daftarkanDenganKuota($pdo, $data('Cici', $kecil));
    uji('Pendaftar ke-3 ditolak (kuota penuh)', 'KuotaPenuhException', 'tidak ada exception');
} catch (KuotaPenuhException $e) {
    uji('Pendaftar ke-3 ditolak (kuota penuh)', 'Kuota kelas sudah penuh.', $e->getMessage());
}
uji('Data ke-3 tidak tersimpan', 2, (int) $pdo->query("SELECT COUNT(*) FROM pendaftar WHERE kelas_id=$kecil")->fetchColumn());
uji('Transaksi tidak menggantung setelah penolakan', false, $pdo->inTransaction());
uji('Email ganda → false', false, daftarkanDenganKuota($pdo, $data('Ani', $besar)));
uji('Email ganda tidak menambah baris', 2, (int) $pdo->query('SELECT COUNT(*) FROM pendaftar')->fetchColumn());
try {
    daftarkanDenganKuota($pdo, $data('Dodi', 99999));
    uji('Kelas tidak ada → exception', 'KuotaPenuhException', 'tidak ada exception');
} catch (KuotaPenuhException $e) {
    uji('Kelas tidak ada → exception', 'Kelas tidak ditemukan.', $e->getMessage());
}

$ani = (int) $pdo->query("SELECT id FROM pendaftar WHERE nama='Ani'")->fetchColumn();
$baru = ambilPendaftarById($pdo, $ani);
uji('Pindah kelas ke kelas besar berhasil', true, ubahDenganKuota($pdo, $ani, ['nama' => 'Ani', 'email' => 'ani@contoh.test', 'no_hp' => '081234567890', 'alamat' => '', 'kelas_id' => $besar], (int) $baru["kelas_id"]));
uji('Sisa kelas kecil kembali 1', 1, hitungSisaKuota($pdo, $kecil));
daftarkanDenganKuota($pdo, $data('Eka', $kecil)); // kelas kecil penuh lagi
try {
    ubahDenganKuota($pdo, $ani, ['nama' => 'Ani', 'email' => 'ani@contoh.test', 'no_hp' => '081234567890', 'alamat' => '', 'kelas_id' => $kecil], $besar);
    uji('Pindah ke kelas penuh ditolak', 'KuotaPenuhException', 'tidak ada exception');
} catch (KuotaPenuhException $e) {
    uji('Pindah ke kelas penuh ditolak', $besar, (int) ambilPendaftarById($pdo, $ani)['kelas_id']);
}
uji('Ubah tanpa pindah kelas tidak memeriksa kuota', true, ubahDenganKuota($pdo, (int) $pdo->query("SELECT id FROM pendaftar WHERE nama='Budi'")->fetchColumn(), ['nama' => 'Budi Baru', 'email' => 'budi@contoh.test', 'no_hp' => '081234567890', 'alamat' => '', 'kelas_id' => $kecil], $kecil));

$v = validasiPendaftar(['nama' => 'A', 'email' => 'salah', 'no_hp' => '123', 'kelas_id' => '0'], $pdo, null);
uji('Validasi server: 4 bidang galat', ['nama', 'email', 'no_hp', 'kelas_id'], array_keys($v['galat']));
$v = validasiPendaftar(['nama' => 'Ani', 'email' => 'BUDI@contoh.test', 'no_hp' => '081234567890', 'kelas_id' => (string) $besar], $pdo, null);
uji('Validasi server: email ganda (huruf besar dinormalkan)', 'Email sudah terdaftar.', $v['galat']['email'] ?? '');
$v = validasiPendaftar(['nama' => 'Ani', 'email' => 'budi@contoh.test', 'no_hp' => '081234567890', 'kelas_id' => (string) $besar], $pdo, (int) $pdo->query("SELECT id FROM pendaftar WHERE nama='Budi Baru'")->fetchColumn());
uji('Validasi server: email milik sendiri saat ubah lolos', [], $v['galat']);

$peta = petaKelasById(daftarKelasDenganSisa($pdo));
uji('Hash map: lookup kelas per id', 'Kelas Kecil', $peta[$kecil]['nama_kelas']);
uji('Hash map: id tak ada → tidak ada kunci', false, isset($peta[123456]));
$hampir = kelasHampirPenuh(daftarKelasDenganSisa($pdo));
uji('Hampir penuh: kelas penuh (sisa 0) tidak ikut', ['Kelas Besar'], array_column($hampir, 'nama_kelas'));

uji('Hapus kelas berisi pendaftar ditolak', false, hapusKelas($pdo, $kecil));
$idBaru = tambahKelas($pdo, ['nama_kelas' => 'Kelas Kosong', 'kuota' => 5]);
uji('Hapus kelas kosong berhasil', true, hapusKelas($pdo, $idBaru));
$vk = validasiKelas(['nama_kelas' => 'Kelas Kecil', 'kuota' => '1'], $pdo, null, 0);
uji('Validasi kelas: nama ganda ditolak', 'Nama kelas sudah dipakai.', $vk['galat']['nama_kelas'] ?? '');
$vk = validasiKelas(['nama_kelas' => 'Kelas Kecil', 'kuota' => '1'], $pdo, $kecil, 2);
uji('Validasi kelas: kuota di bawah terisi ditolak', true, isset($vk['galat']['kuota']));

$injeksi = "x' OR '1'='1";
$stmt = $pdo->prepare('SELECT COUNT(*) FROM pendaftar WHERE nama = :n');
$stmt->execute(['n' => $injeksi]);
uji('Prepared statement: payload SQLi tidak mengembalikan baris', 0, (int) $stmt->fetchColumn());

$pdo->prepare('INSERT INTO admin (username, password_hash) VALUES (:u, :p)')->execute(['u' => 'admin', 'p' => password_hash('rahasia123', PASSWORD_DEFAULT)]);
$admin = cariAdmin($pdo, 'admin');
uji('Admin ditemukan', 'admin', $admin['username'] ?? null);
uji('password_verify benar', true, password_verify('rahasia123', $admin['password_hash']));
uji('password_verify salah', false, password_verify('salah', $admin['password_hash']));
uji('Hash tidak menyimpan sandi polos', false, str_contains($admin['password_hash'], 'rahasia123'));
uji('Admin tak ada → null', null, cariAdmin($pdo, 'hantu'));

$root->exec('DROP DATABASE db_pelatihan_uji');
echo "\nRingkasan: $lolos lolos, $gagal gagal\n";
exit($gagal === 0 ? 0 : 1);
