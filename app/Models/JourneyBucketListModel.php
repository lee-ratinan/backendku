<?php

namespace App\Models;

use CodeIgniter\Model;

class JourneyBucketListModel extends Model
{
    protected $table = 'journey_bucket_list';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'activity_name',
        'activity_name_local',
        'activity_slug',
        'activity_location',
        'country_code',
        'category_code',
        'completed_date',
        'description',
        'trip_codes',
        'building_height',
        'building_built_year',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 643;

    private array $configurations = [
        'id'                 => [
            'type'  => 'hidden',
            'label' => 'ID'
        ],
    ];

    /**
     * @param $code
     * @return string|array
     */
    private function getCategoryCode($code = ''): string|array
    {
        $categories = [
            'observatory' => 'Observatory',
            'sport'       => 'Extreme Sport',
            'costume',
            'culture',
            'movie',
            'transportation',
            'destination'
        ];
        return $categories[$code] ?? $categories;
    }

    /**
     * Get configurations for generating forms
     * @param array $columns
     * @return array
     */
    public function getConfigurations(array $columns = []): array
    {
        $configurations  = $this->configurations;
        // Countries
        $countries       = lang('ListCountries.countries');
        $final_countries = array_map(function ($value) {
            return $value['common_name'];
        }, $countries);
        $configurations['country_code']['options'] = $final_countries;
        // Category Code
        return $columns ? array_intersect_key($configurations, array_flip($columns)) : $configurations;
    }
}