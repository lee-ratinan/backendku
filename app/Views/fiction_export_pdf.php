<html lang="en">
<head>
    <title><?= $title['fiction_title'] ?> | Fiction</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="<?= base_url('file/favicon.jpg') ?>" rel="icon">
    <link href="<?= base_url('appstack/css/app.css') ?>" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400..700&family=Noto+Sans+Thai&family=Noto+Sans:ital,wght@0,400..700;1,400..700&family=Noto+Serif+Thai:wght@400..700&family=Noto+Serif:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        @page:first { size: A4 portrait; margin: 0; }
        @page { size: A4 portrait; margin: 0.75in; }
        html, body {margin: 0;padding: 0;}
        strong { font-weight: 700!important; }
        .container { max-width: 750px; font-family: 'Noto Serif', 'Noto Serif Thai', 'Noto Sans JP', serif; }
        div, p { color: #000!important; }
        .chapter-content p { text-indent: 0.3in;line-height: 2.5em; }
        h1, h2, h3, h4, h5, h6 { margin: 1rem 0; font-family: 'Noto Sans', 'Noto Sans Thai', 'Noto Sans JP', sans-serif; page-break-after: avoid; }
        blockquote { border-left: 5px solid #aaa; }
        img.full-page {
            width: 100vw;
            height: 100vh;
            object-fit: contain;
            page-break-before: avoid;
            page-break-after: avoid;
            page-break-inside: avoid;
            display: block;
        }
        @media print {
            html, body {margin: 0;padding: 0;}
            body, .container { background-color:#fff; color: #000!important; }
            strong, b, p { color: #000!important; }
            .print-page-break { page-break-before: always; break-before: page; }
            img.full-page {width: 100%;height: 100%;}
            #toolbarContainer {display:none;}
        }
    </style>
</head>
<body>
<img class="full-page" src="<?= base_url('file/fiction_' . $title['fiction_slug'] . '.jpg') ?>" alt="<?= $title['fiction_title'] ?>" />
<div class="print-page-break"></div>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="row mt-3 mb-5">
                <div class="col">
                    รตินันท์ ลีลางามวงศา<br>
                    19 Punggol Field Walk #02-12 Waterwoods<br>
                    Singapore 828748<br>
                    lee@ratinan.com<br>
                    +65 9775 4577<br>
                </div>
                <div class="col text-end">
                    ประมาณ <?= number_format($word_count) ?> คำ (<?= number_format($char_count) ?> ตัวอักษร)
                </div>
            </div>
            <br><br><br>
            <h1 class="text-center"><?= strtoupper($title['fiction_title']) ?></h1>
            <p class="text-center">โดย <?= $title['pen_name'] ?></p>
            <br><br><br><br><br><br><br>
            <p class="text-center">&copy; <?= date('Y') . ' ' . $title['pen_name'] ?></p>
            <div class="print-page-break"></div>
            <?php foreach ($entries as $entry) : ?>
                <?php if (in_array($entry['entry_type'], ['front-matter', 'research'])) : ?>
                    <div class="<?= $entry['entry_type'] ?>">
                        <?= $entry['entry_content'] ?>
                    </div>
                    <?php if (!empty($entry['footer_section'])) : ?>
                        <hr />
                        <div><?= $entry['footer_section'] ?></div>
                    <?php endif; ?>
                <?php elseif (in_array($entry['entry_type'], ['chapter', 'folder'])) : ?>
                    <br><br><br><br><br>
                    <h2 class="text-center"><?= $entry['entry_title'] ?></h2>
                    <br><br>
                <?php elseif ('scene' == $entry['entry_type']) : ?>
                    <br>
                    <div class="chapter-content">
                        <?= $entry['entry_content'] ?>
                    </div>
                    <?php if (!empty($entry['footer_section'])) : ?>
                        <hr />
                        <div><?= $entry['footer_section'] ?></div>
                    <?php endif; ?>
                    <br>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>