<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyFreelanceIncomeModel extends Model
{
    protected $table = 'company_freelance_income';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'company_id',
        'project_title',
        'project_slug',
        'project_start_date',
        'project_end_date',
        'client_name',
        'client_organization_name',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 661;

    /**
     * @param string $method
     * @return array|string
     */
    public function getPaymentMethod(string $method = ''): array|string
    {
        $methods = [
            'wire' => 'Wire Transfer',
            'check' => 'Check',
            'cash' => 'Cash',
            'giro' => 'GIRO',
        ];
        if (isset($methods[$method])) {
            return $methods[$method];
        }
        return $methods;
    }
    /**
     * @param string $search_value
     * @param int $project_id
     * @param string $year
     * @return void
     */
    private function applyFilter(string $search_value, int $project_id, string $year): void
    {
        if (!empty($search_value)) {
            $this->groupStart()
                ->like('payment_details', $search_value)
                ->groupEnd();
        }
        if (!empty($project_id)) {
            $this->where('project_id', $project_id);
        }
        if (!empty($year)) {
            $this->where('pay_date <=', $year . '-12-31')
                ->where('pay_date >=', $year . '-01-01');
        }
    }

    /**
     * @param int $start
     * @param int $length
     * @param string $order_column
     * @param string $order_direction
     * @param string $search_value
     * @param int $project_id
     * @param string $year
     * @return array
     */
    public function getDataTables(int $start, int $length, string $order_column, string $order_direction, string $search_value, int $project_id, string $year): array
    {
        $record_total    = $this->countAllResults();
        $record_filtered = $record_total;
        if (!empty($search_value) || !empty($project_id) || !empty($year)) {
            $this->applyFilter($search_value, $project_id, $year);
            $record_filtered = $this->countAllResults();
            $this->applyFilter($search_value, $project_id, $year);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->select('company_freelance_income.*, company_freelance_project.project_title')
            ->join('company_freelance_project', 'company_freelance_income.project_id = company_freelance_project.id', 'left outer')
            ->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        foreach ($raw_result as $row) {
            $new_id       = $row['id'] * self::ID_NONCE;
            $result[]     = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/employment/freelance-income/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['id'],
                $row['project_title'],
                (empty($row['pay_date']) ? '-' : date(DATE_FORMAT_UI, strtotime($row['pay_date']))),
                $this->getPaymentMethod($row['payment_method']),
                $row['payment_currency'],
                currency_format($row['payment_currency'], $row['base_amount']),
                currency_format($row['payment_currency'], $row['deduction_amount']),
                currency_format($row['payment_currency'], $row['claim_amount']),
                currency_format($row['payment_currency'], $row['subtotal_amount']),
                currency_format($row['payment_currency'], $row['tax_amount']),
                currency_format($row['payment_currency'], $row['total_amount']),
                $row['payment_details'],
                (empty($row['google_drive_link']) ? '-' : '<a class="btn btn-outline-primary btn-sm" href="' . $row['google_drive_link'] . '" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>')
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result,
            'footer'          => []
        ];
    }
}