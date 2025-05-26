<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <style>
        .row-ordinary-account td{background-color:#437271!important;color:#222!important;}
        .row-special-account td{background-color:#DFB670!important;color:#222!important;}
        .row-medisave-account td{background-color:#7D9ADE!important;color:#222!important;}
    </style>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body pt-3">
                        <h2>Current Account Balance</h2>
                        <script>
                            <?php echo generate_pie_chart_script($chart_1, 'chart_1', 'account', 'value');?>
                        </script>
                        <div id="chart_1" style="width:100%;height:500px"></div>
                        <table class="table table-striped table-hover table-borderless table-sm mt-3">
                            <?php $total = 0; ?>
                            <?php foreach ($chart_1 as $row) : ?>
                            <tr class="row-<?= str_replace(' ', '-', strtolower($row['account'])) ?>">
                                <td><?= $row['account'] ?></td>
                                <td class="text-end"><?= currency_format('SGD', $row['value']) ?></td>
                                <?php $total += $row['value']; ?>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td><b>TOTAL</b></td>
                                <td class="text-end"><?= currency_format('SGD', $total) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body pt-3">
                        <h2>YTD Contributions</h2>
                        <script>
                            <?php echo generate_pie_chart_script($chart_2, 'chart_2', 'contributor', 'value');?>
                        </script>
                        <div id="chart_2" style="width:100%;height:500px"></div>
                        <table class="table table-striped table-hover table-borderless table-sm mt-3">
                            <?php $total = 0; ?>
                            <?php foreach ($chart_2 as $row) : ?>
                                <tr>
                                    <td><?= $row['contributor'] ?></td>
                                    <td class="text-end"><?= currency_format('SGD', $row['value']) ?></td>
                                    <?php $total += $row['value']; ?>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td><b>TOTAL</b></td>
                                <td class="text-end"><?= currency_format('SGD', $total) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $this->endSection() ?>