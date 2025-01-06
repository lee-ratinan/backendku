<?php

namespace App\Models;

use CodeIgniter\Model;

class JourneyAttractionModel extends Model
{
    protected $table = 'journey_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'trip_code',
        'country_code',
        'date_entry',
        'date_exit',
        'day_count',
        'entry_port_id',
        'exit_port_id',
        'visa_info',
        'journey_details',
        'journey_status',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 17;

    /**
     * @param string $search_value
     * @return void
     */
    private function applyFilter(string $search_value): void
    {
        if (!empty($search_value)) {
            $this->groupStart()
                ->like('trip_code', $search_value)
                ->orLike('visa_info', $search_value)
                ->orLike('journey_details', $search_value)
                ->groupEnd();
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
        $raw_result = $this->select('journey_master.*, entry_port.port_name AS entry_port_name, exit_port.port_name AS exit_port_name')
            ->join('journey_port AS entry_port', 'journey_master.entry_port_id = entry_port.id')
            ->join('journey_port AS exit_port',  'journey_master.exit_port_id = exit_port.id')
            ->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        $countries  = lang('ListCountries.countries');
        foreach ($raw_result as $row) {
            $new_id       = $row['id'] * self::ID_NONCE;
            $result[]     = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/journey/trip/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['id'],
                $countries[$row['country_code']]['common_name'],
                '<span class="date-to-readable">' . $row['date_entry'] . '</span> - <span class="date-to-readable">' . $row['date_exit'] . '</span>',
                $row['day_count'],
                $row['entry_port_name'],
                $row['exit_port_name'],
                $row['journey_details'],
                $row['journey_status']
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result
        ];
    }

}