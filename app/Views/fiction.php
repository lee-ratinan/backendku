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
                        <a class="btn btn-outline-primary btn-sm float-end ms-3" href="<?= base_url($session->locale . '/office/fiction/create') ?>"><i class="fa-solid fa-plus-circle"></i> New Fiction</a>
                        <h5 class="card-title"><i class="fa-solid fa-location-dot fa-fw me-3"></i> <?= $page_title ?></h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Penname</th>
                                    <th>Title</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($titles as $fiction) : ?>
                                <tr>
                                    <td><?= $fiction['id'] ?></td>
                                    <td><?= $fiction['id'] ?></td>
                                    <td><?= $fiction['pen_name'] ?></td>
                                    <td><?= $fiction['fiction_title'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('table').DataTable({
                processing: false,
                serverSide: false,
                fixedHeader: true,
                searching: true,
                pageLength: 10,
                columnDefs: [{orderable: false, targets: 0}],
            });
        });
    </script>
<?php $this->endSection() ?>