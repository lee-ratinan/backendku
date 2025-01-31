<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Health extends BaseController
{

    const PERMISSION_REQUIRED = 'health';

    /**
     * @return string
     */
    public function gym(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'   => 'Gym',
            'slug'         => 'health-gym',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('health_gym', $data);
    }

    /**
     * @return string
     */
    public function vaccine(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'   => 'Vaccine',
            'slug'         => 'health-vaccine',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('health_vaccine', $data);
    }

    /**
     * @return string
     */
    public function activity(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'   => 'Activity',
            'slug'         => 'health-activity',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('health_activity', $data);
    }

    /**
     * This API retrieves immigration data and return for DataTables
     * Table: journey_master
     * @return ResponseInterface
     */
    public function activityList(): ResponseInterface
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied('datatables');
        }
//        $model              = new JourneyMasterModel();
//        $columns            = [
//            '',
//            'journey_master.id',
//            'journey_master.country_code',
//            'journey_master.date_entry',
//            'journey_master.day_count',
//            'entry_port.port_name',
//            'exit_port.port_name',
//            'journey_master.journey_details',
//            'journey_master.journey_status'
//        ];
//        $order              = $this->request->getPost('order');
//        $search             = $this->request->getPost('search');
//        $start              = $this->request->getPost('start');
//        $length             = $this->request->getPost('length');
//        $order_column_index = $order[0]['column'] ?? 0;
//        $order_column       = $columns[$order_column_index];
//        $order_direction    = $order[0]['dir'] ?? 'desc';
//        $search_value       = $search['value'];
//        $country_code       = $this->request->getPost('country_code');
//        $year               = $this->request->getPost('year');
//        $journey_status     = $this->request->getPost('journey_status');
//        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value, $country_code, $year, $journey_status);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => 0, //$result['recordsTotal'],
            'recordsFiltered' => 0, //$result['recordsFiltered'],
            'data'            => [] //$result['data']
        ]);
    }
}