<?php /* public/_form.php  dipakai bersama oleh tambah.php dan ubah.php */ ?>
<form method='post'>
    <input type='hidden' name='csrf' value='<?= e(tokenCsrf()) ?>'>
    <p>
        <label for='nama'>Nama</label><br>
        <input id='nama' name='nama' value='<?= e($lama['nama']) ?>'>
        <?php if (isset($galat['nama'])): ?><span><?= e($galat['nama']) ?></span><?php endif; ?>
    </p>
    <p>
        <label for='email'>Email</label><br>
        <input id='email' name='email' type='email' value='<?= e($lama['email']) ?>'>
        <?php if (isset($galat['email'])): ?><span><?= e($galat['email']) ?></span><?php endif; ?>
    </p>
    <p>
        <label for='no_hp'>No. HP</label><br>
        <input id='no_hp' name='no_hp' value='<?= e($lama['no_hp']) ?>'>
        <?php if (isset($galat['no_hp'])): ?><span><?= e($galat['no_hp']) ?></span><?php endif; ?>
    </p>
    <p>
        <label for='kelas_id'>Kelas</label><br>
        <select id='kelas_id' name='kelas_id'>
            <option value=''>-- Pilih kelas --</option>
            <?php foreach ($kelas as $k): ?>
                <?php $dipilih = (int) $lama['kelas_id'] === (int) $k['id']; ?>
                <option value='<?= (int) $k['id'] ?>' <?= $dipilih ? 'selected' : '' ?>>
                    <?= e($k['nama_kelas']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($galat['kelas_id'])): ?><span><?= e($galat['kelas_id']) ?></span><?php endif; ?>
    </p>
    <p>
        <label for='alamat'>Alamat</label><br>
        <textarea id='alamat' name='alamat' rows='3'><?= e($lama['alamat']) ?></textarea>
    </p>
    <button type='submit'>Simpan</button>
    <a href='<?= e($batalUrl ?? 'index.php') ?>'>Batal</a>
</form>
