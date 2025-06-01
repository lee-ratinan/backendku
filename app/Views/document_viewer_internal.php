<html lang="en">
<head>
    <title><?= strip_tags($document['doc_title']) ?> | Document</title>
    <link href="<?= base_url('file/favicon.jpg') ?>" rel="icon">
    <link href="<?= base_url('appstack/css/app.css') ?>" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400..700;1,400..700&family=Noto+Serif:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        strong { font-weight: 700!important; }
        .container { max-width: 750px; font-family: 'Noto Serif', serif; }
        div, p { color: #111!important; }
        h1, h2, h3, h4, h5, h6 { margin: 1rem 0; font-family: 'Noto Sans', sans-serif; }
        .history-table td, .history-table th { padding: 1px!important; }
        li p { margin-bottom: 0!important; }
        .table>tbody>tr>td { border: none; vertical-align: top!important; }
        table { border: none; margin-bottom: 1rem; }
        @media print {
            body, .container { background-color:#fff; color: #000; }
            .print-page-break { page-break-before: always; break-before: page; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col">
            <div class="my-5 py-5 text-end">
                <br><br><br><br><br>
                <h1><?= $document['doc_title'] ?></h1>
                <h2>by <?= $document['user_name_first'] . ' ' . $document['user_name_family'] ?></h2>
                <br>
                <p>V<?= $document['version_number'] ?> | <?= date(DATE_FORMAT_UI, strtotime($document['published_date'])) ?></p>
            </div>
            <div class="print-page-break"></div>
            <h1><?= $document['doc_title'] ?></h1>
            <p>by <?= $document['user_name_first'] . ' ' . $document['user_name_family'] ?></p>
            <hr class="my-2" />
            <br><br>
            <table class="table history-table">
                <thead>
                <tr class="text-center">
                    <th>Version</th>
                    <th>Date</th>
                    <th>By</th>
                    <th>Detail</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($history as $row) : ?>
                    <tr>
                        <td class="text-center"><?= $row['version_number'] ?></td>
                        <td class="text-center"><?= date(DATE_FORMAT_UI, strtotime($row['published_date'])) ?></td>
                        <td><?= ucwords($row['user_name_first'] . ' ' . $row['user_name_family']) ?></td>
                        <td><?= $row['version_description'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <article>
                <?= $document['doc_content'] ?>
            </article>
            <hr class="my-3" />
            <div class="text-center">*** END OF DOCUMENT ***</div>
        </div>
    </div>
</div>
<script src="<?= base_url('appstack/js/app.js') ?>"></script>
<script>
    $(document).ready(function () {
        $('s').each(function () {
            $(this).replaceWith($(this).text());
        });
        $('article table').each(function () {
            $(this).css({'font-family': 'inherit', color: 'inherit'}).addClass('table table-sm table-borderless');
        });
        $('article p').each(function () {
            if ($(this).text().trim() === '[NEW_PAGE]') {
                $(this).replaceWith('<div class="print-page-break"></div>');
            }
        });
    });
</script>
</body>
</html>