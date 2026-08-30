<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyPartTimeScheduleModel extends Model
{
    protected $table = 'company_pt_schedule';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'period_id',
        'scheduled_start',
        'scheduled_end',
        'scheduled_hours',
        'scheduled_break',
        'work_location',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 593;

    public function applyFilter(string $start_date, string $end_date, int $period_id): void
    {
        if (!empty($start_date)) {
            $this->where('scheduled_start >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->where('scheduled_end <=', $end_date);
        }
        if (0 < $period_id) {
            $this->where('period_id', $period_id);
        }
    }

    public function  getDataTables(int $start, int $length, string $order_column, string $order_direction, string $start_date, string $end_date, int $period_id): array
    {
        $record_total    = $this->countAllResults();
        $record_filtered = $record_total;
        if (!empty($start_date) || !empty($end_date) || 0 < $period_id) {
            if (!empty($start_date)) {
                $start_date .= ' 00:00:00';
            }
            if (!empty($end_date)) {
                $end_date .= ' 23:59:59';
            }
            $this->applyFilter($start_date, $end_date, $period_id);
            $record_filtered = $this->countAllResults();
            $this->applyFilter($start_date, $end_date, $period_id);
        }
        $raw_result = $this
            ->select('company_pt_schedule.*, company_pt_period.period_start, company_pt_period.period_end')
            ->join('company_pt_period', 'company_pt_period.id = company_pt_schedule.period_id')
            ->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        $hours      = 0.0;
        $breaks     = 0.0;
        foreach ($raw_result as $row) {
            $result[]     = [
                date(DATE_FORMAT_UI, strtotime($row['period_start'])) . ' - ' . date(DATE_FORMAT_UI, strtotime($row['period_end'])),
                date(DATE_FORMAT_UI, strtotime($row['scheduled_start'])) . ': ' . date(TIME_FORMAT_UI, strtotime($row['scheduled_start'])),
                'to ' . date(TIME_FORMAT_UI, strtotime($row['scheduled_end'])),
                number_format($row['scheduled_hours'] ?? 0, 1),
                number_format($row['scheduled_break'] ?? 0, 1),
                $row['work_location'],
            ];
            $hours      += $row['scheduled_hours'];
            $breaks     += $row['scheduled_break'];
        }
        $footer = [
            '',
            '',
            'Total',
            number_format($hours, 1),
            number_format($breaks, 1),
            ''
        ];
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result,
            'footer'          => $footer
        ];
    }

    public function getScheduledHoursByPeriodIds(int|array $period_ids): array
    {
        if (is_int($period_ids)) {
            $period_ids = [$period_ids];
        }
        $results = $this->select('period_id, SUM(scheduled_hours) as scheduled_hours, SUM(scheduled_break) as scheduled_break')
            ->whereIn('period_id', $period_ids)
            ->groupBy('period_id')
            ->findAll();
        $query = $this->db->getLastQuery();
        log_message('warning', $query->getQuery());
        return $results;
    }
}