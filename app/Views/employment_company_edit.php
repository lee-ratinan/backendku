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
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/employment') ?>">Employment</a></li>
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
                            'company_slug',
                            'company_legal_name',
                            'company_trade_name',
                            'company_other_names',
                            'company_address',
                            'company_country_code',
                            'company_hq_country_code',
                            'company_currency_code',
                            'company_website',
                            'company_details',
                            'company_registration',
                            'company_color',
                            'employment_start_date',
                            'employment_end_date',
                            'position_titles'
                        ];
                        foreach ($fields as $field) {
                            generate_form_field($field, $config[$field], @$company[$field]);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php if ('edit' == $mode) : ?>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Company Details</h5>
                        <table class="table table-sm table-borderless">
                            <?php foreach ($fields as $field) : ?>
                            <tr>
                                <td class="text-end"><?= $config[$field]['label'] ?></td>
                                <td>
                                    <?php
                                    switch ($field) {
                                        case 'company_other_names':
                                            echo ($company[$field] ? str_replace(';', '<br>', $company[$field]) : '-');
                                            break;
                                        case 'company_country_code':
                                        case 'company_hq_country_code':
                                            echo lang('ListCountries.countries.'. $company[$field] . '.common_name');
                                            break;
                                        case 'company_website':
                                            $websites = explode(';', $company[$field]);
                                            foreach ($websites as $website) {
                                                echo '<a href="' . $website . '" target="_blank">' . $website . '</a><br>';
                                            }
                                            break;
                                        case 'company_color':
                                            echo '<span class="badge rounded-pill px-5" style="background-color:' . $company[$field] . '"> ' . $company[$field] . ' </div>';
                                            break;
                                        case 'employment_start_date':
                                        case 'employment_end_date':
                                            echo (empty($company[$field]) ? '-' : date(DATE_FORMAT_UI, strtotime($company[$field])));
                                            break;
                                        default:
                                            echo $company[$field];
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