<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $job_title ?> - Ratinan L. - Resume</title>
    <style>
        body {color:#444!important;font-family:Arial,sans-serif;font-size: 12px;margin:0 auto;width:700px;}
        table {width:700px!important;}
        td, p {color:#444!important;font-family:Arial,sans-serif;font-size: 12px;vertical-align:top;}
        h1 {font-size: 18px;}
        h2 {font-size: 16px;}
        h3 {font-size: 14px;}
        h1, h2 {margin: 0;text-align: center;}
        h3 {margin: 10px 0;}
        a {color:#444;text-decoration:none;}
        p {margin: 5px 0;}
        .bb {border-bottom: 1px solid #444;}
        .center {text-align: center;}
        .right {text-align: right}
    </style>
</head>
<body>
<h1 style="margin-top:10px">RATINAN “NAT” LEELA-NGAMWONGSA</h1>
<h2 style="margin-bottom:8px"><?= $job_title ?> - MSc, CSM, PSM, PSPO</h2>
<p class="center">
    <a href="https://wa.me/6597754577">+65 9775 4577</a> -
    <a href="mailto:lee@ratinan.com">lee@ratinan.com</a> -
    <a href="https://lee.ratinan.com" target="_blank">lee.ratinan.com</a> -
    <a href="https://www.google.com/maps/place/Singapore/" target="_blank">Singapore</a><br>
    <a href="https://www.linkedin.com/in/ratinanlee/" target="_blank">LinkedIn: /ratinanlee</a> -
    <a href="https://github.com/lee-ratinan" target="_blank">GitHub: /lee-ratinan</a> -
    <a href="https://www.credly.com/users/ratinanlee" target="_blank">Credly: /ratinanlee</a>
</p>
<p><?= str_replace('[JOB-TITLE]', $job_title, $summary) ?></p>
<h3 class="center bb">Skills</h3>
<?php
foreach ($skills as $key => $value) {
    if (empty($value)) {
        continue;
    }
    echo '<p><b>' . $skill_keys[$key] . '</b>: ' . $value . '</p>';
}
?>
<h3 class="center bb">Experience</h3>
<table>
    <?php
    foreach ($experiences as $key => $values) {
        echo '<tr><td><b>' . $values['title'] . '<br>' . $values['company'] . '</b></td>';
        echo '<td class="right">' . $values['location'] . '<br>' . $values['period'] . '</td></tr>';
        echo '<tr><td colspan="2" style="padding-bottom:10px">' . str_replace("\n", '<br>', $experience[$key]) . '</td></tr>';
    }
    ?>
</table>
<h3 class="center bb">Education</h3>
<p>
    <?php
    foreach ($education as $values) {
        echo '<b>' . $values['degree'] . '</b>, ' . $values['school'] . ' - ' . $values['location'] . ' - ' . $values['class_of'] . '<br>';
    }
    ?>
</p>
<h3 class="center bb">Certifications</h3>
<table>
    <?php foreach ($certifications as $key => $values) {
        echo '<tr><td style="padding-right:10px"><b>' . $key . '</b></td><td>';
        echo implode('<br>', $values);
        echo '</td></tr>';
    } ?>
</table>
<h3 class="center bb">Awards</h3>
<p>
    <b>Singapore FinTech AI Award</b> at Singapore FinTech Festival, Nov 2023
</p>
<h3 class="center bb">Languages</h3>
<p><b>English</b> (full professional proficiency), <b>Thai</b> (native proficiency)</p>
</body>
</html>