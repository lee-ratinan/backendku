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
                        <a class="btn btn-outline-primary btn-sm float-end ms-3" href="<?= base_url($session->locale . '/office/employment/cpf/create') ?>"><i class="fa-solid fa-plus-circle"></i> New CPF</a>
                        <h5 class="card-title"><?= $page_title ?></h5>
                        <div class="row mb-3">
                            <div class="col-6 col-md-4">
                                <label for="transaction_code" class="form-label">Transaction Code</label><br>
                                <select class="form-select form-select-sm" id="transaction_code">
                                    <option value="">All</option>

                                </select>
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="company_id" class="form-label">Company</label><br>
                                <select class="form-select form-select-sm" id="company_id">
                                    <option value="">All</option>
                                    <?php foreach ($companies as $country_code => $company): ?>
                                        <option value="<?= $company['id'] ?>"><?= $company['company_legal_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="year" class="form-label">Year</label><br>
                                <select class="form-select form-select-sm" id="year">
                                    <option value="">All</option>
                                    <?php for ($year = date('Y'); $year > 2019; $year--): ?>
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
                                    <th style="min-width:100px">Date</th>
                                    <th style="min-width:80px">Code</th>
                                    <th style="min-width:150px">OA Amount</th>
                                    <th style="min-width:150px">OA Balance</th>
                                    <th style="min-width:150px">SA Amount</th>
                                    <th style="min-width:150px">SA Balance</th>
                                    <th style="min-width:150px">MA Amount</th>
                                    <th style="min-width:150px">MA Balance</th>
                                    <th style="min-width:150px">Trx Total</th>
                                    <th style="min-width:150px">CPF Balance</th>
                                    <th style="min-width:100px">Month</th>
                                    <th style="min-width:180px">Company</th>
                                    <th style="min-width:150px">Staff Contribution</th>
                                    <th style="min-width:150px">Staff YTD</th>
                                    <th style="min-width:150px">Company Match</th>
                                    <th style="min-width:150px">Company YTD</th>
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
                searching: false,
                ajax: {
                    url: '<?= base_url($session->locale . '/office/employment/cpf') ?>',
                    type: 'POST',
                    data: function (d) {
                        d.year = $('#year').val();
                        d.transaction_code = $('#transaction_code').val();
                        d.company_id = $('#company_id').val();
                    },
                },
                order: [[2, 'asc']],
                columnDefs: [
                    {orderable: false, targets: 0},
                    {className: 'text-end', targets: [4,5,6,7,8,9,10,11,14,15,16,17] }
                ],
                fixedColumns: {start:4},
                scrollX: true,
                scrollY: 400,
            });
            $('#btn-filter').on('click', function () {
                table.ajax.reload();
            });
            $('#btn-reset').on('click', function () {
                $('#year').val('');
                $('#transaction_code').val('');
                $('#company_id').val('');
                table.ajax.reload();
            });
        });
    </script>
<?php $this->endSection() ?>