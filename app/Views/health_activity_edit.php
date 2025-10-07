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
                        <?php
//                        $fields = [
//                            'id',
//                            'record_type',
//                            'event_type',
//                            'journey_id',
//                            'time_start_utc',
//                            'time_end_utc',
//                            'event_timezone',
//                            'event_duration',
//                            'duration_from_prev_ejac',
//                            'is_ejac',
//                            'spa_name',
//                            'spa_type',
//                            'currency_code',
//                            'price_amount',
//                            'price_tip',
//                            'event_notes',
//                            'event_location',
//                        ];
//                        foreach ($fields as $field) {
//                            generate_form_field($field, $configuration[$field], @$record[$field]);
//                        }
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

        });
    </script>
<?php $this->endSection() ?>