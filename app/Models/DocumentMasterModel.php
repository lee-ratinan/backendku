<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentMasterModel extends Model
{
    protected $table = 'document_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'doc_title',
        'doc_slug',
        'doc_content',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 641;

    private array $configurations = [
        'id'          => [
            'type'  => 'hidden',
            'label' => 'ID'
        ],
        'doc_title'   => [
            'type'        => 'text',
            'label'       => 'Document Title',
            'required'    => true,
            'placeholder' => 'Document Title'
        ],
        'doc_slug'    => [
            'type'        => 'text',
            'label'       => 'Document Slug',
            'required'    => true,
            'placeholder' => 'Document Slug'
        ],
        'doc_content' => [
            'type'     => 'tinymce',
            'label'    => 'Document Content',
            'required' => true,
            'details'  => 'Use <s>strikethrough</s> for redacted content.<br>Use <code>[NEW_PAGE]</code> to split the page.',
        ],
        'version_number' => [
            'type'        => 'text',
            'label'       => 'Version Number',
            'required'    => false,
            'maxlength'   => 6,
            'details'     => 'Leave this version number empty if you don\'t want to publish the new version.',
        ],
        'version_description' => [
            'type'        => 'text',
            'label'       => 'Version Description',
            'required'    => false,
            'details'     => 'This version description will be used only if the version number is not empty.'
        ]
    ];

    /**
     * Get configurations for generating forms
     * @param array $columns
     * @return array
     */
    public function getConfigurations(array $columns = []): array
    {
        $configurations  = $this->configurations;
        return $columns ? array_intersect_key($configurations, array_flip($columns)) : $configurations;
    }

    /**
     * @param string $search
     * @return void
     */
    private function applyFilter(string $search): void
    {
        if (!empty($search)) {
            $this->like('doc_title', $search);
        }
    }

    /**
     * @param int $start
     * @param int $length
     * @param string $order_column
     * @param string $order_direction
     * @param string $search
     * @return array
     */
    public function getDataTables(int $start, int $length, string $order_column, string $order_direction, string $search): array
    {
        $record_total    = $this->countAllResults();
        $record_filtered = $record_total;
        if (!empty($search)) {
            $this->applyFilter($search);
            $record_filtered = $this->countAllResults();
            $this->applyFilter($search);
        }
        $session    = session();
        $locale     = $session->locale;
        $raw_result = $this->orderBy($order_column, $order_direction)->limit($length, $start)->findAll();
        $result     = [];
        foreach ($raw_result as $row) {
            $new_id       = $row['id'] * self::ID_NONCE;
            $result[]     = [
                $row['doc_title'],
                date(DATETIME_FORMAT_UI, strtotime($row['created_at'])),
                date(DATETIME_FORMAT_UI, strtotime($row['updated_at'])),
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/document/edit/' . $new_id) . '"><i class="fa-solid fa-edit"></i> Edit</a>',
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/document/public-document/' . $row['doc_slug']) . '"><i class="fa-solid fa-globe"></i> Read (Public)</a>',
                '<a class="btn btn-outline-primary btn-sm" href="' . base_url($locale . '/office/document/internal-document/' . $row['doc_slug']) . '"><i class="fa-solid fa-eye"></i> Read (Internal)</a>',
            ];
        }
        return [
            'recordsTotal'    => $record_total,
            'recordsFiltered' => $record_filtered,
            'data'            => $result
        ];
    }

    /**
     * @param string $field
     * @param int|string $identifier
     * @param string $version_number
     * @return array|null|bool
     */
    public function getDocumentVersion(string $field, int|string $identifier, string $version_number = ''): array|null|bool
    {
        if ('doc_slug' == $field) {
            if (empty($version_number)) {
                return $this->select('document_version.*')
                    ->join('document_version', 'document_version.doc_id = document_master.id')
                    ->where('doc_slug', $identifier)
                    ->orderBy('document_version.version_number', 'DESC')
                    ->first();
            }
            return $this->select('document_version.*')
                ->join('document_version', 'document_version.doc_id = document_master.id')
                ->where('doc_slug', $identifier)
                ->where('version_number', $version_number)
                ->first();
        } else if ('doc_id' == $field) {
            $doc_version_model = new DocumentVersionModel();
            return $doc_version_model->getDocumentVersion($identifier, $version_number);
        }
        return false;
    }

}