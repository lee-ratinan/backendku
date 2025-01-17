<?php

namespace App\Models;

use CodeIgniter\Model;

class TaxRecordModel extends Model
{
    protected $table = 'tax_record';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'tax_year_id',
        'tax_description',
        'desc_type',
        'money_amount',
        'item_notes',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 797;
}