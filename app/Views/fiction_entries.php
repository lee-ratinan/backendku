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
                <li class="breadcrumb-item"><a href="<?= base_url($session->locale . '/office/fiction') ?>">Fiction</a></li>
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body pt-3">
                        <div class="row">
                            <div class="col-md-4 col-xl-3">
                                <img class="img-fluid mb-2" src="<?= base_url('file/fiction_' . $title['fiction_slug'] . '.png') ?>" alt="<?= $title['fiction_title'] ?>" />
                            </div>
                            <div class="col-md-8 col-xl-9">
                                <h2><?= $title['fiction_title'] ?></h2>
                                <p>by <?= $title['pen_name'] ?> | <?= $title['fiction_genre'] ?> | Last updated: <span class="utc-to-local"><?= $title['updated_at'] ?></span></p>
                                <hr />
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover table-striped">
                                        <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Title</th>
                                            <th>Short Note</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($entries as $entry) : ?>
                                            <tr>
                                                <td><?= $types[$entry['entry_type']] ?></td>
                                                <td>
                                                    <a href="<?= base_url($session->locale . '/office/fiction/edit-entry/' . ($entry['id']*$nonce)) ?>">
                                                        [<?= $entry['entry_position'] ?>] <?= $entry['entry_title'] ?>
                                                    </a>
                                                </td>
                                                <td><?= $entry['entry_short_note'] ?></td>
                                                <td><?= $statuses[$entry['entry_status']] ?></td>
                                            </tr>
                                            <?php if (isset($entry['children'])) : ?>
                                                <?php foreach ($entry['children'] as $child) : ?>
                                                    <tr>
                                                        <td><?= $types[$child['entry_type']] ?></td>
                                                        <td class="ps-4">
                                                            <a href="<?= base_url($session->locale . '/office/fiction/edit-entry/' . ($child['id']*$nonce)) ?>">
                                                                [<?= $entry['entry_position'] ?>.<?= $child['entry_position'] ?>] <?= $child['entry_title'] ?>
                                                            </a>
                                                        </td>
                                                        <td><?= $child['entry_short_note'] ?></td>
                                                        <td><?= $statuses[$child['entry_status']] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $this->endSection() ?>