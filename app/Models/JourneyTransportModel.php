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
     * @param int $minutes
     * @return string
     */
    private function printMinutes(int $minutes): string
    {
        $hour   = floor($minutes/60);
        $minute = $minutes%60;
        if (0 == $hour) {
            return "{$minute}m";
        }
        return "{$hour}h {$minute}m";
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
                ->like('journey_transport.flight_number', $search_value)
                ->orLike('journey_transport.pnr_number', $search_value)
                ->orLike('port_departure.port_name', $search_value)
                ->orLike('port_arrival.port_name', $search_value)
                ->orLike('journey_operator.operator_name', $search_value)
                ->orLike('journey_transport.journey_details', $search_value)
                ->groupEnd();
        }
        if (!empty($country_code)) {
            $this->groupStart()
                ->where('port_departure.country_code', $country_code)
                ->orwhere('port_arrival.country_code', $country_code)
                ->groupEnd();
        }
        if (!empty($year)) {
            $this->groupStart()
                ->where('arrival_date_time >=', $year . '-01-01')
                ->where('departure_date_time <=', $year . '-12-31')
                ->groupEnd();
        }
        if (!empty($journey_status)) {
            $this->where('journey_transport.journey_status', $journey_status);
        }
        if (!empty($mode_of_transport)) {
            $this->where('journey_transport.mode_of_transport', $mode_of_transport);
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
            $record_filtered = $this
                ->join('journey_operator', 'journey_transport.operator_id = journey_operator.id', 'left outer')
                ->join('journey_port AS port_departure', 'journey_transport.departure_port_id = port_departure.id', 'left outer')
                ->join('journey_port AS port_arrival',   'journey_transport.arrival_port_id = port_arrival.id', 'left outer')
                ->countAllResults();
            $this->applyFilter($search_value, $country_code, $year, $journey_status, $mode_of_transport);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->select('journey_transport.*, journey_operator.operator_name, journey_operator.operator_logo_file_name,
            port_departure.port_name AS departure_port_name, port_departure.country_code AS departure_country_code, port_departure.port_code_1 AS departure_port_code,
            port_arrival.port_name AS arrival_port_name,     port_arrival.country_code AS arrival_country_code,     port_arrival.port_code_1 AS arrival_port_code')
            ->join('journey_operator', 'journey_transport.operator_id = journey_operator.id', 'left outer')
            ->join('journey_port AS port_departure', 'journey_transport.departure_port_id = port_departure.id', 'left outer')
            ->join('journey_port AS port_arrival',   'journey_transport.arrival_port_id = port_arrival.id', 'left outer')
            ->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        foreach ($raw_result as $row) {
            $new_id         = $row['id'] * self::ID_NONCE;
            $flight_numbers = [];
            $canceled_class = '';
            if ('canceled' == $row['journey_status']) {
                $canceled_class = 'text-danger';
            }
            if (!empty($row['flight_number'])) {
                $flight_numbers[] = "<h4 class='mb-0 {$canceled_class}'>" . $row['flight_number'] . '</h4>';
            }
            if (!empty($row['pnr_number'])) {
                $flight_numbers[] = '<i class="fa-solid fa-ticket text-success"></i> <b>' . $row['pnr_number'] . '</b>';
            }
            if (empty($flight_numbers) && 'canceled' == $row['journey_status']) {
                $flight_numbers[] = '<i class="fa-solid fa-times-circle text-danger"></i> <b>CANCELED</b>';
            }
            $journey_details = str_replace('[R]', '<span class="badge bg-success"><i class="fa-solid fa-rotate-left"></i> RETURN</span>', $row['journey_details'] ?? '');
            $journey_details = str_replace('[C]', '<span class="badge bg-success"><i class="fa-solid fa-right-left"></i> CONNECTING</span>', $journey_details);
            $journey_details = str_replace('[RI]', '<span class="badge bg-success"><i class="fa-solid fa-hand-holding-dollar"></i> REIMBURSED</span>', $journey_details);
            if ('Y' == $row['is_time_known']) {
                $departure_time  = date(DATETIME_FORMAT_UI, strtotime($row['departure_date_time']));
                $departure_time .= '<br><small>' . lang('ListTimeZones.timezones.' . $row['departure_timezone'] . '.label') . '</small>';
                $arrival_time    = date(DATETIME_FORMAT_UI, strtotime($row['arrival_date_time']));
                $arrival_time   .= '<br><small>' . lang('ListTimeZones.timezones.' . $row['arrival_timezone'] . '.label') . '</small>';
            } else {
                $departure_time = date(DATE_FORMAT_UI, strtotime($row['departure_date_time']));
                $arrival_time   = date(DATE_FORMAT_UI, strtotime($row['arrival_date_time']));
            }
            $result[]      = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/journey/transport/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['id'],
                (empty($flight_numbers) ? '-' : implode('', $flight_numbers)),
                '<img style="height:2.5rem" class="img-thumbnail" src="' . base_url('file/operator-' . $row['operator_logo_file_name'] . '.png') . '" alt="' . $row['flight_number'] . '" /><br>' . $row['operator_name'],
                $this->getModeOfTransport($row['mode_of_transport']) . (empty($row['craft_type']) ? '' : '<br><small><i class="fa-solid fa-caret-right"></i> ' . $row['craft_type'] . '</small>'),
                $departure_time,
                $arrival_time,
                (empty($row['departure_port_code']) ? '' : '<h4 class="mb-0">' . $row['departure_port_code'] . '</h4>') .
                '<span class="flag-icon flag-icon-' . strtolower($row['departure_country_code']) . '"></span> ' . $row['departure_port_name'],
                (empty($row['arrival_port_code']) ? '' : '<h4 class="mb-0">' . $row['arrival_port_code'] . '</h4>') .
                '<span class="flag-icon flag-icon-' . strtolower($row['arrival_country_code']) . '"></span> ' . $row['arrival_port_name'],
                empty($row['trip_duration']) ? '-' : $this->printMinutes($row['trip_duration']),
                empty($row['distance_traveled']) ? '-' : number_format($row['distance_traveled']) . ' km',
                (empty($row['price_amount']) ? '-' : currency_format($row['price_currency_code'], $row['price_amount'])) .
                (empty($row['charged_amount']) ? '' : '<br><span class="badge badge-success"><i class="fa-regular fa-credit-card"></i></span>' . currency_format($row['charged_currency_code'], $row['charged_amount'])),
                $journey_details,
                (empty($row['google_drive_link']) ? '' : '<a class="btn btn-sm btn-outline-primary" href="' . $row['google_drive_link'] . '" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>'),
                translate_journey_status($row['journey_status'], $row['departure_date_time'], $row['arrival_date_time'], date('Y-m-d')),
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result
        ];
    }

    /**
     * @param int $id This can be either the primary key or journey_id
     * @param string $field (optional), it should be either 'id' or 'journey_id'
     * @return array
     */
    public function findById(int $id, string $field = 'id'): array
    {
        if (!in_array($field, ['id', 'journey_id'])) {
            return [];
        }
        return $this->select('journey_transport.*, journey_operator.operator_name, journey_operator.operator_logo_file_name,
            port_departure.port_name AS departure_port_name, port_departure.country_code AS departure_country_code, port_departure.port_code_1 AS departure_port_code,
            port_arrival.port_name AS arrival_port_name,     port_arrival.country_code AS arrival_country_code,     port_arrival.port_code_1 AS arrival_port_code')
            ->join('journey_operator', 'journey_transport.operator_id = journey_operator.id', 'left outer')
            ->join('journey_port AS port_departure', 'journey_transport.departure_port_id = port_departure.id', 'left outer')
            ->join('journey_port AS port_arrival',   'journey_transport.arrival_port_id = port_arrival.id', 'left outer')
            ->where('journey_id', $id)
            ->orderBy('departure_date_time', 'asc')
            ->findAll();
    }

}