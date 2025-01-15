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
}