<?php

namespace App\Models;

use CodeIgniter\Model;

class HealthActivityModel extends Model
{
    protected $table = 'health_activity';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'journey_id',
        'time_start_utc',
        'time_end_utc',
        'event_timezone',
        'event_duration',
        'duration_from_prev_ejac',
        'record_type',
        'event_type',
        'is_ejac',
        'spa_name',
        'spa_type',
        'currency_code',
        'price_amount',
        'price_tip',
        'event_notes',
        'event_location',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 691;

    /**
     * @return array
     */
    public function getRecordCategories(): array
    {
        return [
            'ejac'     => 'Ejaculation',
            'chastity' => 'Chastity',
            'enlarge'  => 'Enlarge',
            'spa'      => 'Spa'
        ];
    }
    /**
     * @return array
     */
    public function getRecordTypes(): array
    {
        return [
            'ejac'     => [
                'jerk-off' => 'Ejaculation / Jerk-off',
                'sex'      => 'Ejaculation / Sex',
                'hand-job' => 'Ejaculation / Hand-job',
                'milking'  => 'Ejaculation / Milking'
            ],
            'chastity' => [
                'cb_minime'       => 'Chastity / CB-MiniMe',
                'mancage'         => 'Chastity / ManCage',
                'bent'            => 'Chastity / Bent Cage',
                'inverted'        => 'Chastity / Inverted Cage',
                'bent_n_inverted' => 'Chastity / Bent&Inverted Cage series',
                'flat'            => 'Chastity / Trumpet (Flat) Cage',
                'prison'          => 'Chastity / Prison Bird Cage',
            ],
            'enlarge'  => [], // event_type is mm
            'spa'      => [
                'hand-job' => 'Massage Spa / Hand Job',
                'b2b'      => 'Massage Spa / Body-2-Body',
                'sex'      => 'Massage Spa / Sex',
                'milking'  => 'Massage Spa / Milking',
                'clean'    => 'Massage Spa / Clean Massage',
            ]
        ];
    }

    /**
     * @param string $search_value
     * @param string $from
     * @param string $to
     * @param string $record_type
     * @param string $is_ejac
     * @param string $event_location
     * @return void
     */
    private function applyFilter(string $search_value, string $from, string $to, string $record_type, string $is_ejac, string $event_location): void
    {
        if (!empty($search_value)) {
            $this->groupStart()
                ->like('spa_name', $search_value)
                ->orLike('spa_type', $search_value)
                ->orLike('event_notes', $search_value)
                ->orLike('event_location', $search_value)
                ->groupEnd();
        }
        if (!empty($from) && empty($to)) {
            $to = date(DATETIME_FORMAT_DB, strtotime('+24 hours')); // make it future to ensure no timezone problems
        } else if (empty($from) && !empty($to)) {
            $from = date(DATETIME_FORMAT_DB, strtotime($to . ' -1 month'));
        }
        if (!empty($from) && !empty($to)) {
            $this->where('time_start_utc <=', $to . ' 23:59:59')
                ->where('time_end_utc >=', $from . ' 00:00:00');
        }
        if (!empty($record_type)) {
            $split = explode(':', $record_type);
            $record_type = $split[0];
            $event_type  = ($split[1] ?? '');
            $this->where('record_type', $record_type);
            if (!empty($event_type)) {
                $this->where('event_type', $event_type);
            }
        }
        if (!empty($is_ejac)) {
            $this->where('is_ejac', $is_ejac);
        }
        if (!empty($event_location)) {
            $this->where('event_location', $event_location);
        }
    }

    /**
     * @param int $start
     * @param int $length
     * @param string $order_column
     * @param string $order_direction
     * @param string $search_value
     * @param string $from
     * @param string $to
     * @param string $record_type
     * @param string $is_ejac
     * @param string $event_location
     * @return array
     */
    public function getDataTables(int $start, int $length, string $order_column, string $order_direction, string $search_value, string $from, string $to, string $record_type, string $is_ejac, string $event_location): array
    {
        $record_total    = $this->countAllResults();
        $record_filtered = $record_total;
        if (!empty($search_value) || !empty($from) || !empty($to) || !empty($record_type) || !empty($is_ejac) || !empty($event_location)) {
            $this->applyFilter($search_value, $from, $to, $record_type, $is_ejac, $event_location);
            $record_filtered = $this->countAllResults();
            $this->applyFilter($search_value, $from, $to, $record_type, $is_ejac, $event_location);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        $types      = $this->getRecordTypes();
        foreach ($raw_result as $row) {
            $new_id       = $row['id'] * self::ID_NONCE;
            $result[]     = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/health/activity/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['id'],
                '<span class="utc-to-local-time">' . str_replace(' ', 'T', $row['time_start_utc']) . 'Z</span>' . ($row['time_start_utc'] != $row['time_end_utc'] ? ' - <span class="utc-to-local-time">' . str_replace(' ', 'T', $row['time_end_utc']) . 'Z</span>' : '') . '<br><span class="smal">' . $row['event_timezone'] . '</span>',
                minute_format($row['event_duration']),
                minute_format($row['duration_from_prev_ejac']),
                ('enlarge' != $row['record_type'] ? $types[$row['record_type']][$row['event_type']] : 'Enlarge @ ' . number_format($row['event_type']/10, 1) . 'cm'),
                ($row['is_ejac'] == 'Y' ? '<i class="fa-solid fa-droplet fa-rotate-by" style="--fa-rotate-angle: 45deg;"></i><i class="fa-solid fa-droplet fa-2xs fa-rotate-by" style="--fa-rotate-angle: 45deg;"></i>' : '-'),
                $row['spa_name'] . (empty($row['spa_type']) ? '' : ' (' . $row['spa_type'] . ')'),
                ($row['price_amount'] > 0 ? currency_format($row['currency_code'], $row['price_amount']) : '') . ($row['price_tip'] > 0 ? '<br>' . currency_format($row['currency_code'], $row['price_tip']) . ' tip' : ''),
                $row['event_notes'],
                $row['event_location'],
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result
        ];
    }
}