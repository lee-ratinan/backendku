<?php

namespace App\Models;

use CodeIgniter\Model;

class HealthLeisureModel extends Model
{
    protected $table = 'health_leisure';
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
}