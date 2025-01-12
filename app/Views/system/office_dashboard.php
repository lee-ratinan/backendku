<?php
$layout = getenv('LAYOUT_FILE_OFFICE');
$layout = (!empty($layout) ? $layout : 'system/_layout_office');
$this->extend($layout);
?>
<?= $this->section('content') ?>
    <?php $session = session(); ?>
    <div class="pagetitle">
        <h1><?= lang('System.dashboard.welcome', [$session->display_name]) ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><?= $page_title ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2>Your Roles</h2>
                        <p>You are logged in as</p>
                        <div class="badge text-bg-secondary w-100 p-3 mb-3">
                            <h4><?= get_role_icons($session->current_role, TRUE) ?></h4>
                        </div>
                        <h3>Switch Role</h3>
                        <div class="row g-3">
                            <?php foreach ($session->roles as $role): ?>
                                <div class="col-6 col-lg-4">
                                    <button class="btn btn-outline-primary w-100 p-3 btn-switch-role <?= ($role == $session->current_role ? 'disabled' : '') ?>" data-role="<?= $role ?>" <?= ($role == $session->current_role ? 'disabled' : '') ?>><?= get_role_icons($role, TRUE) ?></button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.btn-switch-role').on('click', function (e) {
                e.preventDefault();
                let new_role = $(this).data('role');
                $.ajax({
                    url: '<?= base_url($session->locale . '/office/switch-role') ?>',
                    type: 'POST',
                    data: {role: new_role},
                    success: function (response) {
                        toastr.success('<?= lang('System.switch_role.switched') ?>');
                        setTimeout(function () {
                            window.location.href = '<?= base_url($session->locale . '/office/dashboard') ?>';
                        }, 3000);
                    },
                    error: function () {
                        toastr.error('<?= lang('System.status_message.generic_error') ?>');
                    }
                });
            });
        });
    </script>
<?php $this->endSection() ?>