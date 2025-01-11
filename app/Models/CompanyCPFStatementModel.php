<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyCPFStatementModel extends Model
{
    protected $table = 'company_cpf_statement';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'statement_year',
        'google_drive_url',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 617;

}