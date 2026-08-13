<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyCPFInvestmentSnapshotModel extends Model
{
    protected $table = 'company_cpf';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'snapshot_date',
        'investment_value',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}