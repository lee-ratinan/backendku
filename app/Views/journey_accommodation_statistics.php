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
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/journey/accommodation') ?>">Accommodation</a></li>
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h2>Accommodation By Year</h2>
                        <table class="table table-sm table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="max-width:40px">Year</th>
                                <th style="min-width:200px"></th>
                                <th class="text-end" style="max-width:80px">Nights</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for ($year = date('Y'); $year >= 1989; $year--) : ?>
                                <tr>
                                    <?php $class_set = $color_classes[$year % 4]; ?>
                                    <?php $annual_sum = 0; ?>
                                    <td><?= $year ?></td>
                                    <td>
                                        <?php if (isset($by_year[$year])) : ?>
                                            <?php foreach ($by_year[$year] as $country_code => $list) : ?>
                                                <span class="badge text-bg-primary rounded-pill">= <?= array_sum($list) ?> nights</span>
                                                <span class="flag-icon flag-icon-<?= strtolower($country_code) ?>"></span> <?= $countries[$country_code]['common_name'] ?>
                                                <?php foreach ($list as $i => $nights) : ?>
                                                    <span class="badge bg-<?= $class_set[$i % 2] ?> rounded-pill"><?= $nights ?></span>
                                                    <?php $annual_sum += $nights; ?>
                                                <?php endforeach; ?>
                                                <br>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end"><?= (0 < $annual_sum ? '<h6>' . number_format($annual_sum) . '</h6>' : '') ?></td>
                                </tr>
                            <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h2>Accommodation By Country</h2>
                        <table class="table table-sm table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="max-width:100px">Country</th>
                                <th style="min-width:200px"></th>
                                <th class="text-end" style="max-width:80px">Nights</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $row = 0; ?>
                            <?php foreach ($by_country as $country_code => $list) : ?>
                                <?php $class_set = $color_classes[$row % 4]; $row++; ?>
                                <tr>
                                    <td>
                                        <span class="flag-icon flag-icon-<?= strtolower($country_code) ?>"></span> <?= $countries[$country_code]['common_name'] ?>
                                    </td>
                                    <td>
                                        <?= count($list) ?> times:
                                        <?php foreach ($list as $i => $nights) : ?>
                                            <span class="badge bg-<?= $class_set[$i % 2] ?> rounded-pill"><?= $nights ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td class="text-end"><h6><?= array_sum($list) ?></h6></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $this->endSection() ?>