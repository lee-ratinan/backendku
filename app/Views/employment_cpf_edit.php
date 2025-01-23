<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <style>
        .text-oa{color:#437271!important;}
        .bg-oa{background-color:#437271!important;color:#222!important;}
        .text-sa{color:#DFB670!important;}
        .bg-sa{background-color:#DFB670!important;color:#222!important;}
        .text-ma{color:#7D9ADE!important;}
        .bg-ma{background-color:#7D9ADE!important;color:#222!important;}
    </style>
    <div class="pagetitle">
        <h1><?= $page_title ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/dashboard') ?>"><?= lang('System.dashboard.page_title') ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/employment/cpf') ?>">CPF</a></li>
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2><?= $page_title ?></h2>
                        <?php
                        $fields = [
                            'transaction_date',
                            'transaction_code',
                            'ordinary_amount',
                            'ordinary_balance',
                            'special_amount',
                            'special_balance',
                            'medisave_amount',
                            'medisave_balance',
                            'transaction_amount',
                            'account_balance',
                            'contribution_month',
                            'company_id',
                            'staff_contribution',
                            'staff_ytd',
                            'company_match',
                            'company_ytd',
                        ];
                        if ('new' == $mode) {
                            foreach ($fields as $field) {
                                generate_form_field($field, $config[$field], '');
                            }
                            echo '<pre>';
                            print_r($cpf_latest);
                            print_r($cpf_last_con);
                            echo '</pre>';
                        } else {
                            echo '<table class="table table-sm table-borderless">';
                            foreach ($fields as $field) {
                                if ('CON' != $cpf['transaction_code'] && 'contribution_month' == $field) {
                                    break;
                                } else if ('contribution_month' == $field) {
                                    echo '<tr><td colspan="2" class="text-center"><b>--- CONTRIBUTION ---</b></td></tr>';
                                }
                                echo '<tr><td class="text-end">' . $config[$field]['label'] . '</td><td>';
                                switch ($field) {
                                    case 'transaction_code':
                                    case 'company_id':
                                        echo (empty($cpf[$field]) ? '-' : $config[$field]['options'][$cpf[$field]]);
                                        break;
                                    case 'transaction_date':
                                        echo date(DATE_FORMAT_UI, strtotime($cpf[$field]));
                                        break;
                                    case 'contribution_month':
                                        echo (empty($cpf[$field]) ? '-' : date(MONTH_FORMAT_UI, strtotime($cpf[$field] . '-01')));
                                        break;
                                    default:
                                        echo (empty($cpf[$field]) ? '-' : currency_format('SGD', $cpf[$field]));
                                        break;
                                }
                                echo '</td></tr>';
                            }
                            echo '</table>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $this->endSection() ?>