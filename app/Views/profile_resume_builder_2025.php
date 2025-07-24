<?php $session = session(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 5 Admin &amp; Dashboard Template">
    <meta name="author" content="Nat Lee">
    <title>Resume | <?= $session->app_name ?></title>
    <link href="<?= base_url('file/favicon.jpg') ?>" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <style>
        body {
            font-size: 12px !important;
            font-family: 'Raleway', sans-serif;
        }
        td {padding:0 !important;}
        .page {
            width: calc(794px - 45px * 2);  /* A4 width (21cm) ≈ 794px, minus padding */
            height: calc(1123px - 45px * 2); /* A4 height (29.7cm) ≈ 1123px */
            padding: 45px;
            box-sizing: border-box;
            background: white;
            margin: auto;
            box-shadow: 0 0 1rem rgba(0,0,0,0.1);
        }
        h1, h2, h3, h4, h5, h6 { font-weight: bold; margin: .5rem 0; }
        h1 {font-size:15px;}
        h2 {font-size:14px;}
        h3, h4, h5, h6 {font-size:12px;}
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            .page {
                width: 17cm;
                height: 25.7cm;
                padding: 2cm;
                margin: 0;
                box-shadow: none;
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
<div class="page">
    <?php
    function print_link($label, $link = ''): string
    {
        if (empty($link) || '#' == $link) {
            return $label;
        }
        return '<a href="' . $link . '" target="_blank">' . $label . '</a>';
    }
    ?>
    <h1 class="text-center">Ratinan Leela-ngamwongsa</h1>
    <h2 class="text-center"><?= $job_title ?> | Agile/Scrum | Artificial Intelligence</h2>
    <p class="text-center">
        +65 9775 4577
        lee@ratinan.com
        lee.ratinan.com
        LinkedIn
        Singapore
    </p>
    <h3>EXPERIENCE</h3>
    <h3>EDUCATION</h3>
    <h3>CERTIFICATIONS</h3>
    <?php
    function build_certifications($certifications): void
    {
        echo '<table class="table table-sm table-borderless">';
        foreach ($certifications as $org => $certs) {
            echo '<tr>';
            echo '<td colspan="2"><b>' . $org . '</b></td>';
            echo '</tr>';
            foreach ($certs as $cert) {
                echo '<tr>';
                echo '<td>- ' . print_link($cert[0], $cert[2]) . '</td>';
                echo '<td>' . $cert[1] . '</td>';
                echo '</tr>';
            }
        }
        echo '</table>';
    }
    build_certifications([
            'Scrum.org' => [
                    ['Professional Scrum Master I (PSM I)', 'Oct 2024', '#'],
                    ['Professional Scrum Master II (PSM II)', 'Nov 2024', '#'],
                    ['Professional Scrum Product Owner I (PSPO I)', 'Oct 2024', '#'],
                    ['Professional Scrum Product Owner II (PSPO II)', 'Feb 2025', '#'],
            ],
            'Scrum Alliance' => [
                    ['Certified ScrumMaster (CSM)', 'Feb 2025', '#']
            ],
            'Coursera Courses' => [
                    ['AWS Cloud Practitioner Essentials course on Coursera', 'Sep 2024', '#'],
                    ['Google Project Management Professional Certificate', 'Sep 2024', '#'],
                    ['Google AI Essentials Certificate', 'Sep 2024', '#'],
                    ['Google Data Analytics Professional Certificate', 'Oct 2024', '#'],
                    ['Google UX Design Professional Certificate', 'Sep 2024', '#'],
            ]
    ])
    ?>
    <h3>LANGUAGES</h3>
    <?php
    function build_languages($languages) {
        echo '<table class="table table-sm table-borderless">';
        foreach ($languages as $language) {
            echo '<tr>';
            echo '<td>' . print_link($language[0], $language[3]) . '</td>';
            echo '<td>' . $language[1] . '</td>';
            echo '<td>' . $language[2] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    build_languages([
            ['English', 'CEFR: C1/C2: Proficient', '', '#'],
            ['Thai (ภาษาไทย)', 'Native', '', '#'],
            ['Japanese （日本語）', 'Beginner’s', 'Expected to get JLPT N5 (CEFR: A1) in 2026', '#']
    ]);
    ?>
</div>
</body>
</html>