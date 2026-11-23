<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= esc($title); ?></h1>

    <div class="card mb-4">
        <div class="card-header font-weight-bold">Dataset (kasus per penyakit)</div>
        <div class="card-body">
            <p class="mb-1">Atribut (kategori): <b><?= esc($atributCount); ?></b></p>
            <p class="mb-3">Jumlah kasus: <b><?= esc($jumlahkasus); ?></b></p>
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead><tr><th>#</th><th>Penyakit</th><?php foreach (array_keys(reset($instances)['attrs'] ?? []) as $k): ?><th><?= esc($k); ?></th><?php endforeach; ?></tr></thead>
                    <tbody>
                    <?php $n=1; foreach ($instances as $inst): ?>
                        <tr>
                            <td><?= $n++; ?></td>
                            <td><?= esc($penyakitInfo[$inst['penyakit']] ?? $inst['penyakit']); ?></td>
                            <?php foreach ($inst['attrs'] as $gid): ?>
                                <td><?= $gid && isset($gejalaInfo[$gid]) ? esc($gejalaInfo[$gid]['gejala']) : '-'; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header font-weight-bold">Rumus &amp; Perhitungan (Node Akar)</div>
        <div class="card-body">
            <p class="mb-1"><b>Entropy(S)</b> = − Σ p<sub>i</sub> · log<sub>2</sub>(p<sub>i</sub>)</p>
            <p class="mb-1"><b>Gain(S,A)</b> = Entropy(S) − Σ (|S<sub>v</sub>|/|S|) · Entropy(S<sub>v</sub>)</p>
            <p class="mb-3"><b>SplitInfo(S,A)</b> = − Σ (|S<sub>v</sub>|/|S|) · log<sub>2</sub>(|S<sub>v</sub>|/|S|), &nbsp; <b>GainRatio(S,A)</b> = Gain(S,A) / SplitInfo(S,A)</p>

            <p class="mb-1">Distribusi kelas: <?php
                $parts = [];
                foreach ($calc['distribution'] as $pid => $c) { $parts[] = ($penyakitInfo[$pid] ?? $pid) . '=' . $c; }
                echo esc(implode(', ', $parts));
            ?></p>
            <p class="mb-3"><b>Entropy(S)</b> = <?= number_format($calc['totalEntropy'], 6); ?></p>

            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="thead-light"><tr><th>Atribut</th><th>Nilai</th><th>|Sv|</th><th>Entropy(Sv)</th><th>Gain</th><th>SplitInfo</th><th>GainRatio</th></tr></thead>
                    <tbody>
                    <?php foreach ($calc['attributes'] as $attr => $info): $first=true; $rows=max(count($info['values']),1); foreach ($info['values'] as $gid => $v): ?>
                        <tr>
                            <?php if ($first): ?><td rowspan="<?= $rows; ?>"><?= esc($attr); ?></td><?php endif; ?>
                            <td><?= esc(($gejalaInfo[$gid]['label'] ?? $gid)); ?></td>
                            <td><?= esc($v['count']); ?></td>
                            <td><?= number_format($v['entropy'], 6); ?></td>
                            <?php if ($first): ?>
                                <td rowspan="<?= $rows; ?>"><?= number_format($info['gain'], 6); ?></td>
                                <td rowspan="<?= $rows; ?>"><?= number_format($info['splitInfo'], 6); ?></td>
                                <td rowspan="<?= $rows; ?>"><b><?= number_format($info['gainRatio'], 6); ?></b></td>
                            <?php $first=false; endif; ?>
                        </tr>
                    <?php endforeach; if (empty($info['values'])): ?>
                        <tr><td><?= esc($attr); ?></td><td colspan="3" class="text-muted">-</td><td>0</td><td>0</td><td>0</td></tr>
                    <?php endif; endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p class="text-muted mb-0">Atribut terpilih (GainRatio tertinggi): <b><?php
                $bestA=null; $best=null; foreach($calc['attributes'] as $a=>$i){ if($best===null || $i['gainRatio']>$best){$best=$i['gainRatio']; $bestA=$a;} }
                echo esc($bestA ?? '-');
            ?></b></p>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
