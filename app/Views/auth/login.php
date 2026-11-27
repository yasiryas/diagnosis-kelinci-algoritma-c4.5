<?php $this->setData(['title' => 'Masuk — Diagnosis Kelinci']); ?>
<?= $this->extend('auth/templates/index'); ?>

<?php $this->section('content'); ?>
<?php
$loginType = $config->validFields === ['email'] ? 'email' : 'text';
$loginLabel = $loginType === 'email' ? 'Alamat email' : 'Email atau nama pengguna';
$loginError = session('errors.login');
$passwordError = session('errors.password');
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-9">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="<?= base_url('img/rabbit.svg'); ?>" alt="" aria-hidden="true" class="logo" style="width: 80px;">
                        <h1 class="h4 text-gray-900 mb-4">Masuk ke Sistem Diagnosis Penyakit Kelinci</h1>
                    </div>

                    <?= view('Myth\Auth\Views\_message_block') ?>

                    <form action="<?= route_to('login') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="login"><?= $loginLabel ?></label>
                            <input
                                type="<?= $loginType ?>"
                                id="login"
                                name="login"
                                class="form-control<?= $loginError ? ' is-invalid' : '' ?>"
                                <?= $loginError ? 'aria-invalid="true" aria-describedby="login-error"' : '' ?>
                            >
                            <div class="invalid-feedback" id="login-error"><?= esc($loginError) ?></div>
                        </div>

                        <div class="form-group">
                            <label for="password">Kata sandi</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control<?= $passwordError ? ' is-invalid' : '' ?>"
                                <?= $passwordError ? 'aria-invalid="true" aria-describedby="password-error"' : '' ?>
                            >
                            <div class="invalid-feedback" id="password-error"><?= esc($passwordError) ?></div>
                        </div>

                        <?php if ($config->allowRemembering) : ?>
                            <div class="custom-control custom-checkbox small mb-4">
                                <input class="custom-control-input" type="checkbox" name="remember" id="remember">
                                <label class="custom-control-label" for="remember">Ingat saya</label>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary btn-user btn-block">Masuk</button>
                    </form>

                    <hr>
                    <div class="text-center">
                        <?php if ($config->allowRegistration) : ?>
                            <a class="small text-primary" href="<?= route_to('register') ?>">Belum punya akun? Daftar</a>
                        <?php endif; ?>
                    </div>
                    <?php if ($config->activeResetter) : ?>
                        <div class="text-center mt-2">
                            <a class="small text-primary" href="<?= route_to('forgot') ?>">Lupa kata sandi?</a>
                        </div>
                    <?php endif; ?>
                    <div class="text-center mt-3">
                        <a class="small text-gray-600" href="<?= base_url('/'); ?>"><i class="fas fa-arrow-left" aria-hidden="true"></i> Kembali ke beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
