<html lang="en">
<head>
    <title><?= $document['doc_title'] ?> | Document</title>
    <link href="<?= base_url('appstack/css/app.css') ?>" rel="stylesheet"/>
    <style>
        .container { max-width: 750px; }
        div, p { color: #222!important; }
        .history-table td, .history-table th { padding: 1px!important; }
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
        $('article s').each(function () {
            $(this).replaceWith($(this).text());
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