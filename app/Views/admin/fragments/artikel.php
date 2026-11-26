<div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" style="width: 70px;">Gambar</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Kategori</th>
                        <th scope="col" style="width: 100px;">Status</th>
                        <th scope="col" style="width: 160px;">Diterbitkan</th>
                        <th scope="col" style="width: 170px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($artikel as $item) : ?>
                        <tr data-row-id="<?= (int) $item['id']; ?>">
                            <td class="align-middle">
                                <?php if (!empty($item['gambar'])) : ?>
                                    <img src="<?= esc($item['gambar'], 'attr'); ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #e3e6f0;">
                                <?php else : ?>
                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px; border-radius: 6px; font-size: 11px;">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle" data-field="judul">
                                <strong><?= esc($item['judul']); ?></strong>
                                <br>
                                <small class="text-muted" style="max-width: 320px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: bottom;">
                                    <?= esc($item['ringkasan']); ?>
                                </small>
                            </td>
                            <td class="align-middle" data-field="slug">
                                <code><?= esc($item['slug']); ?></code>
                            </td>
                            <td class="align-middle" data-field="kategori">
                                <span class="badge badge-info px-2 py-1"><?= esc($item['nama_kategori'] ?? 'Umum'); ?></span>
                            </td>
                            <td class="align-middle" data-field="status">
                                <?php if ($item['status'] === 'publish') : ?>
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>publish</span>
                                <?php else : ?>
                                    <span class="badge badge-secondary px-2 py-1"><i class="fas fa-pencil-alt mr-1"></i>draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle small text-muted">
                                <?= !empty($item['published_at']) ? esc($item['published_at']) : '-'; ?>
                            </td>
                            <td class="align-middle text-center">
                                <a href="<?= base_url('artikel/' . esc($item['slug'], 'attr')); ?>" target="_blank" class="btn btn-outline-info btn-sm mr-1" title="Lihat di web">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-info btn-sm btn-edit-artikel mr-1"
                                    data-id="<?= (int) $item['id']; ?>"
                                    data-row-id="<?= (int) $item['id']; ?>"
                                    data-judul="<?= esc($item['judul'], 'attr'); ?>"
                                    data-slug="<?= esc($item['slug'], 'attr'); ?>"
                                    data-ringkasan="<?= esc($item['ringkasan'], 'attr'); ?>"
                                    data-konten="<?= esc($item['konten'], 'attr'); ?>"
                                    data-status="<?= esc($item['status'], 'attr'); ?>"
                                    data-kategori-id="<?= (int) ($item['kategori_id'] ?? 0); ?>"
                                    data-published-at="<?= esc($item['published_at'] ?? '', 'attr'); ?>"
                                    data-gambar="<?= esc($item['gambar'] ?? '', 'attr'); ?>"
                                    data-kategori="<?= esc($item['nama_kategori'] ?? '', 'attr'); ?>"
                                    title="Ubah">
                                    <i class="fas fa-edit"></i> Ubah
                                </a>
                                <a href="#" class="btn btn-danger btn-sm btn-delete-artikel"
                                    data-id="<?= (int) $item['id']; ?>"
                                    data-judul="<?= esc($item['judul'], 'attr'); ?>"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($artikel)) : ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-newspaper fa-2x mb-2 text-gray-300 d-block"></i>
                                Tidak ada data artikel.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center">
    <?= $pager->links() ?>
</div>
