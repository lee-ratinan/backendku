<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentVersionModel extends Model
{
    protected $table = 'document_version';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'doc_id',
        'version_number',
        'version_description',
        'doc_title',
        'doc_content',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 769;

    /**
     * @param int $doc_id
     * @param string $version_number
     * @return array
     */
    public function getDocumentVersion(int $doc_id, string $version_number = ''): array
    {
        if (empty($version_number)) {
            return $this->where('doc_id', $doc_id)->orderBy('version_number', 'DESC')->first();
        }
        return $this->where('doc_id', $doc_id)->where('version_number', $version_number)->first();
    }

    /**
     * Retrieve all version history
     * @param int $doc_id
     * @return array
     */
    public function getDocumentVersionHistory(int $doc_id): array
    {
        return $this->select('id, version_number, version_description')
            ->where('doc_id', $doc_id)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * @param int $doc_id
     * @return array
     */
    public function getVersions(int $doc_id): array
    {
        return $this->select('document_master.doc_slug, document_version.*')
            ->join('document_master', 'document_master.id = document_version.doc_id')
            ->where('document_version.doc_id', $doc_id)
            ->orderBy('document_version.version_number', 'ASC')
            ->findAll();
    }
}