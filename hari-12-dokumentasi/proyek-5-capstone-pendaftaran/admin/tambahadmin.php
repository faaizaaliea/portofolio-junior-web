<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

// (Opsional) Batasi akses hanya untuk admin yang sudah login
// if (empty($_SESSION['admin_login'])) {
//     redirect('login.php');
// }

$pdo = getConnection();
$galat = [];
$sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekCsrf();
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi input
    if (empty($username) || empty($password)) {
        $galat['umum'] = 'Username dan Password tidak boleh kosong.';
    } else {
        // 1. Melakukan hash pada password menggunakan algoritma yang aman (bcrypt)
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // 2. Menyimpan username dan password yang sudah di-hash ke database
        try {
            $sql = "INSERT INTO admin (username, password_hash) VALUES (:username, :password_hash)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'username'      => $username,
                'password_hash' => $password_hash
            ]);

            $sukses = "User '$username' berhasil ditambahkan ke database!";
        } catch (PDOException $e) {
            // Kode 23000 biasanya berarti ada pelanggaran UNIQUE constraint (misal: username sudah ada)
            if ($e->getCode() == 23000) { 
                $galat['umum'] = 'Username sudah digunakan, silakan pilih yang lain.';
            } else {
                $galat['umum'] = 'Terjadi kesalahan saat menyimpan ke database.';
            }
        }
    }
}

$judul = 'Tambah User Baru';
require __DIR__ . '/../templates/header.php';
?>

<h2>Tambah User Baru (Registrasi)</h2>

<?php if ($sukses): ?>
    <p style="color: green; font-weight: bold;"><?= e($sukses) ?></p>
<?php endif; ?>

<?php if (isset($galat['umum'])): ?>
    <p style="color: red;"><?= e($galat['umum']) ?></p>
<?php endif; ?>

<form method="post">
    <!-- Token CSRF untuk keamanan form -->
    <input type="hidden" name="csrf" value="<?= e(tokenCsrf()) ?>">
    
    <p>
        <label for="username">Username Baru</label><br>
        <input type="text" id="username" name="username" required>
    </p>
    
    <p>
        <label for="password">Password (Akan di-hash otomatis)</label><br>
        <input type="password" id="password" name="password" required>
    </p>
    
    <button type="submit">Simpan User</button>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>