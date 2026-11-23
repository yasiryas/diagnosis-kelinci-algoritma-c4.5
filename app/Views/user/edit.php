<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="h3 mb-4 text-gray-800">Edit Profile</h1>

            <div class="card shadow mb-4">
                <div class="card-body d-flex align-items-center">
                    <img src="<?= base_url('img/profile/' . user()->user_image); ?>" class="img-thumbnail img-profile mr-4" alt="Foto profil">
                    <div>
                        <h5 class="mb-1"><?= esc(user()->fullname); ?></h5>
                        <p class="text-muted mb-0"><?= esc(user()->username); ?> · <?= esc(user()->email); ?></p>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#profileModal">Edit profil</button>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#passwordModal">Ganti password</button>
                    <a href="<?= base_url('user/profile'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
