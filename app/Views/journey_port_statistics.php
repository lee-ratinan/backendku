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
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/journey/port') ?>">Port</a></li>
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Visits by Year</h5>
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th style="max-width:80px;">Year</th>
                                <th>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $c = 0; ?>
                            <?php for ($year = date('Y'); $year >= 2006; $year--): ?>
                                <tr>
                                    <td><?= $year ?></td>
                                    <td>
                                        <?php if (isset($by_year[$year])): ?>
                                            <?php arsort($by_year[$year]) ?>
                                            <?php foreach ($by_year[$year] as $port_id => $count) : ?>
                                                <?php $color_set = $colors[$c%4]; $c++; ?>
                                                <span class="float-end"><b><?= $ports[$port_id]['name'] ?> (<?= $count ?>)</b> <?= explode('</i> ', $modes[$ports[$port_id]['type']])[0] . '</i>' ?> <span class="flag-icon flag-icon-<?= strtolower($ports[$port_id]['country_code']) ?>"></span></span>
                                                <?php for ($i = 0; $i < $count; $i++) : ?>
                                                    <i class="fa-solid fa-circle text-<?= $color_set[$i%2] ?>"></i>
                                                <?php endfor; ?>
                                                <br>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
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
                    <div class="card-body">
                        <h5 class="card-title">Visits by Port</h5>
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                            <tr>
                                <th style="min-width:120px;">Port</th>
                                <th>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $c = 0; ?>
                            <?php foreach ($by_port as $port_id => $count) : ?>
                                <?php $color_set = $colors[$c%4]; $c++; ?>
                                <tr>
                                    <td>
                                        <span class="flag-icon flag-icon-<?= strtolower($ports[$port_id]['country_code']) ?>"></span> <?= explode('</i> ', $modes[$ports[$port_id]['type']])[0] . '</i>' ?> <?= $ports[$port_id]['code'] ? '<b>' . $ports[$port_id]['code'] . '</b>' : '' ?><br>
                                        <b><?= $ports[$port_id]['name'] ?></b>
                                    </td>
                                    <td>
                                        <?php for ($i = 0; $i < $count; $i++) : ?>
                                            <i class="fa-solid fa-circle text-<?= $color_set[$i%2] ?>"></i>
                                        <?php endfor; ?>
                                        (<?= $count ?>)
                                    </td>
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