<?php

namespace App\Controllers;

use App\Models\FictionTitleModel;

class Fiction extends BaseController
{

    public function index(): string
    {
        $session = session();
        $model   = new FictionTitleModel();
        $titles  = $model->findAll();
        $data    = [
            'page_title'   => 'Fiction',
            'slug_group'   => 'fiction',
            'slug'         => '/office/fiction',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role,
            'titles'       => $titles,
        ];
        return view('fiction', $data);
    }
}