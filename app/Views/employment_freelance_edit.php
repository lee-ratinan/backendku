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
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/employment/freelance') ?>">Freelance</a></li>
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
                            'project_title',
                            'project_slug',
                            'project_start_date',
                            'project_end_date',
                            'client_name',
                            'client_organization_name',
                        ];
                        foreach ($fields as $field) {
                            generate_form_field($field, $config[$field], @$project[$field]);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php if ('edit' == $mode) : ?>
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Project Details</h5>
                            <table class="table table-sm table-borderless">
                                <?php foreach ($fields as $field) : ?>
                                    <tr>
                                        <td class="text-end"><?= $config[$field]['label'] ?></td>
                                        <td>
                                            <?php
                                            echo match ($field) {
                                                'company_id' => $config[$field]['options'][$project[$field]],
//                                                'tax_country_code' => lang('ListCountries.countries.' . $salary[$field] . '.common_name'),
                                                'project_start_date', 'project_end_date' => (empty($project[$field]) ? '-' : date(DATE_FORMAT_UI, strtotime($project[$field]))),
//                                                'google_drive_link' => (empty($salary[$field]) ? '-' : '<a href="' . $salary[$field] . '" target="_blank">Click</a><br>'),
//                                                'tax_year', 'payment_currency', 'payment_details' => (empty($salary[$field]) ? '-' : $salary[$field]),
                                                default => $project[$field],
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