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
     * @param string $status
     * @return array|string
     */
    public function getEntryStatus(string $status = ''): array|string
    {
        $statuses = [
            'in_progress'   => '<i class="fa-solid fa-circle text-success"></i> In Progress',
            'revised_draft' => '<i class="fa-solid fa-circle text-warning"></i> Revised Draft',
            'final_draft'   => '<i class="fa-solid fa-circle text-primary"></i> Final Draft',
        ];
        return $statuses[$status] ?? $statuses;
    }

    /**
     * @param string $type
     * @return array|string
     */
    public function getEntryType(string $type = ''): array|string
    {
        $types = [
            'chapter'   => '<i class="fa-solid fa-folder-open"></i> Chapter',
            'scene'     => '<i class="fa-solid fa-file-lines"></i> Scene',
            'character' => '<i class="fa-solid fa-person"></i> Character',
            'location'  => '<i class="fa-solid fa-location-dot"></i> Location',
        ];
        return $types[$type] ?? $types;
    }
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