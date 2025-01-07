<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyCPFModel extends Model
{
    protected $table = 'company_cpf';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'user_id',
        'transaction_date',
        'transaction_code',
        'ordinary_amount',
        'ordinary_balance',
        'special_amount',
        'special_balance',
        'medisave_amount',
        'medisave_balance',
        'transaction_amount',
        'account_balance',
        'contribution_month',
        'company_id',
        'staff_contribution',
        'staff_ytd',
        'company_match',
        'company_ytd',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 617;

    /**
     * @param string $transaction_code
     * @param int $company_id
     * @param string $year
     * @return void
     */
    private function applyFilter(string $transaction_code, int $company_id, string $year): void
    {
        if (!empty($transaction_code)) {
            $this->like('transaction_code', $transaction_code);
        }
        if (!empty($company_id)) {
            $this->where('company_id', $company_id);
        }
        if (!empty($year)) {
            if ('CON' == $transaction_code) {
                $this->like('contribution_month', $year);
            } else {
                $this->where('transaction_date >=', $year . '-01-01')
                    ->where('transaction_date <=', $year . '-12-31');
            }
        }
    }

    /**
     * @param int $start
     * @param int $length
     * @param string $order_column
     * @param string $order_direction
     * @param string $transaction_code
     * @param int $company_id
     * @param string $year
     * @return array
     */
    public function getDataTables(int $start, int $length, string $order_column, string $order_direction, string $transaction_code, int $company_id, string $year): array
    {
        $record_total    = $this->countAllResults();
        $record_filtered = $record_total;
        if (!empty($transaction_code) || !empty($company_id) || !empty($year)) {
            $this->applyFilter($transaction_code, $company_id, $year);
            $record_filtered = $this->countAllResults();
            $this->applyFilter($transaction_code, $company_id, $year);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->select('company_cpf.*, company_master.company_legal_name')
            ->join('company_master', 'company_master.id = company_cpf.company_id', 'left outer')
            ->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        foreach ($raw_result as $row) {
            $new_id       = $row['id'] * self::ID_NONCE;
            $result[]     = [
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/employment/cpf/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i></a>',
                $row['id'],
                date(DATE_FORMAT_UI, strtotime($row['transaction_date'])),
                $row['transaction_code'],
                currency_format('SGD', $row['ordinary_amount'] ?? 0),
                currency_format('SGD', $row['ordinary_balance'] ?? 0),
                currency_format('SGD', $row['special_amount'] ?? 0),
                currency_format('SGD', $row['special_balance'] ?? 0),
                currency_format('SGD', $row['medisave_amount'] ?? 0),
                currency_format('SGD', $row['medisave_balance'] ?? 0),
                currency_format('SGD', $row['transaction_amount'] ?? 0),
                currency_format('SGD', $row['account_balance'] ?? 0),
                ('CON' == $row['transaction_code'] ? date(MONTH_FORMAT_UI, strtotime($row['contribution_month'] . '-01')) : ''),
                ('CON' == $row['transaction_code'] ? $row['company_legal_name'] : ''),
                ('CON' == $row['transaction_code'] ? currency_format('SGD', $row['staff_contribution'] ?? 0) : ''),
                ('CON' == $row['transaction_code'] ? currency_format('SGD', $row['staff_ytd'] ?? 0) : ''),
                ('CON' == $row['transaction_code'] ? currency_format('SGD', $row['company_match'] ?? 0) : ''),
                ('CON' == $row['transaction_code'] ? currency_format('SGD', $row['company_ytd'] ?? 0) : '')
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result
        ];
    }
}