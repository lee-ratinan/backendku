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
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/employment/freelance-income') ?>">Freelance Income</a></li>
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
                            'project_id',
                            'pay_date',
                            'payment_method',
                            'payment_currency',
                            'base_amount',
                            'deduction_amount',
                            'claim_amount',
                            'subtotal_amount',
                            'tax_amount',
                            'total_amount',
                            'payment_details',
                            'google_drive_link',
                        ];
                        foreach ($fields as $field) {
                            generate_form_field($field, $config[$field], @$income[$field]);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php if ('edit' == $mode) : ?>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Payment Details</h5>
                        <table class="table table-sm table-borderless">
                            <?php foreach ($fields as $field) : ?>
                                <tr>
                                    <td class="text-end"><?= $config[$field]['label'] ?></td>
                                    <td>
                                        <?php
                                        switch ($field) {
                                            case 'pay_date':
                                                echo date(DATE_FORMAT_UI, strtotime($income[$field]));
                                                break;
                                            case 'payment_method':
                                                echo $config[$field]['options'][$income[$field]];
                                                break;
                                            case 'google_drive_link':
                                                echo (empty($income[$field]) ? '-' : '<a href="' . $income[$field] . '" target="_blank">Click</a><br>');
                                                break;
                                            case 'payment_currency':
                                            case 'payment_details':
                                                echo $income[$field];
                                                break;
                                            default:
                                                echo currency_format($income['payment_currency'], $income[$field]);
                                                break;
                                        }
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