<html lang="en">
<head>
    <title>Ooca Visit Log Export</title>
    <link href="<?= base_url('appstack/css/app.css') ?>" rel="stylesheet"/>
    <style>
        .container { max-width: 750px; }
        div, p { color: #222!important; }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="my-3">บันทึกการพบจิตแพทย์ ประจำปี <?= $year ?></h1>
            <?php
            $fields = [
                'visit_date'          => '<i class="fa-solid fa-calendar-check"></i> วันที่',
                'psychologist_name'   => '<i class="fa-solid fa-user"></i> ชื่อผู้ให้คำปรึกษา',
                'note_what_happened'  => '<i class="fa-solid fa-question-circle"></i> อาการสำคัญ',
                'note_what_i_said'    => '<i class="fa-solid fa-comment"></i> สิ่งที่คุณพูด',
                'note_what_suggested' => '<i class="fa-solid fa-lightbulb"></i> สิ่งที่ผู้ให้คำปรึกษาแนะนำ',
            ];
            ?>
            <?php if (empty($records)) : ?>
                <p>ไม่มีรายการ</p>
            <?php else: ?>
                <?php foreach ($records as $record) : ?>
                    <?php
                    foreach ($fields as $field => $label) {
                        echo '<div class="mb-3"><b>' . $label . ':</b><div class="ms-3">';
                        if ('visit_date' == $field) {
                            echo date(DATE_FORMAT_UI, strtotime($record[$field]));
                        } else {
                            echo $record[$field];
                        }
                        echo '</div></div>';
                    }
                    ?>
                    <hr/>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>