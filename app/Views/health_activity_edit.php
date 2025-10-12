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
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/health/activity') ?>">Activity</a></li>
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <div class="text-end mb-3">
                            <?php foreach ($record_cate as $key => $type) : ?>
                                <a class="btn btn-<?= ($record_type == $key ? '' : 'outline-') ?>primary btn-sm" href="<?= base_url($session->locale . '/office/health/activity/new/' . $key) ?>"><i class="fa-solid fa-plus-circle"></i> <?= $type ?></a>
                            <?php endforeach; ?>
                        </div>
                        <?php
                        $fields = [
                            'record_type',
                            'event_type',
                            'WHEN',
                            'journey_id',
                            'time_start_utc',
                            'time_end_utc',
                            'event_timezone',
                            'event_duration',
                            'duration_from_prev_ejac',
                            'is_ejac',
                            'SPA INFORMATION',
                            'spa_name',
                            'spa_type',
                            'currency_code',
                            'price_amount',
                            'price_tip',
                            'NOTES',
                            'event_notes',
                            'event_location',
                            'previous_ejac_time_utc'
                        ];
                        $configuration['record_type']['options'] = [$record_type => $record_cate[$record_type]];
                        $record['record_type']                   = $record_type;
                        $configuration['event_type']['options']  = $record_types[$record_type];
                        $record['event_type']                    = array_keys($record_types[$record_type])[0];
                        $record['event_timezone']                = 'Asia/Singapore';
                        $record['event_duration']                = 0;
                        $record['duration_from_prev_ejac']       = 0;
                        $record['is_ejac']                       = ('ejac' == $record_type ? 'Y' : '');
                        if ('spa' != $record_type) {
                            $configuration['spa_name']['type']      = 'hidden';
                            $configuration['spa_type']['type']      = 'hidden';
                            $configuration['currency_code']['type'] = 'hidden';
                            $configuration['price_amount']['type']  = 'hidden';
                            $configuration['price_tip']['type']     = 'hidden';
                            $record['spa_name']                     = '';
                            $record['spa_type']                     = '';
                            $record['currency_code']                = '';
                            $record['price_amount']                 = 0;
                            $record['price_tip']                    = 0;
                        }
                        $configuration['previous_ejac_time_utc']['type'] = 'hidden';
                        $record['previous_ejac_time_utc']                = $prev['time_end_utc'];
                        foreach ($fields as $field) {
                            if (in_array($field, ['NOTES', 'WHEN'])) {
                                echo '<h6>' . $field . '</h6>';
                            } else if ('SPA INFORMATION' == $field) {
                                if ('spa' == $record_type) {
                                    echo '<h6>Spa Information</h6>';
                                }
                            } else {
                                generate_form_field($field, $configuration[$field], @$record[$field]);
                            }
                        }
                        ?>
                        <div class="text-end">
                            <button class="btn btn-primary btn-sm" id="btn-save"><i class="fa-solid fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#btn-save').click(function (e) {
                e.preventDefault();
                // let ids = ['transaction_date', 'transaction_code', 'ordinary_amount', 'ordinary_balance', 'special_amount', 'special_balance', 'medisave_amount', 'medisave_balance', 'transaction_amount', 'account_balance', 'contribution_month', 'company_id', 'staff_contribution', 'staff_ytd', 'company_match', 'company_ytd'];
                // for (let i = 0; i < ids.length; i++) {
                //     if ('' === $('#' + ids[i]).val()) {
                //         toastr.warning('Please ensure all mandatory fields are filled.');
                //         $('#' + ids[i]).focus();
                //         return;
                //     }
                // }
                $(this).prop('disabled', true);
                $.ajax({
                    url: '<?= base_url('en/office/health/activity/edit') ?>',
                    type: 'post',
                    data: {
                        <?php foreach ($fields as $field) : ?>
                        <?php if (in_array($field, ['NOTES', 'WHEN', 'SPA INFORMATION'])) { continue; } ?>
                        <?= $field ?>: $('#<?= $field ?>').val(),
                        <?php endforeach; ?>
                    },
                    success: function (response) {
                        if ('success' === response.status) {
                            toastr.success(response.toast);
                            setTimeout(function () {window.location.href = response.redirect;}, 5000);
                        } else {
                            let message = (response.toast ?? 'Sorry! Something went wrong. Please try again.');
                            toastr.error(message);
                            $('#btn-save-cpf').prop('disabled', false);
                        }
                    },
                    error: function (xhr, status, error) {
                        let response = JSON.parse(xhr.responseText);
                        let error_message = (response.toast ?? 'Sorry! Something went wrong. Please try again.');
                        $('#btn-save-cpf').prop('disabled', false);
                        toastr.error(error_message);
                    }
                });
            });
        });
    </script>
<?php $this->endSection() ?>