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
                        <form action="" method="POST">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Cari gejala" name="keyword">
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
                        <th scope="col">Penyakit</th>
                        <th scope="col">Obat</th>
                        <th scope="col">Solusi</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1 + ($page * ($currentPage - 1));
                    foreach ($penyakit as $p) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $p['penyakit']; ?></td>
                            <td><?= $p['obat']; ?></td>
                            <td><?= $p['solusi']; ?></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm btn-edit-penyakit" data-id="<?= $p['id_penyakit']; ?>" data-penyakit="<?= $p['penyakit'] ?>" data-obat="<?= $p['obat'] ?>" data-solusi="<?= $p['solusi'] ?>">
                                    Ubah
                                </a>
                                <a href="#" class="btn btn-danger btn-sm btn-delete-penyakit" data-id="<?= $p['id_penyakit']; ?>" data-penyakit="<?= $p['penyakit'] ?>">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?= $pager->links('penyakit', 'user_pagination') ?>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>