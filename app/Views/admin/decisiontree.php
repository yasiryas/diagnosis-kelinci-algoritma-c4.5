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
                    <!-- <div class="form-group mb-3">
                        <button class="btn btn-primary btn-tambah-gejala" data-toggle="modal">
                            Tambah
                        </button>
                    </div> -->
                </div>
                <div class="col">
                    <div class="col-mb-auto">
                        <form action="" method="POST">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Cari pohon keputusan" name="keyword">
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

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Parent</th>
                        <th scope="col">Akar</th>
                        <th scope="col">Keputusan</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $i = 1 + ($page * ($currentPage - 1));
                    foreach ($decisiontree as $dt) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $dt['parent']; ?></td>
                            <td><?= $dt['akar']; ?></td>
                            <td><?= $dt['penyakit']; ?> </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?= $pager->links('decision_tree', 'user_pagination') ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>