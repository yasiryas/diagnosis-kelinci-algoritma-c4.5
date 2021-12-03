<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <!-- <h1 class="h3 mb-4 text-gray-800">Diagnosis</h1> -->
    <div class="mb-3" style="max-width: 540px;">
        <?php if (session()->getFlashdata('diagnosis')) : ?>
            <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('diagnosis'); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="card">
        <div class="card text">
            <h3 class="card-header text-center">Konsultasi</h3>
            <form action="/user/konsultasi/">
                <div class="card-body">
                    <h5 class="card-title">Pilih gejala sesuai yang dialami kelinci anda!</h5>
                    <?php foreach ($gejala as $g) : ?>
                        <p class="card-text">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="<?= $g['id_gejala']; ?>" name="gejala[]" id="defaultCheck1">
                            <label class="form-check-label" for="defaultCheck1">
                                <?= $g['kategori']; ?> <?= $g['gejala']; ?>
                            </label>
                        </div>
                        </p>
                        <!-- <p class="card-text"><?= $g['gejala']; ?></p> -->
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary">Selanjutnya</button>
                </div>
        </div>

    </div>
    </form>

    <?= $this->endSection(); ?>