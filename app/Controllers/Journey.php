<?php

namespace App\Controllers;

use App\Models\JourneyAccommodationModel;
use App\Models\JourneyAttractionModel;
use App\Models\JourneyHolidayModel;
use App\Models\JourneyMasterModel;
use App\Models\JourneyOperatorModel;
use App\Models\JourneyPortModel;
use App\Models\JourneyTransportModel;
use CodeIgniter\HTTP\ResponseInterface;

class Journey extends BaseController
{

    const PERMISSION_REQUIRED = 'journey';
    private array $color_classes = [
        ['red', 'gold'],
        ['yellow', 'black'],
        ['blue', 'bronze'],
        ['green', 'silver']
    ];

    /************************************************************************
     * TRIP
     ************************************************************************/

    /**
     * This page shows all journeys (immigration records)
     * @return string
     */
    public function trip(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'   => 'Trip',
            'slug'         => 'trip',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'countries'    => lang('ListCountries.countries'),
        ];
        return view('journey_trip', $data);
    }

    /**
     * This API retrieves immigration data and return for DataTables
     * Table: journey_master
     * @return ResponseInterface
     */
    public function tripList(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        $model              = new JourneyMasterModel();
        $columns            = [
            '',
            'journey_master.id',
            'journey_master.country_code',
            'journey_master.date_entry',
            'journey_master.day_count',
            'entry_port.port_name',
            'exit_port.port_name',
            'journey_master.journey_details',
            'journey_master.journey_status'
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
        $journey_status     = $this->request->getPost('journey_status');
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value, $country_code, $year, $journey_status);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    public function tripEdit(string $port_code = 'new')
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
    }

    public function tripSave()
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
    }

    /**
     * @return string
     */
    public function tripStatistics(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session  = session();
        $model    = new JourneyMasterModel();
        $raw_data = $model->where('journey_status', 'as_planned')->where('date_entry <=', date(DATE_FORMAT_DB))->findAll();
        $visited_countries_by_year    = [];
        $countries_by_visits          = [];
        foreach ($raw_data as $row) {
            $year_1 = intval(substr($row['date_entry'], 0, 4));
            $year_2 = intval(substr($row['date_exit'], 0, 4));
            $years  = range($year_1, $year_2);
            foreach ($years as $year) {
                $visited_countries_by_year[$year][$row['country_code']] = 1;
            }
            $countries_by_visits[$row['country_code']] = (isset($countries_by_visits[$row['country_code']]) ? $countries_by_visits[$row['country_code']] + 1 : 1);
        }
        ksort($countries_by_visits);
        $data = [
            'page_title'                   => 'Statistics',
            'slug'                         => 'trip',
            'user_session'                 => $session->user,
            'roles'                        => $session->roles,
            'current_role'                 => $session->current_role,
            'visited_countries_by_year'    => $visited_countries_by_year,
            'countries_by_visits'          => $countries_by_visits,
            'countries'                    => lang('ListCountries.countries'),
            'countries_considered_home'    => ['SG', 'TH']
        ];
        return view('journey_trip_statistics', $data);
    }

    /************************************************************************
     * TRANSPORT
     ************************************************************************/

    /**
     * @return string
     */
    public function transport(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $model   = new JourneyTransportModel();
        $data    = [
            'page_title'   => 'Transport',
            'slug'         => 'transport',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'countries'    => lang('ListCountries.countries'),
            'modes'        => $model->getModeOfTransport()
        ];
        return view('journey_transport', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function transportList(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        $model              = new JourneyTransportModel();
        $columns            = [
            '',
            'journey_transport.id',
            'journey_transport.flight_number',
            'journey_operator.operator_name',
            'journey_transport.mode_of_transport',
            'journey_transport.departure_date_time',
            'journey_transport.arrival_date_time',
            'port_departure.port_name',
            'port_arrival.port_name',
            'journey_transport.trip_duration',
            'journey_transport.distance_traveled',
            'journey_transport.price_amount',
            'journey_transport.journey_details',
            'journey_transport.google_drive_link',
            'journey_transport.journey_status',
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
        $journey_status     = $this->request->getPost('journey_status');
        $mode_of_transport  = $this->request->getPost('mode_of_transport');
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value, $country_code, $year, $journey_status, $mode_of_transport);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    public function transportEdit(string $port_code = 'new', int $journey_id = 0)
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
    }

    public function transportSave()
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
    }

    /**
     * @return string
     */
    public function transportStatistics(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session    = session();
        $model      = new JourneyTransportModel();
        // Count everything that departs before end of today
        $end_today  = date(DATE_FORMAT_DB) . ' 23:59:59';
        $raw_data   = $model->where('journey_status', 'as_planned')->where('departure_date_time <=', $end_today)->orderBy('departure_date_time', 'asc')->findAll();
        // Prepare the data
        $generic_stats       = [];
        $distant_by_year     = []; // only flights
        $distant_by_year_sum = []; // only flights
        foreach ($raw_data as $row) {
            $year = substr($row['departure_date_time'], 0, 4);
            // Generic
            $generic_stats['count_by_mode'][$row['mode_of_transport']] = (isset($generic_stats['count_by_mode'][$row['mode_of_transport']]) ? $generic_stats['count_by_mode'][$row['mode_of_transport']] + 1 : 1);
            $generic_stats['distant_by_mode'][$row['mode_of_transport']] = (isset($generic_stats['distant_by_mode'][$row['mode_of_transport']]) ? $generic_stats['distant_by_mode'][$row['mode_of_transport']] + $row['distance_traveled'] : $row['distance_traveled']);
            // Distant
            if ('airplane' == $row['mode_of_transport']) {
                $distant_by_year[$year][] = $row['distance_traveled'];
                $distant_by_year_sum[$year] = (isset($distant_by_year_sum[$year]) ? $distant_by_year_sum[$year] + $row['distance_traveled'] : $row['distance_traveled']);
            }
        }
        $data = [
            'page_title'          => 'Statistics',
            'slug'                => 'transport',
            'user_session'        => $session->user,
            'roles'               => $session->roles,
            'current_role'        => $session->current_role,
            'color_classes'       => $this->color_classes,
            'countries'           => lang('ListCountries.countries'),
            'modes_of_transport'  => $model->getModeOfTransport(),
            'generic_stats'       => $generic_stats,
            'distant_by_year'     => $distant_by_year,
            'distant_by_year_sum' => $distant_by_year_sum,
            'distant_by_year_max' => max($distant_by_year_sum),
        ];
        return view('journey_transport_statistics', $data);
    }

    /************************************************************************
     * ACCOMMODATION
     ************************************************************************/

    /**
     * @return string
     */
    public function accommodation(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'   => 'Accommodation',
            'slug'         => 'accommodation',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'countries'    => lang('ListCountries.countries'),
        ];
        return view('journey_accommodation', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function accommodationList(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        $model              = new JourneyAccommodationModel();
        $columns            = [
            '',
            'id',
            'country_code',
            'check_in_date',
            'check_out_date',
            'night_count',
            'hotel_name',
            'booking_channel',
            'room_type',
            'breakfast_included',
            'price_amount',
            'journey_details',
            'google_drive_link',
            'journey_status',
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
        $journey_status     = $this->request->getPost('journey_status');
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value, $country_code, $year, $journey_status);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    public function accommodationEdit(string $port_code = 'new', int $journey_id = 0)
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
    }

    public function accommodationSave()
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
    }

    /**
     * @return string
     */
    public function accommodationStatistics(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session    = session();
        $model      = new JourneyAccommodationModel();
        $by_country = [];
        $by_year    = [];
        $raw_data   = $model->where('journey_status', 'as_planned')->where('check_in_date <=', date(DATE_FORMAT_DB))->orderBy('check_in_date', 'asc')->findAll();
        foreach ($raw_data as $row) {
            // count it based on check-in date, although check-out is not in the same year...
            $year = substr($row['check_in_date'], 0, 4);
            $by_year[$year][$row['country_code']][] = $row['night_count'];
            $by_country[$row['country_code']][]     = $row['night_count'];
        }
        ksort($by_country);
        $data = [
            'page_title'    => 'Statistics',
            'slug'          => 'accommodation',
            'user_session'  => $session->user,
            'roles'         => $session->roles,
            'current_role'  => $session->current_role,
            'color_classes' => $this->color_classes,
            'countries'     => lang('ListCountries.countries'),
            'by_year'       => $by_year,
            'by_country'    => $by_country,
        ];
        return view('journey_accommodation_statistics', $data);
    }

    /************************************************************************
     * ATTRACTION
     ************************************************************************/

    /**
     * @return string
     */
    public function attraction(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'   => 'Attraction',
            'slug'         => 'attraction',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'countries'    => lang('ListCountries.countries'),
        ];
        return view('journey_attraction', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function attractionList(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        $model              = new JourneyAttractionModel();
        $columns            = [
            '',
            'id',
            'country_code',
            'attraction_date',
            'attraction_title',
            'price_amount',
            'journey_details',
            'google_drive_link',
            'journey_status',
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
        $journey_status     = $this->request->getPost('journey_status');
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value, $country_code, $year, $journey_status);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    public function attractionEdit(string $port_code = 'new', int $journey_id = 0)
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
    }

    public function attractionSave()
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
    }

    /************************************************************************
     * PORT
     ************************************************************************/

    /**
     * @return string
     */
    public function port(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session   = session();
        $model     = new JourneyPortModel();
        $data      = [
            'page_title'   => 'Port',
            'slug'         => 'port',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'countries'    => lang('ListCountries.countries'),
            'modes'        => $model->getModeOfTransport()
        ];
        return view('journey_port', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function portList(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        $model              = new JourneyPortModel();
        $columns            = [
            '',
            'id',
            'country_code',
            'city_name',
            'mode_of_transport',
            'port_code_1',
            'port_name',
            'location_latitude'
        ];
        $order              = $this->request->getPost('order');
        $search             = $this->request->getPost('search');
        $start              = $this->request->getPost('start');
        $length             = $this->request->getPost('length');
        $country_code       = $this->request->getPost('country_code');
        $mode_of_transport  = $this->request->getPost('mode_of_transport');
        $order_column_index = $order[0]['column'] ?? 0;
        $order_column       = $columns[$order_column_index];
        $order_direction    = $order[0]['dir'] ?? 'desc';
        $search_value       = $search['value'];
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value, $country_code, $mode_of_transport);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    public function portEdit(string $port_code = 'new')
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
    }

    public function portSave()
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
    }

    /************************************************************************
     * OPERATOR
     ************************************************************************/

    /**
     * @return string
     */
    public function operator(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $model   = new JourneyOperatorModel();
        $data    = [
            'page_title'   => 'Operator',
            'slug'         => 'operator',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'modes'        => $model->getModeOfTransport()
        ];
        return view('journey_operator', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function operatorList(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        $model              = new JourneyOperatorModel();
        $columns            = [
            '',
            'id',
            'mode_of_transport',
            'operator_code_1',
            'operator_name'
        ];
        $order              = $this->request->getPost('order');
        $search             = $this->request->getPost('search');
        $start              = $this->request->getPost('start');
        $length             = $this->request->getPost('length');
        $mode_of_transport  = $this->request->getPost('mode_of_transport');
        $order_column_index = $order[0]['column'] ?? 0;
        $order_column       = $columns[$order_column_index];
        $order_direction    = $order[0]['dir'] ?? 'desc';
        $search_value       = $search['value'];
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value, $mode_of_transport ?? '');
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    public function operatorEdit(string $port_code = 'new')
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
    }

    public function operatorSave()
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
    }

    /************************************************************************
     * OPERATOR
     ************************************************************************/

    /**
     * @return string
     */
    public function holiday(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session   = session();
        $data           = [
            'page_title'   => 'Holiday',
            'slug'         => 'holiday',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
        ];
        return view('journey_holiday', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function holidayList(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
        $model              = new JourneyHolidayModel();
        $search             = $this->request->getPost('search');
        $search_value       = $search['value'];
        $country_code       = $this->request->getPost('country_code');
        $start              = $this->request->getPost('start');
        $end                = $this->request->getPost('end');
        $result             = $model->getDataTables($search_value, $country_code, $start, $end);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    public function holidayEdit(string $port_code = 'new')
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
    }

    public function holidaySave()
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('json');
        }
    }

    /************************************************************************
     * EXPORT
     ************************************************************************/

    public function export(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $journey_master    = new JourneyMasterModel();
        $journey_transport = new JourneyTransportModel();
        $journey_accom     = new JourneyAccommodationModel();
        $journey_data      = [];
        $journey_raw       = $journey_master->select('journey_master.*, entry_port.port_name AS entry_port_name, exit_port.port_name AS exit_port_name')
            ->join('journey_port AS entry_port', 'journey_master.entry_port_id = entry_port.id', 'left outer')
            ->join('journey_port AS exit_port',  'journey_master.exit_port_id = exit_port.id', 'left outer')
            ->orderBy('id', 'asc')->findAll();
        $transport_raw     = $journey_transport->select('journey_transport.*, journey_operator.operator_name, port_departure.port_name AS departure_port_name, port_arrival.port_name AS arrival_port_name')
            ->join('journey_operator', 'journey_transport.operator_id = journey_operator.id', 'left outer')
            ->join('journey_port AS port_departure', 'journey_transport.departure_port_id = port_departure.id', 'left outer')
            ->join('journey_port AS port_arrival',   'journey_transport.arrival_port_id = port_arrival.id', 'left outer')
            ->orderBy('id', 'asc')->findAll();
        $accom_raw         = $journey_accom->orderBy('id', 'asc')->findAll();
        foreach ($journey_raw as $row) {
            $journey_data[$row['id']]['master'] = $row;
        }
        foreach ($transport_raw as $row) {
            $journey_data[$row['journey_id']]['transport'][] = $row;
        }
        foreach ($accom_raw as $row) {
            if (0 < $row['journey_id']) {
                $journey_data[$row['journey_id']]['accommodation'][] = $row;
            }
        }
        $data = [
            'journey'   => $journey_data,
            'countries' => lang('ListCountries.countries')
        ];
        return view('journey_export', $data);
    }

    public function fix(): void
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return;
        }
        helper('math');
        $journey_transport = new JourneyTransportModel();
        $transport_raw     = $journey_transport->select('journey_transport.*, 
            port_departure.port_name AS departure_port_name, port_departure.location_latitude AS lat1, port_departure.location_longitude AS lon1,
            port_arrival.port_name AS arrival_port_name, port_arrival.location_latitude AS lat2, port_arrival.location_longitude AS lon2')
            ->join('journey_port AS port_departure', 'journey_transport.departure_port_id = port_departure.id', 'left outer')
            ->join('journey_port AS port_arrival',   'journey_transport.arrival_port_id = port_arrival.id', 'left outer')
            ->orderBy('id', 'asc')->findAll();
        echo '<pre>';
        foreach ($transport_raw as $row) {
//            echo '-- ROW DATA --<br>';
            $set = [];
            if (empty($row['distance_traveled'])) {
                echo '-- FR ' . $row['departure_port_name'] . ' TO ' . $row['arrival_port_name'] . '<br>';
                $distance = round(calculateDistance($row['lat1'], $row['lon1'], $row['lat2'], $row['lon2']));
                $set[]    = " distance_traveled = {$distance} ";
            }
            if (is_null($row['trip_duration'])) {
                if ('N' == $row['is_time_known']) {
                    $set[] = " trip_duration = 0 ";
                } else {
                    // DEPARTURE DATETIME
                    $departure = new \DateTime($row['departure_date_time'], new \DateTimeZone($row['departure_timezone']));
                    $arrival   = new \DateTime($row['arrival_date_time'], new \DateTimeZone($row['arrival_timezone']));
//                    echo "-- DEP {$row['departure_date_time']} - {$row['departure_timezone']}<br>";
//                    echo "-- ARR {$row['arrival_date_time']} - {$row['arrival_timezone']}<br>";
                    // calculate minutes of the difference between $different and $arrival
                    $diff = $arrival->diff($departure);
                    $min  = $diff->days * 24 * 60;
                    $min += $diff->h * 60;
                    $min += $diff->i;
//                    echo "-- MIN {$min}<br>";
                    $set[] = " trip_duration = {$min} ";
                }
            }
            if (!empty($set)) {
                $id = $row['id'];
                $update = implode(',', $set);
                echo "UPDATE journey_transport SET {$update} WHERE id = {$id} LIMIT 1;<br><br>";
            }
            unset($set);
            unset($id);
            unset($min);
            unset($distance);
        }
        echo '</pre>';
    }
}