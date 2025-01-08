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
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body pt-3">
                        <a class="btn btn-outline-primary btn-sm float-end ms-3" href="<?= base_url($session->locale . '/office/employment/cpf/create') ?>"><i class="fa-solid fa-plus-circle"></i> New CPF</a>
                        <h5 class="card-title"><i class="fa-solid fa-calculator fa-fw me-3"></i> <?= $page_title ?></h5>
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