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
    private array $currencies = [
        'AU' => 'AUD',
        'SG' => 'SGD',
        'TH' => 'THB',
        'US' => 'USD',
    ];
    private array $tax_brackets = [
        'AU' => [
            ['limit' => 18200, 'rate' => 0],
            ['limit' => 45000, 'rate' => 16],
            ['limit' => 135000, 'rate' => 30],
            ['limit' => 190000, 'rate' => 37],
            ['limit' => PHP_INT_MAX, 'rate' => 45],
        ],
        'SG' => [
            ['limit' => 20000, 'rate' => 0],
            ['limit' => 30000, 'rate' => 2],
            ['limit' => 40000, 'rate' => 3.5],
            ['limit' => 80000, 'rate' => 7],
            ['limit' => 120000, 'rate' => 11.5],
            ['limit' => 160000, 'rate' => 15],
            ['limit' => 200000, 'rate' => 18],
            ['limit' => 240000, 'rate' => 19],
            ['limit' => 280000, 'rate' => 19.5],
            ['limit' => 320000, 'rate' => 20],
            ['limit' => 500000, 'rate' => 22],
            ['limit' => 1000000, 'rate' => 23],
            ['limit' => PHP_INT_MAX, 'rate' => 24],
        ],
        'TH' => [
            ['limit' => 150000, 'rate' => 0],
            ['limit' => 300000, 'rate' => 5],
            ['limit' => 500000, 'rate' => 10],
            ['limit' => 750000, 'rate' => 15],
            ['limit' => 1000000, 'rate' => 20],
            ['limit' => 2000000, 'rate' => 25],
            ['limit' => 5000000, 'rate' => 30],
            ['limit' => PHP_INT_MAX, 'rate' => 35],
        ],
    ];
    private array $deductions = [
        'AU' => 0,
        'SG' => 1000,
        'TH' => 60000,
    ];
    private array $market_rates = [
        'AU' => [8000, 13000],
        'SG' => [5000, 9000],
        'TH' => [38000, 55000]
    ];

    private function taxCalculation(float $annual_income, string $country_code): array
    {
        if (!isset($this->tax_brackets[$country_code]) || !isset($this->deductions[$country_code])) {
            return [];
        }
        $tax_brackets   = $this->tax_brackets[$country_code];
        $deduction      = $this->deductions[$country_code];
        if ('SG' == $country_code) {
            $cpf_annual = min(102000, $annual_income);
            $deduction += $cpf_annual * 0.2;
        } else if ('TH' == $country_code) {
            $deduction += 9000; // social security (750 monthly)
        }
        $taxable_income = $annual_income - $deduction;
        $taxable_income = (0 > $taxable_income) ? 0 : $taxable_income;
        $total_tax      = 0.00;
        $prev_limit     = 0;
        $line_details   = [];
        foreach ($tax_brackets as $tax_bracket) {
            $limit          = $tax_bracket['limit'];
            $rate           = $tax_bracket['rate'];
            // FOR AMOUNT <= 10,000,000 |     10.5%  | 1,000,000.00
            if ($taxable_income > $limit) {
                $this_amount  = ($limit - $prev_limit) * ($rate / 100);
                $total_tax   += $this_amount;
                $line_details[] = ['prev_limit' => $prev_limit, 'limit' => $limit, 'rate' => $rate, 'amount' => ($limit - $prev_limit), 'subtotal' => $this_amount];
            } else {
                $this_amount  = ($taxable_income - $prev_limit) * ($rate / 100);
                $total_tax   += $this_amount;
                $line_details[] = ['prev_limit' => $prev_limit, 'limit' => $limit, 'rate' => $rate, 'amount' => ($taxable_income - $prev_limit), 'subtotal' => $this_amount];
                break;
            }
            $prev_limit = $limit;
        }
        return [
            'deduction'      => $deduction,
            'taxable_income' => $taxable_income,
            'lines'          => $line_details,
            'total'          => $total_tax,
        ];
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
     * @return string
     */
    public function calculator(): string
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
        return view('tax_calculator', $data);
    }

    /**
     * Calculate tax for various countries
     * @return string
     */
    public function calculatorAjax(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('html_piece');
        }
        $tax_country    = $this->request->getPost('tax_country');
        $monthly_income = $this->request->getPost('monthly_income');
        $annual_income  = $this->request->getPost('annual_income');
        $tax_details    = $this->taxCalculation($annual_income, $tax_country);
        $data           = [
            'monthly_income' => $monthly_income,
            'annual_income'  => $annual_income,
            'tax_country'    => $tax_country,
            'tax_details'    => $tax_details,
            'currency_code'  => $this->currencies[$tax_country],
            'after_tax'      => $annual_income-$tax_details['total']
        ];
        return view('tax_calculator_ajax', $data);
    }

    /**
     * This page shows the projection of the income tax for various countries
     * @return string
     */
    public function projection(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'   => 'Tax Projection',
            'slug'         => 'tax-projection',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('tax_projection', $data);
    }

    public function projectionAjax(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('html_piece');
        }
        $tax_country = $this->request->getPost('tax_country');
        $min_income  = intval($this->request->getPost('min_income'));
        $max_income  = intval($this->request->getPost('max_income'));
        $step        = intval($this->request->getPost('step'));
        $tax_details = [];
        $graph       = [];
        for ($monthly_income = $min_income; $monthly_income <= $max_income; $monthly_income += $step) {
            $annual_income   = $monthly_income * 12;
            $calculation     = $this->taxCalculation($annual_income, $tax_country);
            $graph[]         = [
                'x' => $monthly_income,
                'y' => $calculation['total'],
                'l' => "Monthly income: " . number_format($monthly_income, 2) . "\nAnnual income: " . number_format($annual_income, 2) . "\nTax: " . number_format($calculation['total'], 2)
            ];
            $tax_details []  = [
                'monthly_income' => $monthly_income,
                'annual_income'  => $annual_income,
                'deduction'      => $calculation['deduction'],
                'taxable_income' => $calculation['taxable_income'],
                'total_tax'      => $calculation['total'],
                'lines'          => $calculation['lines'],
            ];
        }
        $data = [
            'tax_details' => $tax_details,
            'graph'       => $graph,
            'green_range' => $this->market_rates[$tax_country]
        ];
        return view('tax_projection_ajax', $data);
    }
}