<div class="table-responsive">
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
            <?php if (empty($penyakit)) : ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Tidak ada data penyakit.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $pager->links('penyakit', 'user_pagination') ?>
