<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Tax extends BaseController
{
    const PERMISSION_REQUIRED = 'finance';
    private array $countries = [
        'AU',
        'SG',
        'TH',
        'US',
    ];

    private function calculateTaxSG(int $annual_income)
    {

    }
    public function index(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        return '';
    }

    public function masterList(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        return $this->response->setJSON([]);
    }

    public function masterEdit(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        return '';
    }

    public function masterSave(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
        return $this->response->setJSON([]);
    }

    /**
     * Calculate tax for various countries
     * @param string $country_code
     * @return string
     */
    public function calculator(string $country_code = 'sg'): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session      = session();
        $data         = [
            'page_title'   => 'Tax Calculator',
            'slug'         => 'tax-calculator',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('tax_calculator_' . $country_code, $data);
    }
}