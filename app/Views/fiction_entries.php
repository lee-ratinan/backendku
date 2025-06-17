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
                            <div class="col-md-8 col-xl-9">
                                <h2><?= $title['fiction_title'] ?></h2>
                                <p>
                                    by <?= $title['pen_name'] ?> |
                                    <?= $title['fiction_genre'] ?> |
                                    Last updated: <span class="utc-to-local"><?= $title['updated_at'] ?></span> |
                                    Word count: <?= number_format($word_count) ?>
                                </p>
                                <hr />
                                <div class="row">
                                    <div class="col"><a class="btn btn-outline-primary btn-sm" href="<?= base_url($session->locale . '/office/fiction/new-entry/' . $title_id) ?>"><i class="fa-solid fa-circle-plus"></i> New Entry</a></div>
                                    <div class="col text-end"><a class="btn btn-outline-primary btn-sm" href="<?= base_url($session->locale . '/office/fiction/export-pdf/' . $title['fiction_slug']) ?>" target="_blank"><i class="fa-solid fa-file-pdf"></i> Export Manuscript</a></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover table-striped">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" style="min-width:100px" class="text-center">Type</th>
                                            <th rowspan="2" style="min-width:225px" class="text-center">Title</th>
                                            <th colspan="2" style="min-width:200px" class="text-center">Count</th>
                                            <th rowspan="2" style="min-width:120px" class="text-center">Status</th>
                                            <th rowspan="2" style="min-width:250px" class="text-center">Short Note</th>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Word</th>
                                            <th class="text-end">Character</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($entries as $entry) : ?>
                                            <tr>
                                                <td><?= $types[$entry['data']['entry_type']] ?></td>
                                                <td>
                                                    <a href="<?= base_url($session->locale . '/office/fiction/edit-entry/' . ($entry['data']['id']*$nonce)) ?>">
                                                        [<?= $entry['data']['entry_position'] ?>] <?= $entry['data']['entry_title'] ?>
                                                    </a>
                                                </td>
                                                <td class="text-end"><?= (0 < $entry['data']['word_count'] ? number_format($entry['data']['word_count']) : '-') ?></td>
                                                <td class="text-end"><?= (0 < $entry['data']['char_count'] ? number_format($entry['data']['char_count']) : '-') ?></td>
                                                <td><?= $statuses[$entry['data']['entry_status']] ?></td>
                                                <td><?= $entry['data']['entry_short_note'] ?></td>
                                            </tr>
                                            <?php if (isset($entry['children'])) : ?>
                                                <?php foreach ($entry['children'] as $child) : ?>
                                                    <tr>
                                                        <td><?= $types[$child['entry_type']] ?></td>
                                                        <td class="ps-4">
                                                            <a href="<?= base_url($session->locale . '/office/fiction/edit-entry/' . ($child['id']*$nonce)) ?>">
                                                                [<?= $entry['data']['entry_position'] ?>.<?= $child['entry_position'] ?>] <?= $child['entry_title'] ?>
                                                            </a>
                                                        </td>
                                                        <td class="text-end"><?= (0 < $child['word_count'] ? number_format($child['word_count']) : '-') ?></td>
                                                        <td class="text-end"><?= (0 < $child['char_count'] ? number_format($child['char_count']) : '-') ?></td>
                                                        <td><?= $statuses[$child['entry_status']] ?></td>
                                                        <td><?= $child['entry_short_note'] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a class="btn btn-outline-primary btn-sm" href="<?= base_url($session->locale . '/office/fiction/new-entry/' . $title_id) ?>"><i class="fa-solid fa-circle-plus"></i> New Entry</a>
                            </div>
                            <div class="col-md-4 col-xl-3">
                                <img class="img-fluid mb-2" src="<?= base_url('file/fiction_' . $title['fiction_slug'] . '.jpg') ?>" alt="<?= $title['fiction_title'] ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $this->endSection() ?>