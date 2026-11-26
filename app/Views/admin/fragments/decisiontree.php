<details class="mb-4">
    <summary>Lihat aturan mentah</summary>
    <div class="card mb-4">
        <div class="card-header font-weight-bold">Pohon Keputusan (aturan)</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Aturan (IF)</th>
                            <th>Keputusan (THEN)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 + ($page * ($currentPage - 1));
                        foreach ($decisiontree as $dt) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td>Jika <?= esc($dt['detail']); ?></td>
                                <td><b><?= esc($dt['penyakit']); ?></b></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($decisiontree)) : ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Tidak ada data pohon keputusan. Jalankan Proses Mining dari Data Sample.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</details>

<?= $pager->links('decision_tree', 'user_pagination') ?>
