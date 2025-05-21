<?php

namespace App\Controllers;

use App\Models\CompanyCPFModel;
use App\Models\CompanyCPFStatementModel;
use App\Models\CompanyFreelanceIncomeModel;
use App\Models\CompanyFreelanceProjectModel;
use App\Models\CompanyMasterModel;
use App\Models\CompanySalaryModel;
use App\Models\LogActivityModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use DateMalformedStringException;
use DateTime;
use ReflectionException;

class Employment extends BaseController
{

    const PERMISSION_REQUIRED = 'finance';
    private array $currencies = [
        'AUD',
        'SGD',
        'THB',
        'USD',
    ];
    private array $countries = [
        'AU',
        'GB',
        'ID',
        'MY',
        'SG',
        'TH',
        'TW',
        'US'
    ];

    /************************************************************************
     * COMPANY
     ************************************************************************/

    /**
     * @return string
     */
    public function index(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Employment',
            'slug_group'   => 'employment',
            'slug'         => '/office/employment',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'countries'    => $this->countries,
        ];
        return view('employment_company', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function companyList(): ResponseInterface
    {
        $model              = new CompanyMasterModel();
        $columns            = [
            '',
            '',
            'company_legal_name',
            'company_country_code',
            'company_hq_country_code',
            'employment_start_date',
            'employment_end_date',
            'position_titles'
        ];
        $order              = $this->request->getPost('order');
        $search             = $this->request->getPost('search');
        $start              = $this->request->getPost('start');
        $length             = $this->request->getPost('length');
        $order_column_index = $order[0]['column'] ?? 0;
        $order_column       = $columns[$order_column_index];
        $order_direction    = $order[0]['dir'] ?? 'desc';
        $search_value       = $search['value'];
        $country_code       = $this->request->getPost('country_code');
        $year               = $this->request->getPost('year');
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value, $country_code, $year);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    /**
     * @param string $company_id
     * @return string
     */
    public function companyEdit(string $company_id = 'new'): string
    {
        $session       = session();
        $company_model = new CompanyMasterModel();
        $page_title    = 'New Company';
        $mode          = 'new';
        if ('new' != $company_id && is_numeric($company_id)) {
            $company_id = $company_id/$company_model::ID_NONCE;
            $company    = $company_model->find($company_id);
            $page_title = 'Edit [' . $company['company_trade_name'] . ']';
            $mode       = 'edit';
        } else {
            $company    = [];
        }
        $data    = [
            'page_title'   => $page_title,
            'slug_group'   => 'employment',
            'slug'         => '/office/employment',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'mode'         => $mode,
            'company'      => $company,
            'config'       => $company_model->getConfigurations([], $this->countries, $this->currencies)
        ];
        return view('employment_company_edit', $data);
    }

    /**
     * @return ResponseInterface
     * @throws ReflectionException
     */
    public function companySave(): ResponseInterface
    {
        $mode          = $this->request->getPost('mode');
        $company_model = new CompanyMasterModel();
        $log_model     = new LogActivityModel();
        $session       = session();
        $id            = $this->request->getPost('id');
        $data          = [];
        $fields        = [
            'company_legal_name',
            'company_trade_name',
            'company_slug',
            'company_other_names',
            'company_address',
            'company_country_code',
            'company_hq_country_code',
            'company_currency_code',
            'company_website',
            'company_details',
            'company_registration',
            'company_color',
            'employment_start_date',
            'employment_end_date',
            'position_titles'
        ];
        foreach ($fields as $field) {
            $value        = $this->request->getPost($field);
            $data[$field] = (!empty($value)) ? $value : null;
        }
        if ('edit' == $mode) {
            if ($company_model->update($id, $data)) {
                $log_model->insertTableUpdate('company_master', $id, $data, $session->user_id);
                $new_id = $id * $company_model::ID_NONCE;
                return $this->response->setJSON([
                    'status'  => 'success',
                    'toast'   => 'Successfully updated the company.',
                    'redirect' => base_url($session->locale . '/office/employment/company/edit/' . $new_id)
                ]);
            }
        } else {
            $data['created_by'] = $session->user_id;
            // INSERT
            if ($id = $company_model->insert($data)) {
                $log_model->insertTableUpdate('company_master', $id, $data, $session->user_id);
                $new_id = $id * $company_model::ID_NONCE;
                return $this->response->setJSON([
                    'status'   => 'success',
                    'toast'    => 'Successfully created new company.',
                    'redirect' => base_url($session->locale . '/office/employment/company/edit/' . $new_id)
                ]);
            }
        }
        return $this->response->setJSON([
            'status'  => 'error',
            'toast'   => lang('System.status_message.generic_error')
        ])->setStatusCode(HTTP_STATUS_SOMETHING_WRONG);
    }

    /**
     * @return string
     * @throws DateMalformedStringException
     */
    public function companyStats(): string
    {
        $session           = session();
        $company_model     = new CompanyMasterModel();
        $companies         = $company_model->orderBy('company_trade_name')->findAll();
        $duration          = [];
        $country_days      = [];
        $country_companies = [];
        $main_chart        = [];
        $charts            = [];
        foreach ($companies as $company) {
            if ('0000-00-00' == $company['employment_end_date']) {
                $company['employment_end_date'] = null;
            }
            $start_date = new DateTime($company['employment_start_date']);
            $end_date   = (empty($company['employment_end_date']) ? new DateTime('now') : new DateTime($company['employment_end_date']));
            $diff       = $start_date->diff($end_date);
            $days       = $diff->days;
            $length     = (0 < $diff->y ? $diff->y . 'y ' : '') . (0 < $diff->m ? $diff->m . 'm ' : '') . (0 < $diff->d ? $diff->d . 'd' : '');
            $duration[] = [
                'name'      => $company['company_trade_name'],
                'country'   => $company['company_country_code'],
                'days'      => $days,
                'length'    => $length,
                'dates'     => [$company['employment_start_date'], $company['employment_end_date']],
            ];
            $main_chart[] = [
                'company' => $company['company_trade_name'],
                'days'    => $days,
                'label'   => $length,
            ];
            $country_days[$company['company_country_code']]      = (isset($country_days[$company['company_country_code']]) ? $country_days[$company['company_country_code']] + $days : $days);
            $country_companies[$company['company_country_code']] = (isset($country_companies[$company['company_country_code']]) ? $country_companies[$company['company_country_code']] + 1 : 1);
        }
        $country_length = [];
        foreach ($country_days as $country_code => $days) {
            $y        = floor($days/365);
            $m        = round(($days % 365)/30);
            $country_length[$country_code] = ($y > 0 ? $y . 'y ' : '') . ($m > 0 ? $m . 'm ' : '');
            $charts[] = [
                'country'   => lang('ListCountries.countries.' . $country_code . '.common_name'),
                'days'      => $days,
                'companies' => $country_companies[$country_code]
            ];
        }
        $data = [
            'page_title'        => 'Company Statistics',
            'slug_group'        => 'employment',
            'slug'              => '/office/employment/company/stats',
            'user_session'      => $session->user,
            'roles'             => $session->roles,
            'current_role'      => $session->current_role,
            'duration'          => $duration,
            'country_days'      => $country_days,
            'country_companies' => $country_companies,
            'country_length'    => $country_length,
            'charts'            => $charts,
            'main_chart'        => $main_chart,
        ];
        return view('employment_company_stats', $data);
    }
    /************************************************************************
     * SALARY
     ************************************************************************/

    /**
     * @return string
     */
    public function salary(): string
    {
        $session      = session();
        $company      = new CompanyMasterModel();
        $company_raw  = $company->orderBy('company_trade_name', 'asc')->findAll();
        $company_list = [];
        foreach ($company_raw as $row) {
            $company_list[$row['company_country_code']][] = [
                'id'   => $row['id'],
                'name' => $row['company_trade_name']
            ];
        }
        $data         = [
            'page_title'   => 'Salary',
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/salary',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'currencies'   => $this->currencies,
            'companies'    => $company_list,
        ];
        return view('employment_salary', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function salaryList(): ResponseInterface
    {
        $model              = new CompanySalaryModel();
        $columns            = [
            '',
            '',
            'pay_date',
            'company_legal_name',
            'tax_year',
            'tax_country_code',
            'payment_method',
            'payment_currency',
            'pay_type',
            'base_amount',
            'allowance_amount',
            'training_amount',
            'overtime_amount',
            'adjustment_amount',
            'bonus_amount',
            'subtotal_amount',
            'social_security_amount',
            'us_tax_fed_amount',
            'us_tax_state_amount',
            'us_tax_city_amount',
            'us_tax_med_ee_amount',
            'us_tax_oasdi_ee_amount',
            'th_tax_amount',
            'sg_tax_amount',
            'au_tax_amount',
            'claim_amount',
            'provident_fund_amount',
            'total_amount',
            'payment_details'
        ];
        $order              = $this->request->getPost('order');
        $start              = $this->request->getPost('start');
        $length             = $this->request->getPost('length');
        $order_column_index = $order[0]['column'] ?? 0;
        $order_column       = $columns[$order_column_index];
        $order_direction    = $order[0]['dir'] ?? 'desc';
        $currency_code      = $this->request->getPost('currency_code');
        $company_id         = intval($this->request->getPost('company_id'));
        $year               = $this->request->getPost('year');
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $currency_code, $company_id, $year);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data'],
            'footer'          => $result['footer']
        ]);
    }

    /**
     * @param string $salary_id
     * @return string
     */
    public function salaryEdit(string $salary_id = 'new'): string
    {
        $session       = session();
        $salary_model  = new CompanySalaryModel();
        $page_title    = 'New Salary';
        $mode          = 'new';
        if ('new' != $salary_id && is_numeric($salary_id)) {
            $salary_id  = $salary_id/$salary_model::ID_NONCE;
            $salary     = $salary_model->find($salary_id);
            $page_title = 'Edit [' . date(MONTH_FORMAT_UI, strtotime($salary['pay_date'])) . ' Salary]';
            $mode       = 'edit';
        } else {
            $salary    = [];
        }
        $data      = [
            'page_title'   => $page_title,
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/salary',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'mode'         => $mode,
            'salary'       => $salary,
            'config'       => $salary_model->getConfigurations([], $this->countries, $this->currencies)
        ];
        return view('employment_salary_edit', $data);
    }

    /**
     * @return ResponseInterface
     * @throws ReflectionException
     */
    public function salarySave(): ResponseInterface
    {
        $mode         = $this->request->getPost('mode');
        $salary_model = new CompanySalaryModel();
        $log_model    = new LogActivityModel();
        $session      = session();
        $id           = $this->request->getPost('id');
        $data         = [];
        $fields       = [
            'company_id',
            'pay_date',
            'tax_year',
            'tax_country_code',
            'payment_method',
            'payment_currency',
            'pay_type',
            'base_amount',
            'allowance_amount',
            'training_amount',
            'overtime_amount',
            'adjustment_amount',
            'bonus_amount',
            'subtotal_amount',
            'social_security_amount',
            'us_tax_fed_amount',
            'us_tax_state_amount',
            'us_tax_city_amount',
            'us_tax_med_ee_amount',
            'us_tax_oasdi_ee_amount',
            'th_tax_amount',
            'sg_tax_amount',
            'au_tax_amount',
            'claim_amount',
            'provident_fund_amount',
            'total_amount',
            'payment_details',
            'google_drive_link'
        ];
        foreach ($fields as $field) {
            $value        = $this->request->getPost($field);
            $data[$field] = (!empty($value)) ? $value : null;
        }
        if ('edit' == $mode) {
            if ($salary_model->update($id, $data)) {
                $log_model->insertTableUpdate('company_salary', $id, $data, $session->user_id);
                $new_id = $id * $salary_model::ID_NONCE;
                return $this->response->setJSON([
                    'status'  => 'success',
                    'toast'   => 'Successfully updated the salary.',
                    'redirect' => base_url($session->locale . '/office/employment/salary/edit/' . $new_id)
                ]);
            }
        } else {
            $data['created_by'] = $session->user_id;
            // INSERT
            if ($id = $salary_model->insert($data)) {
                $log_model->insertTableUpdate('company_salary', $id, $data, $session->user_id);
                $new_id = $id * $salary_model::ID_NONCE;
                return $this->response->setJSON([
                    'status'   => 'success',
                    'toast'    => 'Successfully created new salary.',
                    'redirect' => base_url($session->locale . '/office/employment/salary/edit/' . $new_id)
                ]);
            }
        }
        return $this->response->setJSON([
            'status'  => 'error',
            'toast'   => lang('System.status_message.generic_error')
        ])->setStatusCode(HTTP_STATUS_SOMETHING_WRONG);
    }

    /**
     * @param string $currency_code
     * @return string
     */
    public function salaryStatisticsCurrency(string $currency_code = ''): string
    {
        $session       = session();
        $locale        = $this->request->getLocale();
        $company_model = new CompanyMasterModel();
        $salary_model  = new CompanySalaryModel();
        if (empty($currency_code)) {
            $currency_code = 'SGD';
        }
        $companies     = $company_model->where('company_currency_code', $currency_code)->findAll();
        if (empty($companies)) {
            throw new PageNotFoundException();
        }
        $company_list  = [];
        $company_ids   = [];
        foreach ($companies as $company) {
            $company_list[$company['id']] = $company['company_trade_name'];
            $company_ids[]                = $company['id'];
        }
        $currency_list  = [];
        $dedupe_ccy     = $company_model->select('company_currency_code')->distinct()->findAll();
        foreach ($dedupe_ccy as $ccy) {
            $currency_list[] = $ccy['company_currency_code'];
        }
        $salary_records = $salary_model->whereIn('company_id', $company_ids)->whereIn('pay_type', ['salary', 'claim', 'other'])->findAll();
        $salary_by_year = [];
        $base_amounts   = [];
        foreach ($salary_records as $salary_record) {
            $year                              = substr($salary_record['tax_year'], 0, 4);
            $base_amounts[$year][]             = $salary_record['base_amount'];
            $salary_by_year[$year]['subtotal'] = (isset($salary_by_year[$year]['subtotal']) ? $salary_by_year[$year]['subtotal'] += $salary_record['subtotal_amount'] : $salary_record['subtotal_amount']);
            $salary_by_year[$year]['total']    = (isset($salary_by_year[$year]['total'])    ? $salary_by_year[$year]['total']    += $salary_record['total_amount']    : $salary_record['total_amount']);
        }
        $chart_data     = [];
        $max_bases      = [];
        $chart_data_2   = [];
        ksort($salary_by_year);
        for ($y = 2010; $y <= date('Y'); $y++) {
            if (isset($base_amounts[$y])) {
                $max_base       = max($base_amounts[$y]);
                $chart_data[]   = [
                    'year'     => "$y",
                    'subtotal' => round($salary_by_year[$y]['subtotal']),
                    'total'    => round($salary_by_year[$y]['total'])
                ];
                $chart_data_2[] = [
                    'year'     => "$y",
                    'base'     => round($max_base)
                ];
                $max_bases[$y]  = $max_base;
            } else {
                $chart_data[]   = [
                    'year'     => "$y",
                    'subtotal' => 0,
                    'total'    => 0
                ];
                $chart_data_2[] = [
                    'year'     => "$y",
                    'base'     => 0
                ];
                $max_bases[$y]  = 0.0;
            }
        }
        $data           = [
            'lang'           => $locale,
            'page_title'     => 'Salary Statistics - by Currency',
            'slug_group'     => 'employment',
            'slug'           => '/office/employment/salary/stats/currency/',
            'user_session'   => $session->user,
            'roles'          => $session->roles,
            'current_role'   => $session->current_role,
            'currency_code'  => $currency_code,
            'company_list'   => $company_list,
            'currency_list'  => $currency_list,
            'max_bases'      => $max_bases,
            'salary_by_year' => $salary_by_year,
            'chart_data'     => $chart_data,
            'chart_data_2'   => $chart_data_2,
        ];
        return view('employment_salary_statistics_currency', $data);
    }

    /**
     * @param int $company_id
     * @return string
     */
    public function salaryStatisticsCompany(int $company_id = 0): string
    {
        $session        = session();
        $locale         = $this->request->getLocale();
        $company_model  = new CompanyMasterModel();
        $salary_model   = new CompanySalaryModel();
        if (0 == $company_id) {
            $company    = $company_model->where('company_country_code', 'SG')->orderBy('employment_start_date', 'DESC')->first();
            $company_id = $company['id'];
        } else {
            $company    = $company_model->find($company_id);
        }
        $salaries       = $salary_model->where('company_id', $company_id)->whereIn('pay_type', ['salary', 'claim', 'other'])->findAll();
        $base_amount    = 0;
        $base_amounts   = [];
        $by_year        = [];
        foreach ($salaries as $salary) {
            if ($salary['base_amount'] != $base_amount && $salary['base_amount'] > 0) {
                $base_amounts[$salary['pay_date']] = $salary['base_amount'];
                $base_amount                       = $salary['base_amount'];
            }
            $year                       = substr($salary['pay_date'], 0, 4);
            $by_year[$year]['subtotal'] = (isset($by_year[$year]['subtotal']) ? $by_year[$year]['subtotal'] += $salary['subtotal_amount'] : $salary['subtotal_amount']);
            $by_year[$year]['total']    = (isset($by_year[$year]['total'])    ? $by_year[$year]['total']    += $salary['total_amount']    : $salary['total_amount']);
        }
        $chart_data     = [];
        foreach ($by_year as $year => $data) {
            $chart_data[] = [
                'year'     => "$year",
                'subtotal' => round($data['subtotal']),
                'total'    => round($data['total'])
            ];
        }
        $chart_data_2   = [];
        foreach ($base_amounts as $date => $amount) {
            $chart_data_2[] = [
                'month' => date(MONTH_FORMAT_UI, strtotime($date)),
                'base'  => round($amount)
            ];
        }
        $company_ids    = $salary_model->select('company_id')->distinct()->findAll();
        $company_ids    = array_column($company_ids, 'company_id');
        $data           = [
            'lang'           => $locale,
            'page_title'     => 'Salary Statistics - by Company',
            'slug_group'     => 'employment',
            'slug'           => '/office/employment/salary/stats/company/',
            'user_session'   => $session->user,
            'roles'          => $session->roles,
            'current_role'   => $session->current_role,
            'company_id'     => $company_id,
            'company'        => $company,
            'currency_code'  => $company['company_currency_code'],
            'company_list'   => $company_model->whereIn('id', $company_ids)->findAll(),
            'chart_data'     => $chart_data,
            'chart_data_2'   => $chart_data_2,
        ];
        return view('employment_salary_statistics_company', $data);
    }

    /************************************************************************
     * CPF
     ************************************************************************/

    /**
     * @return string
     */
    public function cpf(): string
    {
        $session = session();
        $model   = new CompanyMasterModel();
        $data    = [
            'page_title'   => 'CPF',
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/cpf',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'companies'    => $model
                ->where('company_country_code', 'SG')
                ->groupStart()
                ->where('employment_end_date >=', '2020-01-02')
                ->orWhere('employment_end_date', null)
                ->groupEnd()
                ->orderBy('company_legal_name', 'asc')->findAll()
        ];
        return view('employment_cpf', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function cpfList(): ResponseInterface
    {
        $model              = new CompanyCPFModel();
        $columns            = [
            '',
            'user_id',
            'transaction_date',
            'transaction_code',
            'ordinary_amount',
            'ordinary_balance',
            'special_amount',
            'special_balance',
            'medisave_amount',
            'medisave_balance',
            'transaction_amount',
            'account_balance',
            'contribution_month',
            'company_id',
            'staff_contribution',
            'staff_ytd',
            'company_match',
            'company_ytd',
        ];
        $order              = $this->request->getPost('order');
        $start              = $this->request->getPost('start');
        $length             = $this->request->getPost('length');
        $order_column_index = $order[0]['column'] ?? 0;
        $order_column       = $columns[$order_column_index];
        $order_direction    = $order[0]['dir'] ?? 'desc';
        $transaction_code   = $this->request->getPost('transaction_code');
        $company_id         = intval($this->request->getPost('company_id'));
        $year               = $this->request->getPost('year');
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $transaction_code, $company_id, $year);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    /**
     * @param string $cpf_id
     * @return string
     */
    public function cpfEdit(string $cpf_id = 'new'): string
    {
        $session       = session();
        $cpf_model     = new CompanyCPFModel();
        $page_title    = 'New CPF';
        $cpf           = [];
        $cpf_latest    = [];
        $cpf_last_con  = [];
        $mode          = 'new';
        if ('new' != $cpf_id && is_numeric($cpf_id)) {
            $cpf_id     = $cpf_id/$cpf_model::ID_NONCE;
            $cpf        = $cpf_model->find($cpf_id);
            $page_title = 'View CPF [' . $cpf['transaction_code'] . ' - ' . date(MONTH_FORMAT_UI, strtotime($cpf['transaction_date'])) . ']';
            $mode       = 'edit';
        } else {
            $cpf_latest   = $cpf_model->orderBy('id', 'desc')->first();
            $cpf_last_con = $cpf_model->where('transaction_code', 'CON')->orderBy('id', 'desc')->first();
        }
        $data = [
            'page_title'   => $page_title,
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/cpf',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'cpf'          => $cpf,
            'config'       => $cpf_model->getConfigurations(),
            'mode'         => $mode,
            'cpf_latest'   => $cpf_latest,
            'cpf_last_con' => $cpf_last_con
        ];
        return view('employment_cpf_edit', $data);
    }

    /**
     * @return ResponseInterface
     * @throws ReflectionException
     */
    public function cpfSave(): ResponseInterface
    {
        $cpf_model = new CompanyCPFModel();
        $log_model = new LogActivityModel();
        $session   = session();
        $data      = [];
        $fields    = [
            'transaction_date',
            'transaction_code',
            'ordinary_amount',
            'ordinary_balance',
            'special_amount',
            'special_balance',
            'medisave_amount',
            'medisave_balance',
            'transaction_amount',
            'account_balance',
            'contribution_month',
            'company_id',
            'staff_contribution',
            'staff_ytd',
            'company_match',
            'company_ytd',
        ];
        foreach ($fields as $field) {
            $value        = $this->request->getPost($field);
            $data[$field] = (!empty($value)) ? $value : null;
        }
        $data['created_by'] = $session->user_id;
        if (0 > $data['company_id']) {
            $data['company_id'] = null;
        }
        // INSERT
        if ($id = $cpf_model->insert($data)) {
            $log_model->insertTableUpdate('company_cpf', $id, $data, $session->user_id);
            $new_id = $id * $cpf_model::ID_NONCE;
            return $this->response->setJSON([
                'status'   => 'success',
                'toast'    => 'Successfully created new CPF record.',
                'redirect' => base_url($session->locale . '/office/employment/cpf/edit/' . $new_id)
            ]);
        }
        return $this->response->setJSON([
            'status'  => 'error',
            'toast'   => lang('System.status_message.generic_error')
        ])->setStatusCode(HTTP_STATUS_SOMETHING_WRONG);
    }

    /**
     * CPF Annual Statement
     * @return string
     */
    public function cpfStatement(): string
    {
        $session = session();
        $model   = new CompanyCPFStatementModel();
        $data    = [
            'page_title'   => 'CPF Statement',
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/cpf',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'statements'   => $model->findAll(),
            'nonce'        => $model::ID_NONCE
        ];
        return view('employment_cpf_statement', $data);
    }

    /**
     * @param string $cpf_statement_id
     * @return string
     */
    public function cpfStatementEdit(string $cpf_statement_id = 'new'): string
    {
        $session    = session();
        $model      = new CompanyCPFStatementModel();
        $page_title = 'New CPF Statement';
        $mode       = 'new';
        $statement  = [];
        if ('new' != $cpf_statement_id && is_numeric($cpf_statement_id)) {
            $cpf_id     = $cpf_statement_id/$model::ID_NONCE;
            $statement  = $model->find($cpf_id);
            $page_title = 'View CPF Statement [' . $statement['statement_year'] . ']';
            $mode       = 'edit';
        }
        $data    = [
            'page_title'   => $page_title,
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/cpf',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'statement'    => $statement,
            'mode'         => $mode,
            'config'       => $model->getConfiguration()
        ];
        return view('employment_cpf_statement_edit', $data);
    }

    /************************************************************************
     * Freelance
     ************************************************************************/

    /**
     * @return string
     */
    public function freelance(): string
    {
        $session       = session();
        $company      = new CompanyMasterModel();
        $company_raw  = $company->orderBy('company_trade_name', 'asc')->findAll();
        $company_list = [];
        foreach ($company_raw as $row) {
            $company_list[$row['company_country_code']][] = [
                'id'   => $row['id'],
                'name' => $row['company_trade_name']
            ];
        }
        $data          = [
            'page_title'   => 'Freelance',
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/freelance',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'companies'    => $company_list
        ];
        return view('employment_freelance', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function freelanceList(): ResponseInterface
    {
        $model              = new CompanyFreelanceProjectModel();
        $columns            = [
            '',
            'company_legal_name',
            'project_title',
            'client_name',
            'client_organization_name',
            'project_start_date',
            'project_end_date'
        ];
        $order              = $this->request->getPost('order');
        $search             = $this->request->getPost('search');
        $start              = $this->request->getPost('start');
        $length             = $this->request->getPost('length');
        $order_column_index = $order[0]['column'] ?? 0;
        $order_column       = $columns[$order_column_index];
        $order_direction    = $order[0]['dir'] ?? 'desc';
        $search_value       = $search['value'];
        $company_id         = intval($this->request->getPost('company_id'));
        $year               = $this->request->getPost('year');
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value, $company_id, $year);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    /**
     * @param string $freelance_project_id
     * @return string
     */
    public function freelanceEdit(string $freelance_project_id = 'new'): string
    {
        $session       = session();
        $project_model = new CompanyFreelanceProjectModel();
        $page_title    = 'New Freelance Project';
        $project       = [];
        $mode          = 'new';
        if ('new' != $freelance_project_id && is_numeric($freelance_project_id)) {
            $freelance_project_id = $freelance_project_id/$project_model::ID_NONCE;
            $project              = $project_model->find($freelance_project_id);
            $page_title           = 'Edit Freelance Project [' . $project['project_title'] . ']';
            $mode                 = 'edit';
        }
        $data          = [
            'page_title'   => $page_title,
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/freelance',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'project'      => $project,
            'config'       => $project_model->getConfigurations(),
            'mode'         => $mode
        ];
        return view('employment_freelance_edit', $data);
    }

    /**
     * @return ResponseInterface
     * @throws ReflectionException
     */
    public function freelanceSave(): ResponseInterface
    {
        $mode          = $this->request->getPost('mode');
        $project_model = new CompanyFreelanceProjectModel();
        $log_model     = new LogActivityModel();
        $session       = session();
        $id            = $this->request->getPost('id');
        $data          = [];
        $fields        = [
            'company_id',
            'project_title',
            'project_slug',
            'project_start_date',
            'project_end_date',
            'client_name',
            'client_organization_name',
        ];
        foreach ($fields as $field) {
            $value        = $this->request->getPost($field);
            $data[$field] = (!empty($value)) ? $value : null;
        }
        if ('edit' == $mode) {
            if ($project_model->update($id, $data)) {
                $log_model->insertTableUpdate('company_freelance_project', $id, $data, $session->user_id);
                $new_id = $id * $project_model::ID_NONCE;
                return $this->response->setJSON([
                    'status'  => 'success',
                    'toast'   => 'Successfully updated the company.',
                    'redirect' => base_url($session->locale . '/office/employment/freelance/edit/' . $new_id)
                ]);
            }
        } else {
            $data['created_by'] = $session->user_id;
            // INSERT
            if ($id = $project_model->insert($data)) {
                $log_model->insertTableUpdate('company_freelance_project', $id, $data, $session->user_id);
                $new_id = $id * $project_model::ID_NONCE;
                return $this->response->setJSON([
                    'status'   => 'success',
                    'toast'    => 'Successfully created new company.',
                    'redirect' => base_url($session->locale . '/office/employment/freelance/edit/' . $new_id)
                ]);
            }
        }
        return $this->response->setJSON([
            'status'  => 'error',
            'toast'   => lang('System.status_message.generic_error')
        ])->setStatusCode(HTTP_STATUS_SOMETHING_WRONG);
    }

    /**
     * @return string
     */
    public function freelanceStats(): string
    {
        $lang    = $this->request->getLocale();
        $session = session();
        $data = [
            'lang' => $lang,
            'page_title'   => 'Freelance Statistics',
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/freelance/stats',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
        ];
        return view('employment_freelance_stats', $data);
    }

    /**
     * @return string
     */
    public function freelanceIncome(): string
    {
        $session       = session();
        $project_model = new CompanyFreelanceProjectModel();
        $company_model = new CompanyMasterModel();
        $project_raw   = $project_model->orderBy('project_title', 'asc')->findAll();
        $project_list  = [];
        $company_list  = [];
        $company_ids   = [];
        foreach ($project_raw as $row) {
            $project_list[$row['id']] = $row['project_title'];
            $company_ids[]            = $row['company_id'];
        }
        $company_raw  = $company_model->whereIn('id', $company_ids)->orderBy('company_trade_name', 'asc')->findAll();
        foreach ($company_raw as $row) {
            $company_list[$row['id']] = $row['company_trade_name'];
        }
        $data          = [
            'page_title'   => 'Freelance Income',
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/freelance-income',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'projects'     => $project_list,
            'companies'    => $company_list
        ];
        return view('employment_freelance_income', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function freelanceIncomeList(): ResponseInterface
    {
        $model              = new CompanyFreelanceIncomeModel();
        $columns            = [
            '',
            'google_drive_link',
            'company_freelance_project.project_title',
            'company_master.company_trade_name',
            'pay_date',
            'payment_method',
            'payment_currency',
            'base_amount',
            'deduction_amount',
            'claim_amount',
            'subtotal_amount',
            'tax_amount',
            'total_amount',
            'payment_details',
        ];
        $order              = $this->request->getPost('order');
        $search             = $this->request->getPost('search');
        $start              = $this->request->getPost('start');
        $length             = $this->request->getPost('length');
        $order_column_index = $order[0]['column'] ?? 0;
        $order_column       = $columns[$order_column_index];
        $order_direction    = $order[0]['dir'] ?? 'desc';
        $company_id         = intval($this->request->getPost('company_id'));
        $project_id         = intval($this->request->getPost('project_id'));
        $year               = $this->request->getPost('year');
        $search_value       = $search['value'];
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value, $company_id, $project_id, $year);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data'],
            'footer'          => $result['footer']
        ]);
    }

    /**
     * @param string $freelance_income_id
     * @return string
     */
    public function freelanceIncomeEdit(string $freelance_income_id = 'new'): string
    {
        $session       = session();
        $income_model  = new CompanyFreelanceIncomeModel();
        $page_title    = 'New Freelance Income';
        $income        = [];
        $mode          = 'new';
        if ('new' != $freelance_income_id && is_numeric($freelance_income_id)) {
            $freelance_income_id = $freelance_income_id/$income_model::ID_NONCE;
            $income              = $income_model->find($freelance_income_id);
            $page_title          = 'Edit Freelance Income [' . date(DATE_FORMAT_UI, strtotime($income['pay_date'])) . ']';
            $mode                = 'edit';
        }
        $data          = [
            'page_title'   => $page_title,
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/freelance-income',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'income'       => $income,
            'mode'         => $mode,
            'config'       => $income_model->getConfigurations([], $this->currencies)
        ];
        return view('employment_freelance_income_edit', $data);
    }

    /**
     * @return ResponseInterface
     * @throws ReflectionException
     */
    public function freelanceIncomeSave(): ResponseInterface
    {
        $mode          = $this->request->getPost('mode');
        $income_model  = new CompanyFreelanceIncomeModel();
        $log_model     = new LogActivityModel();
        $session       = session();
        $id            = $this->request->getPost('id');
        $data          = [];
        $fields        = [
            'project_id',
            'pay_date',
            'payment_method',
            'payment_currency',
            'base_amount',
            'deduction_amount',
            'claim_amount',
            'subtotal_amount',
            'tax_amount',
            'total_amount',
            'payment_details',
            'google_drive_link',
        ];
        foreach ($fields as $field) {
            $value        = $this->request->getPost($field);
            $data[$field] = (!empty($value)) ? $value : null;
        }
        if ('edit' == $mode) {
            if ($income_model->update($id, $data)) {
                $log_model->insertTableUpdate('company_freelance_income', $id, $data, $session->user_id);
                $new_id = $id * $income_model::ID_NONCE;
                return $this->response->setJSON([
                    'status'   => 'success',
                    'toast'    => 'Successfully updated the income.',
                    'redirect' => base_url($session->locale . '/office/employment/freelance-income/edit/' . $new_id)
                ]);
            }
        } else {
            $data['created_by'] = $session->user_id;
            // INSERT
            if ($id = $income_model->insert($data)) {
                $log_model->insertTableUpdate('company_freelance_income', $id, $data, $session->user_id);
                $new_id = $id * $income_model::ID_NONCE;
                return $this->response->setJSON([
                    'status'   => 'success',
                    'toast'    => 'Successfully created new income.',
                    'redirect' => base_url($session->locale . '/office/employment/freelance-income/edit/' . $new_id)
                ]);
            }
        }
        return $this->response->setJSON([
            'status'  => 'error',
            'toast'   => lang('System.status_message.generic_error')
        ])->setStatusCode(HTTP_STATUS_SOMETHING_WRONG);
    }

    /**
     * @return string
     */
    public function freelanceIncomeStats(): string
    {
        $lang    = $this->request->getLocale();
        $session = session();
        $data = [
            'lang' => $lang,
            'page_title'   => 'Freelance Income Statistics',
            'slug_group'   => 'employment',
            'slug'         => '/office/employment/freelance-income/stats',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
        ];
        return view('employment_freelance_income_stats', $data);
    }

    /**
     * @param string $year
     * @return string
     */
    public function totalIncome(string $year = ''): string
    {
        $lang    = $this->request->getLocale();
        $session = session();
        if (empty($year)) {
            $year = date('Y');
        }
        $company_model          = new CompanyMasterModel();
        $salary_model           = new CompanySalaryModel();
        $freelance_income_model = new CompanyFreelanceIncomeModel();
        $company_list           = $company_model->where('employment_start_date <=', $year . '-12-31')
            ->groupStart()
            ->where('employment_end_date >=', $year . '-01-01')
            ->orWhere('employment_end_date IS NULL')
            ->groupEnd()
            ->findAll();
        $company_info           = [];
        foreach ($company_list as $company) {
            $company_info[$company['id']] = [
                'company_name'  => $company['company_trade_name'],
                'country_code'  => $company['company_country_code'],
                'currency_code' => $company['company_currency_code'],
            ];
        }
        $salary_records         = $salary_model->where('pay_date >=', $year . '-01-01')
            ->whereIn('pay_type', ['salary', 'claim', 'other'])
            ->where('pay_date <=', $year . '-12-31')
            ->orderBy('pay_date', 'ASC')
            ->findAll();
        $freelance_records     = $freelance_income_model
            ->select('company_freelance_income.*, company_freelance_project.company_id')
            ->join('company_freelance_project', 'company_freelance_income.project_id = company_freelance_project.id')
            ->where('pay_date >=', $year . '-01-01')
            ->where('pay_date <=', $year . '-12-31')
            ->orderBy('pay_date', 'ASC')
            ->findAll();
        $income_records        = [];
        foreach ($salary_records as $record) {
            $income_records[$record['payment_currency']][] = [
                'company_name'    => $company_info[$record['company_id']]['company_name'],
                'pay_date'        => $record['pay_date'],
                'country_code'    => $record['tax_country_code'],
                'base_amount'     => $record['base_amount'],
                'other_amount'    => $record['allowance_amount'] + $record['training_amount'] + $record['overtime_amount'] + $record['adjustment_amount'] + $record['bonus_amount'],
                'taxes'           => $record['us_tax_fed_amount'] + $record['us_tax_state_amount'] + $record['us_tax_city_amount'] + $record['us_tax_med_ee_amount'] + $record['us_tax_oasdi_ee_amount'] + $record['th_tax_amount'] + $record['sg_tax_amount'] + $record['au_tax_amount'],
                'claim_amount'    => $record['claim_amount'],
                'social_security' => $record['social_security_amount'],
                'provident_fund'  => $record['provident_fund_amount'],
                'total'           => $record['total_amount'],
            ];
        }
        foreach ($freelance_records as $record) {
            $income_records[$record['payment_currency']][] = [
                'company_name'    => $company_info[$record['company_id']]['company_name'],
                'pay_date'        => $record['pay_date'],
                'country_code'    => $company_info[$record['company_id']]['country_code'],
                'base_amount'     => $record['base_amount'],
                'other_amount'    => $record['deduction_amount'],
                'taxes'           => $record['tax_amount'],
                'claim_amount'    => $record['claim_amount'],
                'social_security' => 0,
                'provident_fund'  => 0,
                'total'           => $record['total_amount'],
            ];
        }
        $data                  = [
            'lang'              => $lang,
            'page_title'        => 'Total Income',
            'slug_group'        => 'employment',
            'slug'              => '/office/employment/company/total-income',
            'user_session'      => $session->user,
            'roles'             => $session->roles,
            'current_role'      => $session->current_role,
            'year'              => $year,
            'income_records'    => $income_records
        ];
        return view('employment_total_income', $data);
    }

}