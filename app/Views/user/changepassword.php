<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>

<div class="container">
    <div class="row">
        <div class="col-8">
            <h2 class="my-3"><?= $title; ?></h2>

            <form action="/user/update/<?= user()->id; ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                <!-- form old-password -->
                <div class="form-group ">
                    <label for="oldpassword" class="col-sm-8 col-form-label">Input Email </label>
                    <div class="col-sm-8">
                        <input type="text" required class="form-control" id="oldpassword" name="oldpassword" autofocus>
                        <div class="invalid-feedback">
                        </div>
                    </div>
                </div>

                <!-- Button Navigation -->
                <div class="form-group ">
                    <div class="col-sm-8">
                    </div>
                    <div class="col-sm-8">
                        <button type="submit" class="btn btn-primary">Change</button>
                        <a href="<?= base_url('user/edit'); ?>" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>