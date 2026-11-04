
<div class="card">
    <div class="card-body">

        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(tokenCsrf()) ?>">

            <div class="row">

                <!-- Nama -->
                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        class="form-control <?= isset($galat['nama']) ? 'is-invalid' : '' ?>"
                        value="<?= e($lama['nama']) ?>"
                    >
                    <?php if (isset($galat['nama'])): ?>
                        <div class="invalid-feedback">
                            <?= e($galat['nama']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control <?= isset($galat['email']) ? 'is-invalid' : '' ?>"
                        value="<?= e($lama['email']) ?>"
                    >
                    <?php if (isset($galat['email'])): ?>
                        <div class="invalid-feedback">
                            <?= e($galat['email']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- No HP -->
                <div class="col-md-6 mb-3">
                    <label for="no_hp" class="form-label">No HP</label>
                    <input
                        type="text"
                        id="no_hp"
                        name="no_hp"
                        class="form-control <?= isset($galat['no_hp']) ? 'is-invalid' : '' ?>"
                        value="<?= e($lama['no_hp']) ?>"
                    >
                    <?php if (isset($galat['no_hp'])): ?>
                        <div class="invalid-feedback">
                            <?= e($galat['no_hp']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Kelas -->
                <div class="col-md-6 mb-3">
                    <label for="kelas_id" class="form-label">Kelas</label>
                    <select
                        id="kelas_id"
                        name="kelas_id"
                        class="form-select <?= isset($galat['kelas_id']) ? 'is-invalid' : '' ?>"
                    >
                        <option value="">-- Pilih kelas --</option>
                        <?php foreach ($kelas as $k): ?>
                            <option
                                value="<?= (int) $k['id'] ?>"
                                <?= (int) $lama['kelas_id'] === (int) $k['id'] ? 'selected' : '' ?>
                            >
                                <?= e($k['nama_kelas']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($galat['kelas_id'])): ?>
                        <div class="invalid-feedback">
                            <?= e($galat['kelas_id']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Alamat -->
                <div class="col-md-6 mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="3"
                        class="form-control <?= isset($galat['alamat']) ? 'is-invalid' : '' ?>"
                    ><?= e($lama['alamat']) ?></textarea>
                    <?php if (isset($galat['alamat'])): ?>
                        <div class="invalid-feedback">
                            <?= e($galat['alamat']) ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= e($batalUrl ?? 'index.php') ?>" class="btn btn-secondary">Batal</a>

        </form>

    </div>
</div>
