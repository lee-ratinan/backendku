<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanySalaryModel extends Model
{
    protected $table = 'company_salary';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
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
        'google_drive_link',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 521;

    /**
     * @param string $currency_code
     * @param int $company_id
     * @param string $year
     * @return void
     */
    private function applyFilter(string $currency_code, int $company_id, string $year): void
    {
        if (!empty($currency_code)) {
            $this->where('payment_currency', $currency_code);
        }
        if (!empty($company_id)) {
            $this->where('company_id', $company_id);
        }
        if (!empty($year)) {
            $this->where('tax_year', $year);
        }
    }

    /**
     * @param int $start
     * @param int $length
     * @param string $order_column
     * @param string $order_direction
     * @param string $currency_code
     * @param int $company_id
     * @param string $year
     * @return array
     */
    public function getDataTables(int $start, int $length, string $order_column, string $order_direction, string $currency_code, int $company_id, string $year): array
    {
        $columns_to_calc = [
            9 => 'base_amount',
            10 => 'allowance_amount',
            11 => 'training_amount',
            12 => 'overtime_amount',
            13 => 'adjustment_amount',
            14 => 'bonus_amount',
            15 => 'subtotal_amount',
            16 => 'social_security_amount',
            17 => 'us_tax_fed_amount',
            18 => 'us_tax_state_amount',
            19 => 'us_tax_city_amount',
            20 => 'us_tax_med_ee_amount',
            21 => 'us_tax_oasdi_ee_amount',
            22 => 'th_tax_amount',
            23 => 'sg_tax_amount',
            24 => 'au_tax_amount',
            25 => 'claim_amount',
            26 => 'provident_fund_amount',
            27 => 'total_amount'
        ];
        $record_total    = $this->countAllResults();
        $record_filtered = $record_total;
        if (!empty($currency_code) || !empty($company_id) || !empty($year)) {
            $this->applyFilter($currency_code, $company_id, $year);
            $record_filtered = $this->countAllResults();
            $this->applyFilter($currency_code, $company_id, $year);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->select('company_salary.*, company_master.company_legal_name')
            ->join('company_master', 'company_salary.company_id = company_master.id', 'left outer')
            ->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        $countries  = lang('ListCountries.countries');
        $footer     = [];
        foreach ($raw_result as $row) {
            $new_id       = $row['id'] * self::ID_NONCE;
            $result[]     = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/employment/salary/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['id'],
                ('US' == $row['tax_country_code'] ? date(DATE_FORMAT_UI, strtotime($row['pay_date'])) : date(MONTH_FORMAT_UI, strtotime($row['pay_date']))),
                $row['company_legal_name'],
                $row['tax_year'],
                '<span class="flag-icon flag-icon-' . strtolower($row['tax_country_code']) . '"></span> ' . $countries[$row['tax_country_code']]['common_name'],
                strtoupper($row['payment_method']),
                $row['payment_currency'],
                ucwords(str_replace('_', ' ', $row['pay_type'])),
                currency_format($row['payment_currency'], $row['base_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['allowance_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['training_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['overtime_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['adjustment_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['bonus_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['subtotal_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['social_security_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['us_tax_fed_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['us_tax_state_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['us_tax_city_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['us_tax_med_ee_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['us_tax_oasdi_ee_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['th_tax_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['sg_tax_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['au_tax_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['claim_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['provident_fund_amount'] ?? 0),
                currency_format($row['payment_currency'], $row['total_amount'] ?? 0),
                $row['payment_details'],
                (empty($row['google_drive_link']) ? '' : '<a class="btn btn-sm btn-outline-primary" href="' . $row['google_drive_link'] . '" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>'),
            ];
            foreach ($columns_to_calc as $key => $column) {
                if (isset($row[$column]) && 0 < $row[$column]) {
                    $footer[$key][$row['payment_currency']] = (isset($footer[$key][$row['payment_currency']]) ? $footer[$key][$row['payment_currency']] + $row[$column] : $row[$column]);
                }
            }
        }
        $footer_value    = [];
        for ($i = 0; $i <= 29; $i++) {
            $footer_value[$i] = '';
            if (isset($footer[$i])) {
                foreach ($footer[$i] as $code => $amount) {
                    $footer_value[$i] .= currency_format($code, $amount) . '<br>';
                }
            }
        }
        $footer_value[0] = 'Total';
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result,
            'footer'          => $footer_value
        ];
    }
}