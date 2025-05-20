<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <style>
        .chart {width: 100%; height: 500px;}
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
                        <?php foreach ($company_list as $company) : ?>
                            <a class="btn btn<?= ($company_id == $company['id'] ? '' : '-outline') ?>-success btn-sm mb-2" href="<?= base_url($lang . '/office/employment/salary/stats/company/' . $company['id']) ?>"><?= $company['company_trade_name'] ?></a>
                        <?php endforeach; ?>
                        <h3><?= $company['company_trade_name'] ?></h3>
                        <p>
                            <?= $currency_code ?> |
                            <?= date(DATE_FORMAT_UI, strtotime($company['employment_start_date'])) ?> to
                            <?= (empty($company['employment_end_date']) ? 'Present' : date(DATE_FORMAT_UI, strtotime($company['employment_end_date']))) ?>
                        </p>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <h4>Total Income By Year</h4>
                                <script>
                                    am5.ready(function() {
                                        let root = am5.Root.new("chart_1");
                                        root.setThemes([am5themes_Animated.new(root)]);
                                        let chart = root.container.children.push(am5xy.XYChart.new(root, {panX: false, panY: false, paddingLeft: 0, wheelX: "panX", wheelY: "zoomX", layout: root.verticalLayout}));
                                        let legend = chart.children.push(am5.Legend.new(root, {centerX: am5.p50, x: am5.p50}));
                                        let data = <?= json_encode($chart_data) ?>;
                                        let xRenderer = am5xy.AxisRendererX.new(root, {cellStartLocation: 0.1, cellEndLocation: 0.9, minorGridEnabled: true});
                                        let xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {categoryField: "year", renderer: xRenderer, tooltip: am5.Tooltip.new(root, {})}));
                                        xRenderer.grid.template.setAll({location: 1});
                                        xAxis.data.setAll(data);
                                        let yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {renderer: am5xy.AxisRendererY.new(root, {strokeOpacity: 0.1}), min: 0}));
                                        function makeSeries(name, fieldName) {
                                            let series = chart.series.push(am5xy.ColumnSeries.new(root, {name: name, xAxis: xAxis, yAxis: yAxis, valueYField: fieldName, categoryXField: "year"}));
                                            series.columns.template.setAll({tooltipText: "{name} of {categoryX} is <?= $currency_code ?> {valueY}", width: am5.percent(90), tooltipY: 0, strokeOpacity: 0});
                                            series.data.setAll(data);
                                            series.appear();
                                            series.bullets.push(function () {return am5.Bullet.new(root, {locationY: 0, sprite: am5.Label.new(root, {text: "{valueY}", fill: root.interfaceColors.get("alternativeText"), centerY: 0, centerX: am5.p50, populateText: true})});});
                                            legend.data.push(series);
                                        }
                                        makeSeries("Subtotal", "subtotal");
                                        makeSeries("Total", "total");
                                        chart.appear(1000, 100);
                                    });
                                </script>
                                <div class="chart" id="chart_1"></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <h4>Base Salaries</h4>
                                <script>
                                    am5.ready(function() {
                                        let root = am5.Root.new("chart_2");
                                        root.setThemes([am5themes_Animated.new(root)]);
                                        let chart = root.container.children.push(am5xy.XYChart.new(root, {panX: true, panY: true, wheelX: "panX", wheelY: "zoomX", pinchZoomX: true, paddingLeft:0, paddingRight:1}));
                                        let cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
                                        cursor.lineY.set("visible", false);
                                        let xRenderer = am5xy.AxisRendererX.new(root, {minGridDistance: 30, minorGridEnabled: true});
                                        xRenderer.labels.template.setAll({rotation: -90, centerY: am5.p50, centerX: am5.p100, paddingRight: 15});
                                        xRenderer.grid.template.setAll({location: 1});
                                        let xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {maxDeviation: 0.3, categoryField: "month", renderer: xRenderer, tooltip: am5.Tooltip.new(root, {})}));
                                        let yRenderer = am5xy.AxisRendererY.new(root, {strokeOpacity: 0.1});
                                        let yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {maxDeviation: 0.3, renderer: yRenderer, min: 0}));
                                        let series = chart.series.push(am5xy.ColumnSeries.new(root, {name: "Promotion", xAxis: xAxis, yAxis: yAxis, valueYField: "base", sequencedInterpolation: true, categoryXField: "month", tooltip: am5.Tooltip.new(root, {labelText: "{valueY}"})}));
                                        series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
                                        series.columns.template.adapters.add("fill", function (fill, target) {return chart.get("colors").getIndex(series.columns.indexOf(target));});
                                        series.columns.template.adapters.add("stroke", function (stroke, target) {return chart.get("colors").getIndex(series.columns.indexOf(target));});
                                        let data = <?= json_encode($chart_data_2) ?>;
                                        xAxis.data.setAll(data);
                                        series.data.setAll(data);
                                        series.appear(1000);
                                        chart.appear(1000, 100);
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
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($chart_data as $row) : ?>
                                <tr>
                                    <td><?= $row['year'] ?></td>
                                    <td class="text-end"><?= currency_format($currency_code, $row['subtotal']) ?></td>
                                    <td class="text-end"><?= currency_format($currency_code, $row['total']) ?></td>
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