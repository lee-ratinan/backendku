<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <style>
        .chart {width: 100%; height: 600px;}
    </style>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
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
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <?php foreach ($currency_list as $currency) : ?>
                            <a class="btn btn<?= ($currency == $currency_code ? '' : '-outline') ?>-success btn-sm mb-2" href="<?= base_url($lang . '/office/employment/salary/stats/currency/' . $currency) ?>"><?= $currency ?></a>
                        <?php endforeach; ?>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <h4>Total Income By Year</h4>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        <?php echo generate_bar_chart_script($chart_data, 'chart_1', 'year', ['total' => 'Total', 'subtotal' => 'Subtotal'], '90vh'); ?>
                                    });
                                </script>
                                <div class="chart" id="chart_1"></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <h4>Base Salary By Year</h4>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        <?php echo generate_bar_chart_script($chart_data_2, 'chart_2', 'year', ['base' => 'Base Salary'], '90vh'); ?>
                                    });
                                </script>
                                <div class="chart" id="chart_2"></div>
                            </div>
                        </div>
                        <h4 class="mt-3">Summary</h4>
                        <div class="table-responsive">
                            <table id="summary" class="table table-striped table-hover table-borderless">
                                <thead>
                                <tr>
                                    <th>Year</th>
                                    <th class="text-end">Highest Base Pay</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($salary_by_year as $year => $salary) : ?>
                                <tr>
                                    <td><?= $year ?></td>
                                    <td class="text-end"><?= currency_format($currency_code, $max_bases[$year]) ?></td>
                                    <td class="text-end"><?= currency_format($currency_code, $salary['subtotal']) ?></td>
                                    <td class="text-end"><?= currency_format($currency_code, $salary['total']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('table').DataTable({
                fixedHeader: true,
                searching: false, // don't allow the search for this one
                pageLength: 25,
                scrollX: true,
                order: [[0, 'desc']],
            });
        });
    </script>
<?php $this->endSection() ?>