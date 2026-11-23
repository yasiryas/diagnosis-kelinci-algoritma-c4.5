<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('erorr') || session()->getFlashdata('update erorr')) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= session()->getFlashdata('erorr') ?: session()->getFlashdata('update erorr'); ?>
                </div>
            <?php endif; ?>
            <div class="row justify-content-between">
                <div class="col-6">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary btn-tambah-artikel" data-toggle="modal">Tambah</button>
                    </div>
                </div>
                <div class="col">
                    <form action="" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Cari artikel" name="keyword" value="<?= esc($keyword ?? ''); ?>">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit" name="submit">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Judul</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Status</th>
                            <th scope="col">Diterbitkan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($artikel as $item) : ?>
                            <tr data-row-id="<?= (int) $item['id']; ?>">
                                <td data-field="judul"><?= esc($item['judul']); ?></td>
                                <td data-field="slug"><?= esc($item['slug']); ?></td>
                                <td data-field="kategori"><?= esc($item['nama_kategori'] ?? ''); ?></td>
                                <td data-field="status"><?= esc($item['status']); ?></td>
                                <td><?= esc($item['published_at'] ?? '-'); ?></td>
                                <td>
                                    <a href="#" class="btn btn-info btn-sm btn-edit-artikel" data-id="<?= (int) $item['id']; ?>" data-row-id="<?= (int) $item['id']; ?>" data-judul="<?= esc($item['judul'], 'attr'); ?>" data-slug="<?= esc($item['slug'], 'attr'); ?>" data-ringkasan="<?= esc($item['ringkasan'], 'attr'); ?>" data-konten="<?= esc($item['konten'], 'attr'); ?>" data-status="<?= esc($item['status'], 'attr'); ?>" data-kategori-id="<?= (int) ($item['kategori_id'] ?? 0); ?>" data-published-at="<?= esc($item['published_at'] ?? '', 'attr'); ?>" data-kategori="<?= esc($item['nama_kategori'] ?? '', 'attr'); ?>">Ubah</a>
                                    <a href="#" class="btn btn-danger btn-sm btn-delete-artikel" data-id="<?= (int) $item['id']; ?>" data-judul="<?= esc($item['judul'], 'attr'); ?>">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($artikel)) : ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Tidak ada data artikel.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?= $pager->links() ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
