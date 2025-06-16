<?php

namespace App\Controllers;

use App\Models\FictionEntryModel;
use App\Models\FictionTitleModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

class Fiction extends BaseController
{

    /**
     * @return string
     */
    public function index(): string
    {
        $session = session();
        $model   = new FictionTitleModel();
        $titles  = $model->orderBy('updated_at', 'DESC')->findAll();
        $nonce   = $model::ID_NONCE;
        $data    = [
            'page_title'   => 'Fiction',
            'slug_group'   => 'fiction',
            'slug'         => '/office/fiction',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'titles'       => $titles,
            'nonce'        => $nonce,
        ];
        return view('fiction', $data);
    }

    /**
     * @param string $id
     * @return string
     */
    public function edit(string $id): string
    {
        $session = session();
        $model   = new FictionTitleModel();
        $mode    = 'edit';
        $title   = 'New Fiction Title';
        $row     = [];
        if ('new' == $id) {
            $mode   = 'new';
        } else {
            $id    = $id / $model::ID_NONCE;
            $row   = $model->find($id);
            if (empty($row)) {
                throw new PageNotFoundException();
            }
            $title = 'Edit Fiction: ' . $row['fiction_title'];
        }
        $data    = [
            'page_title'   => $title,
            'slug_group'   => 'fiction',
            'slug'         => '/office/fiction',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'mode'         => $mode,
            'row'          => $row,
        ];
        return view('fiction_edit', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function save(): ResponseInterface
    {
        return $this->response->setJSON([]);
    }

    public function viewContents(string $slug): string
    {
        $session     = session();
        $title_model = new FictionTitleModel();
        $entry_model = new FictionEntryModel();
        $title       = $title_model->where('fiction_slug', $slug)->first();
        if (empty($title)) {
            throw new PageNotFoundException();
        }
        $title_id = $title['id'];
        $entries  = $entry_model->getEntriesOfTitle($title_id);
        $data     = [
            'page_title'   => $title['fiction_title'],
            'slug_group'   => 'fiction',
            'slug'         => '/office/fiction',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'title'        => $title,
            'entries'      => $entries,
            'statuses'     => $entry_model->getEntryStatus(),
            'types'        => $entry_model->getEntryType(),
            'nonce'        => $entry_model::ID_NONCE,
            'title_id'     => $title_id * $title_model::ID_NONCE,
        ];
        return view('fiction_entries', $data);
    }

    public function editContent(string $mode, int $entry_id): string
    {
        $session     = session();
        $title_model = new FictionTitleModel();
        $entry_model = new FictionEntryModel();
        $entry_row   = [];
        $page_title  = 'New Entry';
        if ('edit' == $mode) {
            $entry_id    = $entry_id / $entry_model::ID_NONCE;
            $entry_row   = $entry_model->find($entry_id);
            if (empty($entry_row)) {
                throw new PageNotFoundException();
            }
            $title_row     = $title_model->find($entry_row['fiction_title_id']);
            $page_title    = 'Edit: ' . $entry_row['entry_title'];
            $real_entry_id = $entry_id;
            $real_title_id = $entry_row['fiction_title_id'];
        } else {
            $real_entry_id = 0;
            $real_title_id = $entry_id / $title_model::ID_NONCE;
            $title_row     = $title_model->find($real_title_id);
        }
        $data = [
            'page_title'     => $page_title,
            'slug_group'     => 'fiction',
            'slug'           => '/office/fiction',
            'user_session'   => $session->user,
            'roles'          => $session->roles,
            'current_role'   => $session->current_role,
            'mode'           => $mode,
            'entry_row'      => $entry_row,
            'title_row'      => $title_row,
            'real_entry_id'  => $real_entry_id,
            'real_title_id'  => $real_title_id,
            'configurations' => $entry_model->getConfigurations($mode, $real_title_id),
        ];
        return view('fiction_edit_entry', $data);
    }
}