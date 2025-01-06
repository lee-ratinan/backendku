<?php

namespace App\Controllers;

use App\Models\JourneyMasterModel;
use App\Models\JourneyPortModel;
use CodeIgniter\HTTP\ResponseInterface;

class Journey extends BaseController
{

    const PERMISSION_REQUIRED = 'journey';

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
            'current_role' => $session->current_role
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
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    public function tripEdit(string $port_code = 'new')
    {

    }

    public function tripSave()
    {

    }

    /************************************************************************
     * TRANSPORT
     ************************************************************************/

    /**
     * @return string
     */
    public function transport(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Transport',
            'slug'         => 'transport',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('journey_transport', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function transportList(): ResponseInterface
    {
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
    }

    public function transportEdit(string $port_code = 'new')
    {

    }

    public function transportSave()
    {

    }

    /************************************************************************
     * ACCOMMODATION
     ************************************************************************/

    /**
     * @return string
     */
    public function accommodation(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Accommodation',
            'slug'         => 'accommodation',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('journey_accommodation', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function accommodationList(): ResponseInterface
    {
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
    }

    public function accommodationEdit(string $port_code = 'new')
    {

    }

    public function accommodationSave()
    {

    }

    /************************************************************************
     * ATTRACTION
     ************************************************************************/

    /**
     * @return string
     */
    public function attraction(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Attraction',
            'slug'         => 'attraction',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('journey_attraction', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function attractionList(): ResponseInterface
    {
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
    }

    public function attractionEdit(string $port_code = 'new')
    {

    }

    public function attractionSave()
    {

    }

    /************************************************************************
     * PORT
     ************************************************************************/

    /**
     * @return string
     */
    public function port(): string
    {
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

    }

    public function portSave()
    {

    }

    /************************************************************************
     * OPERATOR
     ************************************************************************/

    /**
     * @return string
     */
    public function operator(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Operator',
            'slug'         => 'operator',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('journey_operator', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function operatorList(): ResponseInterface
    {
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
    }

    public function operatorEdit(string $port_code = 'new')
    {

    }

    public function operatorSave()
    {

    }
}