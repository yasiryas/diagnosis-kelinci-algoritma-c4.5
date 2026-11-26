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
