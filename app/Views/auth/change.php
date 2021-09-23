<?= $this->extend('auth/templates/index'); ?>

<?= $this->section('content'); ?>

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-change-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2"><?= lang('Auth.changeYourPassword') ?></h1>

                                    <?= view('Myth\Auth\Views\_message_block') ?>

                                    <p class="mb-4"><?= lang('Auth.enterCodeEmailPassword') ?></p>
                                </div>
                                <form class="user" action="<?= route_to('change-password') ?>" method="post">
                                    <?= csrf_field() ?>

                                    <div class="form-group">
                                        <input type="text" class="form-control <?php if (session('errors.token')) : ?>is-invalid<?php endif ?> form-control-user" name="token" placeholder="<?= lang('Auth.token') ?>" value="<?= old('token', $token ?? '') ?>">
                                        <div class="invalid-feedback">
                                            <?= session('errors.token') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email"><?= lang('Auth.email') ?></label>
                                        <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?> form-control-user" name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>">
                                        <div class="invalid-feedback">
                                            <?= session('errors.email') ?>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="form-group">
                                        <label for="password"><?= lang('Auth.newPassword') ?></label>
                                        <input type="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?> form-control-user" name="password">
                                        <div class="invalid-feedback">
                                            <?= session('errors.password') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="pass_confirm"><?= lang('Auth.newPasswordRepeat') ?></label>
                                        <input type="password" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?> form-control-user" name="pass_confirm">
                                        <div class="invalid-feedback">
                                            <?= session('errors.pass_confirm') ?>
                                        </div>
                                    </div>

                                    <br>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        <?= lang('Auth.changePassword') ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<?= $this->endSection(); ?>