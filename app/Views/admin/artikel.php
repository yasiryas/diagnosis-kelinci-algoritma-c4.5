<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('pesan'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('erorr') || session()->getFlashdata('update erorr')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('erorr') ?: session()->getFlashdata('update erorr'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <div class="row justify-content-between align-items-center mb-3">
                <div class="col-md-4 mb-2 mb-md-0">
                    <button class="btn btn-primary btn-tambah-artikel" data-toggle="modal">
                        <i class="fas fa-plus mr-1"></i> Tambah
                    </button>
                </div>
                <div class="col-md-5">
                    <form action="" method="GET" data-live-search data-page-param="page">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari artikel" name="keyword" value="<?= esc($keyword ?? ''); ?>">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit" name="submit">Cari</button>
                                <?php if (!empty($keyword)) : ?>
                                    <a href="<?= base_url('admin/artikel'); ?>" class="btn btn-outline-danger" title="Reset pencarian">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div data-admin-list>
                <?= $this->include('admin/fragments/artikel'); ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).on('change', '.custom-file-input', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName || "Pilih file gambar...");
    });
</script>
<?= $this->endSection(); ?>
