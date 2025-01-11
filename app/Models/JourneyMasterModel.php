<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class JourneyMasterModel extends Model
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
        'trip_tags',
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
    const ID_NONCE = 827;

    /**
     * @param string $search_value
     * @param string $country_code
     * @param string $year
     * @param string $journey_status
     * @return void
     */
    private function applyFilter(string $search_value, string $country_code, string $year, string $journey_status): void
    {
        if (!empty($search_value)) {
            $this->groupStart()
                ->like('trip_code', $search_value)
                ->orLike('visa_info', $search_value)
                ->orLike('journey_details', $search_value)
                ->orLike('trip_tags', $search_value)
                ->orLike('entry_port.port_name', $search_value)
                ->orLike('exit_port.port_name', $search_value)
                ->groupEnd();
        }
        if (!empty($country_code)) {
            $this->where('journey_master.country_code', $country_code);
        }
        if (!empty($year)) {
            $this->where('date_entry <=', $year . '-12-31')
                ->groupStart()
                ->where('date_exit >=', $year . '-01-01')
                ->orWhere('date_exit', null)
                ->groupEnd();
        }
        if (!empty($journey_status)) {
            $this->where('journey_status', $journey_status);
        }
    }

    /**
     * @param int $start
     * @param int $length
     * @param string $order_column
     * @param string $order_direction
     * @param string $search_value
     * @param string $country_code
     * @param string $year
     * @param string $journey_status
     * @return array
     */
    public function getDataTables(int $start, int $length, string $order_column, string $order_direction, string $search_value, string $country_code, string $year, string $journey_status): array
    {
        $record_total    = $this->countAllResults();
        $record_filtered = $record_total;
        if (!empty($search_value) || !empty($country_code) || !empty($year) || !empty($journey_status)) {
            $this->applyFilter($search_value, $country_code, $year, $journey_status);
            $record_filtered = $this->countAllResults();
            $this->applyFilter($search_value, $country_code, $year, $journey_status);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->select('journey_master.*, entry_port.port_name AS entry_port_name, exit_port.port_name AS exit_port_name')
            ->join('journey_port AS entry_port', 'journey_master.entry_port_id = entry_port.id', 'left outer')
            ->join('journey_port AS exit_port',  'journey_master.exit_port_id = exit_port.id', 'left outer')
            ->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        $countries  = lang('ListCountries.countries');
        $today      = date('Y-m-d');
        foreach ($raw_result as $row) {
            $new_id       = $row['id'] * self::ID_NONCE;
            if (empty($row['date_exit'])) {
                if ($today < $row['date_entry']) {
                    $row['day_count'] = '∞';
                } else {
                    try {
                        $row['day_count'] = number_format((new \DateTime($row['date_entry']))->diff(new \DateTime())->days + 1) . '+';
                    } catch (Exception $e) {
                        $row['day_count'] = 'N/A';
                    }
                }
            } else {
                $row['day_count'] = number_format($row['day_count']);
            }
            $class        = '';
            if ('canceled' == $row['journey_status']) {
                $class    = 'text-danger';
            }
            $split_tags   = explode(',', $row['trip_tags']);
            $tags         = '<span class="badge bg-primary rounded-pill">' . implode('</span><span class="badge bg-primary rounded-pill">', $split_tags) . '</span>';
            $result[]     = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/journey/trip/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['id'],
                '<span class="flag-icon flag-icon-' . strtolower($row['country_code']) . '"></span><h5 class="' . $class . '">' . $countries[$row['country_code']]['common_name'] . '</h5>',
                (empty($row['date_entry']) ? '' : date(DATE_FORMAT_UI, strtotime($row['date_entry']))),
                (empty($row['date_exit']) ? '' : date(DATE_FORMAT_UI, strtotime($row['date_exit']))),
                $row['day_count'],
                $row['entry_port_name'],
                $row['exit_port_name'],
                $row['journey_details'],
                $tags,
                translate_journey_status($row['journey_status'], $row['date_entry'], $row['date_exit'] ?? '', $today)
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result
        ];
    }

}