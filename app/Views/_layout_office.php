<?php $session = session(); ?>
<!DOCTYPE html>
<?php /*
  HOW TO USE: 
  data-layout: fluid (default), boxed
  data-sidebar-theme: dark (default), colored, light
  data-sidebar-position: left (default), right
  data-sidebar-behavior: sticky (default), fixed, compact
*/ ?>
<html lang="en" data-bs-theme="dark" data-layout="fluid" data-sidebar-theme="dark" data-sidebar-position="left" data-sidebar-behavior="sticky">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 5 Admin &amp; Dashboard Template">
    <meta name="author" content="Bootlab">
    <title><?= $page_title ?> | <?= $session->app_name ?></title>
    <link href="<?= base_url('file/favicon.jpg') ?>" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Thai&family=Oxanium:wght@700&family=Poppins:ital@0;1&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/DataTables/datatables.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/flag-icon/css/flag-icon.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/toastrjs/toastr.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('appstack/css/app.css') ?>" rel="stylesheet" />
    <style>
        h1,h2,h3,h4,h5,h6,th{font-family:"Oxanium","Noto Serif Thai",sans-serif;} .alert{padding:1rem;}  svg:not(:host).svg-inline--fa, svg:not(:root).svg-inline--fa {overflow: visible;box-sizing: content-box;margin: auto 0.25rem;}
        .sidebar-header .avatar-img, .sidebar-header .avatar-txt {width: 3rem !important;height: 3rem !important;}
        table.dataTable tbody tr>.dtfc-fixed-start, table.dataTable tbody tr>.dtfc-fixed-end {background-color:#202634;}
        table.dataTable tbody tr:nth-of-type(odd)>.dtfc-fixed-start, table.dataTable tbody tr:nth-of-type(odd)>.dtfc-fixed-end {background-color:#323c4c!important;}
        table.dataTable thead tr>.dtfc-fixed-start, table.dataTable thead tr>.dtfc-fixed-end, table.dataTable tfoot tr>.dtfc-fixed-start, table.dataTable tfoot tr>.dtfc-fixed-end {background-color:#292f43;}
        .bg-red {background-color: #740001 !important;} .bg-gold {background-color: #D3A625 !important;color:#000!important;} .bg-yellow {background-color: #FFD800 !important;color:#000!important;} .bg-black {background-color: #000000 !important;} .bg-blue {background-color: #0E1A40 !important;} .bg-bronze {background-color: #946B2D !important;} .bg-green {background-color: #1A472A !important;} .bg-silver {background-color: #5D5D5D !important;}
        .text-red {color: #740001 !important;} .text-gold {color: #D3A625 !important} .text-yellow {color: #FFD800 !important} .text-black {color: #000000 !important;} .text-blue {color: #0E1A40 !important;} .text-bronze {color: #946B2D !important;} .text-green {color: #1A472A !important;} .text-silver {color: #5D5D5D !important;}
    </style>
</head>
<body>
<div class="wrapper">
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-content js-simplebar">
            <!-- SIDEBAR NAVIGATION -->
            <ul class="sidebar-nav">
                <li class="sidebar-header p-0">
                    <?php
                    switch ($session->current_role) {
                        case 'journey':
                            echo '<img class="img-fluid" src="' . base_url('appstack/sidebar_journey.jpg') . '" class="rounded" alt="Journey">';
                            break;
                        case 'finance':
                            echo '<img class="img-fluid" src="' . base_url('appstack/sidebar_finance.jpg') . '" class="rounded" alt="Finance">';
                            break;
                        default:
                            echo '<img class="img-fluid" src="' . base_url('appstack/sidebar_main.jpg') . '" class="rounded" alt="Header">';
                            break;
                    }
                    ?>
                </li>
                <li class="sidebar-header my-3">
                    <div class="float-start me-3"><?= $session->avatar ?></div>
                    <h6><?= $session->display_name ?></h6>
                    <span><?= $session->user['employee_title'] ?></span>
                </li>
                <li class="sidebar-item <?= ('dashboard' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/dashboard') ?>"><i class="fa-solid fa-house-chimney fa-fw me-3"></i><span><?= lang('System.dashboard.page_title') ?></span></a></li>
                <!-- USER -->
                <?php if (isset($session->permitted_features['user_master'])): ?>
                    <li class="sidebar-item <?= ('user' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/user') ?>"><i class="fa-solid fa-user fa-fw me-3"></i><span><?= lang('User.index.page_title') ?></span></a></li>
                <?php endif; ?>
                <!-- ROLE -->
                <?php if (isset($session->permitted_features['role_master'])): ?>
                    <li class="sidebar-item <?= ('role' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/role') ?>"><i class="fa-solid fa-list-check fa-fw me-3"></i><span><?= lang('Role.index.page_title') ?></span></a></li>
                <?php endif; ?>
                <!-- LOG -->
                <?php if (isset($session->permitted_features['log'])): ?>
                    <li class="sidebar-item <?= ('log' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/log') ?>"><i class="fa-solid fa-list fa-fw me-3"></i><span><?= lang('Log.index.page_title') ?></span></a></li>
                    <li class="sidebar-item <?= ('log-email' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/log/email') ?>"><i class="fa-solid fa-list fa-fw me-3"></i><span><?= lang('Log.email.page_title') ?></span></a></li>
                    <li class="sidebar-item <?= ('log-file' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/log/log-file') ?>"><i class="fa-solid fa-list fa-fw me-3"></i><span><?= lang('Log.file_list.page_title') ?></span></a></li>
                <?php endif; ?>
                <!-- ORGANIZATION -->
                <?php if (isset($session->permitted_features['organization'])): ?>
                    <li class="sidebar-item <?= ('organization' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/organization') ?>"><i class="fa-solid fa-building fa-fw me-3"></i><span><?= lang('Organization.page_title') ?></span></a></li>
                <?php endif; ?>
                <!-- FINANCE -->
                <?php if (isset($session->permitted_features['finance'])): ?>
                    <li class="sidebar-header">Employment</li>
                    <li class="sidebar-item <?= ('company' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/employment') ?>"><i class="fa-solid fa-suitcase fa-fw me-3"></i><span>Company</span></a></li>
                    <li class="sidebar-item <?= ('salary' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/employment/salary') ?>"><i class="fa-solid fa-dollar-sign fa-fw me-3"></i><span>Salary</span></a></li>
                    <li class="sidebar-item <?= ('cpf' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/employment/cpf') ?>"><i class="fa-solid fa-piggy-bank fa-fw me-3"></i><span>CPF</span></a></li>
                    <li class="sidebar-item <?= ('freelance' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/employment/freelance') ?>"><i class="fa-solid fa-laptop-code fa-fw me-3"></i><span>Freelance</span></a></li>
                    <li class="sidebar-header">Tax</li>
                    <li class="sidebar-item <?= ('tax' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/tax') ?>"><i class="fa-solid fa-building-columns fa-fw me-3"></i><span>Tax</span></a></li>
                    <li class="sidebar-item <?= ('tax-calculator' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/tax/calculator') ?>"><i class="fa-solid fa-calculator fa-fw me-3"></i><span>Tax Calculator</span></a></li>
                    <li class="sidebar-item <?= ('tax-projection' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/tax/projection') ?>"><i class="fa-solid fa-chart-line fa-fw me-3"></i><span>Tax Projection</span></a></li>
                    <li class="sidebar-item <?= ('tax-comparison' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/tax/comparison') ?>"><i class="fa-solid fa-code-compare fa-fw me-3"></i><span>Tax Comparison</span></a></li>
                <?php endif; ?>
                <!-- JOURNEY -->
                <?php if (isset($session->permitted_features['journey'])): ?>
                    <li class="sidebar-item <?= ('trip' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/trip') ?>"><i class="fa-solid fa-passport fa-fw me-3"></i><span>Trip</span></a></li>
                    <li class="sidebar-item <?= ('transport' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/transport') ?>"><i class="fa-solid fa-person-walking-luggage fa-fw me-3"></i><span>Transportation</span></a></li>
                    <li class="sidebar-item <?= ('accommodation' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/accommodation') ?>"><i class="fa-solid fa-bed fa-fw me-3"></i><span>Accommodation</span></a></li>
                    <li class="sidebar-item <?= ('attraction' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/attraction') ?>"><i class="fa-solid fa-ticket fa-fw me-3"></i><span>Attraction</span></a></li>
                    <li class="sidebar-item <?= ('port' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/port') ?>"><i class="fa-solid fa-location-dot fa-fw me-3"></i><span>Port</span></a></li>
                    <li class="sidebar-item <?= ('operator' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/operator') ?>"><i class="fa-solid fa-user-tie fa-fw me-3"></i><span>Operator</span></a></li>
                    <li class="sidebar-item <?= ('holiday' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/holiday') ?>"><i class="fa-solid fa-umbrella-beach fa-fw me-3"></i><span>Holiday</span></a></li>
                    <li class="sidebar-item <?= (str_contains($slug, '-stats') ? 'active' : '' ) ?>">
                        <a data-bs-target="#sidebar-journey-statistics" data-bs-toggle="collapse" class="sidebar-link <?= (str_contains($slug, '-stats') ? '' : 'collapsed' ) ?>" aria-expanded="<?= (str_contains($slug, '-stats') ? 'true' : 'false' ) ?>"><i class="fa-solid fa-fw fa-chart-line text-info"></i> Statistics</a>
                        <ul id="sidebar-journey-statistics" class="sidebar-dropdown list-unstyled <?= (str_contains($slug, '-stats') ? '' : 'collapse' ) ?>" data-bs-parent="#sidebar" style="">
                            <li class="sidebar-item <?= ('trip-stats' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/trip/statistics') ?>">Trip</a></li>
                            <li class="sidebar-item <?= ('transport-stats' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/transport/statistics') ?>">Transport</a></li>
                            <li class="sidebar-item <?= ('accommodation-stats' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/accommodation/statistics') ?>">Accommodation</a></li>
                            <li class="sidebar-item <?= ('attraction-stats' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/attraction/statistics') ?>">Attraction</a></li>
                            <li class="sidebar-item <?= ('port-stats' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/port/statistics') ?>">Port</a></li>
                            <li class="sidebar-item <?= ('operator-stats' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/operator/statistics') ?>">Operator</a></li>
                            <li class="sidebar-item <?= ('aircraft-stats' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/operator/aircraft/statistics') ?>">Aircraft</a></li>
                            <li class="sidebar-item <?= ('trip-finance-stats' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/journey/trip/finance') ?>">Finance</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <!-- PROFILE -->
                <?php if (isset($session->permitted_features['profile'])): ?>
                    <li class="sidebar-item <?= ('profile-data' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/profile/data') ?>"><i class="fa-regular fa-address-card fa-fw me-3"></i><span>Profile</span></a></li>
                    <li class="sidebar-item <?= ('resume' == $slug ? 'active' : '' ) ?>"><a class="sidebar-link" href="<?= base_url($session->locale . '/office/profile/resume') ?>"><i class="fa-regular fa-file-lines fa-fw me-3"></i><span>Resume</span></a></li>
                <?php endif; ?>
            </ul>
            <!-- SIDEBAR DOWNLOAD BTN -->
            <div class="sidebar-cta d-none">
                <div class="sidebar-cta-content">
                    <strong class="d-inline-block mb-2">Monthly Sales Report</strong>
                    <div class="mb-3 text-sm">
                        Your monthly sales report is ready for download!
                    </div>
                    <div class="d-grid">
                        <a href="#" class="btn btn-primary" target="_blank">Download</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="main">
        <!-- HEADER NAV -->
        <nav class="navbar navbar-expand navbar-bg">
            <a class="sidebar-toggle"><i class="hamburger align-self-center"></i></a>
            <!-- SEARCH BAR -->
            <form class="d-none d-sm-inline-block">
                <div class="input-group input-group-navbar">
                    <input type="text" class="form-control" placeholder="Search projects…" aria-label="Search">
                    <button class="btn" type="button">
                        <i class="align-middle" data-lucide="search"></i>
                    </button>
                </div>
            </form>
            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align">
                    <li class="nav-item dropdown d-none">
                        <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
                            <div class="position-relative">
                                <i class="align-middle text-body" data-lucide="message-circle"></i>
                                <span class="indicator">4</span>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="messagesDropdown">
                            <div class="dropdown-menu-header">
                                <div class="position-relative">
                                    4 New Messages
                                </div>
                            </div>
                            <div class="list-group">
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <img src="#" class="img-fluid rounded-circle" alt="Ashley Briggs" width="40" height="40">
                                        </div>
                                        <div class="col-10 ps-2">
                                            <div>Ashley Briggs</div>
                                            <div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>
                                            <div class="text-muted small mt-1">15m ago</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-menu-footer">
                                <a href="#" class="text-muted">Show all messages</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown d-none">
                        <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
                            <div class="position-relative">
                                <i class="align-middle text-body" data-lucide="bell-off"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
                            <div class="dropdown-menu-header">
                                4 New Notifications
                            </div>
                            <div class="list-group">
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <i class="text-danger" data-lucide="alert-circle"></i>
                                        </div>
                                        <div class="col-10">
                                            <div>Update completed</div>
                                            <div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
                                            <div class="text-muted small mt-1">2h ago</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-menu-footer">
                                <a href="#" class="text-muted">Show all notifications</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item nav-theme-toggle dropdown d-none">
                        <a class="nav-icon js-theme-toggle" href="#">
                            <div class="position-relative">
                                <i class="align-middle text-body nav-theme-toggle-light" data-lucide="sun"></i>
                                <i class="align-middle text-body nav-theme-toggle-dark" data-lucide="moon"></i>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-flag dropdown-toggle" href="#" id="languageDropdown" data-bs-toggle="dropdown"><i class="flag-icon flag-icon-us small"></i></a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <a class="dropdown-item" href="<?= base_url('en/office/dashboard') ?>">
                                <i class="flag-icon flag-icon-us"></i>
                                <span class="align-middle">English (US)</span>
                            </a>
                            <a class="dropdown-item" href="<?= base_url('th/office/dashboard') ?>">
                                <i class="flag-icon flag-icon-th"></i>
                                <span class="align-middle">ภาษาไทย</span>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                            <i class="align-middle" data-lucide="settings"></i>
                        </a>
                        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                            <?= $session->avatar ?>
                            <span><?= $session->display_name ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="<?= base_url($session->locale . '/office/profile') ?>"><i class="fa-solid fa-user-cog fa-fw me-3"></i><span><?= lang('System.menu.my_profile') ?></span></a>
                            <a class="dropdown-item" href="<?= base_url($session->locale . '/office/switch-role') ?>"><i class="fa-solid fa-arrows-rotate fa-fw me-3"></i><span><?= lang('System.menu.switch_role') ?></span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('logout') ?>">Sign out</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="content">
            <div class="container-fluid p-0">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
        <footer class="footer">
            <div class="container-fluid">
                <div class="row text-muted">
                    <div class="col-6 text-start">
                        <ul class="list-inline">
                            <li class="list-inline-item"><a class="text-muted" href="<?= base_url($session->locale . '/office/profile') ?>">Profile</a></li>
                            <li class="list-inline-item"><a class="text-muted" href="<?= base_url($session->locale . '/office/switch-role') ?>">Switch Role</a></li>
                            <li class="list-inline-item"><a class="text-muted" href="<?= base_url('logout') ?>">Sign out</a></li>
                        </ul>
                    </div>
                    <div class="col-6 text-end">
                        <p class="mb-0">
                            &copy; <?= date('Y') . ' ' . $session->organization['organization_name'] ?>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="<?= base_url('appstack/js/app.js') ?>"></script>
<script src="<?= base_url('assets/vendor/tinymce/tinymce.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/toastrjs/toastr.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/Luxon/luxon.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/DataTables/datatables.min.js') ?>"></script>
</body>
</html>