<?php

namespace App\Models;

use CodeIgniter\Model;

class FictionTitleModel extends Model
{
    protected $table = 'fiction_title';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'fiction_title',
        'fiction_genre',
        'pen_name',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 743;

//    /**
//     * @param int $doc_id
//     * @param string $version_number
//     * @return array
//     */
//    public function getDocumentVersion(int $doc_id, string $version_number = ''): array
//    {
//        if (empty($version_number)) {
//            return $this->select('document_version.*, user_name_first, user_name_family')
//                ->join('user_master', 'document_version.created_by = user_master.id')
//                ->where('doc_id', $doc_id)->orderBy('version_number', 'DESC')->first();
//        }
//        return $this->select('document_version.*, user_name_first, user_name_family')
//            ->join('user_master', 'document_version.created_by = user_master.id')
//            ->where('doc_id', $doc_id)->where('version_number', $version_number)->first();
//    }
//
//    /**
//     * Retrieve all version history
//     * @param int $doc_id
//     * @return array
//     */
//    public function getDocumentVersionHistory(int $doc_id): array
//    {
//        return $this->select('version_number, version_description, published_date, user_name_first, user_name_family')
//            ->join('user_master', 'user_master.id = document_version.created_by')
//            ->where('doc_id', $doc_id)
//            ->orderBy('document_version.id', 'ASC')
//            ->findAll();
//    }
//
//    /**
//     * @param int $doc_id
//     * @return array
//     */
//    public function getVersions(int $doc_id): array
//    {
//        return $this->select('document_master.doc_slug, document_version.*')
//            ->join('document_master', 'document_master.id = document_version.doc_id')
//            ->where('document_version.doc_id', $doc_id)
//            ->orderBy('document_version.version_number', 'ASC')
//            ->findAll();
//    }
}