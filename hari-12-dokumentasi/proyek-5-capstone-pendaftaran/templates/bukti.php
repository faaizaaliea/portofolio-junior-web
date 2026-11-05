<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
h1 { font-size: 18px; margin-bottom: 4px; }
table { width: 100%; border-collapse: collapse; margin-top: 12px; }
td { padding: 6px; border-bottom: 1px solid #cccccc; }
td:first-child { width: 35%; font-weight: bold; }
</style>
<h1>Bukti Pendaftaran</h1>
<p>Nomor: <strong><?= e($nomor) ?></strong></p>
<table>
<tr><td>Nama</td><td><?= e($bukti['nama']) ?></td></tr>
<tr><td>Email</td><td><?= e($bukti['email']) ?></td></tr>
<tr><td>No. HP</td><td><?= e($bukti['no_hp']) ?></td></tr>
<tr><td>Kelas</td><td><?= e($bukti['nama_kelas']) ?></td></tr>
<tr><td>Tanggal daftar</td><td><?= e($bukti['tgl_daftar']) ?></td></tr>
</table>
<p><small>Dicetak pada <?= date('d-m-Y H:i') ?></small></p>