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
                        <form action="" method="GET">
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

            <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Gejala</th>
                        <th scope="col">Penyakit</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $i = 1 + ($page * ($currentPage - 1));

                    foreach ($sample as $s) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $s['kategori'] ?> <?= $s['gejala'] ?></td>
                            <td><?= $s['penyakit'] ?></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm btn-edit-sample" data-id_sample="<?= $s['id_sample']; ?>" data-id_gejala="<?= $s['id_gejala']; ?>" data-id_penyakit="<?= $s['id_penyakit']; ?>">
                                    Ubah
                                </a>
                                <a href="#" class="btn btn-danger btn-sm btn-delete-sample" data-sample="<?= $s['id_sample']; ?>" data-penyakit="<?= $s['penyakit']; ?>" data-gejala="<?= $s['gejala']; ?>" data-kategori="<?= $s['kategori']; ?>">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($sample)) : ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Tidak ada data sample.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>

            <?= $pager->links('sample', 'user_pagination') ?>
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