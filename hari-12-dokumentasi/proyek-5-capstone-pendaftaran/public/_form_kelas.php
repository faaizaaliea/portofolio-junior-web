<?php
if (!(isset($lama))) {
  $lama = ['nama_kelas' => '', 'kuota' => ''];
}
?>
<form method='post'>
  <input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>

  <div class='mb-3'>
    <label for='nama_kelas' class='form-label'>Nama Kelas</label>
    <input id='nama_kelas' name='nama_kelas' class='form-control <?= isset($galat['nama_kelas']) ? 'is-invalid' : '' ?>'
      value='<?= e((string)$lama['nama_kelas']) ?>'>
    <?php if (isset($galat['nama_kelas'])): ?>
      <div class='invalid-feedback'><?= e($galat['nama_kelas']) ?></div>
    <?php endif; ?>
  </div>

  <div class='mb-3'>
    <label for='kuota' class='form-label'>Kuota Peserta</label>
    <input id='kuota' name='kuota' type='number' class='form-control <?= isset($galat['kuota']) ? 'is-invalid' : '' ?>'
      value='<?= e((string)$lama['kuota']) ?>'>
    <?php if (isset($galat['kuota'])): ?>
      <div class='invalid-feedback'><?= e($galat['kuota']) ?></div>
    <?php endif; ?>
  </div>

  <div class='mb-3'>
    <button type='submit' class='btn btn-primary'>Simpan</button>
    <button type='reset' class='btn btn-warning'>Reset</button>
  </div>
  <div class='mb-3'>
    <a href='kelas.php'>Batal</a>
  </div>
</form>