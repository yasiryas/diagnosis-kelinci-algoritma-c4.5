<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="h3 mb-4 text-gray-800"><?= esc($title); ?></h1>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <p class="mb-0 text-muted">Ganti password akun Anda tanpa meninggalkan halaman ini.</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#passwordModal">Ganti password</button>
                    <a href="<?= base_url('user/profile'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
