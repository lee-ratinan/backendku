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
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <?php foreach ($documents as $row) : ?>
                <div class="col">
                    <div class="card">
                        <?php
                        if ('passport' == $row['document_type']) {
                            $image = 'profile-header-passport.jpg';
                        } else {
                            $image = 'profile-header-' . strtolower($row['country_code']) . '.jpg';
                        }
                        ?>
                        <img src="<?= base_url('file/' . $image)?>" class="card-img-top" alt="<?= $row['document_title'] ?>">
                        <div class="card-body pt-3">
                            <h6 class="card-title"><?= $row['document_title'] ?></h6>
                            <?php
                            $links = json_decode($row['google_drive_link'], true);
                            if (!empty($links)) {
                                foreach ($links as $key => $link) {
                                    echo '<a class="btn btn-outline-primary btn-sm me-3 my-2" href="' . $link . '" target="_blank">' . $key . '</a>';
                                }
                            }
                            ?>
                            <div><?= $document_types[$row['document_type']] ?></div>
                            <div class="row">
                                <div class="col-4 text-end">#</div>
                                <div class="col-8"><?= $row['document_number'] ?></div>
                                <div class="col-4 text-end">Issued</div>
                                <div class="col-8"><?= date(DATE_FORMAT_UI, strtotime($row['issued_date'])) ?></div>
                                <div class="col-4 text-end">Expiry</div>
                                <div class="col-8"><?= empty($row['expiry_date']) ? '-' : date(DATE_FORMAT_UI, strtotime($row['expiry_date'])) ?></div>
                            </div>
                            <hr>
                            <?= $row['other_notes'] ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php $this->endSection() ?>