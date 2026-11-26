<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <h1 class="h3 mb-4 text-gray-800"><?= esc($title); ?></h1>
            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success" role="alert"><?= session()->getFlashdata('pesan'); ?></div>
            <?php endif; ?>
            <div class="row justify-content-between mb-2">
                <div class="col-6"></div>
                <div class="col-md-5">
                    <form action="" method="GET" data-live-search data-page-param="page_decision_tree">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Cari pohon keputusan" name="keyword" value="<?= esc($keyword ?? ''); ?>">
                            <div class="input-group-append"><button class="btn btn-outline-secondary" type="submit">Cari</button></div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header font-weight-bold">Visualisasi Pohon</div>
                <div class="card-body">
                    <div class="dk-tree">
                        <?= $svg; ?>
                    </div>
                    <?= $treeList; ?>
                </div>
            </div>

            <div data-admin-list>
                <?= $this->include('admin/fragments/decisiontree'); ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
