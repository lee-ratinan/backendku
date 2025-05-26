<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
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
                            <?php foreach ($chart_1 as $row) : ?>
                            <tr>
                                <td><?= $row['account'] ?></td>
                                <td><?= currency_format('SGD', $row['value']) ?></td>
                            </tr>
                            <?php endforeach; ?>
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
                            <?php foreach ($chart_2 as $row) : ?>
                                <tr>
                                    <td><?= $row['contributor'] ?></td>
                                    <td><?= currency_format('SGD', $row['value']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $this->endSection() ?>