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
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/employment/cpf') ?>">CPF</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/employment/cpf/statement') ?>">CPF Statement</a></li>
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12 col-md-10 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2><?= $page_title ?></h2>
                        <?php
                        $fields = [
                            'statement_year',
                            'google_drive_url'
                        ];
                        if ('new' == $mode) {
                            foreach ($fields as $field) {
                                generate_form_field($field, $config[$field], @$statement[$field]);
                            }
                        } else {
                            echo '<table class="table table-sm table-borderless">';
                            foreach ($fields as $field) {
                                echo '<tr>';
                                echo '<td style="width:50%;" class="text-end">' . $config[$field]['label'] . '</td>';
                                echo '<td style="width:50%;">' . ('statement_year' == $field ? $statement[$field] : '<a href="' . $statement[$field] . '" class="btn btn-outline-primary btn-sm" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>') . '</td>';
                                echo '</tr>';
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