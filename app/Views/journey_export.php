<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Export Journey</title>
    <link rel="shortcut icon" href="<?= base_url('file/favicon.jpg') ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Thai&family=Oxanium:wght@700&family=Poppins:ital@0;1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Thai&family=Oxanium:wght@700&family=Poppins:ital@0;1&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/flag-icon/css/flag-icon.min.css') ?>" rel="stylesheet">
    <style>
        h1,h2,h3,h4,h5,h6{font-family:"Oxanium",sans-serif;} .alert{padding:1rem;}  svg:not(:host).svg-inline--fa, svg:not(:root).svg-inline--fa {overflow: visible;box-sizing: content-box;margin: auto 0.25rem;}
        h2{font-size:1.2em}
    </style>
</head>
<body>
<div class="container">
    <h1>Journey</h1>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Country</th>
            <th>Details</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($journey as $journey_master_id => $data) : ?>
            <tr>
                <td rowspan="4"><?= $journey_master_id ?></td>
                <td rowspan="4"><span class="flag-icon flag-icon-<?= strtolower($data['master']['country_code']) ?>"></span> <?= $countries[$data['master']['country_code']]['common_name'] ?></td>
                <td>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td style="width:25%">
                                ENTRY: <?= $data['master']['date_entry'] ?><br>
                                PORT: <?= $data['master']['entry_port_name'] ?? '-' ?>
                            </td>
                            <td style="width:10%">
                                <?= $data['master']['day_count'] ?> days
                            </td>
                            <td style="width:25%">
                                EXIT: <?= $data['master']['date_exit'] ?><br>
                                PORT: <?= $data['master']['exit_port_name'] ?? '-' ?>
                            </td>
                            <td style="width:40%">
                                <?= $data['master']['journey_status'] ?><br>
                                <small>
                                    <?= $data['master']['visa_info'] ?><br><?= $data['master']['journey_details'] ?>
                                </small>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <h2>Transport</h2>
                    <?php if (empty($data['transport'])) : ?>
                        NO DATA
                    <?php else: ?>
                    <table class="table table-borderless mb-0">
                        <?php foreach ($data['transport'] as $transport) : ?>
                        <tr>
                            <td>
                                [TRA]<br>
                                <?= $transport['operator_name'] ?><br>
                                <?= $transport['flight_number'] ?><br>
                                <?= $transport['pnr_number'] ?><br>
                            </td>
                            <td>
                                DEPART: <?= $transport['departure_port_name'] ?><br>
                                AT: <?= $transport['departure_date_time'] ?> <?= $transport['departure_timezone'] ?><br>
                                TIME IS KNOWN? <?= $transport['is_time_known'] ?><br>
                            </td>
                            <td>
                                DURATION: <?= floor($transport['trip_duration']/60) ?>h <?= $transport['trip_duration']%60 ?>m<br>
                                DISTANCE: <?= number_format($transport['distance_traveled']) ?> km<br>
                                MODE: <?= $transport['mode_of_transport'] ?><br>
                                TYPE: <?= $transport['craft_type'] ?><br>
                            </td>
                            <td>
                                ARRIVE: <?= $transport['arrival_port_name'] ?><br>
                                AT: <?= $transport['arrival_date_time'] ?> <?= $transport['arrival_timezone'] ?><br>
                            </td>
                            <td class="small">
                                PRICE: <?= $transport['price_amount'] ?> <?= $transport['price_currency_code'] ?><br>
                                CHARGED: <?= $transport['charged_amount'] ?> <?= $transport['charged_currency_code'] ?><br>
                                STATUS: <?= $transport['journey_status'] ?><br>
                                DETAILS: <?= $transport['journey_details'] ?><br>
                                <?php if (!empty($transport['google_drive_link'])) : ?>
                                <a href="<?= $transport['google_drive_link'] ?>" target="_blank">Google Drive</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h2>Accommodation</h2>
                    <?php if (empty($data['accommodation'])) : ?>
                        NO DATA
                    <?php else: ?>
                    <table class="table table-borderless mb-0">
                        <?php foreach ($data['accommodation'] as $row) : ?>
                        <tr>
                            <td>
                                [ACC]<br>
                                FR: <?= $row['check_in_date'] ?><br>
                                TO: <?= $row['check_out_date'] ?><br>
                                (<?= $row['night_count'] ?> nights)
                            </td>
                            <td>
                                <?= lang('ListCountries.countries.'. $row['country_code'] . '.common_name') ?>
                                HOTEL: <?= $row['hotel_name'] ?><br>
                                <?= $row['hotel_address'] ?><br>
                                CHANNEL: <?= $row['booking_channel'] ?><br>
                                ROOM: <?= $row['room_type'] ?><br>
                                BF INCL: <?= $row['breakfast_included'] ?><br>
                            </td>
                            <td>
                                PRICE: <?= $row['price_amount'] ?> <?= $row['price_currency_code'] ?><br>
                                CHARGED: <?= $row['charged_amount'] ?> <?= $row['charged_currency_code'] ?><br>
                                STATUS: <?= $row['journey_status'] ?><br>
                                DETAILS: <?= $row['journey_details'] ?><br>
                                <?= (empty($row['google_drive_link']) ? '' : '<a href="' . $row['google_drive_link'] . '" target="_blank">Google Drive</a>') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h2>Attraction</h2>
                    <pre><?php print_r(@$data['attraction']); ?></pre>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>