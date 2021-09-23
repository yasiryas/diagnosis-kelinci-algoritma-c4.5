<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <div class="row">
        <div class="col-lg-8">
            Atribut berjumlah <?= $atribut->atribut; ?>
            <br>
            Data kasus berjumlah <?= $jumlahkasus; ?>
            <br>

            <br>
            <?php $entropytotal = 0; ?>
            <?php foreach ($jumlahpenyakit as $jp) : ?>
                <?= $jp->penyakit; ?> = <?= ($jp->total) / ($atribut->atribut); ?>
                <br>
                <?php
                $jumlahAtribut = ($jp->total) / ($atribut->atribut);;
                $proportion = $jumlahAtribut / $jumlahkasus;
                $entropy = (-$proportion * log($proportion, 2));
                $entropytotal += $entropy;
                ?>
                <?= $entropy;
                ?>

                <br>
            <?php endforeach; ?>
            <br>
            Entropy Total = <?= $entropytotal; ?>
            <br><br>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nilai Atribut</th>
                        <th scope="col">Jumlah Kasus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kasus as $k) : ?>
                        <tr>

                            <td><?= $k->kategori; ?> <?= $k->gejala; ?></td>
                            <td><?= $k->total; ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Gejala</th>
                        <th scope="col">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jumlahgejala as $jg) : ?>
                        <tr>
                            <td><?= $jg->kategori; ?> <?= $jg->gejala; ?></td>
                            <td> <?= $jg->total; ?> </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Kasus</th>
                        <th scope="col">Penyakit</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Entropy</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $entropyHitung = $entropytotal;
                    $entropyAtr = 0;
                    $gainAtr = 0;
                    foreach ($kasus as $k) :
                        foreach ($jumlahgejala as $jg) :
                            foreach ($jumlahkasuspenyakit as $jkp) :
                    ?>
                                <tr>
                                    <td><?= $jkp->kategori; ?> <?= $jkp->gejala; ?></td>
                                    <td><?= $jkp->penyakit; ?></td>
                                    <td> <?= $jkp->total; ?> </td>
                                    <td>
                                        <?php
                                        if ($jkp->id_gejala == $jg->id_gejala) { ?>
                                        <?= $entropyA = (- ($jkp->total / $jg->total) * log(($jkp->total / $jg->total), 2));

                                            if ($jkp->id_gejala == $jg->id_gejala and $jg->gejala) {
                                                $entropyAtr += $entropyA;
                                            } else {
                                                $entropyAtr = 0;
                                            }
                                        } else {
                                            $entropyAtr = 0;
                                        }
                                        if ($jkp->kategori == $k->kategori) {
                                            $gainA = ((($k->total / $jumlahkasus)) * $entropyAtr);
                                            $gainAtr += $gainA;
                                            $gain = $entropyHitung - $gainAtr;
                                            // $gainAtr = $entropyHitung - (((($k->total) / $jumlahkasus) * $entropyAtr));
                                        } else {
                                            $entropyHitung = $entropytotal;
                                            $gain = 0;
                                        }
                                        ?>
                                        <br>
                                        <?=

                                        'Entropy Atribut = ' . $entropyAtr . '<br>' .
                                            'Gain = ' . $gain;

                                        ?>
                                    </td>
                                </tr>
                    <?php
                            endforeach;
                        endforeach;
                    endforeach;
                    ?>
                </tbody>
            </table>
            <?= $entropyAtr; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>