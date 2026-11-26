<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">No.</th>
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
            <?php if (empty($gejala)) : ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">Tidak ada data gejala.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $pager->links('gejala', 'user_pagination') ?>
