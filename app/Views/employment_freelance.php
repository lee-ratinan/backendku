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
                        <a class="btn btn-outline-primary btn-sm float-end ms-3" href="<?= base_url($session->locale . '/office/employment/freelance/create') ?>"><i class="fa-solid fa-plus-circle"></i> New Freelance Project</a>
                        <h5 class="card-title"><i class="fa-solid fa-laptop-code fa-fw me-3"></i> <?= $page_title ?></h5>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="company_id" class="form-label">Company</label><br>
                                <select class="form-select form-select-sm" id="company_id">
                                    <option value="">All</option>
                                    <?php foreach ($companies as $country_code => $group): ?>
                                        <optgroup label="<?= lang('ListCountries.countries.' . $country_code . '.common_name') ?>">
                                            <?php foreach ($group as $company) : ?>
                                                <option value="<?= $company['id'] ?>"><?= $company['name'] ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="year" class="form-label">Year</label><br>
                                <select class="form-select form-select-sm" id="year">
                                    <option value="">All</option>
                                    <?php for ($year = date('Y'); $year > 2009; $year--): ?>
                                        <option value="<?= $year ?>"><?= $year ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col text-end">
                                <button id="btn-reset" class="btn btn-sm btn-outline-primary">Reset</button>
                                <button id="btn-filter" class="btn btn-sm btn-primary">Filter</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Company</th>
                                    <th>Project Title</th>
                                    <th>Client Name</th>
                                    <th>Client’s Organization</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
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
                ajax: {
                    url: '<?= base_url($session->locale . '/office/employment/freelance') ?>',
                    type: 'POST',
                    data: function (d) {
                        d.company_id = $('#company_id').val();
                        d.year = $('#year').val();
                    }
                },
                order: [[2, 'asc']],
                columnDefs: [{orderable: false, targets: 0}],
                drawCallback: function () {
                    let DateTime = luxon.DateTime;
                    $('.utc-to-local-time').each(function () {
                        const utc = $(this).text();
                        if ('' !== utc) {
                            $(this).text(DateTime.fromISO(utc).toLocaleString(DateTime.DATETIME_MED));
                        } else {
                            $(this).text('-');
                        }
                    });
                },
            });
            $('#btn-filter').on('click', function () {
                table.ajax.reload();
            });
            $('#btn-reset').on('click', function () {
                $('#country_code').val('');
                $('#year').val('');
                $('#journey_status').val('');
                $('#mode_of_transport').val('');
                table.ajax.reload();
            });
        });
    </script>
<?php $this->endSection() ?>