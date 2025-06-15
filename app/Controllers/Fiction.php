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
        $row     = [];
        if ('new' == $id) {
            $mode   = 'new';
        } else {
            $id  = $id / $model::ID_NONCE;
            $row = $model->find($id);
            if (empty($row)) {
                throw new PageNotFoundException();
            }
        }
        $data    = [
            'page_title'   => 'Edit Fiction',
            'slug_group'   => 'fiction',
            'slug'         => '/office/fiction',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'mode'         => $mode,
            'row'          => $row,
        ];
        return view('fiction', $data);
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
        $data    = [
            'page_title'   => $title['fiction_title'],
            'slug_group'   => 'fiction',
            'slug'         => '/office/fiction',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'title'        => $title,
            'entries'      => $entries,
        ];
        return view('fiction_entries', $data);
    }
}