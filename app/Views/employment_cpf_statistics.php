<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
?>
<?= $this->section('content') ?>
<?php $session = session(); ?>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
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
                        <h3>Summary and Statement</h3>
                        <?php for ($y = 2020; $y <= date('Y'); $y++) : ?>
                            <a class="btn btn-<?= ($y == $year ? '' : 'outline-') ?>success btn-sm mb-2" href="<?= base_url($session->locale . '/office/employment/cpf/stats/' . $y) ?>"><?= $y ?></a>
                        <?php endfor; ?>
                        <h4>Contribution</h4>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover table-borderless">
                                <?php foreach ($contribution as $row) : ?>
                                <tr>
                                    <td><?= $row['contributor'] ?></td>
                                    <td class="text-end text-success"><?= currency_format('SGD', $row['amount']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <h4>Transactions</h4>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover table-borderless">
                                <?php foreach ($by_tc as $row) : ?>
                                    <tr>
                                        <td><?= $row['transaction_code'] ?></td>
                                        <td class="text-end text-success"><?= currency_format('SGD', @$row['pos']) ?></td>
                                        <td class="text-end text-danger"><?= currency_format('SGD', @$row['neg']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <h4>Account Movements</h4>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover table-borderless">
                                <?php foreach ($by_ac as $row) : ?>
                                    <tr>
                                        <td><?= $row['account'] ?></td>
                                        <td class="text-end text-success"><?= currency_format('SGD', @$row['pos']) ?></td>
                                        <td class="text-end text-danger"><?= currency_format('SGD', @$row['neg']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <h4>Statement</h4>
                        <?php if (empty($statement)) : ?>
                            <p>n/a</p>
                        <?php else: ?>
                            <a href="<?= $statement['google_drive_url'] ?>" target="_blank" class="btn btn-outline-success btn-sm"><i class="fa-solid fa-file-pdf"></i> View Statement</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body pt-3">
                        <h3>Total Contribution</h3>
                        <script>
                            <?php echo generate_pie_chart_script($contribution, 'total-contribution', 'contributor', 'amount'); ?>
                        </script>
                        <div id="total-contribution" style="width:100%;height:500px"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body pt-3">
                        <h3>Summary by Transaction Code</h3>
                        <script>
                            <?php
                            $height = ((count($by_tc) * 60) + 100) . 'px';
                            echo generate_bar_chart_script($by_tc, 'summary-by-tc', 'transaction_code', ['neg' => 'Withdrawal', 'pos' => 'Deposit'], $height);
                            ?>
                        </script>
                        <div id="summary-by-tc"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body pt-3">
                        <h3>Summary by Account</h3>
                        <script>
                            <?php
                            $height = ((count($by_ac) * 60) + 100). 'px';
                            echo generate_bar_chart_script($by_ac, 'summary-by-account', 'account', ['neg' => 'Withdrawal', 'pos' => 'Deposit'], $height); ?>
                        </script>
                        <div id="summary-by-account"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $this->endSection() ?>