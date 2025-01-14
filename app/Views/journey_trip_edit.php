<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="pagetitle">
        <h1><?= $page_title ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/dashboard') ?>"><?= lang('System.dashboard.page_title') ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/journey/trip') ?>">Trip</a></li>
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fa-solid fa-passport fa-fw me-3"></i> Master
                            <?php if ('edit' == $mode) : ?>
                                <span class="flag-icon flag-icon-<?= strtolower($trip_data['master_data']['country_code']) ?>"></span> ID: <?= $trip_data['master_data']['id'] ?>
                            <?php endif; ?>
                        </h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <?php
                                generate_form_field('country_code', $master_config['country_code'], @$trip_data['master_data']['country_code']);
                                generate_form_field('date_entry', $master_config['date_entry'], @$trip_data['master_data']['date_entry']);
                                generate_form_field('date_exit', $master_config['date_exit'], @$trip_data['master_data']['date_exit']);
                                generate_form_field('day_count', $master_config['day_count'], @$trip_data['master_data']['day_count']);
                                generate_form_field('entry_port_id', $master_config['entry_port_id'], @$trip_data['master_data']['entry_port_id']);
                                generate_form_field('exit_port_id', $master_config['exit_port_id'], @$trip_data['master_data']['exit_port_id']);
                                ?>
                            </div>
                            <div class="col-12 col-md-6">
                                <?php
                                generate_form_field('trip_code', $master_config['trip_code'], @$trip_data['master_data']['trip_code']);
                                generate_form_field('visa_info', $master_config['visa_info'], @$trip_data['master_data']['visa_info']);
                                generate_form_field('trip_tags', $master_config['trip_tags'], @$trip_data['master_data']['trip_tags']);
                                generate_form_field('journey_details', $master_config['journey_details'], @$trip_data['master_data']['journey_details']);
                                generate_form_field('journey_status', $master_config['journey_status'], @$trip_data['master_data']['journey_status']);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ('edit' == $mode) : ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-person-walking-luggage fa-fw me-3"></i> Transport</h5>
                            <?php if (!empty($trip_data['transport_data'])) : ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped">
                                    <?php foreach ($trip_data['transport_data'] as $row) : ?>
                                        <?php $new_id = $row['id'] * $nonces['transport'] ?>
                                        <tr>
                                            <td style="min-width:50px">
                                                <a class="btn btn-sm btn-outline-primary" href="<?= base_url($session->locale . '/office/journey/transport/edit/' . $new_id) ?>" title="Edit"><i class="fa-solid fa-edit"></i></a>
                                            </td>
                                            <td style="min-width:150px;">
                                                <h6><?= $row['flight_number'] ?> <?= $row['pnr_number'] ?></h6>
                                                <?= $modes_of_transport[$row['mode_of_transport']] ?><br>
                                                <?= $row['craft_type'] ?>
                                            </td>
                                            <td style="min-width:200px;">
                                                <b><?= $row['departure_port_name'] ?><?= !empty($row['departure_port_code']) ? ' [' . $row['departure_port_code'] . ']' : ''  ?></b><br>
                                                <?php if ('Y' == $row['is_time_known']) : ?>
                                                    <?= date(DATETIME_FORMAT_UI, strtotime($row['departure_date_time'])) ?>
                                                <?php else: ?>
                                                    <?= date(DATE_FORMAT_UI, strtotime($row['departure_date_time'])) ?>
                                                <?php endif; ?>
                                            </td>
                                            <td style="min-width:200px;">
                                                <b><?= $row['arrival_port_name'] ?><?= !empty($row['arrival_port_code']) ? ' [' . $row['arrival_port_code'] . ']' : ''  ?></b><br>
                                                <?php if ('Y' == $row['is_time_known']) : ?>
                                                    <?= date(DATETIME_FORMAT_UI, strtotime($row['arrival_date_time'])) ?>
                                                <?php else: ?>
                                                    <?= date(DATE_FORMAT_UI, strtotime($row['arrival_date_time'])) ?>
                                                <?php endif; ?>
                                            </td>
                                            <td style="min-width:150px;"><?= $row['journey_details'] ?></td>
                                            <td style="min-width:150px;"><span class="badge bg-<?= ('as_planned' == $row['journey_status'] ? 'success' : 'danger') ?>"><?= ucwords(strtolower(str_replace('_', ' ', $row['journey_status']))) ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-bed fa-fw me-3"></i> Accommodation</h5>
                            <?php if (!empty($trip_data['accommodation_data'])) : ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped">
                                    <?php foreach ($trip_data['accommodation_data'] as $row) : ?>
                                        <?php $new_id = $row['id'] * $nonces['accommodation'] ?>
                                        <tr>
                                            <td style="min-width:50px">
                                                <a class="btn btn-sm btn-outline-primary" href="<?= base_url($session->locale . '/office/journey/accommodation/edit/' . $new_id) ?>" title="Edit"><i class="fa-solid fa-edit"></i></a>
                                            </td>
                                            <td style="min-width:150px">
                                                <b><?= $row['hotel_name'] ?></b><br>
                                                [<?= $row['night_count'] ?> nights]
                                                <?= $row['room_type'] ?>
                                            </td>
                                            <td style="min-width:200px">
                                                Check-in:<br>
                                                <?php if (empty($row['accommodation_timezone'])) : ?>
                                                    <?= date(DATE_FORMAT_UI, strtotime($row['check_in_date'])) ?>
                                                <?php else: ?>
                                                    <?= date(DATETIME_FORMAT_UI, strtotime($row['check_in_date'])) ?>
                                                <?php endif; ?>
                                            </td>
                                            <td style="min-width:200px">
                                                Check-out:<br>
                                                <?php if (empty($row['accommodation_timezone'])) : ?>
                                                    <?= date(DATE_FORMAT_UI, strtotime($row['check_out_date'])) ?>
                                                <?php else: ?>
                                                    <?= date(DATETIME_FORMAT_UI, strtotime($row['check_out_date'])) ?>
                                                <?php endif; ?>
                                            </td>
                                            <td style="min-width:150px;"><?= $row['journey_details'] ?></td>
                                            <td style="min-width:150px;"><span class="badge bg-<?= ('as_planned' == $row['journey_status'] ? 'success' : 'danger') ?>"><?= ucwords(strtolower(str_replace('_', ' ', $row['journey_status']))) ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-ticket fa-fw me-3"></i> Attraction</h5>
                            <?php if (!empty($trip_data['attraction_data'])) : ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover table-striped">
                                        <?php foreach ($trip_data['attraction_data'] as $row) : ?>
                                            <?php $new_id = $row['id'] * $nonces['attraction'] ?>
                                            <tr>
                                                <td style="min-width:50px">
                                                    <a class="btn btn-sm btn-outline-primary" href="<?= base_url($session->locale . '/office/journey/attraction/edit/' . $new_id) ?>" title="Edit"><i class="fa-solid fa-edit"></i></a>
                                                </td>
                                                <td style="min-width:150px">
                                                    <b><?= $row['attraction_title'] ?></b><br>
                                                    <?= $row['attraction_type'] ?>
                                                </td>
                                                <td style="min-width:150px">
                                                    <?php
                                                    $date   = $row['attraction_date'];
                                                    $time   = substr($date, -8);
                                                    $format = DATETIME_FORMAT_UI;
                                                    if ('00:00:00' == $time) {
                                                        $format = DATE_FORMAT_UI;
                                                    }
                                                    echo date($format, strtotime($date));
                                                    ?>
                                                </td>
                                                <td style="min-width:150px;"><?= $row['journey_details'] ?></td>
                                                <td style="min-width:150px;"><span class="badge bg-<?= ('as_planned' == $row['journey_status'] ? 'success' : 'danger') ?>"><?= ucwords(strtolower(str_replace('_', ' ', $row['journey_status']))) ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php $this->endSection() ?>