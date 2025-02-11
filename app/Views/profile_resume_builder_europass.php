<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $job_title ?> - Ratinan L. - Resume</title>
    <style>
        /* all */
        body {
            color: #444 !important;
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
        }

        .container {
            margin: 0 auto;
            width: 700px;
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 0;
        }

        h2 {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 0;
        }
        h3 {
            font-size: 18px;
            font-weight: 400;
            padding: 2px;
            margin-bottom: 0;
        }
        h3 small {
            font-size: 14px;
            font-weight: 400;
        }

        hr {
            height: 3px;
            color: #080;
            background: #080;
            font-size: 0;
            border: 0;
            margin: 10px 0;
        }
        hr.small {
            height: 1px;
            color: #080;
            background: #080;
            font-size: 0;
            border: 0;
            margin: 3px 0;
        }

        p {
            margin: 3px 0;
        }

        /*table {width:700px!important;}*/
        /*td, p {color:#444!important;font-family:Arial,sans-serif;font-size: 12px;vertical-align:top;}*/
        /*h1 {font-size: 18px;}*/
        /*h2 {font-size: 16px;}*/
        /*h3 {font-size: 14px;}*/
        /*h1, h2 {margin: 0;text-align: center;}*/
        /*h3 {margin: 10px 0;}*/
        a {
            color: #444;
            text-decoration: none;
        }

        /*p {margin: 5px 0;}*/
        /*.bb {border-bottom: 1px solid #444;}*/
        /*.center {text-align: center;}*/
        /*.right {text-align: right}*/

        .text-end {
            text-align: right;
        }

        .float-end {
            float: right;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>
        <img class="float-end" src="<?= base_url('assets/img/europass.png') ?>" alt="EUROPASS" style="height:30px;"/>
        Ratinan “Nat” Leela-Ngamwongsa
    </h1>
    <hr/>
    <p>
        <b>Date of birth:</b> 15/11/1989
        <b>Nationality:</b> Thai
        <b>Gender:</b> Male
        <img src="<?= base_url('assets/img/icon-phone.png') ?>" alt="phone number" style="height:16px;"/> (+65) 97754577
        <img src="<?= base_url('assets/img/icon-email.png') ?>" alt="email address" style="height:16px;"/> <a href="mailto:lee@ratinan.com" target="_blank">lee@ratinan.com</a><br>
        <img src="<?= base_url('assets/img/icon-website.png') ?>" alt="email address" style="height:16px;"/> <a href="https://lee.ratinan.com" target="_blank">https://lee.ratinan.com</a>
        <img src="<?= base_url('assets/img/icon-linkedin.png') ?>" alt="email address" style="height:16px;"/> <a href="https://linkedin.com/in/ratinanlee" target="_blank">https://linkedin.com/in/ratinanlee</a>
        <img src="<?= base_url('assets/img/icon-github.png') ?>" alt="email address" style="height:16px;"/> <a href="https://github.com/lee-ratinan" target="_blank">https://github.com/lee-ratinan</a>
        <img src="<?= base_url('assets/img/icon-map-pin.png') ?>" alt="email address" style="height:16px;"/> Singapore
    </p>
    <h2>ABOUT ME</h2>
    <hr/>
    <p><?= $summary ?></p>
    <h2>WORK EXPERIENCE</h2>
    <hr/>
    <?php foreach ($experiences as $key => $values) : ?>
        <h3>
            <small><?= $values['period_eu'] ?> <?= $values['location'] ?></small><br>
            <b><?= strtoupper($values['title']) ?></b> <?= strtoupper($values['company']) ?>
        </h3>
        <hr class="small">
        <p><?= str_replace("\n", '<br>', $experience[$key]) ?></p>
        <p><b>Business or Sector</b> <?= $values['sector'] ?> | <b>Department</b> | <b>Website</b></p>
    <?php endforeach; ?>

</div>
</body>
</html>