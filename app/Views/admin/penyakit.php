<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            <?php endif; ?>
            <div class="row justify-content-between">
                <div class="col-6">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary btn-tambah-penyakit">
                            Tambah
                        </button>
                    </div>
                </div>
                <div class="col">
                    <div class="col-mb-auto">
                        <form action="" method="GET" data-live-search data-page-param="page_penyakit">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Cari penyakit" name="keyword" value="<?= esc($keyword ?? ''); ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit" name="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div data-admin-list>
                <?= $this->include('admin/fragments/penyakit'); ?>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>