<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
            <?php if (session()->getFlashdata('pesanSample')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('pesanSample'); ?>
                </div>
            <?php endif; ?>
            <div class="row justify-content-between">
                <div class="col-6">
                    <div class="form-group mb-3">
                        <div class="mining">
                            <button class="btn btn-primary btn-tambah-sample" data-toggle="modal">
                                Tambah
                            </button>
                            <form action="<?= base_url('mining/mining'); ?>" method="post" class="d-inline" data-ajax-form data-ajax-url="<?= base_url('mining/mining'); ?>" data-ajax-method="post" data-ajax-action="mining" data-ajax-redirect="<?= base_url('admin/decisiontree'); ?>">
                                <?= csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-primary" id="getMining">Proses Mining</button>
                            </form>
                            <a href="<?= base_url('mining/hitung'); ?>" class="btn btn-link">Lihat hasil</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="col-mb-auto">
                        <form action="" method="GET" data-live-search data-page-param="page_sample">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Cari data sample" name="keyword" value="<?= esc($keyword ?? ''); ?>">
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
                <?= $this->include('admin/fragments/sample'); ?>
            </div>
        </div>
    </div>
</div>

<!-- <script>
    var delay = 1000;
    $(window).load(function() {
        setTimeout(function() {
            $(".mining").hide();
        }, delay);
    });
</script> -->

<?= $this->endSection(); ?>