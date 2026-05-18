<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <!--    <script src="--><?php //= base_url('assets/vendor/amcharts5/index.js') ?><!--"></script>-->
    <!--    <script src="--><?php //= base_url('assets/vendor/amcharts5/xy.js') ?><!--"></script>-->
    <!--    <script src="--><?php //= base_url('assets/vendor/amcharts5/percent.js') ?><!--"></script>-->
    <!--    <script src="--><?php //= base_url('assets/vendor/amcharts5/themes/Animated.js') ?><!--"></script>-->
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
            <div class="col-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <h5 class="card-title"><i class="fa-solid fa-piggy-bank fa-fw me-3"></i> <?= $page_title ?></h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <h3>Total Investment Deducted:</h3>
                                <h2 class="text-center"><?= currency_format('SGD', $total_investment_deducted) ?></h2>
                            </div>
                            <div class="col-12 col-md-6">
                                <h3>Total Fees</h3>
                                <h2 class="text-center"><?= currency_format('SGD', $total_fees) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $this->endSection() ?>