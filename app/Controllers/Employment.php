<?php

namespace App\Controllers;

use App\Models\CompanyCPFModel;
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
            'countries'    => lang('ListCountries.countries'),
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

    public function companyEdit(string $port_code = 'new'): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        return '';
    }

    public function companySave()
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

    public function salaryEdit(string $port_code = 'new')
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
    }

    public function salarySave()
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
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

    public function cpfEdit(string $port_code = 'new')
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
    }

    public function cpfSave()
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
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

    public function freelanceEdit(string $port_code = 'new')
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
    }

    public function freelanceSave()
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
    }

}