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
                <?php if ($year == 0) : ?>
                    <li class="breadcrumb-item active"><?= $page_title ?></li>
                <?php else : ?>
                    <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/profile/plan') ?>">Plan</a></li>
                    <li class="breadcrumb-item active"><?= $year ?></li>
                <?php endif; ?>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col">
                <a class="btn btn-<?= (2030 == $year ? '' : 'outline-') ?>primary" href="<?= base_url($session->locale . '/office/profile/plan/2030') ?>">2030</a>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col">
                <?php if (0 != $year) : ?>
                    <?php include 'profile_plan_' . $year . '.php'; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $this->endSection() ?>