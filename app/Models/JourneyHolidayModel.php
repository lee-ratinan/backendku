<?php

namespace App\Models;

use CodeIgniter\Model;

class JourneyHolidayModel extends Model
{
    protected $table = 'journey_holiday';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'country_code',
        'holiday_date',
        'holiday_date_to',
        'holiday_name',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 757;

    /**
     * @param string $search_value
     * @param string $country_code
     * @param string $year
     * @return void
     */
    private function applyFilter(string $search_value, string $country_code, string $year): void
    {
        if (!empty($search_value)) {
            $this->like('holiday_name', $search_value);
        }
        if (!empty($country_code)) {
            $this->where('country_code', $country_code);
        }
        if (!empty($year)) {
            $this->where('holiday_date <=', $year . '-12-31')
                ->groupStart()
                ->where('holiday_date_to >=', $year . '-01-01')
                ->orWhere('holiday_date_to', null)
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
    public function getDataTables(int $start, int $length, string $order_column, string $order_direction, string $search_value, string $country_code, string $year): array
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
            $country      = ('XV' == $row['country_code'] ? '<h5>Vacation</h5>' : '<span class="flag-icon flag-icon-' . strtolower($row['country_code']) . '"></span><h5>' . $countries[$row['country_code']]['common_name'] . '</h5>');
            $result[]     = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/journey/holiday/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['id'],
                $country,
                date(DATE_FORMAT_UI, strtotime($row['holiday_date'])) . (empty($row['holiday_date_to']) ? '' : ' to ' . date(DATE_FORMAT_UI, strtotime($row['holiday_date_to']))),
                $row['holiday_name']
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result
        ];
    }

}