<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Page Heading -->
            <!-- <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1> -->
            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            <?php endif; ?>

            <!-- jumbotron -->
            <div class="jumbotron">
                <h1 class="display-4"><?= $title; ?></h1>
                <p class="lead">Diagnosis penyakit yang dialami kelinci anda sekarang!</p>
                <hr class="my-4">
                <p>Untuk memulai diagnosis silahkan tekan tombol <b>mulai konsultasi </b></p>
                <p class="lead">
                    <a class="btn btn-primary" href="<?= base_url('user/prosesdiagnosis'); ?>">
                        Mulai Konsultasi
                    </a>
                </p>
            </div>



            <!-- <div class="row justify-content-between">
                <div class="col-6">
                    <div class="form-group mb-3"> -->
            <!-- <button class="btn btn-primary btn-diagnosis" data-toggle="modal">
                            Mulai Diagnosis
                        </button> -->
            <!-- <a class="btn btn-primary" href="<?= base_url('user/prosesdiagnosis'); ?>">
                            Mulai Diagnosis
                        </a>
                    </div>
                </div> -->
            <!-- <div class="col">
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
                </div> -->
        </div>
    </div>
</div>
<!-- <div class="row">
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

                    <?php // $i = 1 + ($page * ($currentPage - 1));
                    //foreach ($gejala as $g) : 
                    $i = 1; ?>
                    <tr>
                        <th scope="row"><?= $i++; ?></th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>

                        </td>
                    </tr>
                    <?php // endforeach; 
                    ?>
                </tbody>
            </table>


        </div>
    </div> -->


</div>

<?= $this->endSection(); ?>