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

    /**
     * Get configurations for generating forms
     * @param array $columns
     * @param array $currencies
     * @return array
     */
    public function getConfigurations(array $columns = [], $currencies = []): array
    {
        $configurations = $this->configurations;
        // Countries
        $countries       = lang('ListCountries.countries');
        $country_options = [];
        foreach ($countries as $key => $country) {
            $country_options[$key] = $country['common_name'];
        }
        $configurations['country_code']['options'] = $countries;
        return $columns ? array_intersect_key($configurations, array_flip($columns)) : $configurations;
    }

    /**
     * @param string $search_value
     * @return void
     */
    public function applyFilter(string $search_value): void
    {
        if (!empty($search_value)) {
            $this->like('client_company_name', $search_value);
        }
    }
    /**
     * @param int $start
     * @param int $length
     * @param string $order_column
     * @param string $order_direction
     * @param string $search_value
     * @return array
     */
    public function getDataTables(int $start, int $length, string $order_column, string $order_direction, string $search_value): array
    {
        $record_total    = $this->countAllResults();
        $record_filtered = $record_total;
        if (!empty($search_value)) {
            $this->applyFilter($search_value);
            $record_filtered = $this->countAllResults();
            $this->applyFilter($search_value);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result       = [];
        foreach ($raw_result as $row) {
            $new_id       = $row['id'] * self::ID_NONCE;
            $result[]     = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/employment/freelance/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['client_company_name'],
                $row['client_type'],
                $row['country_code'],
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result
        ];
    }
}