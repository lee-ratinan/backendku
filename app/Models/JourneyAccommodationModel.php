<?php

namespace App\Models;

use CodeIgniter\Model;

class JourneyAccommodationModel extends Model
{
    protected $table = 'journey_accommodation';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'journey_id',
        'country_code',
        'check_in_date',
        'check_out_date',
        'night_count',
        'hotel_name',
        'hotel_address',
        'booking_channel',
        'room_type',
        'breakfast_included',
        'price_amount',
        'price_currency_code',
        'charged_amount',
        'charged_currency_code',
        'journey_details',
        'journey_status',
        'google_drive_link',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 607;

    /**
     * @param string $search_value
     * @param string $country_code
     * @param string $year
     * @param string $journey_status
     * @return void
     */
    private function applyFilter(string $search_value, string $country_code, string $year, string $journey_status): void
    {
        if (!empty($search_value)) {
            $this->groupStart()
                ->like('hotel_name', $search_value)
                ->orLike('hotel_address', $search_value)
                ->orLike('room_type', $search_value)
                ->orLike('journey_details', $search_value)
                ->groupEnd();
        }
        if (!empty($country_code)) {
            $this->where('country_code', $country_code);
        }
        if (!empty($year)) {
            $this->where('check_in_date <=', $year . '-12-31')
                ->groupStart()
                ->where('check_out_date >=', $year . '-01-01')
                ->orWhere('check_out_date', null)
                ->groupEnd();
        }
        if (!empty($journey_status)) {
            $this->where('journey_status', $journey_status);
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
     * @param string $journey_status
     * @return array
     */
    public function getDataTables(int $start, int $length, string $order_column, string $order_direction, string $search_value, string $country_code, string $year, string $journey_status): array
    {
        $record_total    = $this->countAllResults();
        $record_filtered = $record_total;
        if (!empty($search_value) || !empty($country_code) || !empty($year) || !empty($journey_status)) {
            $this->applyFilter($search_value, $country_code, $year, $journey_status);
            $record_filtered = $this->countAllResults();
            $this->applyFilter($search_value, $country_code, $year, $journey_status);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        $countries  = lang('ListCountries.countries');
        foreach ($raw_result as $row) {
            $new_id       = $row['id'] * self::ID_NONCE;
            $journey_details = str_replace('[RI]', '<span class="badge bg-success"><i class="fa-solid fa-hand-holding-dollar"></i> REIMBURSED</span>', $row['journey_details']);
            $class        = '';
            if ('canceled' == $row['journey_status']) {
                $class    = 'text-danger';
            }
            $result[]     = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/journey/accommodation/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['id'],
                '<span class="flag-icon flag-icon-' . strtolower($row['country_code']) . '"></span><h5 class="' . $class . '">' . $countries[$row['country_code']]['common_name'] . '</h5>',
                (empty($row['check_in_date']) ? '' : date(DATE_FORMAT_UI, strtotime($row['check_in_date']))),
                (empty($row['check_out_date']) ? '' : date(DATE_FORMAT_UI, strtotime($row['check_out_date']))),
                $row['night_count'],
                $row['hotel_name'] . '<br><small>' . $row['hotel_address'] . '</small>',
                $row['booking_channel'],
                $row['room_type'],
                ('Y' == $row['breakfast_included'] ? '<i class="fa-solid fa-check text-success"></i>' : '-'),
                (empty($row['price_amount']) ? '-' : currency_format($row['price_currency_code'], $row['price_amount'])) .
                (empty($row['charged_amount']) ? '' : '<br><span class="badge badge-success"><i class="fa-regular fa-credit-card"></i></span>' . currency_format($row['charged_currency_code'], $row['charged_amount'])),
                $journey_details,
                (empty($row['google_drive_link']) ? '' : '<a class="btn btn-sm btn-outline-primary" href="' . $row['google_drive_link'] . '" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>'),
                translate_journey_status($row['journey_status'], $row['check_in_date'], $row['check_out_date'], date('Y-m-d')),
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result
        ];
    }

}