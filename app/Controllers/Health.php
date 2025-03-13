<?php

namespace App\Controllers;

use App\Models\HealthActivityModel;
use App\Models\JourneyHolidayModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use ReflectionException;

class Health extends BaseController
{

    const PERMISSION_REQUIRED = 'health';
    private $fitness_first = [
        'SG' => [
            [
                'club'      => '100 AM',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/100-am-tanjong-pagar',
                'latitude'  => 1.2750018,
                'longitude' => 103.8436614,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6', '7', 'PH'],
                        'time' => ['08:00:00', '20:00:00']
                    ]
                ]
            ],
            [
                'club'      => '321 Clementi',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/321-clementi',
                'latitude'  => 1.3119864,
                'longitude' => 103.7650332,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6'],
                        'time' => ['07:00:00', '19:00:00']
                    ],
                    [
                        'days' => ['7', 'PH'],
                        'time' => ['08:00:00', '18:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'AMK Hub (PREMIUM)',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/amk-hub',
                'latitude'  => 1.3697323,
                'longitude' => 103.8487065,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6', '7', 'PH'],
                        'time' => ['08:00:00', '21:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'Alexandra',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/alexandra-mapletree-business-city',
                'latitude'  => 1.275215,
                'longitude' => 103.7989239,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6'],
                        'time' => ['08:00:00', '18:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'Bugis Junction',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/bugis-junction',
                'latitude'  => 1.2991347,
                'longitude' => 103.855363,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6', '7', 'PH'],
                        'time' => ['08:00:00', '22:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'Capital Tower',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/capital-tower',
                'latitude'  => 1.278047,
                'longitude' => 103.8474288,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6'],
                        'time' => ['08:00:00', '18:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'Fusionopolis',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/fusionopolis',
                'latitude'  => 1.2995647,
                'longitude' => 103.7876738,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6'],
                        'time' => ['07:00:00', '18:00:00']
                    ],
                    [
                        'days' => ['7', 'PH'],
                        'time' => ['08:00:00', '17:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'Junction 10 (PREMIUM)',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/junction-10',
                'latitude'  => 1.3808842,
                'longitude' => 103.759926,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6', '7', 'PH'],
                        'time' => ['08:00:00', '20:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'Market Street',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/marketstreet',
                'latitude'  => 1.2843755,
                'longitude' => 103.8505206,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6'],
                        'time' => ['07:00:00', '18:00:00']
                    ],
                    [
                        'days' => ['7', 'PH'],
                        'time' => ['08:00:00', '17:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'The Metropolis',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/the-metropolis',
                'latitude'  => 1.3055177,
                'longitude' => 103.7919789,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6'],
                        'time' => ['08:00:00', '18:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'One George Street',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/one-george-street',
                'latitude'  => 1.2843755,
                'longitude' => 103.8505206,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6'],
                        'time' => ['08:00:00', '18:00:00']
                    ],
                    [
                        'days' => ['7', 'PH'],
                        'time' => ['09:00:00', '18:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'One Raffles Quay',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/one-raffles-quay',
                'latitude'  => 1.2811658,
                'longitude' => 103.8516453,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6'],
                        'time' => ['08:00:00', '18:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'Paragon',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/paragon',
                'latitude'  => 1.303408,
                'longitude' => 103.8353432,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6'],
                        'time' => ['07:00:00', '21:00:00']
                    ],
                    [
                        'days' => ['7', 'PH'],
                        'time' => ['08:00:00', '21:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'Payar Lebar',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/paya-lebar-singpost-centre',
                'latitude'  => 1.318983,
                'longitude' => 103.8948672,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6', '7', 'PH'],
                        'time' => ['08:00:00', '21:00:00']
                    ],
                ]
            ],
            [
                'club'      => 'Tampines (PREMIUM)',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/tampines-cpf-building',
                'latitude'  => 1.3530306,
                'longitude' => 103.9437137,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '23:00:00']
                    ],
                    [
                        'days' => ['6'],
                        'time' => ['07:00:00', '21:00:00']
                    ],
                    [
                        'days' => ['7', 'PH'],
                        'time' => ['08:00:00', '20:00:00']
                    ]
                ]
            ],
            [
                'club'      => 'Westgate',
                'url'       => 'https://www.fitnessfirst.com/sg/en/clubs/westgate',
                'latitude'  => 1.3343179,
                'longitude' => 103.7429477,
                'opens'     => [
                    [
                        'days' => ['1', '2', '3', '4', '5'],
                        'time' => ['06:00:00', '22:00:00']
                    ],
                    [
                        'days' => ['6'],
                        'time' => ['07:00:00', '21:00:00']
                    ],
                    [
                        'days' => ['7', 'PH'],
                        'time' => ['08:00:00', '21:00:00']
                    ],
                ]
            ]
        ],
    ];

    /**
     * @return string
     */
    public function gym(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Gym',
            'slug_group'   => 'health',
            'slug'         => '/office/health/gym',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('health_gym', $data);
    }

    /**
     * @return string
     */
    public function gymFinder(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Gym Finder',
            'slug_group'   => 'health',
            'slug'         => '/office/health/gym',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('health_gym_finder', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function gymFinderList(): ResponseInterface
    {
        helper('math');
        $holiday      = new JourneyHolidayModel();
        $latitude     = $this->request->getPost('latitude');
        $longitude    = $this->request->getPost('longitude');
        $city_code    = $this->request->getPost('city_code');
        $clubs        = $this->fitness_first[$city_code] ?? [];
        $country_code = ($city_code == 'SG') ? 'SG' : 'TH';
        $holidays     = $holiday->where('country_code', $country_code)->where('holiday_date >=', date(DATE_FORMAT_DB))->where('holiday_date_to <=', date(DATE_FORMAT_DB))->findAll();
        if (empty($holidays)) {
            $dow = date('N');
        } else {
            $dow = 'PH';
        }
        $result    = [];
        foreach ($clubs as $club) {
            $distance = calculateDistance($latitude, $longitude, $club['latitude'], $club['longitude']);
            $opens    = [];
            foreach ($club['opens'] as $group) {
                if (in_array($dow, $group['days'])) {
                    $opens['open']  = date(TIME_FORMAT_UI, strtotime('2025-01-01 ' . $group['time'][0]));
                    $opens['close'] = date(TIME_FORMAT_UI, strtotime('2025-01-01 ' . $group['time'][1]));
                }
            }
            $result[$distance*1000] = [
                'club'     => $club['club'],
                'distance' => number_format($distance, 2),
                'open'     => $opens['open'] ?? '',
                'close'    => $opens['close'] ?? '',
                'url'      => $club['url']
            ];
        }
        ksort($result);
        return $this->response->setJSON([
            'data' => $result
        ]);
    }

    /**
     * @return string
     */
    public function vaccine(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Vaccine',
            'slug_group'   => 'health',
            'slug'         => '/office/health/vaccine',
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
        $session = session();
        $data    = [
            'page_title'   => 'Activity',
            'slug_group'   => 'health',
            'slug'         => '/office/health/activity',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'record_cate'  => (new HealthActivityModel())->getRecordCategories(),
            'record_types' => (new HealthActivityModel())->getRecordTypes()
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
        $model              = new HealthActivityModel();
        $columns            = [
            '',
            'id',
            'time_start_utc',
            'event_duration',
            'duration_from_prev_ejac',
            'record_type',
            'is_ejac',
            'spa_name',
            'price_amount',
            'event_notes',
            'event_location'
        ];
        $order              = $this->request->getPost('order');
        $search             = $this->request->getPost('search');
        $start              = $this->request->getPost('start');
        $length             = $this->request->getPost('length');
        $order_column_index = $order[0]['column'] ?? 0;
        $order_column       = $columns[$order_column_index];
        $order_direction    = $order[0]['dir'] ?? 'desc';
        $search_value       = $search['value'];
        $from               = $this->request->getPost('from') ?? '';
        $to                 = $this->request->getPost('to') ?? '';
        $record_type        = $this->request->getPost('record_type') ?? '';
        $is_ejac            = $this->request->getPost('is_ejac') ?? '';
        $event_location     = $this->request->getPost('event_location') ?? '';
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value, $from, $to, $record_type, $is_ejac, $event_location);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    /**
     * @param string $activity_id
     * @return string
     */
    public function activityEdit(string $activity_id): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Edit Activity',
            'slug_group'   => 'health',
            'slug'         => '/office/health/activity',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'record_cate'  => (new HealthActivityModel())->getRecordCategories(),
            'record_types' => (new HealthActivityModel())->getRecordTypes()
        ];
        return view('health_activity_edit', $data);
    }

    public function activitySave(): ResponseInterface
    {
        $session = session();
        $model   = new HealthActivityModel();
        $mode    = $this->request->getPost('mode');
        $data    = [];
//        $data    = [
//            'country_code'    => $this->request->getPost('country_code'),
//            'date_entry'      => $this->request->getPost('date_entry'),
//            'day_count'       => $this->request->getPost('day_count') ?? 0,
//            'visa_info'       => $this->request->getPost('visa_info'),
//            'journey_status'  => $this->request->getPost('journey_status'),
//            'created_by'      => $session->user_id,
//        ];
//        $trip_code       = $this->request->getPost('trip_code');
//        $date_exit       = $this->request->getPost('date_exit');
//        $entry_port_id   = $this->request->getPost('entry_port_id');
//        $exit_port_id    = $this->request->getPost('exit_port_id');
//        $trip_tags       = $this->request->getPost('trip_tags');
//        $journey_details = $this->request->getPost('journey_details');
//        if (!empty($trip_code)) {
//            $data['trip_code'] = $trip_code;
//        }
//        if (!empty($date_exit)) {
//            $data['date_exit'] = $date_exit;
//        }
//        if (!empty($entry_port_id)) {
//            $data['entry_port_id'] = $entry_port_id;
//        }
//        if (!empty($exit_port_id)) {
//            $data['exit_port_id'] = $exit_port_id;
//        }
//        if (!empty($trip_tags)) {
//            $data['trip_tags'] = $trip_tags;
//        }
//        if (!empty($journey_details)) {
//            $data['journey_details'] = $journey_details;
//        }
        try {
            if ('new' == $mode) {
                $inserted_id = $model->insert($data);
                if ($inserted_id) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'toast'  => 'Trip has been added',
                        'url'    => base_url($session->locale . '/office/health/activity/edit/' . ($inserted_id * $model::ID_NONCE))
                    ]);
                }
            } else {
                $id = $this->request->getPost('id');
                if ($model->update($id, $data)) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'toast'  => 'Trip has been updated',
                        'url'    => base_url($session->locale . '/office/health/activity/edit/' . ($id * $model::ID_NONCE))
                    ]);
                }
            }
            return $this->response->setJSON([
                'status' => 'error',
                'toast'  => 'There was some unknown error, please try again later.'
            ]);
        } catch (DatabaseException|ReflectionException $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'toast'  => 'ERROR: ' . $e->getMessage()
            ]);
        }
    }
}