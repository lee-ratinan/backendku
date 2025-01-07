<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyMasterModel extends Model
{
    protected $table = 'company_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'user_id',
        'company_slug',
        'company_legal_name',
        'company_trade_name',
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
        'position_titles',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 647;

    /**
     * @param string $search_value
     * @param string $country_code
     * @param string $year
     * @return void
     */
    private function applyFilter(string $search_value, string $country_code, string $year): void
    {
        if (!empty($search_value)) {
            $this->groupStart()
                ->like('company_legal_name', $search_value)
                ->orLike('company_trade_name', $search_value)
                ->orLike('company_address', $search_value)
                ->orLike('position_titles', $search_value)
                ->groupEnd();
        }
        if (!empty($country_code)) {
            $this->groupStart()
                ->where('company_country_code', $country_code)
                ->orWhere('company_hq_country_code', $country_code)
                ->groupEnd();
        }
        if (!empty($year)) {
            $this
                ->where('employment_start_date <=', $year . '-12-31')
                ->groupStart()
                ->where('employment_end_date >=', $year . '-01-01')
                ->orWhere('employment_end_date', null)
                ->groupEnd();
        }
    }

    /**
     * @param int $start
     * @param int $length
     * @param string $order_column
     * @param string $order_direction
     * @param string $search_value
     * @param string $country_code
     * @param string $year
     * @return array
     */
    public function getDataTables(int $start, int $length, string $order_column, string $order_direction, string $search_value, string $country_code = '', string $year = ''): array
    {
        $record_total    = $this->countAllResults();
        $record_filtered = $record_total;
        if (!empty($search_value) || !empty($country_code) || !empty($year)) {
            $this->applyFilter($search_value, $country_code, $year);
            $record_filtered = $this->countAllResults();
            $this->applyFilter($search_value, $country_code, $year);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        $countries  = lang('ListCountries.countries');
        foreach ($raw_result as $row) {
            $new_id       = $row['id'] * self::ID_NONCE;
            $position_str = '';
            $positions    = explode(';', $row['position_titles']);
            if (1 == count($positions)) {
                $position_str = $positions[0];
            } else {
                foreach ($positions as $position) {
                    $explode = explode(':', $position);
                    $position_str .= date(MONTH_FORMAT_UI, strtotime($explode[0] . '-01')) . ': ' . $explode[1] . '<br>';
                }
            }
            $result[]     = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/employment/company/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['id'],
                '<img style="height:4rem" class="img-thumbnail" src="' . base_url('file/company-' . $row['company_slug'] . '.png') . '" alt="' . $row['company_legal_name'] . '" />',
                '<b>' . $row['company_legal_name'] . '</b>' . ($row['company_trade_name'] == $row['company_legal_name'] ? '' : '<br>' . $row['company_trade_name']),
                '<span class="flag-icon flag-icon-' . strtolower($row['company_country_code']) . '"></span> ' . $countries[$row['company_country_code']]['common_name'],
                '<span class="flag-icon flag-icon-' . strtolower($row['company_hq_country_code']) . '"></span> ' . $countries[$row['company_hq_country_code']]['common_name'],
                date(DATE_FORMAT_UI, strtotime($row['employment_start_date'])),
                (empty($row['employment_end_date']) ? 'present' : date(DATE_FORMAT_UI, strtotime($row['employment_end_date']))),
                $position_str
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result
        ];
    }
}