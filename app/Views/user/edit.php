<?= $this->extend('templates/index'); ?>


<?= $this->section('page-content'); ?>

<div class="container">
    <div class="row">
        <div class="col-8">
            <h2 class="my-3">Edit Profile</h2>

            <form action="/user/updateProfile/<?= user()->id; ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                <input type="hidden" name="userImageOld" value="<?= user()->user_image; ?>">

                <!-- form email -->
                <div class="form-group row">
                    <label for="judul" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : ''; ?>" id="email" name="email" autofocus value="<?= (old('email')) ? old('email') : user()->email; ?>" readonly>
                        <div class="invalid-feedback">
                            <?= $validation->getError('email'); ?>
                        </div>
                    </div>
                </div>

                <!-- form username -->
                <div class="form-group row">
                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?= (old('username')) ? old('username') : user()->username; ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('username'); ?>
                        </div>
                    </div>
                </div>

                <!-- form fullname -->
                <div class="form-group row">
                    <label for="fullname" class="col-sm-2 col-form-label">Fullname</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= ($validation->hasError('fullname')) ? 'is-invalid' : ''; ?>" id="fullname" name="fullname" value="<?= (old('fullname')) ? old('fullname') : user()->fullname; ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('fullname'); ?>
                        </div>
                    </div>
                </div>

                <!-- Form User Image -->
                <div class="form-group row">
                    <label for="user_image" class="col-sm-2 col-form-label">Profile picture</label>
                    <div class="col-sm-2">
                        <img src="/img/profile/<?= user()->user_image; ?>" class="img-thumbnail img-preview">
                    </div>
                    <div class="col-sm-8">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input <?= ($validation->hasError('user_image')) ? 'is-invalid' : ''; ?>" id="user_image" name="user_image" onchange="previewImage()">
                            <div class="invalid-feedback">
                                <?= $validation->getError('user_image'); ?>
                            </div>
                            <label class="custom-file-label" for="user_image" name="user_image"></label>
                        </div>
                    </div>
                </div>

                <!-- Button Navigation -->
                <div class="form-group row">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#changePasswordModal">Change password</a>
                        <a href="<?= base_url('user/profile'); ?>" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>