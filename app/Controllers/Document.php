<?php

namespace App\Controllers;

use App\Models\DocumentMasterModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

class Document extends BaseController
{

    /**
     * @return string
     */
    public function index(): string
    {
        $session = session();
        $data    = [
            'page_title'   => 'Document',
            'slug_group'   => 'document',
            'slug'         => '/office/document',
            'user_session' => $session->user,
            'roles'        => $session->roles,
            'current_role' => $session->current_role
        ];
        return view('document', $data);
    }

    public function list(): ResponseInterface
    {
        $model              = new DocumentMasterModel();
        $columns            = [
            'doc_title',
            'created_at',
            'updated_at',
            '',
            '',
            '',
        ];
        $order              = $this->request->getPost('order');
        $search             = $this->request->getPost('search');
        $start              = $this->request->getPost('start');
        $length             = $this->request->getPost('length');
        $order_column_index = $order[0]['column'] ?? 0;
        $order_column       = $columns[$order_column_index];
        $order_direction    = $order[0]['dir'] ?? 'desc';
        $search_value       = $search['value'];
        $result             = $model->getDataTables($start, $length, $order_column, $order_direction, $search_value);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $result['data']
        ]);
    }

    public function edit(int|string $id = 0): string
    {
        return '';
    }

    public function save(): ResponseInterface
    {
        return $this->response->setJSON([]);
    }

    /**
     * @param string $mode
     * @param string $slug
     * @param string $version
     * @return string
     */
    public function view(string $mode, string $slug, string $version = ''): string
    {
        $doc_model = new DocumentMasterModel();
        $document  = $doc_model->getDocumentVersion('doc_slug', $slug, $version);
        if (!$document) {
            throw PageNotFoundException::forPageNotFound();
        }
        echo '<pre>';
        print_r($document);
        echo '</pre>';
        if ('public' === $mode) {
            return '1';
        }
        // internal mode
        return '0';
    }
}