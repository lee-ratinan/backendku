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
            <div class="col">
                <div class="card">
                    <div class="card-body pt-3">
                        <div class="text-end">
                            <a class="btn btn-outline-primary btn-sm" href="<?= base_url($session->locale . '/office/document/create') ?>"><i class="fa-solid fa-plus-circle"></i> New Document</a>
                        </div>
                        <h5 class="card-title"><i class="fa-solid fa-suitcase fa-fw me-3"></i> <?= $page_title ?></h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover">
                                <thead>
                                <tr>
                                    <th style="min-width:180px">Document Title</th>
                                    <th style="min-width:150px">Created At</th>
                                    <th style="min-width:150px">Updated At</th>
                                    <th style="max-width:120px">Edit</th>
                                    <td style="max-width:120px">Public Document</td>
                                    <th style="max-width:120px">Internal Document</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = $('table').DataTable({
                processing: true,
                serverSide: true,
                fixedHeader: true,
                searching: true,
                pageLength:25,
                ajax: {
                    url: '<?= base_url($session->locale . '/office/document') ?>',
                    type: 'POST'
                },
                order: [[2, 'desc']],
                columnDefs: [{orderable: false, targets: [3,4,5]}],
                fixedColumns: {start:1},
                scrollX: true,
            });
        });
    </script>
<?php $this->endSection() ?>