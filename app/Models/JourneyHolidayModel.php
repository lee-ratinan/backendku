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
     * @param string $start
     * @param string $end
     * @return void
     */
    private function applyFilter(string $search_value, string $country_code, string $start, string $end): void
    {
        if (!empty($search_value)) {
            $this->like('holiday_name', $search_value);
        }
        if (!empty($country_code)) {
            $parts = explode('-', $country_code);
            $this->where('country_code', $parts[0]);
            if (!empty($parts[1]) && 'ALL' != $parts[1]) {
                $this->whereIn('region_code', [$parts[1], 'NAT', 'FED']);
            }
        }
        if (!empty($start)) {
            $this->where('holiday_date_to >=', $start);
        }
        if (!empty($end)) {
            $this->where('holiday_date <=', $end);
        }
    }

    /**
     * Get DataTables, but don't allow ordering and paging
     * @param string $search_value
     * @param string $country_code
     * @param string $start
     * @param string $end
     * @return array
     */
    public function getDataTables(string $search_value, string $country_code, string $start, string $end): array
    {
        if (!empty($search_value) || !empty($country_code) || !empty($start) || !empty($end)) {
            $this->applyFilter($search_value, $country_code, $start, $end);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->orderBy('holiday_date', 'asc')->orderBy('country_code', 'asc')->findAll();
        $countries  = lang('ListCountries.countries');
        $result     = [];
        $group_date = [];
        foreach ($raw_result as $row) {
            $new_id  = $row['id'] * self::ID_NONCE;
            $start   = $row['holiday_date'];
            $end     = $row['holiday_date_to'];
            $range   = [];
            while ($start <= $end) {
                $range[] = $start;
                $start   = date('Y-m-d', strtotime($start . ' +1 day'));
            }
            foreach ($range as $date) {
                $country = ('XV' == $row['country_code'] ? '<span class="badge text-bg-success rounded-pill"><h6 class="mb-0 d-inline-block"><i class="fa-solid fa-magnifying-glass-location"></i> Vacation</h6></span>' : '<h5 class="d-inline-block"><span class="flag-icon flag-icon-' . strtolower($row['country_code']) . '"></span> ' . $countries[$row['country_code']]['common_name'] . '</h5>');
                if (!empty($row['region_code'])) {
                    if (('AU' == $row['country_code'] && 'NAT' != $row['region_code'])
                        || ('US' == $row['country_code'] && 'FED' != $row['region_code'])){
                        $country .= " <span class='badge text-bg-danger rounded-pill'>{$row['region_code']}</span>";
                    }
                }
                $group_date[$date][] = [
                    'edit_link'    => base_url($locale . '/office/journey/holiday/edit/' . $new_id),
                    'country'      => $country,
                    'holiday_name' => $row['holiday_name'],
                ];
            }
        }
        ksort($group_date);
        foreach ($group_date as $date => $data) {
            $detail = '';
            foreach ($data as $row) {
                $detail .= '<a class="btn btn-outline-primary btn-sm me-3" href="' . $row['edit_link'] . '"><i class="fa-solid fa-edit"></i></a>' . $row['country'] . ' <h4 class="d-inline-block">' . $row['holiday_name'] . '</h4><br>';
            }
            $result[] = [
                date(DATE_FORMAT_UI . " (D)", strtotime($date)),
                $detail,
            ];
        }
        $count = count($result);
        return [
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => $result
        ];
    }

}