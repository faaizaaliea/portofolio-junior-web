<?php
declare(strict_types=1);
/*
 * DEMONSTRASI SQL INJECTION — HANYA UNTUK LINGKUNGAN LATIHAN.
 * Halaman ini sengaja memuat kode rentan. Jangan di-commit ke repositori publik,
 * jangan di-deploy ke server. Dikunci: hanya bisa dibuka dari localhost dan saat debug = true.
 */
require_once __DIR__ . '/../src/bootstrap.php';

$dariLokal = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
if (!$dariLokal || empty($konfigurasi['debug'])) {
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}

/** VERSI BERBAHAYA: input pengguna disambung langsung ke query (pola yang diperingatkan di T3). */
function cariEmailBerbahaya(PDO $pdo, string $email): array
{
    $sql = "SELECT * FROM pendaftar WHERE email = '$email'";
    return ['sql' => $sql, 'baris' => $pdo->query($sql)->fetchAll()];
}

/** VERSI AMAN: prepare() + execute() dengan placeholder :email, persis pola T3. */
function cariEmailAman(PDO $pdo, string $email): array
{
    $sql = 'SELECT * FROM pendaftar WHERE email = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    return ['sql' => $sql, 'baris' => $stmt->fetchAll()];
}

$pdo = getConnection();
$email = (string) ($_GET['email'] ?? '');
$payload = "x' OR '1'='1";
$berbahaya = $aman = null;
$galatBerbahaya = '';

if ($email !== '') {
    try {
        $berbahaya = cariEmailBerbahaya($pdo, $email);
    } catch (PDOException $e) {
        $galatBerbahaya = $e->getMessage();
    }
    $aman = cariEmailAman($pdo, $email);
}

function tabelHasil(array $baris): void
{
    if ($baris === []) {
        echo '<p><strong>0 baris</strong> — tidak ada data yang cocok.</p>';
        return;
    }
    echo '<p><strong>' . count($baris) . ' baris</strong> dikembalikan:</p><table border="1" cellpadding="4"><tr><th>ID</th><th>Nama</th><th>Email</th><th>No. HP</th></tr>';
    foreach ($baris as $b) {
        echo '<tr><td>' . (int) $b['id'] . '</td><td>' . e($b['nama']) . '</td><td>' . e($b['email']) . '</td><td>' . e($b['no_hp']) . '</td></tr>';
    }
    echo '</table>';
}

$area = 'publik';
$judul = 'Demo SQL Injection (latihan)';
require __DIR__ . '/../templates/header.php';
?>
<h2>Demo SQL Injection — hanya untuk latihan</h2>
<p>Coba: <a href="?email=<?= e(urlencode($payload)) ?>">?email=<?= e($payload) ?></a>
 · pembanding normal: <a href="?email=<?= e(urlencode('budi@contoh.test')) ?>">email biasa</a></p>
<form method="get">
<label for="email">Email</label>
<input id="email" name="email" value="<?= e($email) ?>" size="40">
<button type="submit">Cari</button>
</form>

<?php if ($email !== ''): ?>
<h3>1. Versi BERBAHAYA (string digabung)</h3>
<p>Query yang dijalankan:</p>
<pre><?= e($berbahaya['sql'] ?? ("SELECT * FROM pendaftar WHERE email = '" . $email . "'")) ?></pre>
<?php if ($galatBerbahaya !== ''): ?>
<p role="alert">Query error (sintaks SQL rusak oleh input): <?= e($galatBerbahaya) ?></p>
<?php else: tabelHasil($berbahaya['baris']); endif; ?>

<h3>2. Versi AMAN (prepared statement)</h3>
<p>Query yang dikirim ke database:</p>
<pre><?= e($aman['sql']) ?></pre>
<p>Nilai :email yang dikirim terpisah: <code><?= e($email) ?></code></p>
<?php tabelHasil($aman['baris']); ?>

<h3>Penjelasan</h3>
<p><strong>Versi berbahaya.</strong> Input langsung disisipkan ke teks SQL, jadi database menerima satu string utuh
lalu mem-parse-nya. Tanda kutip <code>'</code> pada input menutup literal email lebih awal, dan sisa input
<code>OR '1'='1</code> dibaca sebagai bagian perintah. Hasilnya
<code>WHERE email = 'x' OR '1'='1'</code>: kondisi <code>'1'='1'</code> selalu benar, sehingga seluruh baris
tabel ikut keluar. Penyerang berhasil mengubah <em>struktur</em> query.</p>
<p><strong>Versi aman.</strong> Query dikirim dengan placeholder <code>:email</code> dan nilainya dikirim
terpisah. Karena <code>PDO::ATTR_EMULATE_PREPARES</code> bernilai <code>false</code> (lihat
<code>config/database.php</code>), MySQL mem-parse kerangka query lebih dulu, sebelum nilai ada. Nilai
<code>x' OR '1'='1</code> kemudian hanya diperlakukan sebagai <em>data</em> literal untuk dibandingkan dengan kolom
email, bukan sebagai kode. Tidak ada email yang persis sama dengan teks aneh itu, maka hasilnya 0 baris.
Inti perbedaannya: pada versi aman, input tidak pernah punya kesempatan menjadi bagian dari sintaks SQL.</p>
<?php endif; ?>
<?php require __DIR__ . '/../templates/footer.php'; ?>
