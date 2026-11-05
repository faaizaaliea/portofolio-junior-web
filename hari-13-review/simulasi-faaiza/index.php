<?php
declare(strict_types=1);
/*
 * Halaman utama: form pendaftaran + daftar peserta dengan pencarian.
 * Library (CDN): Bootstrap 5.3.8 (MIT), SweetAlert2 11.26.25 (MIT).
 * Library PDF (bukti.php): FPDF 1.9.0 (lisensi permisif).
 */
require_once __DIR__ . '/fungsi.php';

$lama = ['nama' => '', 'email' => '', 'no_hp' => '', 'kursus_id' => 0];
$galat = [];
$galatUmum = '';
$daftarKursus = [];
$peserta = [];
$kata = trim((string) ($_GET['q'] ?? ''));
$sukses = ($_GET['status'] ?? '') === 'sukses';
$idBaru = (int) ($_GET['id'] ?? 0);

try {
    $pdo = koneksi();
    $daftarKursus = ambilKursus($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $hasil = validasiPeserta($_POST, array_column($daftarKursus, 'id'));
        $lama = $hasil['data'];
        $galat = $hasil['galat'];

        if ($galat === []) {
            try {
                $id = simpanPeserta($pdo, $lama);
                header('Location: index.php?status=sukses&id=' . $id);
                exit;
            } catch (PDOException $ex) {
                if ($ex->getCode() !== '23000') {
                    throw $ex;
                }
                $galat['email'] = 'Email sudah terdaftar.';
            }
        }
    }

    $peserta = cariPeserta($pdo, $kata);
} catch (Throwable $ex) {
    error_log($ex->getMessage());
    $galatUmum = 'Terjadi kesalahan pada sistem. Silakan coba lagi.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Kursus Singkat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<header class="container py-4 text-center">
    <h1 class="h3">Sistem Pendaftaran Kursus Singkat</h1>
    <p class="text-muted mb-0">Mulai karier Anda di dunia digital bersama kami.</p>
</header>

<main class="container pb-5">
    <?php if ($galatUmum !== ''): ?>
        <div class="alert alert-danger" role="alert"><?= e($galatUmum) ?></div>
    <?php endif; ?>

    <?php if ($sukses && $idBaru > 0): ?>
        <div class="alert alert-success" role="status" id="notif-sukses">
            Pendaftaran berhasil.
            <a href="bukti.php?id=<?= $idBaru ?>" target="_blank" rel="noopener">Unduh bukti (PDF)</a>
        </div>
    <?php endif; ?>

    <section class="card mb-4">
        <div class="card-body">
            <h2 class="h5">Formulir Pendaftaran</h2>
            <form method="post" action="index.php" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="nama">Nama</label>
                        <input class="form-control <?= isset($galat['nama']) ? 'is-invalid' : '' ?>"
                               type="text" id="nama" name="nama" required minlength="3"
                               value="<?= e($lama['nama']) ?>">
                        <div class="invalid-feedback"><?= e($galat['nama'] ?? '') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control <?= isset($galat['email']) ? 'is-invalid' : '' ?>"
                               type="email" id="email" name="email" required
                               value="<?= e($lama['email']) ?>">
                        <div class="invalid-feedback"><?= e($galat['email'] ?? '') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="no_hp">No. HP</label>
                        <input class="form-control <?= isset($galat['no_hp']) ? 'is-invalid' : '' ?>"
                               type="tel" id="no_hp" name="no_hp" required
                               pattern="08[0-9]{8,11}" inputmode="numeric"
                               value="<?= e($lama['no_hp']) ?>">
                        <div class="invalid-feedback"><?= e($galat['no_hp'] ?? '') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="kursus_id">Kursus</label>
                        <select class="form-select <?= isset($galat['kursus_id']) ? 'is-invalid' : '' ?>"
                                id="kursus_id" name="kursus_id" required>
                            <option value="">-- Pilih kursus --</option>
                            <?php foreach ($daftarKursus as $k): ?>
                                <option value="<?= (int) $k['id'] ?>"
                                    <?= (int) $lama['kursus_id'] === (int) $k['id'] ? 'selected' : '' ?>>
                                    <?= e($k['nama_kursus']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?= e($galat['kursus_id'] ?? '') ?></div>
                    </div>
                </div>
                <button class="btn btn-primary mt-3" type="submit">Daftar</button>
            </form>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h2 class="h5 mb-0">Daftar Peserta</h2>
                <form method="get" action="index.php" class="d-flex gap-2" role="search">
                    <label class="visually-hidden" for="q">Cari nama atau email</label>
                    <input class="form-control" type="search" id="q" name="q"
                           placeholder="Cari nama atau email" value="<?= e($kata) ?>">
                    <button class="btn btn-outline-secondary" type="submit">Cari</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr><th>Nama</th><th>Email</th><th>No. HP</th><th>Kursus</th><th>Bukti</th></tr>
                    </thead>
                    <tbody>
                    <?php if ($peserta === []): ?>
                        <tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($peserta as $p): ?>
                        <tr>
                            <td><?= e($p['nama']) ?></td>
                            <td><?= e($p['email']) ?></td>
                            <td><?= e($p['no_hp']) ?></td>
                            <td><?= e($p['nama_kursus']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary"
                                   href="bukti.php?id=<?= (int) $p['id'] ?>" target="_blank" rel="noopener">PDF</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25/dist/sweetalert2.all.min.js"></script>
<script>
    // Toast sukses (SweetAlert2) bila baru saja mendaftar.
    if (document.getElementById('notif-sukses') && window.Swal) {
        Swal.fire({ toast: true, position: 'top-end', icon: 'success',
                    title: 'Pendaftaran berhasil', showConfirmButton: false, timer: 3000 });
    }
</script>
</body>
</html>
