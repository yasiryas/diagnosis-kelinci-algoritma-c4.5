<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>

<div class="container-fluid">
    <div class="mb-3" style="max-width: 540px;">
        <?php if (session()->getFlashdata('diagnosis')) : ?>
            <div class="alert alert-warning" role="alert">
                <?= session()->getFlashdata('diagnosis'); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header text-center">
            <h3 class="h5 mb-0">Konsultasi</h3>
        </div>
        <form action="/user/konsultasi" method="post">
            <?= csrf_field(); ?>
            <div class="card-body">
                <h5 class="card-title">Pilih gejala sesuai yang dialami kelinci anda!</h5>
                <?php foreach ($gejala as $g) : ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" value="<?= $g['id_gejala']; ?>" name="gejala[]" id="gejala-<?= $g['id_gejala']; ?>">
                        <label class="form-check-label" for="gejala-<?= $g['id_gejala']; ?>">
                            <?= $g['kategori']; ?> <?= $g['gejala']; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-primary mt-3">Selanjutnya</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>
