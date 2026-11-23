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
                    <form action="" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Cari pohon keputusan" name="keyword" value="<?= esc($keyword ?? ''); ?>">
                            <div class="input-group-append"><button class="btn btn-outline-secondary" type="submit">Cari</button></div>

                        </div>
                    </form>
                </div>
            </div>

            <details class="mb-4">
                <summary>Lihat aturan mentah</summary>
                <div class="card mb-4">
                    <div class="card-header font-weight-bold">Pohon Keputusan (aturan)</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead><tr><th>No.</th><th>Aturan (IF)</th><th>Keputusan (THEN)</th></tr></thead>
                                <tbody>
                                <?php $i = 1 + ($page * ($currentPage - 1)); foreach ($decisiontree as $dt) : ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td>Jika <?= esc($dt['detail']); ?></td>
                                        <td><b><?= esc($dt['penyakit']); ?></b></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($decisiontree)) : ?>
                                    <tr><td colspan="3" class="text-center text-muted py-4">Tidak ada data pohon keputusan. Jalankan Proses Mining dari Data Sample.</td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </details>

            <div class="card mb-4">
                <div class="card-header font-weight-bold">Visualisasi Pohon</div>
                <div class="card-body">
                    <div class="dk-tree">
                        <?= $svg; ?>
                    </div>
                    <?= $treeList; ?>
                </div>
            </div>

            <?= $pager->links('decision_tree', 'user_pagination') ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
