<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 5 Admin &amp; Dashboard Template">
    <meta name="author" content="Nat Lee">
    <title>Resume</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP&family=Noto+Serif+Thai&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 12px !important;
            font-family: 'Raleway', 'Noto Serif Thai', 'Noto Serif JP', sans-serif;
        }
        td {padding:0 !important;}
        .page {
            width: calc(794px - 45px * 2);  /* A4 width (21cm) ≈ 794px, minus padding */
            height: calc(1123px - 45px * 2); /* A4 height (29.7cm) ≈ 1123px */
            padding: 45px;
            box-sizing: border-box;
            background: white;
            margin: auto;
            margin-bottom: 1rem;
            box-shadow: 0 0 1rem rgba(0,0,0,0.1);
        }
        h1, h2, h3, h4, h5, h6 { font-weight: bold; margin: .5rem 0; }
        h1 {font-size:15px;}
        h2 {font-size:14px;}
        h3, h4, h5, h6 {font-size:12px;}
        b { font-weight:600; }
        em { font-size:0.9em; }
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
    <h2 class="text-center"><?= $job_title ?? 'Scrum Master' ?> | Agile/Scrum | Artificial Intelligence</h2>
    <p class="text-center">
        <a href="tel:+6597754577">+65 9775 4577</a> &middot;
        <a href="mailto:lee@ratinan.com" target="_blank">lee@ratinan.com</a> &middot;
        <a href="https://lee.ratinan.com" target="_blank">lee.ratinan.com</a> &middot;
        <a href="" target="_blank">LinkedIn</a>
    </p>
    <h3>EXPERIENCE</h3>
    <?php
    function build_experience($experience): void
    {
        echo '<table class="table table-sm table-borderless">';
        foreach ($experience as $row) {
            echo '';
            echo '<tr><td><b>' . $row[0] . '</b><br>' . $row[1] . '</td><td class="text-end">' . $row[2] . '<br>' . $row[3] . '</td></tr>';
            echo '<tr><td colspan="2">' . $row[4] . '</td></tr>';
        }
        echo '</table>';
    }
    build_experience([
        ['Project Manager, System Engineer', 'SilverLake Axis', 'Apr 2025 - present', 'Singapore', 'para'],
        ['Web Developer', 'Freelance at Fastwork', 'Nov 2024 - present', 'Remote', 'para'],
        ['Senior Tech Lead', 'Moolahgo', 'Jun 2021 - Sep 2024', 'Singapore', 'para'],
        ['Tech Lead', 'Irvins Salted Egg', 'Sep 2020 - May 2021', 'Singapore', 'para'],
        ['Tech Lead', 'Secretlab', 'Feb 2018 - Aug 2020', 'Singapore', 'para'],
        ['Software Engineer', '...', '... - ...', 'Singapore', 'para'],
        ['Programmer', 'DST Worldwide Services', 'Jun 2012 - Jul 2014', 'Bangkok, Thailand', 'para'],
    ]);
    ?>
</div>
<div class="page">
    <!-- EDUCATION -->
    <h3>EDUCATION</h3>
    <?php
    function build_education($education): void
    {
        echo '<table class="table table-sm table-borderless">';
        foreach ($education as $row) {
            echo '<tr>';
            echo '<td><b>' . $row['degree'] . '</b><br>' . $row['school'] . '</td><td class="text-end">' . $row['when'] . '<br>' . $row['where'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    build_education([
        [
            'school' => 'Nanyang Technological University, <em>Wee Kim Wee School of Communication and Information</em>',
            'where'  => 'Singapore',
            'degree' => 'Master of Science in Information Systems',
            'when'   => 'May 2015',
        ],
        [
            'school' => 'Thammasat University, <em>Sirindhorn International Institute of Technology</em>',
            'where'  => 'Pathum Thani, Thailand',
            'degree' => 'Bachelor of Science (First Class Honours) in Computer Science',
            'when'   => 'Mar 2012',
        ]
    ]);
    ?>
    <!-- AWARDS -->
    <h3>AWARDS</h3>
    <?php
    function build_awards($awards): void
    {
        echo '<table class="table table-sm table-borderless">';
        foreach ($awards as $award) {
            echo '<tr>';
            echo '<td><b>' . print_link($award[0], $award[3]) . '</b></td>';
            echo '<td class="text-end">' . $award[1] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="2"><em>' . $award[2] . '</em></td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    build_awards([
        ['Singapore FinTech AI Award', 'Singapore FinTech Festival, Nov 2023', 'Recognised for leading the design and facilitation of AI-driven financial transaction algorithms, enhancing customer experience and security.', 'https://www.mas.gov.sg/news/media-releases/2023/mas-and-sfa-announce-finalists-for-the-2023-singapore-fintech-festival-global-fintech-awards']
    ]);
    ?>
    <!-- CERTIFICATIONS -->
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
                echo '<td class="text-end">' . $cert[1] . '</td>';
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
        ],
        'Nanyang Technological University FlexiMasters Courses' => [
            ['Foundations of Computation Thinking and Programming', 'Apr 2024', '#'],
            ['Introduction to Computer Vision', 'Apr 2024', '#'],
            ['AI Foundation', 'May 2024', '#'],
            ['Computational Game Theory', 'Jun 2024', '#'],
        ]
    ])
    ?>
    <!-- LANGUAGES -->
    <h3>LANGUAGES</h3>
    <?php
    function build_languages($languages) {
        echo '<table class="table table-sm table-borderless">';
        foreach ($languages as $language) {
            echo '<tr>';
            echo '<td>' . print_link($language[0], $language[3]) . '</td>';
            echo '<td>' . $language[1] . '</td>';
            echo '<td class="text-end"><em>' . $language[2] . '</em></td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    build_languages([
        ['English', 'C1/C2 Proficient', 'EF SET', '#'],
        ['Thai (ภาษาไทย)', 'Native', '', '#'],
        ['Japanese （日本語）', 'Pre-A1', 'Expected JLPT N5 (A1) in 2026', '#']
    ]);
    ?>
</div>
</body>
</html>