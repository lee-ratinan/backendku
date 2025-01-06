<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Journey extends BaseController
{

    /************************************************************************
     * TRIP
     ************************************************************************/

    /**
     * @return string
     */
    public function trip(): string
    {
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
     * @return ResponseInterface
     */
    public function tripList(): ResponseInterface
    {
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
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
        $session = session();
        $data    = [
            'page_title'   => 'Port',
            'slug'         => 'port',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('journey_port', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function portList(): ResponseInterface
    {
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
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