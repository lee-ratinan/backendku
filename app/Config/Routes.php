<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
// Login
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::loginScript');
$routes->post('update-expired-password', 'Auth::updateExpiredPassword');
$routes->post('resend-otp', 'Auth::resendOTP');
$routes->post('verify-otp', 'Auth::verifyOTP');
$routes->post('google-signin', 'Auth::loginGoogle');
// Forgot Password
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::forgotPasswordScript');
// Reset Password (click from email)
$routes->get('reset-password/(:any)/(:any)', 'Auth::resetPassword/$1/$2');
$routes->post('reset-password', 'Auth::resetPasswordScript');
// Register - reserved for future use
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::registerScript');
// Logout
$routes->get('logout', 'Auth::logout');
// Files
$routes->get('file/(:any)', 'File::index/$1/0');
$routes->get('download/(:any)', 'File::index/$1/1');
// Cron
$routes->get('cron/run-monthly', 'Cron::runMonthly');
// SYSTEM
$routes->group('{locale}/office', ['filter' => 'auth'], static function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Office::index');
    $routes->get('dashboard', 'Office::index');
    $routes->get('profile', 'Office::profile');
    $routes->post('profile', 'Office::profileScript');
    $routes->get('switch-role', 'Office::switchRole');
    $routes->post('switch-role', 'Office::switchRoleScript');
    // User
    $routes->get('user', 'User::index');
    $routes->post('user', 'User::list');
    $routes->get('user/create', 'User::edit/new');
    $routes->get('user/edit/(:any)', 'User::edit/$1');
    $routes->post('user/edit', 'User::editScript');
    $routes->get('public-profile/(:any)', 'User::publicProfile/$1');
    // Role
    $routes->get('role', 'Role::index');
    $routes->post('role', 'Role::list');
    $routes->get('role/create', 'Role::edit/new-role');
    $routes->get('role/edit/(:any)', 'Role::edit/$1');
    $routes->post('role/edit', 'Role::editScript');
    $routes->get('role/feature', 'Role::feature');
    // Organization
    $routes->get('organization', 'Organization::index');
    $routes->post('organization', 'Organization::update');
    // Log
    $routes->get('log', 'Log::index');
    $routes->post('log', 'Log::list');
    $routes->get('log/email', 'Log::email');
    $routes->post('log/email', 'Log::emailList');
    $routes->get('log/log-file', 'Log::fileList');
    $routes->get('log/log-file/(:any)', 'Log::fileView/$1');
    /////////////////////////////////////////////////////////////////////////////
    // ALL OTHER CUSTOM ROUTES
    /////////////////////////////////////////////////////////////////////////////
    // FINANCE - EMPLOYMENT
    // company_master table - company with filter by year, country, currency, etc
    $routes->get('employment', 'Employment::index');
    $routes->post('employment/company', 'Employment::companyList');
    $routes->get('employment/company/view/(:any)', 'Employment::companyView/$1');
    $routes->get('employment/company/create', 'Employment::companyEdit/new');
    $routes->get('employment/company/edit/(:any)', 'Employment::companyEdit/$1');
    $routes->post('employment/company/edit', 'Employment::companySave');
    // company_salary table - with filter by year, company, currency, etc
    $routes->get('employment/salary', 'Employment::salary');
    $routes->post('employment/salary', 'Employment::salaryList');
    $routes->get('employment/salary/create', 'Employment::salaryEdit/new');
    $routes->get('employment/salary/edit/(:any)', 'Employment::salaryEdit/$1');
    $routes->post('employment/salary/edit', 'Employment::salarySave');
    // company_cpf table - with filter by year, company (contribution), transaction code, etc
    $routes->get('employment/cpf', 'Employment::cpf');
    $routes->post('employment/cpf', 'Employment::cpfList');
    $routes->get('employment/cpf/create', 'Employment::cpfEdit/new');
    $routes->get('employment/cpf/edit/(:any)', 'Employment::cpfEdit/$1');
    $routes->post('employment/cpf/edit', 'Employment::cpfSave');
    $routes->get('employment/cpf/statement', 'Employment::cpfStatement');
    $routes->get('employment/cpf/statement/create', 'Employment::cpfStatementEdit/new');
    $routes->get('employment/cpf/statement/edit/(:any)', 'Employment::cpfStatementEdit/$1');
    $routes->post('employment/cpf/statement/edit', 'Employment::cpfStatementSave');
    // company_freelance_project and company_freelance_income
    $routes->get('employment/freelance', 'Employment::freelance');
    $routes->post('employment/freelance', 'Employment::freelanceList');
    $routes->get('employment/freelance/view/(:any)', 'Employment::freelanceView/$1');
    $routes->get('employment/freelance/create', 'Employment::freelanceEdit/new');
    $routes->get('employment/freelance/edit/(:any)', 'Employment::freelanceEdit/$1');
    $routes->post('employment/freelance/edit', 'Employment::freelanceSave');
    $routes->get('employment/freelance-income/create', 'Employment::freelanceIncomeEdit/new');
    $routes->get('employment/freelance-income/edit/(:any)', 'Employment::freelanceIncomeEdit/$1');
    $routes->post('employment/freelance-income/edit', 'Employment::freelanceIncomeSave');
    // FINANCE - TAX
    // tax_master and tax_breakdown tables
    $routes->get('tax', 'Tax::index');
    $routes->post('tax', 'Tax::masterList');
    $routes->get('tax/create', 'Tax::masterEdit/new');
    $routes->get('tax/edit/(:num)', 'Tax::masterEdit/$1');
    $routes->post('tax/edit', 'Tax::masterSave'); // both master and breakdown
    // tax calculator app
    $routes->get('tax/calculator', 'Tax::calculator'); // just simple tax calculator
    $routes->post('tax/calculator', 'Tax::calculatorAjax'); // just simple tax calculator
    /////////////////////////////////////////////////////////////////////////////
    // JOURNEY
    // journey_port table
    $routes->get('journey/port', 'Journey::port');
    $routes->post('journey/port', 'Journey::portList');
    $routes->get('journey/port/create', 'Journey::portEdit/new');
    $routes->get('journey/port/edit/(:any)', 'Journey::portEdit/$1');
    $routes->post('journey/port/edit', 'Journey::portSave');
    // journey_operator table
    $routes->get('journey/operator', 'Journey::operator');
    $routes->post('journey/operator', 'Journey::operatorList');
    $routes->get('journey/operator/create', 'Journey::operatorEdit/new');
    $routes->get('journey/operator/edit/(:any)', 'Journey::operatorEdit/$1');
    $routes->post('journey/operator/edit', 'Journey::operatorSave');
    // journey_master table
    $routes->get('journey/trip', 'Journey::trip');
    $routes->post('journey/trip', 'Journey::tripList');
    $routes->get('journey/trip/create', 'Journey::tripEdit/new');
    $routes->get('journey/trip/edit/(:any)', 'Journey::tripEdit/$1');
    $routes->post('journey/trip/edit', 'Journey::tripSave');
    $routes->get('journey/trip/statistics', 'Journey::tripStatistics');
    $routes->get('journey/trip/finance', 'Journey::tripFinance');
    // journey_transport table
    $routes->get('journey/transport', 'Journey::transport');
    $routes->post('journey/transport', 'Journey::transportList');
    $routes->get('journey/transport/create/(:num)', 'Journey::transportEdit/new/$1');
    $routes->get('journey/transport/edit/(:num)', 'Journey::transportEdit/$1');
    $routes->post('journey/transport/edit', 'Journey::transportSave');
    $routes->get('journey/transport/statistics', 'Journey::transportStatistics');
    // journey_accommodation table
    $routes->get('journey/accommodation', 'Journey::accommodation');
    $routes->post('journey/accommodation', 'Journey::accommodationList');
    $routes->get('journey/accommodation/create/(:num)', 'Journey::accommodationEdit/new/$1');
    $routes->get('journey/accommodation/edit/(:num)', 'Journey::accommodationEdit/$1');
    $routes->post('journey/accommodation/edit', 'Journey::accommodationSave');
    $routes->get('journey/accommodation/statistics', 'Journey::accommodationStatistics');
    // journey_attraction table
    $routes->get('journey/attraction', 'Journey::attraction');
    $routes->post('journey/attraction', 'Journey::attractionList');
    $routes->get('journey/attraction/create/(:num)', 'Journey::attractionEdit/new/$1');
    $routes->get('journey/attraction/edit/(:num)', 'Journey::attractionEdit/$1');
    $routes->post('journey/attraction/edit', 'Journey::attractionSave');
    // journey_holiday table
    $routes->get('journey/holiday', 'Journey::holiday');
    $routes->post('journey/holiday', 'Journey::holidayList');
    $routes->get('journey/holiday/create', 'Journey::holidayEdit/new');
    $routes->get('journey/holiday/edit/(:any)', 'Journey::holidayEdit/$1');
    $routes->post('journey/holiday/edit', 'Journey::holidaySave');
    // Summary, export, statistics, etc
    $routes->get('journey/export', 'Journey::export');
    $routes->get('journey/fix', 'Journey::fix');
    /////////////////////////////////////////////////////////////////////////////
    // PROFILE
    // Profile
    $routes->get('profile/data', 'Profile::index');
    // Resume
    $routes->get('profile/resume', 'Profile::resume');
    $routes->post('profile/resume/builder', 'Profile::resumeBuilder');
    $routes->get('profile/resume/cover-letter', 'Profile::resumeCoverLetter');
});
