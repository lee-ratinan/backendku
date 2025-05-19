<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <style>
        #main-chart {width: 100%;height: 500px;}
        #country-company, #country-day {width: 100%;height: 300px;}
    </style>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3>Days</h3>
                        <div class="table-responsive">
                            <table id="main-table" class="table table-sm table-striped table-hover">
                                <thead>
                                <tr>
                                    <th style="min-width:100px">Country</th>
                                    <th style="min-width:150px">Company</th>
                                    <th style="min-width:100px">From</th>
                                    <th style="min-width:100px">To</th>
                                    <th style="min-width:80px" class="text-end">Days</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($duration as $company) : ?>
                                    <tr>
                                        <td><?= lang('ListCountries.countries.' . $company['country'] . '.common_name') ?></td>
                                        <td><?= $company['name'] ?></td>
                                        <td data-sort="<?= $company['dates'][0] ?>"><?= date(DATE_FORMAT_UI, strtotime($company['dates'][0])) ?></td>
                                        <td data-sort="<?= @$company['dates'][1] ?>"><?= (empty($company['dates'][1]) ? '-' : date(DATE_FORMAT_UI, strtotime($company['dates'][1]))) ?></td>
                                        <td class="text-end" data-sort="<?= $company['days'] ?>"><?= number_format($company['days']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3>Days</h3>
                        <script>
                            am5.ready(function() {
                                let root = am5.Root.new("main-chart");
                                root.setThemes([am5themes_Animated.new(root)]);
                                let chart = root.container.children.push(am5xy.XYChart.new(root, {panX: true, panY: true, wheelX: "panX", wheelY: "zoomX", pinchZoomX: true, paddingLeft:0, paddingRight:1}));
                                let cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
                                cursor.lineY.set("visible", false);
                                let xRenderer = am5xy.AxisRendererX.new(root, {minGridDistance: 30, minorGridEnabled: true});
                                xRenderer.labels.template.setAll({rotation: -90, centerY: am5.p50, centerX: am5.p100, paddingRight: 15});
                                xRenderer.grid.template.setAll({location: 1})
                                let xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {maxDeviation: 0.3, categoryField: "name", renderer: xRenderer, tooltip: am5.Tooltip.new(root, {})}));
                                let yRenderer = am5xy.AxisRendererY.new(root, {strokeOpacity: 0.1});
                                let yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {maxDeviation: 0.3, renderer: yRenderer}));
                                let series = chart.series.push(am5xy.ColumnSeries.new(root, {
                                    name: "Company",
                                    xAxis: xAxis,
                                    yAxis: yAxis,
                                    valueYField: "days",
                                    sequencedInterpolation: true,
                                    categoryXField: "name",
                                    tooltip: am5.Tooltip.new(root, {labelText: "{days} days"})
                                }));
                                series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
                                series.columns.template.adapters.add("fill", function (fill, target) {return chart.get("colors").getIndex(series.columns.indexOf(target));});
                                series.columns.template.adapters.add("stroke", function (stroke, target) {return chart.get("colors").getIndex(series.columns.indexOf(target));});
                                let data = <?= json_encode($duration) ?>;
                                xAxis.data.setAll(data);
                                series.data.setAll(data);
                                series.appear(1000);
                                chart.appear(1000, 100);
                            });
                        </script>
                        <div id="main-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h3>By Country</h3>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Country</th>
                                    <th class="text-end">Companies</th>
                                    <th class="text-end">Days</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($country_days as $country_code => $days) : ?>
                                <tr>
                                    <td><?= lang('ListCountries.countries.' . $country_code . '.common_name') ?></td>
                                    <td class="text-end"><?= number_format($country_companies[$country_code]) ?></td>
                                    <td class="text-end"><?= number_format($days) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3>By Country</h3>
                        <h4>Companies by country</h4>
                        <script>
                            am5.ready(function () {
                                let root = am5.Root.new("country-company");
                                root.setThemes([am5themes_Animated.new(root)]);
                                let chart = root.container.children.push(am5percent.PieChart.new(root, {endAngle: 270}));
                                let series = chart.series.push(am5percent.PieSeries.new(root, {
                                    valueField: "companies",
                                    categoryField: "country",
                                    endAngle: 270
                                }));
                                series.states.create("hidden", {endAngle: -90});
                                series.data.setAll(<?= json_encode($charts) ?>);
                                series.appear(1000, 100);
                            });
                        </script>
                        <div id="country-company"></div>
                        <hr />
                        <h4>Days by country</h4>
                        <script>
                            am5.ready(function () {
                                let root = am5.Root.new("country-day");
                                root.setThemes([am5themes_Animated.new(root)]);
                                let chart = root.container.children.push(am5percent.PieChart.new(root, {endAngle: 270}));
                                let series = chart.series.push(am5percent.PieSeries.new(root, {
                                    valueField: "days",
                                    categoryField: "country",
                                    endAngle: 270
                                }));
                                series.states.create("hidden", {endAngle: -90});
                                series.data.setAll(<?= json_encode($charts) ?>);
                                series.appear(1000, 100);
                            });
                        </script>
                        <div id="country-day"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#main-table').DataTable({
                searching: false,
                paging: false,
                info: false,
                order: [4, 'desc'],
            });
        });
    </script>
<?php $this->endSection() ?>