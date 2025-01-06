<?php

namespace App\Models;

use CodeIgniter\Model;

class JourneyTransportModel extends Model
{
    protected $table = 'journey_transport';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'journey_id',
        'operator_id',
        'departure_port_id',
        'arrival_port_id',
        'flight_number',
        'pnr_number',
        'departure_date_time',
        'departure_timezone',
        'arrival_date_time',
        'arrival_timezone',
        'is_time_known',
        'trip_duration',
        'distance_traveled',
        'mode_of_transport',
        'craft_type',
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
    const ID_NONCE = 587;

    /**
     * Get mode of transport
     * @param string $mode (optional)
     * @return string|array
     */
    public function getModeOfTransport(string $mode = ''): string|array
    {
        $modes = [
            'airplane'        => '<i class="fa-solid fa-plane-departure"></i> Airplane',
            'ferry'           => '<i class="fa-solid fa-ferry"></i> Ferry',
            'train'           => '<i class="fa-solid fa-train"></i> Train',
            'high_speed_rail' => '<i class="fa-solid fa-train"></i> High Speed Rail',
            'bus'             => '<i class="fa-solid fa-bus"></i> Bus',
        ];
        if (empty($mode)) {
            return $modes;
        }
        return $modes[$mode] ?? '[ERROR]';
    }

    /**
     * @param string $search_value
     * @param string $country_code
     * @param string $year
     * @param string $journey_status
     * @param string $mode_of_transport
     * @return void
     */
    private function applyFilter(string $search_value, string $country_code, string $year, string $journey_status, string $mode_of_transport): void
    {
        if (!empty($search_value)) {
            $this->groupStart()
                ->like('flight_number', $search_value)
                ->orLike('pnr_number', $search_value)
                ->orLike('port_origin.port_name', $search_value)
                ->orLike('port_destination.port_name', $search_value)
                ->orLike('journey_operator.operator_name', $search_value)
                ->orLike('journey_details', $search_value)
                ->groupEnd();
        }
        if (!empty($country_code)) {
            $this->groupStart()
                ->where('port_origin.country_code', $country_code)
                ->orwhere('port_destination.country_code', $country_code)
                ->groupEnd();
        }
        if (!empty($year)) {
            $this->groupStart()
                ->where('arrival_date_time >=', $year . '-01-01')
                ->where('departure_date_time <=', $year . '-12-31')
                ->groupEnd();
        }
        if (!empty($journey_status)) {
            $this->where('journey_status', $journey_status);
        }
        if (!empty($mode_of_transport)) {
            $this->where('mode_of_transport', $mode_of_transport);
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
     * @param string $mode_of_transport
     * @return array
     */
    public function getDataTables(int $start, int $length, string $order_column, string $order_direction, string $search_value, string $country_code, string $year, string $journey_status, string $mode_of_transport): array
    {
        $record_total    = $this->countAllResults();
        $record_filtered = $record_total;
        if (!empty($search_value) || !empty($country_code) || !empty($year) || !empty($journey_status) || !empty($mode_of_transport)) {
            $this->applyFilter($search_value, $country_code, $year, $journey_status, $mode_of_transport);
            $record_filtered = $this->countAllResults();
            $this->applyFilter($search_value, $country_code, $year, $journey_status, $mode_of_transport);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->select('journey_transport.*, journey_operator.operator_name, journey_operator.operator_logo_file_name,
            port_departure.port_name AS departure_port_name, port_departure.country_code AS departure_country_code,
            port_arrival.port_name AS arrival_port_name,     port_arrival.country_code AS arrival_country_code')
            ->join('journey_operator', 'journey_transport.operator_id = journey_operator.id', 'left outer')
            ->join('journey_port AS port_departure', 'journey_transport.departure_port_id = port_departure.id', 'left outer')
            ->join('journey_port AS port_arrival',   'journey_transport.arrival_port_id = port_arrival.id', 'left outer')
            ->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        $countries  = lang('ListCountries.countries');
        foreach ($raw_result as $row) {
            $new_id         = $row['id'] * self::ID_NONCE;
            $flight_numbers = [];
            if (!empty($row['flight_number'])) {
                $flight_numbers[] = '<b>' . $row['flight_number'] . '</b>';
            }
            if (!empty($row['pnr_number'])) {
                $flight_numbers[] = '<span class="badge bg-success-subtle">PNR</span> <b>' . $row['pnr_number'] . '</b>';
            }
            $departure_time = '';
            $arrival_time   = '';
            if ('Y' == $row['is_time_known']) {
                $departure_time  = date(DATETIME_FORMAT_UI, strtotime($row['departure_date_time']));
                $departure_time .= '<br><small>' . lang('ListTimeZones.' . $row['departure_timezone'] . '.label') . '</small>';
                $arrival_time    = date(DATETIME_FORMAT_UI, strtotime($row['arrival_date_time']));
                $arrival_time   .= '<br><small>' . lang('ListTimeZones.' . $row['arrival_timezone'] . '.label') . '</small>';
            } else {
                $departure_time = date(DATE_FORMAT_UI, strtotime($row['departure_date_time']));
                $arrival_time   = date(DATE_FORMAT_UI, strtotime($row['arrival_date_time']));
            }


            $result[]      = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/journey/transport/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['id'],
                (empty($flight_numbers) ? '-' : implode('<br>', $flight_numbers)),
                '<img style="height:2.5rem" class="img-thumbnail me-3" src="' . base_url('file/journey_operator_' . $row['operator_logo_file_name'] . '.png') . '" />' . $row['operator_name'],
                $this->getModeOfTransport($row['mode_of_transport']),
                $departure_time,
                $arrival_time,
                '<span class="flag-icon flag-icon-' . strtolower($row['departure_country_code']) . '"></span> ' . $row['departure_port_name'],
                '<span class="flag-icon flag-icon-' . strtolower($row['arrival_country_code']) . '"></span> ' . $row['arrival_port_name'],
                $row['trip_duration'],
                $row['distance_traveled'],
                $row['price_amount'] . ' ' . $row['price_currency_code'],
                $row['journey_details'],
                (empty($row['google_drive_link']) ? '' : '<a href="' . $row['google_drive_link'] . '" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>'),
                translate_journey_status($row['journey_status'], $row['departure_date_time'], $row['arrival_date_time'], date('Y-m-d')),
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result
        ];
    }

}