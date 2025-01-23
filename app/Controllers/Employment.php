<?php

namespace App\Controllers;

use App\Models\CompanyCPFModel;
use App\Models\CompanyCPFStatementModel;
use App\Models\CompanyFreelanceProjectModel;
use App\Models\CompanyMasterModel;
use App\Models\CompanySalaryModel;
use CodeIgniter\HTTP\ResponseInterface;

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
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'   => 'Employment',
            'slug'         => 'company',
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
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        $model              = new CompanyMasterModel();
        $columns            = [
            '',
            'id',
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
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
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
            'slug'         => 'company',
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
     */
    public function companySave(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
        return $this->response->setJSON([]);
    }

    /************************************************************************
     * SALARY
     ************************************************************************/

    /**
     * @return string
     */
    public function salary(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session      = session();
        $company      = new CompanyMasterModel();
        $company_raw  = $company->orderBy('company_legal_name', 'asc')->findAll();
        $company_list = [];
        foreach ($company_raw as $row) {
            $company_list[$row['company_country_code']][] = [
                'id'   => $row['id'],
                'name' => $row['company_legal_name']
            ];
        }
        $data         = [
            'page_title'   => 'Salary',
            'slug'         => 'salary',
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
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        $model              = new CompanySalaryModel();
        $columns            = [
            '',
            'id',
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
            'payment_details',
            'google_drive_link'
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
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
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
            'slug'         => 'salary',
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
     */
    public function salarySave(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
        return $this->response->setJSON([]);
    }

    /************************************************************************
     * CPF
     ************************************************************************/

    /**
     * @return string
     */
    public function cpf(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $model   = new CompanyMasterModel();
        $data    = [
            'page_title'   => 'CPF',
            'slug'         => 'cpf',
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
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        $model              = new CompanyCPFModel();
        $columns            = [
            '',
            'id',
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
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
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
            'slug'         => 'cpf',
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
     */
    public function cpfSave(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
        return $this->response->setJSON([]);
    }

    /**
     * CPF Annual Statement
     * @return string
     */
    public function cpfStatement(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $model   = new CompanyCPFStatementModel();
        $data    = [
            'page_title'   => 'CPF Statement',
            'slug'         => 'cpf',
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
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
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
            'slug'         => 'cpf',
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
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session       = session();
        $company      = new CompanyMasterModel();
        $company_raw  = $company->orderBy('company_legal_name', 'asc')->findAll();
        $company_list = [];
        foreach ($company_raw as $row) {
            $company_list[$row['company_country_code']][] = [
                'id'   => $row['id'],
                'name' => $row['company_legal_name']
            ];
        }
        $data          = [
            'page_title'   => 'Freelance',
            'slug'         => 'freelance',
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
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        $model              = new CompanyFreelanceProjectModel();
        $columns            = [
            '',
            'id',
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
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
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
            'slug'         => 'freelance',
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
     */
    public function freelanceSave(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
        return $this->response->setJSON([]);
    }

}