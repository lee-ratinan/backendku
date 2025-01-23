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
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/employment/salary') ?>">Salary</a></li>
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $page_title ?></h5>
                        <?php
                        $fields = [
                            'company_id',
                            'pay_date',
                            'tax_year',
                            'tax_country_code',
                            'payment_method',
                            'payment_currency',
                            'pay_type',
                            'base_amount',
                            'allowance_amount',
                            'training_amount',
                            'overtime_amount',
                            'adjustment_amount',
                            'bonus_amount',
                            'subtotal_amount',
                            'social_security_amount',
                            'us_tax_fed_amount',
                            'us_tax_state_amount',
                            'us_tax_city_amount',
                            'us_tax_med_ee_amount',
                            'us_tax_oasdi_ee_amount',
                            'th_tax_amount',
                            'sg_tax_amount',
                            'au_tax_amount',
                            'claim_amount',
                            'provident_fund_amount',
                            'total_amount',
                            'payment_details',
                            'google_drive_link'
                        ];
                        foreach ($fields as $field) {
                            generate_form_field($field, $config[$field], @$salary[$field]);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php if ('edit' == $mode) : ?>
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Salary Details</h5>
                            <table class="table table-sm table-borderless">
                                <?php foreach ($fields as $field) : ?>
                                    <tr>
                                        <td class="text-end"><?= $config[$field]['label'] ?></td>
                                        <td>
                                            <?php
                                            echo match ($field) {
                                                'company_id', 'payment_method', 'pay_type' => $config[$field]['options'][$salary[$field]],
                                                'tax_country_code' => lang('ListCountries.countries.' . $salary[$field] . '.common_name'),
                                                'pay_date' => date(DATE_FORMAT_UI, strtotime($salary[$field])),
                                                'google_drive_link' => (empty($salary[$field]) ? '-' : '<a href="' . $salary[$field] . '" target="_blank">Click</a><br>'),
                                                'tax_year', 'payment_currency', 'payment_details' => (empty($salary[$field]) ? '-' : $salary[$field]),
                                                default => currency_format($salary['payment_currency'], $salary[$field] ?? 0),
                                            };
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php $this->endSection() ?>