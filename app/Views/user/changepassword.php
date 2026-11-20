<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>

<div class="container">
    <div class="row">
        <div class="col-8">
            <h2 class="my-3"><?= $title; ?></h2>

            <?php if (session()->getFlashdata('passwordErrors')) : ?>
                <?php foreach (session()->getFlashdata('passwordErrors') as $error) : ?>
                    <div class="alert alert-danger" role="alert"><?= $error; ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <form action="/user/updatepassword" method="post">
                <?= csrf_field(); ?>

                <div class="form-group">
                    <label for="oldpassword" class="col-sm-8 col-form-label">Password lama</label>
                    <div class="col-sm-8">
                        <input type="password" required class="form-control" id="oldpassword" name="oldpassword" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="newpassword" class="col-sm-8 col-form-label">Password baru</label>
                    <div class="col-sm-8">
                        <input type="password" required class="form-control" id="newpassword" name="newpassword">
                    </div>
                </div>

                <div class="form-group">
                    <label for="repeatnewpassword" class="col-sm-8 col-form-label">Ulangi password baru</label>
                    <div class="col-sm-8">
                        <input type="password" required class="form-control" id="repeatnewpassword" name="repeatnewpassword">
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