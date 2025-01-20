<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
helper('math');
?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <div class="pagetitle">
        <h1><?= $page_title ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/dashboard') ?>"><?= lang('System.dashboard.page_title') ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/journey/transport') ?>">Transport</a></li>
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2>Flights By Year</h2>
                        <table class="table table-sm table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="max-width:40px">Year</th>
                                <th style="max-width:40px">#</th>
                                <th style="max-width:60px">km</th>
                                <th style="min-width:220px"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for ($year = date('Y'); $year >= 2006; $year--) : ?>
                                <tr>
                                    <?php $class_set = $color_classes[$year % 4]; ?>
                                    <td class="text-center"><h6><?= $year ?></h6></td>
                                    <td class="text-center"><h6><?= (!empty($distant_by_year[$year]) ? count($distant_by_year[$year]) : '-') ?></h6></td>
                                    <td class="text-end"><?= (in_array($year, [2020, 2021, 2022]) ? '<i class="fa-solid fa-viruses"></i>' : '') ?><?= (isset($distant_by_year_sum[$year]) ? number_format($distant_by_year_sum[$year]) : '') ?></td>
                                    <td>
                                        <div class="progress-stacked">
                                            <?php if (isset($distant_by_year[$year])) : ?>
                                                <?php foreach ($distant_by_year[$year] as $i => $flight) : ?>
                                                    <?php $percent = round($flight / $distant_by_year_max * 100); ?>
                                                    <div class="progress" role="progressbar" aria-label="flight <?= $i ?>" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent ?>%"><div class="progress-bar bg-<?= $class_set[$i % 2] ?>"></div></div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <h6 class="display-1"><i class="fa-solid fa-plane-departure small"></i> <?= number_format($generic_stats['count_by_mode']['airplane']) ?></h6>
                        <h3>Counts</h3>
                        <div class="row">
                            <?php
                            $total = [
                                'count'   => 0,
                                'distant' => 0,
                            ];
                            ?>
                            <?php foreach ($modes_of_transport as $key => $name): ?>
                                <?php
                                $total['count']   += $generic_stats['count_by_mode'][$key];
                                $total['distant'] += $generic_stats['distant_by_mode'][$key];
                                ?>
                                <div class="col-5"><h4><?= $name ?></h4></div>
                                <div class="col-2"><?= number_format($generic_stats['count_by_mode'][$key]) ?></div>
                                <div class="col-5">
                                    <?= number_format($generic_stats['distant_by_mode'][$key]) ?> km |
                                    <?= number_format(kmToMiles($generic_stats['distant_by_mode'][$key])) ?> mi
                                </div>
                            <?php endforeach; ?>
                            <div class="col-5"><h4>Total</h4></div>
                            <div class="col-2"><b><?= number_format($total['count']) ?></b> trips</div>
                            <div class="col-5">
                                <?= number_format($total['distant']) ?> km |
                                <?= number_format(kmToMiles($total['distant'])) ?> mi
                            </div>
                            <div class="col-12">
                                <h6>I TRAVELED</h6>
                                <h6 class="display-1"><?= number_format($total['distant']/40075, 2) ?></h6>
                                <h6>ROUND THE GLOBE</h6>
                                <h6><b><?= number_format($generic_stats['distant_by_mode']['airplane']/40075, 2) ?></b> on the plane</h6>
                            </div>
                        </div>
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