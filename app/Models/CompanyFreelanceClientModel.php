<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyFreelanceClientModel extends Model
{
    protected $table = 'company_freelance_client';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'client_company_name',
        'client_type',
        'country_code',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 733;

    private array $configurations = [
        'client_company_name' => [
            'type'     => 'text',
            'label'    => 'Name',
            'required' => true,
        ],
        'client_type'         => [
            'type'     => 'select',
            'label'    => 'ClientType',
            'required' => true,
            'options'  => [
                'corporate'  => 'Corporate',
                'individual' => 'Individual',
            ]
        ],
        'country_code'        => [
            'type'     => 'select',
            'label'    => 'Country',
            'required' => true,
            'options'  => [
                'TH' => 'Thailand',
            ],
        ],
    ];
}