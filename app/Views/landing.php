<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem pendukung keputusan diagnosis penyakit kelinci berbasis algoritma C4.5.">
    <title>Diagnosis Kelinci — C4.5</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="<?= base_url('css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <style>
        :root {
            --ink: #3a3b45;
            --body: #5a5c69;
            --muted: #858796;
            --primary: #4e73df;
            --primary-dark: #224abe;
            --canvas: #f8f9fc;
            --surface: #fff;
            --line: #e3e6f0;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: var(--canvas);
            color: var(--body);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
        }

        a {
            color: var(--primary);
        }

        a:hover {
            color: var(--primary-dark);
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid var(--primary);
            outline-offset: 3px;
        }

        .skip-link {
            position: absolute;
            left: 1rem;
            top: -4rem;
            z-index: 10;
            border-radius: .35rem;
            background: var(--primary);
            color: #fff;
            padding: .5rem 1rem;
            text-decoration: none;
        }

        .skip-link:focus {
            top: 1rem;
            color: #fff;
        }

        .site-header {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid var(--line);
            background: var(--surface);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            color: var(--primary-dark);
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: .05rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .brand:hover {
            color: var(--primary-dark);
        }

        .brand-mark {
            width: 3rem;
            height: 2.6rem;
            display: grid;
            place-items: center;
            border-radius: .35rem;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            transform: rotate(-15deg);
            box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15);
        }

        .brand-mark img {
            width: 2.5rem;
            height: auto;
        }

        .nav-login {
            border: 1px solid var(--primary);
            border-radius: .35rem;
            padding: .4rem 1rem;
            color: var(--primary);
            font-size: .875rem;
            font-weight: 800;
            text-decoration: none;
        }

        .nav-login:hover {
            background: var(--primary);
            color: #fff;
        }

        .hero {
            max-width: 1100px;
            margin: 0 auto;
            padding: 3.5rem 1.5rem 4rem;
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(0, .95fr);
            align-items: center;
            gap: 3.5rem;
        }

        .eyebrow {
            margin: 0 0 1rem;
            color: var(--primary);
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            max-width: 13ch;
            color: var(--ink);
            font-size: clamp(2.25rem, 4.5vw, 3.5rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -.02em;
        }

        .lead {
            margin: 1.5rem 0 0;
            max-width: 34rem;
            color: var(--body);
            font-size: 1.05rem;
        }

        .actions {
            margin-top: 2rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--primary);
            border-radius: .35rem;
            background: var(--primary);
            color: #fff;
            padding: .65rem 1.35rem;
            font-size: .95rem;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15);
        }

        .btn-primary:hover {
            border-color: #2653d4;
            background: #2e59d9;
            color: #fff;
        }

        .btn-text {
            color: var(--primary);
            font-size: .9rem;
            font-weight: 800;
            text-decoration: none;
        }

        .btn-text:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .note {
            margin: 1.5rem 0 0;
            color: var(--muted);
            font-size: .8rem;
        }

        .flow-card {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: .35rem;
            background: var(--surface);
            box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15);
        }

        .flow-heading {
            margin: 0;
            padding: .85rem 1.25rem;
            color: #fff;
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .05rem;
            text-transform: uppercase;
        }

        .flow-body {
            padding: 1.25rem;
        }

        .flow-steps {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: .75rem;
        }

        .flow-steps li,
        .flow-result {
            display: flex;
            align-items: center;
            gap: .9rem;
            border: 1px solid var(--line);
            border-radius: .35rem;
            padding: .75rem 1rem;
        }

        .step-index {
            width: 2rem;
            height: 2rem;
            flex: 0 0 auto;
            display: grid;
            place-items: center;
            border-radius: .35rem;
            background: var(--primary);
            color: #fff;
            font-size: .8rem;
            font-weight: 800;
        }

        .flow-steps strong,
        .flow-result strong {
            display: block;
            color: var(--ink);
            line-height: 1.3;
        }

        .flow-steps small,
        .flow-result small {
            display: block;
            color: var(--muted);
            font-size: .8rem;
        }

        .flow-result {
            margin-top: .75rem;
            background: var(--canvas);
        }

        .pulse {
            width: 2rem;
            height: 2rem;
            flex: 0 0 auto;
            color: var(--primary);
        }

        .steps {
            border-top: 1px solid var(--line);
            background: var(--surface);
            padding: 3.5rem 1.5rem 4rem;
        }

        .steps-inner {
            max-width: 1100px;
            margin: 0 auto;
        }

        .steps h2 {
            margin: 0 0 1.75rem;
            color: var(--ink);
            font-size: clamp(1.4rem, 2.5vw, 1.8rem);
            font-weight: 800;
            letter-spacing: -.01em;
        }

        .step-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .step-grid article {
            border: 1px solid var(--line);
            border-radius: .35rem;
            padding: 1.25rem;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .08);
        }

        .step-number {
            color: var(--primary);
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .08em;
        }

        .step-grid h3 {
            margin: .4rem 0 .35rem;
            color: var(--ink);
            font-size: 1rem;
            font-weight: 800;
        }

        .step-grid p {
            margin: 0;
            font-size: .9rem;
        }

        footer {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1.5rem;
            color: var(--muted);
            font-size: .8rem;
        }

        @media (max-width: 820px) {
            .hero {
                grid-template-columns: 1fr;
                gap: 2.5rem;
                padding-top: 2rem;
            }

            h1 {
                max-width: none;
            }

            .step-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }
        }
    </style>
</head>

<body>
    <a class="skip-link" href="#main">Lewati ke konten</a>

    <header class="site-header">
        <a class="brand" href="<?= base_url('/'); ?>">
            <span class="brand-mark">
                <img src="<?= base_url('img/rabbit.svg'); ?>" alt="">
            </span>
            <span>Diagnosis Kelinci</span>
        </a>
        <a class="nav-login" href="<?= base_url('login'); ?>">Masuk</a>
    </header>

    <main id="main">
        <section class="hero">
            <div class="hero-copy">
                <p class="eyebrow">Sistem pendukung keputusan</p>
                <h1>Kenali penyakit kelinci lebih cepat.</h1>
                <p class="lead">Pilih gejala yang terlihat, biarkan algoritma C4.5 membantu mengarahkan kemungkinan penyakit dan langkah berikutnya.</p>
                <div class="actions">
                    <a class="btn btn-primary" href="<?= base_url('mulai-diagnosis'); ?>">Mulai diagnosis</a>
                    <a class="btn-text" href="#cara-kerja">Lihat cara kerja</a>
                </div>
                <p class="note">Untuk peternak dan praktisi. Bukan pengganti pemeriksaan dokter hewan.</p>
            </div>

            <div class="hero-visual">
                <div class="flow-card card">
                    <div class="flow-heading bg-gradient-primary">Alur konsultasi</div>
                    <div class="flow-body card-body">
                        <ol class="flow-steps">
                            <li>
                                <span class="step-index badge badge-primary">1</span>
                                <span><strong>Gejala</strong><small>Pilih tanda yang dialami kelinci.</small></span>
                            </li>
                            <li>
                                <span class="step-index badge badge-primary">2</span>
                                <span><strong>C4.5</strong><small>Aturan pohon keputusan menilai kombinasi gejala.</small></span>
                            </li>
                        </ol>
                        <div class="flow-result">
                            <svg class="pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 12h4l2.5-6 4 12 2.5-6H21"></path>
                            </svg>
                            <span><strong>Hasil</strong><small>Kemungkinan penyakit beserta langkah lanjutan.</small></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="steps" id="cara-kerja">
            <div class="steps-inner">
                <h2>Tiga langkah, tanpa ribet.</h2>
                <div class="step-grid">
                    <article class="card">
                        <span class="step-number">01</span>
                        <h3>Pilih gejala</h3>
                        <p>Centang tanda yang terlihat pada kelinci, lalu lanjutkan.</p>
                    </article>
                    <article class="card">
                        <span class="step-number">02</span>
                        <h3>Proses aturan</h3>
                        <p>Sistem mencocokkan pilihan dengan aturan pohon keputusan C4.5.</p>
                    </article>
                    <article class="card">
                        <span class="step-number">03</span>
                        <h3>Baca hasil</h3>
                        <p>Lihat kemungkinan diagnosis dan gunakan sebagai panduan awal.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="steps" id="artikel-terbaru">
            <div class="steps-inner">
                <h2>Artikel terbaru.</h2>
                <div class="step-grid">
                    <?php foreach ($artikelRingkasan as $item) : ?>
                        <article class="card">
                            <h3><?= esc($item['judul']); ?></h3>
                            <p><?= esc($item['ringkasan']); ?></p>
                            <a href="<?= base_url('artikel/' . esc($item['slug'], 'attr')); ?>">Baca artikel</a>
                        </article>
                    <?php endforeach; ?>
                </div>
                <a class="btn btn-primary" href="<?= base_url('artikel'); ?>">Lihat semua artikel</a>
            </div>
        </section>
    </main>

    <footer>Diagnosis Kelinci · Sistem pendukung keputusan berbasis C4.5</footer>
</body>

</html>
