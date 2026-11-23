<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Artikel perawatan dan kesehatan kelinci.">
    <title><?= esc($title); ?></title>
    <link href="<?= base_url(); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="<?= base_url(); ?>/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/css/app.css" rel="stylesheet">
</head>
<body class="bg-light">
    <header class="site-header bg-white border-bottom">
        <div class="container py-3 d-flex align-items-center justify-content-between">
            <a class="brand d-flex align-items-center font-weight-bold text-primary" href="<?= base_url('/'); ?>">
                <img src="<?= base_url('img/rabbit.svg'); ?>" alt="" class="mr-2" width="32" height="32">
                Diagnosis Kelinci
            </a>
            <nav class="d-flex align-items-center">
                <a class="btn btn-primary mr-2" href="<?= base_url('mulai-diagnosis'); ?>">Mulai diagnosis</a>
                <a class="btn btn-outline-secondary" href="<?= base_url('login'); ?>">Masuk</a>
            </nav>
        </div>
    </header>
    <main class="container py-5">
        <?= $this->renderSection('page-content'); ?>
    </main>
    <footer class="container py-4 text-muted border-top">
        Diagnosis Kelinci · Sistem pendukung keputusan berbasis C4.5
    </footer>
    <script src="<?= base_url(); ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
