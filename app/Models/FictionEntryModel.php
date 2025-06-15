<?php

namespace App\Models;

use CodeIgniter\Model;

class FictionEntryModel extends Model
{
    protected $table = 'fiction_entry';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'fiction_title_id',
        'parent_entry_id',
        'entry_position',
        'entry_title',
        'entry_type',
        'entry_note',
        'entry_short_note',
        'entry_status',
        'entry_content',
        'footnote_section',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    const ID_NONCE = 727;


    /**
     * @param int $title_id
     * @return array
     */
    public function getEntriesOfTitle(int $title_id): array
    {
        $entries   = $this->where('fiction_title_id', $title_id)->orderBy('id ASC, entry_position ASC')->findAll();
        $structure = [];
        foreach ($entries as $entry) {
            unset($entry['entry_content']);
            if (empty($entry['parent_entry_id'])) {
                $structure[$entry['id']] = $entry;
            } else {
                $structure[$entry['parent_entry_id']]['children'][$entry['id']] = $entry;
            }
        }
        return $structure;
    }
}