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
                        <button class="btn btn-primary btn-tambah-gejala" data-toggle="modal">
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
                        <th scope="col">Id Gejala</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Gejala</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $i = 1 + ($page * ($currentPage - 1));
                    foreach ($gejala as $g) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $g['id_gejala']; ?></td>
                            <td><?= $g['kategori']; ?></td>
                            <td><?= $g['gejala']; ?></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm btn-edit-gejala" data-id="<?= $g['id_gejala']; ?>" data-kategori="<?= $g['kategori'] ?>" data-gejala="<?= $g['gejala'] ?>">
                                    Ubah
                                </a>
                                <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="<?= $g['id_gejala']; ?>" data-kategori="<?= $g['kategori'] ?>" data-gejala="<?= $g['gejala'] ?>">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?= $pager->links('gejala', 'user_pagination') ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>